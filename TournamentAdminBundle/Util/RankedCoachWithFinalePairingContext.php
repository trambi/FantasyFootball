<?php

namespace FantasyFootball\TournamentAdminBundle\Util;

use Doctrine\ORM\EntityManager;
use FantasyFootball\TournamentCoreBundle\Entity\Edition;
use FantasyFootball\TournamentCoreBundle\DatabaseConfiguration;
use FantasyFootball\TournamentCoreBundle\Util\DataProvider;
use FantasyFootball\TournamentCoreBundle\Util\RankingStrategyFabric;

class RankedCoachWithFinalePairingContext extends CoachPairingContext {

  public function __construct(Edition $edition,EntityManager $em,DatabaseConfiguration $conf){
    parent::__construct($edition,$em,$conf);
  }

  protected function customInit()
  {
    $data = new DataProvider($this->conf);
    $strategy = $this->edition->getRankingStrategy();

    $coachRanking = $data->getMainCoachRanking($this->edition);
    $toPair = array_keys($coachRanking);

    $constraints = $data->getCoachGamesByEdition($this->edition->getId());
    $alreadyPairedGames = [array_shift($toPair),array_shift($toPair)]];
    return ([$toPair, $constraints,$alreadyPairedGames]);
  }
}
