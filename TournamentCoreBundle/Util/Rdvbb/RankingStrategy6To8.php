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
namespace FantasyFootball\TournamentCoreBundle\Util\Rdvbb;

use FantasyFootball\TournamentCoreBundle\Util\IRankingStrategy;

class RankingStrategy6To8 implements IRankingStrategy{

  public function useCoachTeamPoints(){
    return false;
  }  
  
  public function computePoints($game){
    return PointsComputor::win5Draw3SmallLoss1Loss0($game);
  }

  public function compareCoachs($team1,$team2){
    return TeamComparator::finalPointsOpponentsPointsNetTdCasFor($team1,$team2);
  }
  
  public function compareCoachTeams($coachTeam1,$coachTeam2){
    return TeamComparator::finalPointsOpponentsPointsNetTdCasFor($coachTeam1,$coachTeam2);
  }
    
  public function computeCoachTeamPoints($games){
    $points1 = 0;
    $points2 = 0;
    $gameNumber = count($games);
    for( $i = 0 ; $i < $gameNumber ; $i++){
      $points = PointsComputor::win5Draw3SmallLoss1Loss0($games[$i]);
      $points1 += $points[0];
      $points2 += $points[1];
    }
    return [$points1,$points2];
  }  

  public function useOpponentPointsOfYourOwnMatch(){
    return false;   
  }
  
  public function rankingOptions(){
    return array(
    'coach' => array(
      'main' => array('points','opponentPoints','netTd','casualtiesFor'),
      'td' => array('tdFor'),
      'casualties' => array('casualtiesFor'),
      'comeback' => array('diffRanking','firstDayRanking','finalRanking')
    ),
    'coachTeam' => array(
      'main' => array('points','opponentPoints','netTd','casualtiesFor'),
      )
    );
  }
}

?>