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
namespace FantasyFootball\TournamentCoreBundle\Util\Eurobowl;

use FantasyFootball\TournamentCoreBundle\Util\IRankingStrategy;
use FantasyFootball\TournamentCoreBundle\Util\Rdvbb\PointsComputor;

class RankingStrategy2017 implements IRankingStrategy {
  const POINT_MULTIPLIER = 0.5;

  public function useCoachTeamPoints() {
    return true;
  }

  public function computePoints(&$points1, &$points2, $td1, $td2, $cas1, $cas2) {
    $points = PointsComputor::win2Draw1Loss0($td1,$td2,$cas1,$cas2);
    $points1 = $points['points1'] * self::POINT_MULTIPLIER;
    $points2 = $points['points2'] * self::POINT_MULTIPLIER;
  }
    
  public function compareCoachs($coach1, $coach2) {
    $returnValue = 0;
    $params = array('points','opponentsPoints','netTd','netCasualties');
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
    $params = array('coachTeamPoints','opponentCoachTeamPoints','opponentsPoints','netTd','netCasualties');
    foreach ($params as $param){
      $returnValue = $item2->$param - $item1->$param ;
      if( 0 ==! $returnValue ){
        break;
      }
    }
    return $returnValue;
  }

  public function computeCoachTeamPoints(&$points1, &$points2, $td1Array, $td2Array, $cas1Array, $cas2Array) {
    $points = PointsComputor::teamWin2Draw1Loss0FullTeamBonus($td1Array, $td2Array, $cas1Array, $cas2Array);
    $points1 = $points['points1'] * self::POINT_MULTIPLIER;
    $points2 = $points['points2'] * self::POINT_MULTIPLIER;
  }

  public function useOpponentPointsOfYourOwnMatch() {
    return false;
  }

  public function rankingOptions(){
    return array(
    'coach' => array(
      'main' => array('points','opponentsPoints','netTd','netCasualties'),
      'td' => array('tdFor'),
      'casualties' => array('casualtiesFor'),
      'defense' => array('tdAgainst')
    ),
    'coachTeam' => array(
      'main' => array('coachTeamPoints','opponentCoachTeamPoints','netTd','netCasualties'),
      'td' => array('tdFor'),
      'casualties' => array('casualtiesFor'),
      'defense' => array('tdAgainst')
      )
    );
  }
}