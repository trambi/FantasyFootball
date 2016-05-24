<?php

namespace FantasyFootball\TournamentAdminBundle\Util;

use FantasyFootball\TournamentCoreBundle\Entity\Edition;
use FantasyFootball\TournamentCoreBundle\Entity\Game;
use FantasyFootball\TournamentCoreBundle\DatabaseConfiguration;
use Doctrine\ORM\EntityManager;

abstract class CoachPairingContext extends PairingContext implements IPairingContext {

  public function __construct(Edition $edition,EntityManager $em,DatabaseConfiguration $conf){
    parent::__construct($edition, $em, $conf);
  }

  public function init(){
    return $this->customInit();
  }

  public function persist(Array $games,$round){
    $table = 1;
    $edition = $this->edition->getId();
    $coachsById = $this->$coachsById;
    foreach($games as $coachGame){
      $rosterId = $coachGame[0];
      $opponentRosterId = $coachGame[1];
      $game = new Game();
      $game->setEdition($edition);
      $game->setRound($round);
      $game->setTableNumber($table);
      $game->setCoach1($coachsById[$rosterId]);
      $game->setCoach2($coachsById[$opponentRosterId]);
      $em->persist($game);
      $table ++;
    }
  }

  abstract protected function customInit();
}