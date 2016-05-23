<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FantasyFootball\TournamentAdminBundle\Util;

use FantasyFootball\TournamentCoreBundle\Entity\Edition;
use FantasyFootball\TournamentCoreBundle\Entity\Game;
use FantasyFootball\TournamentCoreBundle\DatabaseConfiguration;
use Doctrine\ORM\EntityManager;

/**
 * Description of CoachTeamPairingContext
 *
 * @author Trambi
 */
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
        $game->setCoach1($coachsById[$rosterId]);
        $game->setCoach2($coachsById[$opponentRosterId]);
        $this->em->persist($game);

        $gameIndex ++;
      }
      $teamGameIndex ++;
    }
  }

}
