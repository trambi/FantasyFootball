<?php

namespace FantasyFootball\TournamentAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FantasyFootball\TournamentCoreBundle\Util\DataProvider;
use FantasyFootball\TournamentCoreBundle\Util\RankingStrategyFabric;
use FantasyFootball\TournamentCoreBundle\Entity\Game;

use FantasyFootball\TournamentAdminBundle\Util\SwissRoundStrategy;

class MainController extends Controller
{
        
    protected function _indexActionNotStarted(\FantasyFootball\TournamentCoreBundle\Entity\Edition $edition) {
        $editionId = $edition->getId();
        $em = $this->getDoctrine()->getManager();
        $coachs = $em->getRepository('FantasyFootballTournamentCoreBundle:Coach')->findByEdition($editionId);
        $coachTeams = $em->getRepository('FantasyFootballTournamentCoreBundle:CoachTeam')->findByEditionJoined($editionId);
        return $this->render('FantasyFootballTournamentAdminBundle:Main:index_not_started.html.twig', array('edition' => $editionId,
                    'coachs' => $coachs,
                    'coachTeams' => $coachTeams));
    }

    protected function _indexActionStarted(\FantasyFootball\TournamentCoreBundle\Entity\Edition $edition, $round) {
        $editionId = $edition->getId();
        $conf = $this->get('fantasy_football_core_db_conf');
        $data = new DataProvider($conf);
        $playedMatches = $data->getPlayedMatchsByEditionAndRound($edition->getId(), $round);
        $matchesToPlay = $data->getToPlayMatchsByEditionAndRound($edition->getId(), $round);
        return $this->render('FantasyFootballTournamentAdminBundle:Main:index.html.twig', array('edition' => $editionId,
                    'round' => $round,
                    'matchesToPlay' => $matchesToPlay,
                    'playedMatches' => $playedMatches));
    }
    
    public function indexAction($edition,$round)
    {
        $em = $this->getDoctrine()->getManager();
        $editionObj = $em->getRepository('FantasyFootballTournamentCoreBundle:Edition')->find($edition);
        if( -1 == $round ){
            $round = $editionObj->getCurrentRound();
        }
        if ( 0 == $round ){
            $render = $this->_indexActionNotStarted($editionObj);
        }else{
            $render = $this->_indexActionstarted($editionObj,$round);
        }
        return $render;
        
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
        $coachs = $em->getRepository('FantasyFootballTournamentCoreBundle:Coach')->findByEdition($edition);
        $coachsById = array();
        foreach ($coachs as $coach)
        {
            $coachsById[$coach->getId()] = $coach;
        }
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
        $games = $pairing->pairing(array(), $toPair,$constraints);
        if (null == $games){
            throw new \Exception('Impossible de générer un appariement !');
        }
        $nextRound = $round + 1 ;
        $table = 1;
        if (1 === $isFullTeam )
        {
            foreach($games as $teamGame){
                $teamCoachId1 = $teamGame[0];
                $teamCoachId2 = $teamGame[1];
                
                foreach( $sortedCoachsByTeamCoachId[$teamCoachId1] as $index => $rosterId ){
                    $opponentRosterIds = $sortedCoachsByTeamCoachId[$teamCoachId2];
                    $opponentRosterId = $opponentRosterIds[$index];
                    $game = new Game();
                    $game->setEdition($edition);
                    $game->setRound($nextRound);
                    $game->setTableNumber($table);
                    $game->setCoach1($coachsById[$rosterId]);
                    $game->setCoach2($coachsById[$opponentRosterId]);
                    $em->persist($game);

                    $table ++;
                }
            }
        }else{
            foreach($games as $coachGame){
                $rosterId = $coachGame[0];
                $opponentRosterId = $coachGame[1];
                $game = new Game();
                $game->setEdition($edition);
                $game->setRound($nextRound);
                $game->setTableNumber($table);
                $game->setCoach1($coachsById[$rosterId]);
                $game->setCoach2($coachsById[$opponentRosterId]);
                $em->persist($game);
                $table ++;
            }
        }
        $editionObj->setCurrentRound($nextRound);
        $em->flush();
        $gameRepository = $em->getRepository('FantasyFootballTournamentCoreBundle:Game');
        $pairedGames = $gameRepository->findBy(
            ['edition'=>$edition,
            'round'=>$nextRound]);
        
        return $this->render('FantasyFootballTournamentAdminBundle:Main:next_round.html.twig',
                array('edition'=>$edition,
                    'round'=>$round,
                    'games' => $pairedGames
                ));
        
    }
}
