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

use FantasyFootball\TournamentCoreBundle\Entity\Edition;
use FantasyFootball\TournamentCoreBundle\Entity\Game;
use FantasyFootball\TournamentCoreBundle\DatabaseConfiguration;
use Doctrine\ORM\EntityManager;

abstract class CoachTeamPairingContext extends PairingContext implements IPairingContext {

  protected $coachsById;
  protected $sortedCoachsByTeamCoachId;

  public function __construct(Edition $edition,EntityManager $em,DatabaseConfiguration $conf){
    parent::__construct($edition, $em, $conf);
    $this->coachsById = array();
    $this->sortedCoachsByTeamCoachId = array();
  }

  public function init(){
    $editionId = $this->edition->getId();
    $coachs = $this->em->getRepository('FantasyFootballTournamentCoreBundle:Coach')
                        ->findByEdition($editionId);
    foreach ($coachs as $coach){
      $this->coachsById[$coach->getId()] = $coach;
    }
    return $this->customInit();
  }

  abstract protected function customInit();

  public function persist(Array $games,$round) {
    $coachsById = $this->coachsById;
    $sortedCoachsByTeamCoachId = $this->sortedCoachsByTeamCoachId;
    $editionId = $this->edition->getId();
    $teamGameIndex = 1;
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
        $game->setCoach1($coachsById[$rosterId]);
        $game->setCoach2($coachsById[$opponentRosterId]);
        $this->em->persist($game);

        $gameIndex ++;
      }
      $teamGameIndex ++;
    }
  }

}
