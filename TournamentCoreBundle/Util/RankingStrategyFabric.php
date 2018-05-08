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
use FantasyFootball\TournamentCoreBundle\Util\Eurobowl\RankingStrategy2017;

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
        }else if ( 'Eurobowl2017'=== $name ){
            $strategy = new RankingStrategy2017();
        }
        return $strategy;
    }

    static function getNames(){
        return ['Rdvbb1','Rdvbb2To3','Rdvbb4To5','Rdvbb6To8',
                'Rdvbb9To10','Rdvbb11','Rdvbb12','Rdvbb13',
                'Rdvbb14','Eurobowl2017'];
    }
}
