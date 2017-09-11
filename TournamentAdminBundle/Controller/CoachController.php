<?php
/*
    FantasyFootball Symfony2 bundles - Symfony2 bundles collection to handle fantasy football tournament 
    Copyright (C) 2017  Bertrand Madet

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
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
  
  protected function getEditionByCoachId($coachId){
    $em = $this->getDoctrine()->getManager();
    $coach = $em->getRepository('FantasyFootballTournamentCoreBundle:Coach')->find($coachId);
    $edition = $coach->getEdition();
    return $edition;
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
    $edition = $this->getEditionByCoachId($coachId);
    return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_main',array('edition'=>$edition,'round'=>0)));
  }

  public function UnreadyAction($coachId)
  {
    $this->setReady($coachId,false);
    $edition = $this->getEditionByCoachId($coachId);
    return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_main',array('edition'=>$edition,'round'=>0)));
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
    return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_main',array('edition'=>$edition,'round'=>0)));
  }
  
  public function UnreadyByEditionAction($edition){
    $this->setReadyByEdition($edition,false);
    return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_main',array('edition'=>$edition,'round'=>0)));
  }
}
