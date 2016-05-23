<?php

namespace FantasyFootball\TournamentAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use FantasyFootball\TournamentAdminBundle\Util\DataUpdater;

use FantasyFootball\TournamentCoreBundle\Entity\Coach;
use FantasyFootball\TournamentAdminBundle\Form\CoachType;

class CoachController extends Controller{
  
  public function AddAction(Request $request,$edition){
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

    return $this->render('FantasyFootballTournamentAdminBundle:Coach:Add.html.twig',
      ['form' => $form->createView()]);    
  }

  protected function setReady($coachId, $ready){
    $em = $this->getDoctrine()->getManager();
    $coach = $em->getRepository('FantasyFootballTournamentCoreBundle:Coach')->find($coachId);
    $coach->setReady($ready);
    $em->flush();
  }
  
  protected function setReadyByEdition($edition, $ready){
    $em = $this->getDoctrine()->getManager();
    $coachs = $em->getRepository('FantasyFootballTournamentCoreBundle:Coach')->findByEdition($edition);
    foreach($coachs as $coach){
      $coach->setReady($ready);
    }
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
    return $this->render('FantasyFootballTournamentAdminBundle:Coach:Modify.html.twig', 
      ['form' => $form->createView(),'coach' => $coach] );    
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
      return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_main'));
    }
    return $this->render('FantasyFootballTournamentAdminBundle:Coach:Delete.html.twig',
      ['coach'=>$coach,'form' => $form->createView()]);
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
    return $this->render('FantasyFootballTournamentAdminBundle:Coach:View.html.twig',
      ['coach'=>$coach,'race'=>$coach->getRace(),'coachTeam'=>$coach->getCoachTeam(),'matchs'=>$matchs]);
  }

  public function ListAction($edition){
    $em = $this->getDoctrine()->getManager();
    $coachs = $em->getRepository('FantasyFootballTournamentCoreBundle:Coach')->findByEdition($edition);
    return $this->render('FantasyFootballTournamentAdminBundle:Coach:List.html.twig',
      ['coachs' => $coachs, 'edition'=>$edition]);
  }
  
  public function ReadyByEditionAction($edition){
    $this->setReadyByEdition($edition,true);
    return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_main'));
  }
  
  public function UnreadyByEditionAction($edition){
    $this->setReadyByEdition($edition,false);
    return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_main'));
  }
}
