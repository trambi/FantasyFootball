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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use FantasyFootball\TournamentCoreBundle\Entity\Edition;
use FantasyFootball\TournamentAdminBundle\Form\EditionType;

class EditionController extends Controller{

  public function AddAction(Request $request){
    $edition = new Edition();

    $form = $this->createForm(EditionType::class, $edition);
    $form->handleRequest($request);
    if ( $form->isValid() ) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($edition);
      $em->flush();
      return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_main',array('round'=>0)));
    }

    return $this->render('@tournament_admin/Edition/Add.html.twig',
      ['form' => $form->createView()]);
  }

  public function ModifyAction(Request $request,$edition)
  {
    $em = $this->getDoctrine()->getManager();
    $editionObj = $em->getRepository('FantasyFootballTournamentCoreBundle:Edition')->find($edition);
    $form = $this->createForm(EditionType::class, $editionObj);
    $form->handleRequest($request);
    if ($form->isValid()) {
      $em->flush();
      return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_main',array('edition'=>$edition)));
    }
    return $this->render('@tournament_admin/Edition/Modify.html.twig', 
      ['form' => $form->createView(),'edition' => $editionObj] );    
  }
}
