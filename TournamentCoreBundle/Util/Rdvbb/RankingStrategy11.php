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

class RankingStrategy11 implements IRankingStrategy{
  const VAINQUEUR = 2;
  const FINALISTE = 1;
  const NORMAL = 0;

  public function useCoachTeamPoints(){
    return false;
  }

  public function computePoints($game){
    return PointsComputor::win8Draw4Loss0Bonus1($game);
  }

  public function compareCoachs($team1,$team2){
    return TeamComparator::finalPointsOpponentsPointsNetTdCasFor($team1,$team2);
  }


  public function compareCoachTeams($coachTeam1,$coachTeam2){
    return TeamComparator::pointsOpponentsPointsNetTdCasFor($coachTeam1,$coachTeam2);
  }

  public function computeCoachTeamPoints($games){
    return PointsComputor::teamWithWin8Draw4Loss0Bonus1($games);
  }

  public function useOpponentPointsOfYourOwnMatch(){
    return false;
  }
  
    public function rankingOptions(){
    return array(
    'coach' => array(
      'main' => array('points','opponentsPoints','netTd','casualtiesFor'),
      'td' => array('tdFor'),
      'casualties' => array('casualtiesFor'),
      'comeback' => array('diffRanking','firstDayRanking','finalRanking')
    ),
    'coachTeam' => array(
      'main' => array('points','opponentsPoints','netTd','casualtiesFor'),
      )
    );
  }
  

}

?>