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