<?php

namespace FantasyFootball\TournamentAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use FantasyFootball\TournamentCoreBundle\Entity\Game;
use FantasyFootball\TournamentCoreBundle\Entity\Edition;

use FantasyFootball\TournamentCoreBundle\Util\IRankingStrategy;


class SimulateController extends Controller
{
  public function gamesAction($edition,$round)
  {
    $em = $this->getDoctrine()->getManager();
    $gamesToPlay = $em->getRepository('FantasyFootballTournamentCoreBundle:Game')
                    ->findBy(array('edition' => $edition,
                      'round' => $round,
                      'status' => 'programme'));
    $editionObj = $em->getRepository('FantasyFootballTournamentCoreBundle:Edition')
                      ->findOneById($edition);
    $strategy = $editionObj->getRankingStrategy();
    foreach ($gamesToPlay as $game) {
      $this->_simulateGame($strategy,$game);
    }
    $em->flush();
    return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_main'));
  }

  static protected $tdProbability = array(0,0,0,0,0,1,1,1,1,2,2,2,3,3,4,4,5);
  static protected $casProbability = array(0,0,1,1,1,1,1,2,2,2,3,3,4,5,6,7,8);
    
  protected function _simulateGame(IRankingStrategy $strategy, Game $game)
  {
    $game->setTd1(self::$tdProbability[rand(0,16)]);
    $game->setTd2(self::$tdProbability[rand(0,16)]);
    $game->setCasualties1(self::$casProbability[rand(0,16)]);
    $game->setCasualties2(self::$casProbability[rand(0,16)]);
    $points1 = 0;
    $points2 = 0;
    $strategy->computePoints($points1, $points2, $game->getTd1(), $game->getTd2(),
    $game->getCasualties1(), $game->getCasualties2() );
    $game->setPoints1($points1);
    $game->setPoints2($points2);
    $game->setStatus('resume');
  }
}