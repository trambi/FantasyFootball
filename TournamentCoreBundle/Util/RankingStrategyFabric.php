<?php

namespace FantasyFootball\TournamentCoreBundle\Util;

use FantasyFootball\TournamentCoreBundle\Util\Rdvbb\RankingStrategy1;
use FantasyFootball\TournamentCoreBundle\Util\Rdvbb\RankingStrategy2To3;
use FantasyFootball\TournamentCoreBundle\Util\Rdvbb\RankingStrategy4To5;
use FantasyFootball\TournamentCoreBundle\Util\Rdvbb\RankingStrategy6To8;
use FantasyFootball\TournamentCoreBundle\Util\Rdvbb\RankingStrategy9To10;
use FantasyFootball\TournamentCoreBundle\Util\Rdvbb\RankingStrategy11;
use FantasyFootball\TournamentCoreBundle\Util\Rdvbb\RankingStrategy12;
use FantasyFootball\TournamentCoreBundle\Util\Rdvbb\RankingStrategy13;
use FantasyFootball\TournamentCoreBundle\Util\Rdvbb\RankingStrategy14;

class RankingStrategyFabric {
    static function getByName($name) {
        $strategy = null;
        if ( 'Rdvbb1' === $name ) {
            $strategy = new RankingStrategy1();
        } else if ( 'Rdvbb2To3' === $name ) {
            $strategy = new RankingStrategy2To3();
        } else if ( 'Rdvbb4To5' === $name ) {
            $strategy = new RankingStrategy4To5();
        } else if ( 'Rdvbb6To8' === $name ) {
            $strategy = new RankingStrategy6To8();
        } else if ( 'Rdvbb9To10' === $name ) {
            $strategy = new RankingStrategy9To10();
        } else if ( 'Rdvbb11' === $name ) {
            $strategy = new RankingStrategy11();
        } else if ( 'Rdvbb12' === $name ) {
            $strategy = new RankingStrategy12();
        } else if ( 'Rdvbb13' === $name ){
            $strategy = new RankingStrategy13();
        } else if ( 'Rdvbb14' === $name ){
            $strategy = new RankingStrategy14();
        }
        return $strategy;
    }

}