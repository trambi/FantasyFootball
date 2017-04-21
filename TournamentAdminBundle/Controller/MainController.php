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
use FantasyFootball\TournamentCoreBundle\Util\DataProvider;
use FantasyFootball\TournamentAdminBundle\Util\PairingContextFabric;
use FantasyFootball\TournamentAdminBundle\Util\SwissRoundStrategy;

use Symfony\Component\HttpFoundation\Response;

class MainController extends Controller{

  protected function _indexActionNotStarted(\FantasyFootball\TournamentCoreBundle\Entity\Edition $edition) {
    $editionId = $edition->getId();
    $em = $this->getDoctrine()->getManager();
    $coachs = $em->getRepository('FantasyFootballTournamentCoreBundle:Coach')->findByEdition($editionId,array('name'=>'ASC'));
    $coachTeams = $em->getRepository('FantasyFootballTournamentCoreBundle:CoachTeam')->findByEditionJoined($editionId);
    return $this->render('FantasyFootballTournamentAdminBundle:Main:index_not_started.html.twig',
      ['edition' => $editionId,'currentRound' => $edition->getCurrentRound(),'coachs' => $coachs,'coachTeams' => $coachTeams]);
  }

  protected function _indexActionStarted(\FantasyFootball\TournamentCoreBundle\Entity\Edition $edition, $round) {
    $editionId = $edition->getId();
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $playedMatches = $data->getPlayedMatchsByEditionAndRound($edition->getId(), $round);
    $matchesToPlay = $data->getToPlayMatchsByEditionAndRound($edition->getId(), $round);
    return $this->render('FantasyFootballTournamentAdminBundle:Main:index.html.twig',
      ['edition' => $editionId,'round' => $round,'matchesToPlay' => $matchesToPlay,
        'playedMatches' => $playedMatches,'roundNumber' => $edition->getRoundNumber(),
        'rankings'=>$edition->getRankings()]);
  }

  public function indexAction($edition,$round){
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

  public function nextRoundAction($edition){
    $em = $this->getDoctrine()->getManager();
    $editionObj = $em->getRepository('FantasyFootballTournamentCoreBundle:Edition')->find($edition);
    $round = $editionObj->getCurrentRound();
    $count = $em->getRepository('FantasyFootballTournamentCoreBundle:Game')
                ->countScheduledGamesByEditionAndRound($edition,$round);
    if( ( 0 === $count ) || ( $editionObj->getRoundNumber() === $round  ) ){
      return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_main'));
    }
    $pairingContext = PairingContextFabric::create($editionObj,$em,$this->get('fantasy_football_core_db_conf'));
    list($toPair,$constraints,$alreadyPairedGames) = $pairingContext->init();
    $pairing = new SwissRoundStrategy();
    $games = $pairing->pairing(array(), $toPair,$constraints);
    if (null == $games){
      throw new \Exception('Impossible de générer un appariement !');
    }
    $games = array_merge($alreadyPairedGames,$games);
    $nextRound = $round + 1 ;
    $pairingContext->persist($games, $nextRound);
    $editionObj->setCurrentRound($nextRound);
    $em->flush();
    return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_main'));
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
