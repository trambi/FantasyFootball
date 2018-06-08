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

use FantasyFootball\TournamentCoreBundle\Entity\Game;
use FantasyFootball\TournamentCoreBundle\Entity\CoachRepository;

class GameController extends Controller
{
  public function DeleteAction(Request $request,$gameId)
  {
    $em = $this->getDoctrine()->getManager();
    $game = $em->getRepository('FantasyFootballTournamentCoreBundle:Game')->findOneById($gameId);
    $coach1 = $game->getCoach1();
    $coach2 = $game->getCoach2();
    $form = $this->createFormBuilder($game)
            ->add('delete','submit')
            ->getForm();
    $form->handleRequest($request);
    if ($form->isValid()) {
      $em->remove($game);
      $em->flush();
      $edition = $game->getEdition();
      return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_main',['edition'=>$edition]));
    }
    return $this->render('FantasyFootballTournamentAdminBundle:Game:delete.html.twig', array(
            'game'=>$game,
            'coach1'=>$coach1,
            'coach2'=>$coach2,
            'form' => $form->createView()
    ));
  }

  public function ScheduleAction(Request $request,$edition,$round)
  {
    $game = new Game();
    $em = $this->getDoctrine()->getManager();
    $editionObj = $em->getRepository('FantasyFootballTournamentCoreBundle:Edition')->findOneById($edition);
    if( -1 == $round ){
      $round = $editionObj->getCurrentRound();
    }
    $roundChoice = array( $round => $round );
    $gameCount = 10 ;
    if( 1 !== $round){
      $gameCount = $em->getRepository('FantasyFootballTournamentCoreBundle:Game')->getMaxTableNumberByEditionAndRound($edition,$round -1);
    } else{
      $gameCount = $em->getRepository('FantasyFootballTournamentCoreBundle:Game')->getMaxTableNumberByEditionAndRound($edition,$round) + 1;
    }
    $tableChoice = range(0,$gameCount);
    $form = $this->createFormBuilder($game)
                  ->add('round', 'choice',
                    array('label'=>'Tour :',
                      'choices'   => $roundChoice,
                      'required'  => true,
                      'choices_as_values' => true))
                  ->add('tableNumber', 'choice',
                    array('label'=>'Table :',
                      'choices'   => $tableChoice,
                      'required'  => true,
                      'choices_as_values' => true))
                  ->add('coach1', 'entity',
                      array('label'=>'Coach 1 :',
                        'class'   => 'FantasyFootballTournamentCoreBundle:Coach',
                        'property'  => 'name',
                        'query_builder' => function(CoachRepository $cr) use ($edition,$round){
                          return $cr->getQueryBuilderForCoachsWithoutGameByEditionAndRound($edition,$round);
                        }))
                  ->add('coach2', 'entity',
                    array('label'=>'Coach 2 :',
                      'class'   => 'FantasyFootballTournamentCoreBundle:Coach',
                      'property'  => 'name',
                      'query_builder' => function(CoachRepository $cr) use ($edition,$round) {
                        return $cr->getQueryBuilderForCoachsWithoutGameByEditionAndRound($edition,$round);
                      }))
                  ->add('save','submit',array('label'=>'Valider'))
                  ->getForm();
    $form->handleRequest($request);
    if ($form->isValid()) {
      $game->setRound($round);
      $game->setEdition($edition);
      $em->persist($game);
      $em->flush();
      return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_main',['edition'=>$edition]));
    }

    return $this->render('FantasyFootballTournamentAdminBundle:Game:schedule.html.twig', array(
                        'form' => $form->createView(),
                        'edition' => $edition) );
  }

  public function ResumeAction(Request $request,$gameId)
  {
    $em = $this->getDoctrine()->getManager();
    $game = $em->getRepository('FantasyFootballTournamentCoreBundle:Game')->findOneById($gameId);
    $name1 = $game->getCoach1()->getName();
    $name2 = $game->getCoach2()->getName();
    $form = $this->createFormBuilder($game)
            ->add('td1', 'integer',
              array('label'=>'Touchdown de '.$name1.' :',
                'required'  => true))
            ->add('td2', 'integer',
              array('label'=>'Touchdown de '.$name2.' :',
                'required'  => true))
            ->add('casualties1', 'integer',
              array('label'=>'Sorties de '.$name1.' :',
                'required'  => true))
            ->add('casualties2', 'integer',
              array('label'=>'Sorties de '.$name2.' :',
                'required'  => true))
            ->add('fouls1', 'integer',
                array('label'=>'Aggressions de '.$name1.' :',
                'required'  => false))
            ->add('fouls2', 'integer',
                array('label'=>'Aggressions de '.$name2.' :',
                'required'  => false))
            ->add('completions1', 'integer',
                array('label'=>'Passe de '.$name1.' :',
                'required'  => false))
            ->add('completions2', 'integer',
                array('label'=>'Passe de '.$name2.' :',
                'required'  => false))
            ->add('special1', 'text',
                array('label'=>'Special de '.$name1.' :',
                'required'  => false))
            ->add('special2', 'text',
                array('label'=>'Special de '.$name2.' :',
                'required'  => false))
            ->add('save','submit',array('label'=>'Valider'))
            ->getForm();
    $form->handleRequest($request);
    if ($form->isValid()) {
      $editionObj = $em->getRepository('FantasyFootballTournamentCoreBundle:Edition')
                    ->findOneById($game->getEdition());
      $strategy = $editionObj->getRankingStrategy();
      $points = $strategy->computePoints($game);
      $game->setPoints1($points[0]);
      $game->setPoints2($points[1]);
      $game->setStatus('resume');
      $em->flush();
      $edition = $game->getEdition();
      return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_main',['edition'=>$edition]));
    }
    return $this->render('FantasyFootballTournamentAdminBundle:Game:resume.html.twig', array(
                        'form' => $form->createView(),
                        'game' => $game,
                        'edition'=> $game->getEdition()) );
  }

  protected function createCompleteForm($game){
    $name1 = $game->getCoach1()->getName();
    $name2 = $game->getCoach2()->getName();
    $form = $this->createFormBuilder($game)
              ->add('edition', 'integer',
                array('label'=>'Edition :',
                'required'  => true))
              ->add('round', 'integer',
                array('label'=>'Round :',
                'required'  => true))
              ->add('tableNumber', 'integer',
                array('label'=>'Table :',
                'required'  => true))
              ->add('td1', 'integer',
                array('label'=>'Touchdown de '.$name1.' :',
                'required'  => true))
              ->add('td2', 'integer',
                array('label'=>'Touchdown de '.$name2.' :',
                'required'  => true))
              ->add('casualties1', 'integer',
                array('label'=>'Sorties de '.$name1.' :',
                'required'  => true))
              ->add('casualties2', 'integer',
                array('label'=>'Sorties de '.$name2.' :',
                'required'  => true))
              ->add('completions1', 'integer',
                array('label'=>'Passe de '.$name1.' :',
                'required'  => false))
              ->add('completions2', 'integer',
                array('label'=>'Passe de '.$name2.' :',
                'required'  => false))
              ->add('fouls1', 'integer',
                array('label'=>'Aggressions de '.$name1.' :',
                'required'  => false))
              ->add('fouls2', 'integer',
                array('label'=>'Aggressions de '.$name2.' :',
                'required'  => false))
              ->add('special1', 'text',
                array('label'=>'Special de '.$name1.' :',
                'required'  => false))
              ->add('special2', 'text',
                array('label'=>'Special de '.$name2.' :',
                'required'  => false))
              ->add('status', 'text', array(
                /*'choices'  => Game::getAllowedStatus(),*/
                'label'=>'Statut du match :',
                'required' => true))
              ->add('finale', 'checkbox',array(
                'label' => 'Finale ?',
                'required' => false))
              ->add('save','submit',array('label'=>'Valider'))
              ->getForm();
    return $form;
  }

  public function modifyAction(Request $request,$gameId)
  {
    $em = $this->getDoctrine()->getManager();
    $game = $em->getRepository('FantasyFootballTournamentCoreBundle:Game')->findOneById($gameId);

    $form = $this->createCompleteForm($game);
    $form->handleRequest($request);
    if ( $form->isValid() ) {
      $editionObj = $em->getRepository('FantasyFootballTournamentCoreBundle:Edition')
                    ->findOneById($game->getEdition());
      $strategy = $editionObj->getRankingStrategy();
      $points = $strategy->computePoints($game);
      $game->setPoints1($points[0]);
      $game->setPoints2($points[1]);
      $em->flush();
      $edition = $game->getEdition();
      return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_main',['edition'=>$edition]));
    }
    return $this->render('FantasyFootballTournamentAdminBundle:Game:modify.html.twig', array(
                        'form' => $form->createView(),
                        'game' => $game,
                        'edition'=> $game->getEdition()) );
  }

  protected function _gamesByEditionAndRound($edition, $round)
  {
      $em = $this->getDoctrine()->getManager();
      $criterias = ["edition"=>$edition,"round"=>$round];
      $order = ["tableNumber"=>"asc","id"=>"asc"];
      $games = $em->getRepository('FantasyFootballTournamentCoreBundle:Game')
                  ->findBy($criterias,$order);
      return $games;
  }

  protected function _isTeamEdition($editionId)
  {
    $em = $this->getDoctrine()->getManager();
    $edition = $em->getRepository('FantasyFootballTournamentCoreBundle:Edition')
                ->findOneById($editionId);
    if (null === $edition){
        return FALSE;
    }else{
        return (0 !== $edition->getFullTriplette());
    }
  }

  protected function _summarizeTeam($games, $edition, $round)
  {
      $confrontations = [];
      $currentFirstCoachTeam = 0;
      foreach ($games as $game){
          if ($currentFirstCoachTeam !== $game->getCoach1()->getCoachTeam()->getId()){
            if( 0 !== $currentFirstCoachTeam ){
                $confrontations[] = $confrontation;
            }
            $currentFirstCoachTeam = $game->getCoach1()->getCoachTeam()->getId();
            $confrontation= new \stdClass;
            $confrontation->team1 = $game->getCoach1()->getCoachTeam();
            $confrontation->team2 = $game->getCoach2()->getCoachTeam();
            $confrontation->games = [];
          }
          $confrontation->games[] = $game;
      }
      $confrontations[] = $confrontation;
      return $this->render('FantasyFootballTournamentAdminBundle:Game:summarize_team.html.twig',
                           array(
                               'edition'=>$edition,
                               'round'=>$round,
                               'confrontations'=>$confrontations));
  }

  protected function _summarize($games, $edition, $round)
  {
      return $this->render('FantasyFootballTournamentAdminBundle:Game:summarize.html.twig',
                           array(
                               'edition'=>$edition,
                               'round'=>$round,
                               'games'=>$games));
  }

  public function summarizeAction(Request $request, $edition, $round)
  {
    $games = $this->_gamesByEditionAndRound($edition, $round);
    if($this->_isTeamEdition($edition)){
        return $this->_summarizeTeam($games, $edition, $round);
    }else{
        return $this->_summarize($games, $edition, $round);
    }
  }
}
