<?php
/*
    FantasyFootball Symfony2 bundles - Symfony2 bundles collection to handle fantasy football tournament 
    Copyright (C) 2018  Bertrand Madet

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
namespace FantasyFootball\TournamentCoreBundle\Util\LuteceCup;

use FantasyFootball\TournamentCoreBundle\Util\IRankingStrategy;
use FantasyFootball\TournamentCoreBundle\Util\Rdvbb\PointsComputor;

class RankingStrategyLutece16 implements IRankingStrategy {
  const POINT_MULTIPLIER = 0.5;

  public function useCoachTeamPoints() {
    return true;
  }

  public function computePoints($game) {
    $td1 = $game->getTd1();
    $td2 = $game->getTd2();
    $cas1 = $game->getCasualties1();    
    $cas2 = $game->getCasualties2();
    $compl1 = $game->getCompletions1();    
    $compl2 = $game->getCompletions2();

    if( $td1 === $td2 ){
        $points1 = 400;
        $points2 = 400;
    }else if( $td1 > $td2 ){
        $points1 = 1000;
        $points2 = 0;
    }else{
        $points2 = 1000;
        $points1 = 0;
    }
    $points1 += $td1 * 3 + $cas1 * 2 + $compl1;
    $points2 += $td2 * 3 + $cas2 * 2 + $compl2;
    return [$points1,$points2];
  }
    
  public function compareCoachs($coach1, $coach2) {
    $returnValue = 0;
    $params = array('points');
    foreach ($params as $param){
      $returnValue = $coach2->$param - $coach1->$param ;
      if( 0 ==! $returnValue ){
        break;
      }
    }
    return $returnValue;
  }

  public function compareCoachTeams($item1, $item2) {
    $returnValue = 0;
    $params = array('coachTeamPoints');
    foreach ($params as $param){
      $returnValue = $item2->$param - $item1->$param ;
      if( 0 ==! $returnValue ){
        break;
      }
    }
    return $returnValue;
  }

  public function computeCoachTeamPoints($games) {
    $points1 = 0;
    $points2 = 0;
    $win = 0;
    $loss = 0;
    foreach( $games as $game){
        $points = $this->computePoints($game);
        $points1 += $points[0];
        $points2 += $points[1];
        if ($points[0] > 1000 ){
            $win ++;
        }else if($points[0] < 400){
            $loss ++;
        }
    }
    if( $win > $loss ){
        $points1 += 5000;
    }else if ($win < $loss){
        $points2 += 5000;
    }else{
        $points1 += 2000;
        $points2 += 2000;
    }
    return [$points1,$points2];
  }

  public function useOpponentPointsOfYourOwnMatch() {
    return false;
  }

  public function rankingOptions(){
    return array(
    'coach' => array(
      'main' => array('points'),
      'casualties' => array('casualtiesFor'),
      'fouls'=> array('foulsFor'),
      'td' => array('tdFor'),
      'completions' => array('completionsFor'),
      'casualties' => array('casualtiesFor'),
      'defense' => array('tdAgainst')
    ),
    'coachTeam' => array(
      'main' => array('coachTeamPoints')
      )
    );
  }
}
