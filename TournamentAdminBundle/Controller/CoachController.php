<?php

namespace FantasyFootball\TournamentAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FantasyFootball\TournamentCoreBundle\Entity\Coach;
use FantasyFootball\TournamentCoreBundle\Util\DataProvider;
use FantasyFootball\TournamentAdminBundle\Util\DataUpdater;

use Symfony\Component\HttpFoundation\Request;

class CoachController extends Controller
{
	public function AddAction(Request $request,$edition)
	{
	
		$coach = new Coach();
		$coach->setEdition($edition);
		
		$conf = $this->get('fantasy_football_core_db_conf');
    	$data = new DataUpdater($conf);
		$raceArray = $data->getRacesByEdition($edition);
		$raceChoice = array();
		foreach($raceArray as $key=>$obj){
				$raceChoice[$key]=$obj->nom_fr;
		}
		
		$form = $this->createFormBuilder($coach)
				->add('teamName', 'text')
				->add('name', 'text')
				->add('race', 'choice',array(
				   'label'		=>	'Race',
					'choices'   => $raceChoice,
					'required'  => true))
				->add('emailAddress', 'email')
				->add('nafNumber', 'integer')
				->add('ready', 'checkbox',array(
					'label' => 'coach prêt ?',
					'required' => false))
				->add('save','submit')
				->getForm();

		$form->handleRequest($request);
		print_r($coach);
		if ($form->isValid()) {
			$data->insertCoach($coach);
      	return $this->redirect($this->generateUrl('task_success'));
    	}

      return $this->render('FantasyFootballTournamentAdminBundle:Coach:Add.html.twig', array(
      	'form' => $form->createView(),
		));    
	}

	public function ModifyAction(Request $request,$coachId)
	{
		
		$conf = $this->get('fantasy_football_core_db_conf');
    	$data = new DataUpdater($conf);
    	$coach = new Coach($data->getCoachById($coachId));
    	$edition = $coach->getEdition();
		$raceArray = $data->getRacesByEdition($edition);
		$raceChoice = array();
		foreach($raceArray as $key=>$obj){
				$raceChoice[$key]=$obj->nom_fr;
		}
		
		$form = $this->createFormBuilder($coach)
				->add('teamName', 'text')
				->add('name', 'text')
				->add('race', 'choice',array(
				   'label'		=>	'Race',
					'choices'   => $raceChoice,
					'required'  => true))
				->add('emailAddress', 'email')
				->add('nafNumber', 'integer')
				->add('ready', 'checkbox',array(
					'label' => 'coach prêt ?',
					'required' => false))
				->add('save','submit')
				->getForm();

		$form->handleRequest($request);
		$coach->id = $coachId;
		if ($form->isValid()) {
			$data->modifyCoach($coach);
			//return $this->redirect($this->generateUrl('task_success'));
		}
		return $this->render('FantasyFootballTournamentAdminBundle:Coach:Modify.html.twig', array(
				'form' => $form->createView()
			));    
	}

	public function DeleteAction(Request $request,$coachId)
	{
		$conf = $this->get('fantasy_football_core_db_conf');
    	$data = new DataUpdater($conf);
    	$coach = new Coach($data->getCoachById($coachId));
		
		$form = $this->createFormBuilder($coach)
				->add('delete','submit')
				->getForm();

		$form->handleRequest($request);
		$coach->id = $coachId;
		if ($form->isValid()) {
			$data->deleteCoach($coach);
			//return $this->redirect($this->generateUrl('task_success'));
		}
		return $this->render('FantasyFootballTournamentAdminBundle:Coach:Delete.html.twig', array(
      		'coach'=>$coach,
      		'form' => $form->createView()
			));
	}

	public function viewAction(Request $request,$coachId)
	{
		$conf = $this->get('fantasy_football_core_db_conf');
    	$data = new DataUpdater($conf);
    	$coach = $data->getCoachById($coachId);
    	$matchs = $data->getMatchsByCoach($coachId);
		return $this->render('FantasyFootballTournamentAdminBundle:Coach:View.html.twig', array(
      		'coach'=>$coach,'matchs'=>$matchs
			));
	}
      
	public function ListAction($edition){
		$conf = $this->get('fantasy_football_core_db_conf');
		$data = new DataProvider($conf);
		$coachs = $data->getCoachsByEdition($edition);
		return $this->render('FantasyFootballTournamentAdminBundle:Coach:List.html.twig', array(
      		'coachs' => $coachs, 'edition'=>$edition));
   }

}
