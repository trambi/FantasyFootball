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
namespace FantasyFootball\TournamentAdminBundle\Util;

use Doctrine\ORM\EntityManager;
use FantasyFootball\TournamentCoreBundle\Entity\Edition;
use FantasyFootball\TournamentCoreBundle\DatabaseConfiguration;
use FantasyFootball\TournamentCoreBundle\Util\DataProvider;
use FantasyFootball\TournamentCoreBundle\Entity\Game;

class RankedCoachTeamWithFinalePairingContext extends CoachTeamPairingContext {
    
  public function __construct(Edition $edition,EntityManager $em,DatabaseConfiguration $conf){
    parent::__construct($edition,$em,$conf);
  }

  public function customInit(){
    $data = new DataProvider($this->conf);
    $toPair = array();
    $strategy = $this->edition->getRankingStrategy();
    $coachTeamRanking = $data->getCoachTeamRanking($this->edition);
    $alreadyPairedGames = [[$coachTeamRanking[0]->id,$coachTeamRanking[1]->id]];
    $i = 1;
    foreach ($coachTeamRanking as $coachTeam){
      $id = $coachTeam->id;
      if( 2 < $i ){
        $toPair[] = $id;
      }
      
      $tempSortedCoach = array();
      foreach ($coachTeam->teams as $coach){
          $tempSortedCoach[] = $coach->id;
      }
      $this->sortedCoachsByTeamCoachId[$id] = $tempSortedCoach;
      $i++;
    }
    $constraints = $data->getCoachTeamGamesByEdition($this->edition->getId());

    return ([$toPair, $constraints,$alreadyPairedGames]);
  }
  
  public function persist(Array $games,$round) {
    $coachsById = $this->coachsById;
    $sortedCoachsByTeamCoachId = $this->sortedCoachsByTeamCoachId;
    $editionId = $this->edition->getId();
    $teamGameIndex = 0;
    foreach ( $games as $teamGame ) {
      $teamCoachId1 = $teamGame[0];
      $teamCoachId2 = $teamGame[1];
      $gameIndex = 1;
      foreach ( $sortedCoachsByTeamCoachId[$teamCoachId1] as $index => $rosterId ) {
        $opponentRosterIds = $sortedCoachsByTeamCoachId[$teamCoachId2];
        $opponentRosterId = $opponentRosterIds[$index];
        $game = new Game();
        $game->setEdition($editionId);
        $game->setRound($round);
        $game->setTableNumber( ($teamGameIndex * 10) + $gameIndex );
        if( ( 0 === $teamGameIndex ) && ( 1 === $gameIndex ) ){
          $game->setFinale(true);
        }
        $game->setCoach1($coachsById[$rosterId]);
        $game->setCoach2($coachsById[$opponentRosterId]);
        $this->em->persist($game);

        $gameIndex ++;
      }
      $teamGameIndex ++;
    }
  }
  
}
