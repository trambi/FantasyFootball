<?php

namespace FantasyFootball\TournamentAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use FantasyFootball\TournamentCoreBundle\Util\DataProvider;
use FantasyFootball\TournamentAdminBundle\Util\DataUpdater;

class CoachTeamController extends Controller
{
    public function AddAction()
    {
        return $this->render('FantasyFootballTournamentAdminBundle:CoachTeam:Add.html.twig', array(
                // ...
            ));    }

    public function ModifyAction()
    {
        return $this->render('FantasyFootballTournamentAdminBundle:CoachTeam:Modify.html.twig', array(
                // ...
            ));    }

    public function DeleteAction()
    {
        return $this->render('FantasyFootballTournamentAdminBundle:CoachTeam:Delete.html.twig', array(
                // ...
            ));    }

    public function LoadAction()
    {
        return $this->render('FantasyFootballTournamentAdminBundle:CoachTeam:Load.html.twig', array(
                // ...
            ));    }
    public function viewAction($coachTeamId)
    {
	$conf = $this->get('fantasy_football_core_db_conf');
    	$data = new DataUpdater($conf);
    	$coachTeam = $data->getCoachTeamById($coachTeamId);
    	$matchs = $data->getMatchsByCoachTeam($coachTeamId);
	return $this->render('FantasyFootballTournamentAdminBundle:CoachTeam:View.html.twig', array(
  		'coachTeam'=>$coachTeam,'matchs'=>$matchs));
    }
      
    public function ListAction($edition)
    {
        $conf = $this->get('fantasy_football_core_db_conf');
	$data = new DataProvider($conf);
        $coachTeams = $data->getCoachTeamsByEdition($edition);
	return $this->render('FantasyFootballTournamentAdminBundle:CoachTeam:List.html.twig', array(
      	'coachTeams' => $coachTeams, 'edition'=>$edition));
    }
}
?>