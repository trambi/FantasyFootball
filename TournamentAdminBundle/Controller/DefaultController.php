<?php

namespace FantasyFootball\TournamentAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FantasyFootball\TournamentCoreBundle\Util\DataProvider;

class DefaultController extends Controller
{
    public function indexAction($edition,$round)
    {
        $conf = $this->get('fantasy_football_core_db_conf');
        $data = new DataProvider($conf);
        $editionObj = $data->getEditionById($edition);
        if( -1 == $round ){
            $round = $editionObj->currentRound;
        }
        if ( 0 == $round ){
            $coachs = $data->getCoachsByEdition($edition);
            $coachTeams = $data->getCoachTeamsByEdition($edition);
            $matchesToPlay = array();
            $playedMatches = array();
        }else{
            $coachs = array();
            $coachTeams = array();
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
}
