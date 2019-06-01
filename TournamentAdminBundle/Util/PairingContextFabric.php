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
use FantasyFootball\TournamentCoreBundle\DatabaseConfiguration;
use Doctrine\ORM\EntityManager;

class PairingContextFabric {

  static public function create(Edition $edition,EntityManager $em,DatabaseConfiguration $conf) {
    $round = $edition->getCurrentRound();
    $isFullTeam = $edition->getFullTriplette();

    if (0 == $round) {
      if ( TRUE === $isFullTeam ) {
        return new RandomCoachTeamPairingContext($edition,$em,$conf);
      } else {
        return new RandomCoachPairingContext($edition,$em,$conf);
      }
    } elseif ( ( 1 === $edition->getUseFinale() ) && ($edition->getRoundNumber() === $round +1 ) ) {
      if ( TRUE === $isFullTeam ) {
         return new RankedCoachTeamWithFinalePairingContext($edition,$em,$conf);
      } else {
        return new RankedCoachWithFinalePairingContext($edition,$em,$conf);
      }
    } else {
      if (TRUE === $isFullTeam) {
        return new RankedCoachTeamPairingContext($edition,$em,$conf);
      } else {
        return new RankedCoachPairingContext($edition,$em,$conf);
      }
    }
  }
}
