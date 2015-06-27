<?php

namespace FantasyFootball\TournamentAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use FantasyFootball\TournamentAdminBundle\Util\DataUpdater;

use FantasyFootball\TournamentCoreBundle\Entity\Coach;
use FantasyFootball\TournamentAdminBundle\Form\CoachType;

class CoachController extends Controller
{
    /*protected function createCustomForm(Coach $coach,array $races)
    {
        $raceChoice = array();
        foreach($races as $key=>$obj){
            $raceChoice[$key]=$obj->nom_fr;
        }
        $form = $this->createFormBuilder($coach)
                    ->add('teamName', 'text',array('label'=>'Nom de l\'équipe :'))
                    ->add('name', 'text',array('label'=>'Nom :'))
                    ->add('race', 'entity',
                        array('label'=>'Race :',
                            'choices'   => $raceChoice,
                            'required'  => true))
                    ->add('email', 'email',array('label'=>'Courriel :'))
                    ->add('nafNumber', 'integer',array('label'=>'Numéro NAF :'))
                    ->add('ready', 'checkbox',array(
                        'label' => 'Coach prêt ?',
                        'required' => false))
                    ->add('save','submit',array('label'=>'Valider'))
                    ->getForm();
        return $form;
    }*/
    
    public function AddAction(Request $request,$edition)
    {
        $coach = new Coach();
        $coach->setEdition($edition);
		
        $form = $this->createForm(new CoachType($edition),$coach);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($coach);
            $em->flush();
            return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_main'));
        }

        return $this->render('FantasyFootballTournamentAdminBundle:Coach:Add.html.twig', array(
                                'form' => $form->createView() ) );    
    }

    protected function setReady($coachId, $ready)
    {
        $em = $this->getDoctrine()->getManager();
        $coach = $em->getRepository('FantasyFootballTournamentCoreBundle:Coach')->find($coachId);
	$coach->setReady($ready);
        $em->flush();
    }
    
    public function ReadyAction($coachId)
    {
        $this->setReady($coachId,true);
        return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_main'));
    }
    
    public function UnreadyAction($coachId)
    {
        $this->setReady($coachId,false);
        return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_main'));
    }
    
    public function ModifyAction(Request $request,$coachId)
    {
    	$em = $this->getDoctrine()->getManager();
        $coach = $em->getRepository('FantasyFootballTournamentCoreBundle:Coach')->find($coachId);
    	$edition = $coach->getEdition();
        $form = $this->createForm(new CoachType($edition),$coach);
	$form->handleRequest($request);
	if ($form->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_main'));
	}
	return $this->render('FantasyFootballTournamentAdminBundle:Coach:Modify.html.twig', array(
				'form' => $form->createView(),
                                'coach' => $coach) );    
    }

    public function DeleteAction(Request $request,$coachId)
    {
	$em = $this->getDoctrine()->getManager();
        $coach = $em->getRepository('FantasyFootballTournamentCoreBundle:Coach')->find($coachId);	
        $form = $this->createFormBuilder($coach)
                	->add('delete','submit')
			->getForm();

        $form->handleRequest($request);
	if ($form->isValid()) {
            $em->remove($coach);
            $em->flush();
            //$data->deleteCoach($coach);
            return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_main'));
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
            $em = $this->getDoctrine()->getManager();
            $coach = $em->getRepository('FantasyFootballTournamentCoreBundle:Coach')->findOneByIdJoined($coachId);
            if(! $coach){
                return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_main'));
            }
            $matchs = $data->getMatchsByCoach($coachId);
            return $this->render('FantasyFootballTournamentAdminBundle:Coach:View.html.twig', array(
      		'coach'=>$coach,
                'race'=>$coach->getRace(),
                'coachTeam'=>$coach->getCoachTeam(),
                'matchs'=>$matchs,
			));
	}
      
	public function ListAction($edition){
                $em = $this->getDoctrine()->getManager();
                $coachs = $em->getRepository('FantasyFootballTournamentCoreBundle:Coach')->findByEdition($edition);
		return $this->render('FantasyFootballTournamentAdminBundle:Coach:List.html.twig', array(
      		'coachs' => $coachs, 'edition'=>$edition));
   }

}
