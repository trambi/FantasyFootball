<?php

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
