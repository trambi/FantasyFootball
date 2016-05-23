<?php

namespace FantasyFootball\TournamentAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FantasyFootball\TournamentCoreBundle\Util\DataProvider;
use FantasyFootball\TournamentAdminBundle\Util\PairingContextFabric;
use FantasyFootball\TournamentAdminBundle\Util\SwissRoundStrategy;

use Symfony\Component\HttpFoundation\Response;

class MainController extends Controller
{

  protected function _indexActionNotStarted(\FantasyFootball\TournamentCoreBundle\Entity\Edition $edition) {
    $editionId = $edition->getId();
    $em = $this->getDoctrine()->getManager();
    $coachs = $em->getRepository('FantasyFootballTournamentCoreBundle:Coach')->findByEdition($editionId,array('name'=>'ASC'));
    $coachTeams = $em->getRepository('FantasyFootballTournamentCoreBundle:CoachTeam')->findByEditionJoined($editionId);
    return $this->render('FantasyFootballTournamentAdminBundle:Main:index_not_started.html.twig',
      ['edition' => $editionId,'coachs' => $coachs,'coachTeams' => $coachTeams]);
  }

  protected function _indexActionStarted(\FantasyFootball\TournamentCoreBundle\Entity\Edition $edition, $round) {
    $editionId = $edition->getId();
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $playedMatches = $data->getPlayedMatchsByEditionAndRound($edition->getId(), $round);
    $matchesToPlay = $data->getToPlayMatchsByEditionAndRound($edition->getId(), $round);
    return $this->render('FantasyFootballTournamentAdminBundle:Main:index.html.twig',
      ['edition' => $editionId,'round' => $round,'matchesToPlay' => $matchesToPlay,
        'playedMatches' => $playedMatches]);
  }

  public function indexAction($edition,$round)
  {
    $em = $this->getDoctrine()->getManager();
    if( 0 == $edition ){
      $editionObj = $em->createQueryBuilder()
        ->select('e')
        ->from('FantasyFootballTournamentCoreBundle:Edition', 'e')
        ->orderBy('e.id', 'DESC')
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();
    }else{
      $editionObj = $em->getRepository('FantasyFootballTournamentCoreBundle:Edition')->find($edition);
    }
    if( -1 == $round ){
      $round = $editionObj->getCurrentRound();
    }
    if ( 0 == $round ){
      $render = $this->_indexActionNotStarted($editionObj);
    }else{
      $render = $this->_indexActionstarted($editionObj,$round);
    }
    return $render;
  }

  public function nextRoundAction($edition)
  {
    $em = $this->getDoctrine()->getManager();
    $editionObj = $em->getRepository('FantasyFootballTournamentCoreBundle:Edition')->find($edition);
    $round = $editionObj->getCurrentRound();
    $count = $em->getRepository('FantasyFootballTournamentCoreBundle:Game')
                ->countScheduledGamesByEditionAndRound($edition,$round);
    if( 0 != $count ){
      return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_main'));
    }
    $pairingContext = PairingContextFabric::create($editionObj,$em,$this->get('fantasy_football_core_db_conf'));
    list($toPair,$constraints) = $pairingContext->init();
    $pairing = new SwissRoundStrategy();
    $games = $pairing->pairing(array(), $toPair,$constraints);
    if (null == $games){
      throw new \Exception('Impossible de générer un appariement !');
    }
    $nextRound = $round + 1 ;
    $pairingContext->persist($games, $nextRound);
    $editionObj->setCurrentRound($nextRound);
    $em->flush();
    $pairedGames =  $em->getRepository('FantasyFootballTournamentCoreBundle:Game')
                        ->findBy(['edition'=>$edition,'round'=>$nextRound]);
    return $this->render('FantasyFootballTournamentAdminBundle:Main:next_round.html.twig',
      ['edition'=>$edition,'round'=>$round,'games' => $pairedGames]);
  }

  protected function createDates($edition)
  {
    $em = $this->getDoctrine()->getManager();
    $editionObj = $em->getRepository('FantasyFootballTournamentCoreBundle:Edition')->find($edition);
    $dates = array();
    $firstDayRound = $editionObj->getFirstDayRound();
    for( $i = 0 ; $i < $firstDayRound ; $i++ ){
      $date = new \DateTime($editionObj->getDay1()->format('Y-m-d'));
      $date->setTime( 10 + ( $i * 3 ) , 0);
      $dates[] = $date->format('Y-m-d H:i');
    }
    $roundNumber = $editionObj->getRoundNumber();
    for(  ; $i < $roundNumber ; $i++ ){
      $date = new \DateTime($editionObj->getDay2()->format('Y-m-d'));
      $date->setTime(10 + ( ($i-$firstDayRound) * 3 ), 0);
      $dates[] = $date->format('Y-m-d H:i');
    }
    return $dates;
  }
    
  public function nafAction($edition,$format)
  {
    $dates = $this->createDates($edition);
    $em = $this->getDoctrine()->getManager();
    $games =  $em->getRepository('FantasyFootballTournamentCoreBundle:Game')->findByEdition($edition);
    $coachs =  $em->getRepository('FantasyFootballTournamentCoreBundle:Coach')->findByEdition($edition);
    if ( 'xml' === $format){
      $content = $this->renderView('FantasyFootballTournamentAdminBundle:Main:naf.xml.twig',
        ['coachs' => $coachs,'games' => $games,'dates'=>$dates]);

      $response = new Response($content);
      $response->headers->set('Content-Type', 'xml');
      return $response;
    }else{
      return $this->render('FantasyFootballTournamentAdminBundle:Main:naf.html.twig',
        ['edition'=>$edition,'coachs' => $coachs,'games' => $games,'dates'=>$dates]);
    }
  }
}
