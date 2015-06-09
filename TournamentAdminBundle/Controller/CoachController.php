<?php

namespace FantasyFootball\TournamentAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FantasyFootball\TournamentCoreBundle\Entity\Coach;
use FantasyFootball\TournamentCoreBundle\Util\DataProvider;
use FantasyFootball\TournamentAdminBundle\Util\DataUpdater;
use FantasyFootball\TournamentAdminBundle\Form\CoachType;
use Symfony\Component\HttpFoundation\Request;

class CoachController extends Controller
{
    protected function createCustomForm(Coach $coach,array $races)
    {
        $raceChoice = array();
        foreach($races as $key=>$obj){
            $raceChoice[$key]=$obj->nom_fr;
        }
        $form = $this->createFormBuilder($coach)
                    ->add('teamName', 'text',array('label'=>'Nom de l\'équipe :'))
                    ->add('name', 'text',array('label'=>'Nom :'))
                    ->add('race', 'choice',
                        array('label'=>'Race :',
                            'choices'   => $raceChoice,
                            'required'  => true))
                    ->add('emailAddress', 'email',array('label'=>'Courriel :'))
                    ->add('nafNumber', 'integer',array('label'=>'Numéro NAF :'))
                    ->add('ready', 'checkbox',array(
                        'label' => 'Coach prêt ?',
                        'required' => false))
                    ->add('save','submit',array('label'=>'Valider'))
                    ->getForm();
        return $form;
    }
    
    public function AddAction(Request $request,$edition)
    {
        $coach = new Coach();
        $coach->setEdition($edition);
		
        $conf = $this->get('fantasy_football_core_db_conf');
        $data = new DataUpdater($conf);
        $races = $data->getRacesByEdition($edition);
        
        $form = $this->createCustomForm($coach, $races);
        /*    $form = $this->createFormBuilder($coach)
				->add('teamName', 'text',array('label'=>'Nom de l\'équipe :'))
				->add('name', 'text',array('label'=>'Nom :'))
				->add('race', 'choice',array(
				   'label'		=>	'Race :',
					'choices'   => $raceChoice,
					'required'  => true))
				->add('emailAddress', 'email',array('label'=>'Courriel :'))
				->add('nafNumber', 'integer',array('label'=>'Numéro NAF :'))
				->add('ready', 'checkbox',array(
					'label' => 'Coach prêt ?',
					'required' => false))
				->add('save','submit',array('label'=>'Valider'))
				->getForm();*/
        $form->handleRequest($request);
        if ($form->isValid()) {
            $data->insertCoach($coach);
            return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_homepage'));
        }

        return $this->render('FantasyFootballTournamentAdminBundle:Coach:Add.html.twig', array(
                                'form' => $form->createView() ) );    
    }

    public function ReadyAction($coachId)
    {
	$conf = $this->get('fantasy_football_core_db_conf');
    	$data = new DataUpdater($conf);
    	$coach = new Coach($data->getCoachById($coachId));
        //$coach->id = $coachId;
        $data->setReadyCoach($coach);
        //return $this->render('FantasyFootballTournamentAdminBundle:Coach:View.html.twig', array(
      	//	'coach'=>$coach,
          //      'matchs'=>array()
	//		));
        return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_homepage'));
    }
    
    public function UnreadyAction($coachId)
    {
	$conf = $this->get('fantasy_football_core_db_conf');
    	$data = new DataUpdater($conf);
    	$coach = new Coach($data->getCoachById($coachId));
        $data->setUnreadyCoach($coach);
        return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_homepage'));
    }
    
    public function ModifyAction(Request $request,$coachId)
    {
	$conf = $this->get('fantasy_football_core_db_conf');
    	$data = new DataUpdater($conf);
    	$coach = new Coach($data->getCoachById($coachId));
    	$edition = $coach->getEdition();
	$races = $data->getRacesByEdition($edition);
		
	$form = $this->createCustomForm($coach,$races);
	$form->handleRequest($request);
	$coach->id = $coachId;
	if ($form->isValid()) {
            $data->modifyCoach($coach);
            return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_homepage'));
	}
	return $this->render('FantasyFootballTournamentAdminBundle:Coach:Modify.html.twig', array(
				'form' => $form->createView() ) );    
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
            return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_homepage'));
	}
	return $this->render('FantasyFootballTournamentAdminBundle:Coach:Delete.html.twig', array(
            'coach'=>$coach,
            'form' => $form->createView()
	));
    }

	public function viewAction($coachId)
	{
            $conf = $this->get('fantasy_football_core_db_conf');
            $data = new DataUpdater($conf);
            $coach = $data->getCoachById($coachId);
            $matchs = $data->getMatchsByCoach($coachId);
            return $this->render('FantasyFootballTournamentAdminBundle:Coach:View.html.twig', array(
      		'coach'=>$coach,
                'matchs'=>$matchs
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
