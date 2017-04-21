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
namespace FantasyFootball\TournamentAdminBundle\Tests\Util;

use FantasyFootball\TournamentAdminBundle\Util\SwissRoundStrategy;

class SwissRoundStrategyTest extends \PHPUnit_Framework_TestCase {
    /*
     * @param type $paired Liste d'appariements déjà effectués
     * @param type $toPaired Liste des restants à apparier triée
     * @param type $constraints Liste des appariements interdits
     * @return liste d'appariement satisfaisant les appariements interdits
     */

    public function testPairing() {
        $swiss = new SwissRoundStrategy();
        $paired = array();
        $toPaired = [1,2,3,4];
        $constraints = array();
        $pairings = $swiss->pairing($paired, $toPaired, $constraints);
        $this->assertEquals(2, \count($pairings));
        $this->assertEquals(2, \count($pairings[0]));
        $this->assertEquals(2, \count($pairings[1]));
        $this->assertEquals(1, $pairings[0][0]);
        $this->assertEquals(2, $pairings[0][1]);
        $this->assertEquals(3, $pairings[1][0]);
        $this->assertEquals(4, $pairings[1][1]);
        
        $constraints = [1=>[2],2=>[1],3=>[4],4=>[3]];
        $pairings = $swiss->pairing($paired, $toPaired, $constraints);
        $this->assertEquals(2, \count($pairings));
        $this->assertEquals(2, \count($pairings[0]));
        $this->assertEquals(2, \count($pairings[1]));
        $this->assertEquals(1, $pairings[0][0]);
        $this->assertEquals(3, $pairings[0][1]);
        $this->assertEquals(2, $pairings[1][0]);
        $this->assertEquals(4, $pairings[1][1]);
        
        $constraints = [1=>[2,3],2=>[1,4],3=>[4,1],4=>[3,2]];
        $pairings = $swiss->pairing($paired, $toPaired, $constraints);
        $this->assertEquals(2, \count($pairings));
        $this->assertEquals(2, \count($pairings[0]));
        $this->assertEquals(2, \count($pairings[1]));
        $this->assertEquals(1, $pairings[0][0]);
        $this->assertEquals(4, $pairings[0][1]);
        $this->assertEquals(2, $pairings[1][0]);
        $this->assertEquals(3, $pairings[1][1]);
    }

    public function testIsAllowed() {
        $swiss = new SwissRoundStrategy();
        $this->assertEquals(true, $swiss->isAllowed(1,2));
        
        $oneAndTwo = [1=>[2],2=>[1]];
        $this->assertEquals(false, $swiss->isAllowed(1,2,$oneAndTwo));
        
        $threeAndFour = [3=>[4],4=>[3]];
        $this->assertEquals(true, $swiss->isAllowed(1,2,$threeAndFour));
    }
}
