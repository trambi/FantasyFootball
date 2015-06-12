<?php

namespace FantasyFootball\TournamentAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FantasyFootball\TournamentCoreBundle\Util\DataProvider;

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
            $matchesToPlay = $data->getToPlayMatchsByEditionAndRound($edition,$round);
            $playedMatches = $data->getPlayedMatchsByEditionAndRound($edition,$round);
        }
        return $this->render('FantasyFootballTournamentAdminBundle:Default:index.html.twig',
                array('edition'=>$edition,
                    'round'=>$round,
                    'matchesToPlay' => $matchesToPlay,
                    'playedMatches'=> $playedMatches,
                    'coachs' => $coachs,
                    'coachTeams' => $coachTeams));
        
    }
    
    public function nextRoundAction($edition)
    {
        $em = $this->getDoctrine()->getManager();
        $editionObj = $em->getRepository('FantasyFootballTournamentCoreBundle:Edition')->find($edition);
        $round = $editionObj->getCurrentRound();
        
        $toPair = array();
        $constraints = array();
        
        if ( 0 == $round ){
            //$coachs = $em->getRepository('FantasyFootballTournamentCoreBundle:Coach')->findByEdition($edition);
            $coachTeams = $em->getRepository('FantasyFootballTournamentCoreBundle:CoachTeam')->findByEditionJoined($edition);
            foreach ($coachTeams as $coachTeam){
                $toPair[] = $coachTeam->getId();
            }
            shuffle($toPair);
        }else{
            $conf = $this->get('fantasy_football_core_db_conf');
            $data = new DataProvider($conf);
            
            $strategy = RankingStrategyFabric::getByName( $editionObj->getRankingStrategy() );
            $ranking = $data->getCoachTeamRanking($edition, $strategy);
            
            foreach ($ranking as $coachTeam){
                $toPair[] = $coachTeam->getId();
            }
        }
        
        return $this->render('FantasyFootballTournamentAdminBundle:Default:index.html.twig',
                array('edition'=>$edition,
                    'round'=>$round,
                    'matchesToPlay' => $matchesToPlay,
                    'playedMatches'=> $playedMatches,
                    'coachs' => $coachs,
                    'coachTeams' => $coachTeams));
        
    }
}
