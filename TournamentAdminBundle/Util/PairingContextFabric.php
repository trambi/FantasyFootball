<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FantasyFootball\TournamentAdminBundle\Util;

use FantasyFootball\TournamentCoreBundle\Entity\Edition;
use FantasyFootball\TournamentCoreBundle\DatabaseConfiguration;
use Doctrine\ORM\EntityManager;
/**
 * Description of PairingContextFabric
 *
 * @author Trambi
 */
class PairingContextFabric {

    static public function create(Edition $edition,
            EntityManager $em,
            DatabaseConfiguration $conf) {
        $round = $edition->getCurrentRound();
        $isFullTeam = $edition->getFullTriplette();

        if (0 == $round) {
            if (1 === $isFullTeam) {
                return new RandomCoachTeamPairingContext($edition,$em,$conf);
            } else {
                return new RandomCoachPairingContext($edition,$em,$conf);
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
