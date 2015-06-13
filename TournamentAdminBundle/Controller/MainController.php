<?php

namespace FantasyFootball\TournamentAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FantasyFootball\TournamentCoreBundle\Util\DataProvider;

use FantasyFootball\TournamentAdminBundle\Util\SwissRoundStrategy;

class MainController extends Controller
{
    public function indexAction($edition,$round)
    {
        $em = $this->getDoctrine()->getManager();
        $editionObj = $em->getRepository('FantasyFootballTournamentCoreBundle:Edition')->find($edition);
        if( -1 == $round ){
            $round = $editionObj->getCurrentRound();
        }
        if ( 0 == $round ){
            $coachs = $em->getRepository('FantasyFootballTournamentCoreBundle:Coach')->findByEdition($edition);
            $coachTeams = $em->getRepository('FantasyFootballTournamentCoreBundle:CoachTeam')->findByEditionJoined($edition);
            $matchesToPlay = array();
            $playedMatches = array();
        }else{
            $coachs = array();
            $coachTeams = array();
            $conf = $this->get('fantasy_football_core_db_conf');
            $data = new DataProvider($conf);
            $playedMatches = $data->getPlayedMatchsByEditionAndRound($edition,0);
        }
        return $this->render('FantasyFootballTournamentAdminBundle:Main:index.html.twig',
                array('edition'=>$edition,
                    'round'=>$round,
                    'matchesToPlay' => $matchesToPlay,
                    'playedMatches'=> $playedMatches,
                    'coachs' => $coachs,
                    'coachTeams' => $coachTeams));
        
    }
    
    protected function initFirstRoundDrawWithCoach($edition) {
        $toPair = array();

        $em = $this->getDoctrine()->getManager();
        $coachs = $em->getRepository('FantasyFootballTournamentCoreBundle:Coach')->findByEdition($edition);
        foreach ($coachs as $coach)
        {
            $toPair[] = $coach->getId();
        }
        shuffle($toPair);
        
        return ([$toPair,array(),array()]);
    }
    
    protected function initFirstRoundDrawWithCoachTeam($edition) {
        $toPair = array();

        $em = $this->getDoctrine()->getManager();
        $coachTeams = $em->getRepository('FantasyFootballTournamentCoreBundle:CoachTeam')->findByEditionJoined($edition);
        foreach ($coachTeams as $coachTeam)
        {
            $toPair[] = $coachTeam->getId();
            $shuffledCoachs = array();
            foreach ($coachTeam->getCoachs() as $coach) {
                $shuffledCoachs[] = $coach->getId();
            }
            shuffle($shuffledCoachs);
            $sortedCoachsByTeamCoachId[$coachTeam->getId()] = $shuffledCoachs;
        }
        shuffle($toPair);

        return ([$toPair,array(),$sortedCoachsByTeamCoachId]);
    }
    
    protected function initRoundDrawWithCoachTeam($edition)
    {
        $conf = $this->get('fantasy_football_core_db_conf');
        $data = new DataProvider($conf);

        $strategy = RankingStrategyFabric::getByName($edition->getRankingStrategy());
        $coachTeamRanking = $data->getCoachTeamRanking($edition->getId(), $strategy);
        foreach ($coachTeamRanking as $id => $coachTeam)
        {
            $toPair[] = $id;
            $tempSortedCoach = array();
            foreach ($coachTeam->teams() as $coach)
            {
                $tempSortedCoach[] = $coach->id;
            }
            $sortedCoachsByTeamCoachId[$id] = $tempSortedCoach;
        }
        $constraints = $data->getCoachTeamGamesByEdition($edition);

        return ([$toPair, $constraints, $sortedCoachsByTeamCoachId]);
    }
    
    protected function initRoundDrawWithCoach($edition)
    {
        $conf = $this->get('fantasy_football_core_db_conf');
        $data = new DataProvider($conf);
        $strategy = RankingStrategyFabric::getByName($edition->getRankingStrategy());

        $coachRanking = $data->getMainCoachRanking($edition->getId(), $strategy);
        $toPair = array_keys($coachRanking);
        
        $constraints = $data->getCoachGamesByEdition($edition->getId());

        return ([$toPair, $constraints, array()]);
    }

    public function nextRoundAction($edition)
    {
        $em = $this->getDoctrine()->getManager();
        $editionObj = $em->getRepository('FantasyFootballTournamentCoreBundle:Edition')->find($edition);
        $round = $editionObj->getCurrentRound();
        
        $isFullTeam = $editionObj->getFullTriplette();
        
        if ( 0 == $round )
        {
            if (1 === $isFullTeam )
            {
                list($toPair,$constraints,$sortedCoachsByTeamCoachId) = $this->initFirstRoundDrawWithCoachTeam($edition);
            }
            else 
            {
                list($toPair,$constraints,$sortedCoachsByTeamCoachId) = $this->initFirstRoundDrawWithCoach($edition);
            }
        }
        else
        {
            if (1 === $isFullTeam )
            {
                list($toPair,$constraints,$sortedCoachsByTeamCoachId) = $this->initRoundDrawWithCoachTeam($edition);
            }
            else 
            {
                list($toPair,$constraints,$sortedCoachsByTeamCoachId) = $this->initRoundDrawWithCoach($edition);
            }
            
        }
        
        $pairing = new SwissRoundStrategy();
        $coachTeamPairing = $pairing->pairing(array(), $toPair,$constraints);
        echo "<pre>coachTeamPairing",print_r($coachTeamPairing),"</pre>";
        //$coachTeamPairing = array();
        //echo "<pre>toPair : ",print_r($toPair),"</pre>";
        //echo "<pre>constraints : ",print_r($constraints),"</pre>";
        return $this->render('FantasyFootballTournamentAdminBundle:Main:next_round.html.twig',
                array('edition'=>$edition,
                    'round'=>$round,
                    'coachTeamPairing' => $coachTeamPairing
                ));
        
    }
}
