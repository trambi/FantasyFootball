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
namespace FantasyFootball\TournamentCoreBundle\Tests\Util\Rdvbb;

use FantasyFootball\TournamentCoreBundle\Util\Rdvbb\RankingStrategy1;
use FantasyFootball\TournamentCoreBundle\Entity\Game;

class RankingStrategy1Test extends \PHPUnit_Framework_TestCase
{
  public function testComputePoints(){
    $strategy = new RankingStrategy1();
    $points = $strategy->computePoints(new Game(3,0));
    $this->assertEquals(5, $points[0]);
    $this->assertEquals(0, $points[1]);

    $points = $strategy->computePoints(new Game(2,0));
    $this->assertEquals(5, $points[0]);
    $this->assertEquals(0, $points[1]);

    $points = $strategy->computePoints(new Game(2,1));
    $this->assertEquals(5, $points[0]);
    $this->assertEquals(1, $points[1]);
     
    $points = $strategy->computePoints(new Game(2,2));
    $this->assertEquals(2, $points[0]);
    $this->assertEquals(2, $points[1]);
      
    $points = $strategy->computePoints(new Game(1,1));
    $this->assertEquals(2, $points[0]);
    $this->assertEquals(2, $points[1]);
      
    $points = $strategy->computePoints(new Game(1,2));
    $this->assertEquals(1, $points[0]);
    $this->assertEquals(5, $points[1]);

    $points = $strategy->computePoints(new Game(1,3));
    $this->assertEquals(0, $points[0]);
    $this->assertEquals(5, $points[1]);
      
    $points = $strategy->computePoints(new Game(0,3));
    $this->assertEquals(0, $points[0]);
    $this->assertEquals(5, $points[1]);        
  }

  public function testComputeCoachTeamPoints(){
    $strategy = new RankingStrategy1();
    $points = $strategy->computeCoachTeamPoints([new Game(2,0),new Game(2,0),new Game(2,0)]);
    $this->assertEquals(0, $points[0]);
    $this->assertEquals(0, $points[1]);

    $points = $strategy->computeCoachTeamPoints([new Game(1,1),new Game(2,1),new Game(1,2)]);
    $this->assertEquals(0, $points[0]);
    $this->assertEquals(0, $points[1]);
  }
  
  public function testUseTriplettePoints(){
    $strategy = new RankingStrategy1();
    $result = $strategy->useCoachTeamPoints();
    $this->assertFalse($result);
  }
  
  public function testCompareCoach(){
    $strategy = new RankingStrategy1();
    $coach1 = new \stdClass;
    $coach2 = new \stdClass;
    $coach1->points = 0;
    $coach2->points = 0;
    $coach1->netTd = 0;
    $coach2->netTd = 0;
    $coach1->casualtiesFor = 0;
    $coach2->casualtiesFor = 0;
      
    $result = $strategy->compareCoachs($coach1,$coach2);
    $this->assertEquals(0,$result);
    
    $coach1->points = 1;
    $result = $strategy->compareCoachs($coach1,$coach2);
    $this->assertLessThan(0,$result);
    
    $coach2->points = 2;
    $result = $strategy->compareCoachs($coach1,$coach2);
    $this->assertGreaterThan(0,$result);
    
    $coach1->points = 2;
    $coach2->netTd = 1;
    $result = $strategy->compareCoachs($coach1,$coach2);
    $this->assertGreaterThan(0,$result);
    
    $coach1->netTd = 1;
    $result = $strategy->compareCoachs($coach1,$coach2);
    $this->assertEquals(0,$result);
    
    $coach1->netTd = 2;
    $coach2->netTd = 1;
    $result = $strategy->compareCoachs($coach1,$coach2);
    $this->assertLessThan(0,$result);
    
    $coach1->netTd = 1;
    $coach2->netTd = 2;
    $result = $strategy->compareCoachs($coach1,$coach2);
    $this->assertGreaterThan(0,$result);
    
    $coach2->netTd = 1;
    $coach1->casualtiesFor = 2;
    $coach2->casualtiesFor = 1;
    $result = $strategy->compareCoachs($coach1,$coach2);
    $this->assertLessThan(0,$result);

    $coach2->casualtiesFor = 3;
    $result = $strategy->compareCoachs($coach1,$coach2);
    $this->assertGreaterThan(0,$result);
  }
    
  public function testRankingOptions(){
    $strategy = new RankingStrategy1();
    $rankings = $strategy->rankingOptions();
    $this->assertGreaterThan(0,count($rankings));
  }
}
?>