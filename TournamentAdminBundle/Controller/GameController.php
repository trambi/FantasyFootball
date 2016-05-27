<?php

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
      return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_main'));
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
      return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_main'));
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
            ->add('save','submit',array('label'=>'Valider'))
            ->getForm();
    $form->handleRequest($request);
    if ($form->isValid()) {
      $editionObj = $em->getRepository('FantasyFootballTournamentCoreBundle:Edition')
                    ->findOneById($game->getEdition());
      $strategy = $editionObj->getRankingStrategy();
      $points1 = 0;
      $points2 = 0;
      $strategy->computePoints($points1, $points2, $game->getTd1(), $game->getTd2(),
                                $game->getCasualties1(), $game->getCasualties2() );
      $game->setPoints1($points1);
      $game->setPoints2($points2);
      $game->setStatus('resume');
      $em->flush();
      return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_main'));
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
      $points1 = 0;
      $points2 = 0;
      $strategy->computePoints($points1, $points2, $game->getTd1(), $game->getTd2(),
                                $game->getCasualties1(), $game->getCasualties2() );
      $game->setPoints1($points1);
      $game->setPoints2($points2);
      $em->flush();
      return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_main'));
    }
    return $this->render('FantasyFootballTournamentAdminBundle:Game:modify.html.twig', array(
                        'form' => $form->createView(),
                        'game' => $game,
                        'edition'=> $game->getEdition()) );
  }

  public function viewAction()
  {
    return array(
      // ...
    );
  }
}
