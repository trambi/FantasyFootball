<?php
/*
    FantasyFootball Symfony2 bundles - Symfony2 bundles collection to handle fantasy football tournament 
    Copyright (C) 2019  Bertrand Madet

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
namespace FantasyFootball\TournamentCoreBundle\Util\Rdvbb;

use FantasyFootball\TournamentCoreBundle\Util\IRankingStrategy;

class RankingStrategy17 implements IRankingStrategy {

  public function useCoachTeamPoints() {
    return true;
  }

  public function computePoints($game) {
    $points = PointsComputor::win5Draw2SmallLoss0Loss0($game);
    $points1 = $points[0] ;
    $points2 = $points[1] ;
    return [$points1,$points2];
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
    $returnValue = 1;
    $special1 = 0;
    $special2 = 0;
    if(isset($item1->special)){
      $special1 = $item1->special;
    }
    if(isset($item2->special)){
      $special2 = $item2->special;
    }
    $returnValue = $special2 - $special1;
    if(0 !== $returnValue){
      return $returnValue;
    }
    $params = array('coachTeamPoints','opponentCoachTeamPoints','points','netTd','netCasualties');
    foreach ($params as $param){
      $returnValue = $item2->$param - $item1->$param ;
      if( 0 ==! $returnValue ){
        break;
      }
    }
    return $returnValue;
  }

  public function computeCoachTeamPoints($games) {
    $points = PointsComputor::teamWin2TeamDraw1TeamLoss0($games);
    $points1 = $points[0] ;
    $points2 = $points[1] ;
    return [$points1,$points2];
  }

  public function useOpponentPointsOfYourOwnMatch() {
    return false;
  }

  public function rankingOptions(){
    return array(
    'coach' => array(
      'main' => array('points','opponentsPoints','netTd','netCasualties')
    ),
    'coachTeam' => array(
      'main' => array('coachTeamPoints','opponentCoachTeamPoints','points','netTd','netCasualties'),
      'comeback' => array('diffRanking','firstDayRanking','finalRanking'),
      'td' => array('tdFor'),
      'fouls' => array('foulsFor'),
      'casualties' => array('casualtiesFor'),
      'defense' => array('tdAgainst')
      ),
    'special' => array(
      'guest' => 'usage'
      )
    );
  }
}