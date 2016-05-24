<?php

namespace FantasyFootball\TournamentAdminBundle\Util;

use FantasyFootball\TournamentCoreBundle\Entity\Edition;
use FantasyFootball\TournamentCoreBundle\DatabaseConfiguration;
use Doctrine\ORM\EntityManager;

class PairingContextFabric {

  static public function create(Edition $edition,EntityManager $em,DatabaseConfiguration $conf) {
    $round = $edition->getCurrentRound();
    $isFullTeam = $edition->getFullTriplette();

    if (0 == $round) {
      if ( 1 === $isFullTeam ) {
        return new RandomCoachTeamPairingContext($edition,$em,$conf);
      } else {
        return new RandomCoachPairingContext($edition,$em,$conf);
      }
    } elseif ( ( 1 === $edition->getUseFinale() ) && ($edition->getRoundNumber() === $round +1 ) ) {
      if ( 1 === $isFullTeam ) {
         return new RankedCoachTeamWithFinalePairingContext($edition,$em,$conf);
      } else {
        return new RankedCoachWithFinalePairingContext($edition,$em,$conf);
      }
    } else {
      if (1 === $isFullTeam) {
        return new RankedCoachTeamPairingContext($edition,$em,$conf);
      } else {
        return new RankedCoachPairingContext($edition,$em,$conf);
      }
    }
  }
}
