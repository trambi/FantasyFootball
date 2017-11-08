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
namespace FantasyFootball\TournamentCoreBundle\Tests\Util\Eurobowl;

use FantasyFootball\TournamentCoreBundle\Util\Eurobowl\RankingStrategy2017;
use FantasyFootball\TournamentCoreBundle\Util\Rdvbb\PointsComputor;

class RankingStrategy2017Test extends \PHPUnit_Framework_TestCase {

  public function testComputePoints() {
    $strategy = new RankingStrategy2017();
    $points1 = -1;
    $points2 = -1;

    $strategy->computePoints($points1, $points2, 3, 0, 1, 1);
    $this->assertEquals(1, $points1);
    $this->assertEquals(0, $points2);

    $strategy->computePoints($points1, $points2, 2, 0, 1, 1);
    $this->assertEquals(1, $points1);
    $this->assertEquals(0, $points2);

    $strategy->computePoints($points1, $points2, 2, 1, 1, 1);
    $this->assertEquals(1, $points1);
    $this->assertEquals(0, $points2);

    $strategy->computePoints($points1, $points2, 2, 2, 1, 1);
    $this->assertEquals(0.5, $points1);
    $this->assertEquals(0.5, $points2);

    $strategy->computePoints($points1, $points2, 1, 1, 3, 0);
    $this->assertEquals(0.5, $points1);
    $this->assertEquals(0.5, $points2);

    $strategy->computePoints($points1, $points2, 1, 2, 3, 0);
    $this->assertEquals(0, $points1);
    $this->assertEquals(1, $points2);

    $strategy->computePoints($points1, $points2, 1, 3, 3, 0);
    $this->assertEquals(0, $points1);
    $this->assertEquals(1, $points2);

    $strategy->computePoints($points1, $points2, 0, 3, 3, 0);
    $this->assertEquals(0, $points1);
    $this->assertEquals(1, $points2);
  }

  public function testComputeCoachTeamPoints() {
    $strategy = new RankingStrategy2017();
    $points1 = -1;
    $points2 = -1;

    $cas1Array = array(0, 0, 0);
    $cas2Array = array(0, 0, 0);

    $tds1 = array(2, 2, 2);
    $tds2 = array(0, 0, 0);
    $strategy->computeCoachTeamPoints($points1, $points2, $tds1, $tds2, $cas1Array, $cas2Array);
    $this->assertEquals(4, $points1);
    $this->assertEquals(0, $points2);

    $tds1 = array(2, 2, 2);
    $tds2 = array(1, 0, 0);
    $strategy->computeCoachTeamPoints($points1, $points2, $tds1, $tds2, $cas1Array, $cas2Array);
    $this->assertEquals(4, $points1);
    $this->assertEquals(0, $points2);

    $tds1 = array(2, 2, 2);
    $tds2 = array(2, 0, 0);
    $strategy->computeCoachTeamPoints($points1, $points2, $tds1, $tds2, $cas1Array, $cas2Array);
    $this->assertEquals(3.5, $points1);
    $this->assertEquals(0.5, $points2);

    $tds1 = array(2, 2, 2);
    $tds2 = array(3, 0, 0);
    $strategy->computeCoachTeamPoints($points1, $points2, $tds1, $tds2, $cas1Array, $cas2Array);
    $this->assertEquals(3, $points1);
    $this->assertEquals(1, $points2);

    $tds1 = array(2, 2, 2);
    $tds2 = array(4, 0, 0);
    $strategy->computeCoachTeamPoints($points1, $points2, $tds1, $tds2, $cas1Array, $cas2Array);
    $this->assertEquals(3, $points1);
    $this->assertEquals(1, $points2);

    $tds1 = array(0, 2, 2);
    $tds2 = array(2, 1, 0);
    $strategy->computeCoachTeamPoints($points1, $points2, $tds1, $tds2, $cas1Array, $cas2Array);
    $this->assertEquals(3, $points1);
    $this->assertEquals(1, $points2);

    $tds1 = array(0, 2, 2);
    $tds2 = array(2, 2, 0);
    $strategy->computeCoachTeamPoints($points1, $points2, $tds1, $tds2, $cas1Array, $cas2Array);
    $this->assertEquals(2, $points1);
    $this->assertEquals(2, $points2);

    $tds1 = array(0, 2, 2);
    $tds2 = array(2, 3, 0);
    $strategy->computeCoachTeamPoints($points1, $points2, $tds1, $tds2, $cas1Array, $cas2Array);
    $this->assertEquals(1, $points1);
    $this->assertEquals(3, $points2);

    $tds1 = array(0, 2, 2);
    $tds2 = array(2, 4, 0);
    $strategy->computeCoachTeamPoints($points1, $points2, $tds1, $tds2, $cas1Array, $cas2Array);
    $this->assertEquals(1, $points1);
    $this->assertEquals(3, $points2);

    $tds1 = array(0, 0, 2);
    $tds2 = array(2, 2, 1);
    $strategy->computeCoachTeamPoints($points1, $points2, $tds1, $tds2, $cas1Array, $cas2Array);
    $this->assertEquals(1, $points1);
    $this->assertEquals(3, $points2);

    $tds1 = array(0, 0, 2);
    $tds2 = array(2, 2, 2);
    $strategy->computeCoachTeamPoints($points1, $points2, $tds1, $tds2, $cas1Array, $cas2Array);
    $this->assertEquals(0.5, $points1);
    $this->assertEquals(3.5, $points2);

    $tds1 = array(0, 0, 1);
    $tds2 = array(2, 2, 2);
    $strategy->computeCoachTeamPoints($points1, $points2, $tds1, $tds2, $cas1Array, $cas2Array);
    $this->assertEquals(0, $points1);
    $this->assertEquals(4, $points2);

    $tds1 = array(0, 0, 0);
    $tds2 = array(2, 2, 2);
    $strategy->computeCoachTeamPoints($points1, $points2, $tds1, $tds2, $cas1Array, $cas2Array);
    $this->assertEquals(0, $points1);
    $this->assertEquals(4, $points2);

    $tds1 = array(1, 1, 1);
    $tds2 = array(1, 1, 1);
    $strategy->computeCoachTeamPoints($points1, $points2, $tds1, $tds2, $cas1Array, $cas2Array);
    $this->assertEquals(2, $points1);
    $this->assertEquals(2, $points2);

    $tds1 = array(1, 2, 1);
    $tds2 = array(1, 1, 2);
    $strategy->computeCoachTeamPoints($points1, $points2, $tds1, $tds2, $cas1Array, $cas2Array);
    $this->assertEquals(2, $points1);
    $this->assertEquals(2, $points2);
    
    $tds1 = array(2, 1, 0);
    $tds2 = array(0, 1, 1);
    $strategy->computeCoachTeamPoints($points1, $points2, $tds1, $tds2, $cas1Array, $cas2Array);
    $this->assertEquals(2, $points1);
    $this->assertEquals(2, $points2);
    
    
  }

  public function testUseTriplettePoints() {
    $strategy = new RankingStrategy2017();
    $result = $strategy->useCoachTeamPoints();
    $this->assertTrue($result);
  }

  public function testCompareCoach() {
    $strategy = new RankingStrategy2017();
    $coach1 = new \stdClass;
    $coach2 = new \stdClass;
    $coach1->points = 0;
    $coach2->points = 0;
    $coach1->win = 0;
    $coach2->win = 0;
    $coach1->draw = 0;
    $coach2->draw = 0;
    $coach1->opponentsPoints = 0;
    $coach2->opponentsPoints = 0;
    $coach1->netTd = 0;
    $coach2->netTd = 0;
    $coach1->netCasualties = 0;
    $coach2->netCasualties = 0;
    
    // strictly equal
    $result = $strategy->compareCoachs($coach1, $coach2);
    $this->assertEquals(0, $result);

    // coach1 has more points
    $coach1->points = 1;
    $result = $strategy->compareCoachs($coach1, $coach2);
    $this->assertLessThan(0, $result);

    // coach2 has more points
    $coach2->points = 2;
    $result = $strategy->compareCoachs($coach1, $coach2);
    $this->assertGreaterThan(0, $result);

    // coach1 has some points and more opponentsPoints
    $coach1->points = 2;
    $coach1->opponentsPoints = 1;
    $result = $strategy->compareCoachs($coach1, $coach2);
    $this->assertLessThan(0, $result);

    // coach2 has more opponentsPoints
    $coach2->opponentsPoints = 2;
    $result = $strategy->compareCoachs($coach1, $coach2);
    $this->assertGreaterThan(0, $result);

    // coach1 has more netTd
    $coach1->opponentsPoints = 2;
    $coach1->netTd = 1;
    $result = $strategy->compareCoachs($coach1, $coach2);
    $this->assertLessThan(0, $result);
    
    // coach2 has more netTd
    $coach2->netTd = 2;
    $result = $strategy->compareCoachs($coach1, $coach2);
    $this->assertGreaterThan(0, $result);
    
    // coach1  has more netCasualties
    $coach2->netTd = 1;
    $coach1->netCasualties = 1;
    $result = $strategy->compareCoachs($coach1, $coach2);
    $this->assertLessThan(0, $result);
    
    // coach2 has more netCasualties
    $coach2->netCasualties = 2;
    $result = $strategy->compareCoachs($coach1, $coach2);
    $this->assertGreaterThan(0, $result);
  }

  public function testCompareCoachTeam() {
    $strategy = new RankingStrategy2017();
    $coachTeam1 = new \stdClass;
    $coachTeam2 = new \stdClass;
    $coachTeam1->coachTeamPoints = 0;
    $coachTeam2->coachTeamPoints = 0;
    $coachTeam1->netTd = 0;
    $coachTeam2->netTd = 0;
    $coachTeam1->netCasualties = 0;
    $coachTeam2->netCasualties = 0;
    $coachTeam1->opponentCoachTeamPoints = 0;
    $coachTeam2->opponentCoachTeamPoints = 0;
    $coachTeam1->opponentsPoints = 0;
    $coachTeam2->opponentsPoints = 0;

    // strictly equal
    $result = $strategy->compareCoachTeams($coachTeam1, $coachTeam2);
    $this->assertEquals(0, $result);
    
    // coachTeam1 has more points
    $coachTeam1->coachTeamPoints = 1;
    $result = $strategy->compareCoachTeams($coachTeam1, $coachTeam2);
    $this->assertLessThan(0, $result);

    // coachTeam2 has more points
    $coachTeam2->coachTeamPoints = 2;
    $result = $strategy->compareCoachTeams($coachTeam1, $coachTeam2);
    $this->assertGreaterThan(0, $result);
    
    // coachTeam1 has more opponentCoachTeamPoints
    $coachTeam1->coachTeamPoints = 2;
    $coachTeam1->opponentCoachTeamPoints = 1;
    $result = $strategy->compareCoachTeams($coachTeam1, $coachTeam2);
    $this->assertLessThan(0, $result);

    // coachTeam2 has more opponentCoachTeamPoints
    $coachTeam2->opponentCoachTeamPoints = 2;
    $result = $strategy->compareCoachTeams($coachTeam1, $coachTeam2);
    $this->assertGreaterThan(0, $result);

    // coachTeam1 has more netTd
    $coachTeam2->opponentCoachTeamPoints = 1;
    $coachTeam1->netTd = 1;
    $result = $strategy->compareCoachTeams($coachTeam1, $coachTeam2);
    $this->assertLessThan(0, $result);

    // coachTeam2 has more netTd
    $coachTeam1->netTd = 0;
    $coachTeam2->netTd = 1;
    $result = $strategy->compareCoachTeams($coachTeam1, $coachTeam2);
    $this->assertGreaterThan(0, $result);

    // coachTeam1 has more netCasualties
    $coachTeam1->netTd = 1;
    $coachTeam1->netCasualties = 1;
    $result = $strategy->compareCoachTeams($coachTeam1, $coachTeam2);
    $this->assertLessThan(0, $result);

    // coachTeam2 has more netCasualties
    $coachTeam1->netCasualties = 0;
    $coachTeam2->netCasualties = 1;
    $result = $strategy->compareCoachTeams($coachTeam1, $coachTeam2);
    $this->assertGreaterThan(0, $result);
  }
  
  public function testRankingOptions(){
    $strategy = new RankingStrategy2017();
    $rankings = $strategy->rankingOptions();
    $this->assertGreaterThan(0,count($rankings));
  }

  public function testCompareCoachTeamWithFloat() {
    $strategy = new RankingStrategy2017();
    $coachTeam1 = new \stdClass;
    $coachTeam2 = new \stdClass;
    $coachTeam3 = new \stdClass;
    $coachTeam4 = new \stdClass;
    $coachTeam1->coachTeamPoints = 0;
    $coachTeam2->coachTeamPoints = 0;
    $coachTeam3->coachTeamPoints = 0;
    $coachTeam4->coachTeamPoints = 0;
    $coachTeam1->netTd = 0;
    $coachTeam2->netTd = 0;
    $coachTeam3->netTd = 0;
    $coachTeam4->netTd = 0;
    $coachTeam1->netCasualties = 0;
    $coachTeam2->netCasualties = 0;
    $coachTeam3->netCasualties = 0;
    $coachTeam4->netCasualties = 0;
    $coachTeam1->opponentCoachTeamPoints = 0;
    $coachTeam2->opponentCoachTeamPoints = 0;
    $coachTeam3->opponentCoachTeamPoints = 0;
    $coachTeam4->opponentCoachTeamPoints = 0;
    $coachTeam1->opponentsPoints = 0;
    $coachTeam2->opponentsPoints = 0;
    $coachTeam3->opponentsPoints = 0;
    $coachTeam4->opponentsPoints = 0;

    // strictly equal
    $result = $strategy->compareCoachTeams($coachTeam1, $coachTeam2);
    $this->assertEquals(0, $result);
    
    
    $coachTeam1->coachTeamPoints = 1;
    $coachTeam2->coachTeamPoints = 0.5;
    $coachTeam3->coachTeamPoints = 0.5;
    $coachTeam4->coachTeamPoints = 0;

    // 1 is first
    $result = $strategy->compareCoachTeams($coachTeam1, $coachTeam2);
    $this->assertLessThan(0, $result);
    $result = $strategy->compareCoachTeams($coachTeam1, $coachTeam3);
    $this->assertLessThan(0, $result);
    $result = $strategy->compareCoachTeams($coachTeam1, $coachTeam4);
    $this->assertLessThan(0, $result);

    // equality for 2 and 3
    $result = $strategy->compareCoachTeams($coachTeam2, $coachTeam3);
    $this->assertEquals(0, $result);

    // 4 is last
    $result = $strategy->compareCoachTeams($coachTeam2, $coachTeam4);
    $this->assertLessThan(0, $result);
    $result = $strategy->compareCoachTeams($coachTeam3, $coachTeam4);
    $this->assertLessThan(0, $result);

    $coachTeam1->coachTeamPoints = 0.5;
    $coachTeam2->coachTeamPoints = 1.5;
    $coachTeam3->coachTeamPoints = 2.5;
    $coachTeam4->coachTeamPoints = 3.5;

    // 
    $result = $strategy->compareCoachTeams($coachTeam1, $coachTeam2);
    $this->assertGreaterThan(0, $result);

    $result = $strategy->compareCoachTeams($coachTeam2, $coachTeam3);
    $this->assertGreaterThan(0, $result);

    $result = $strategy->compareCoachTeams($coachTeam3, $coachTeam4);
    $this->assertGreaterThan(0, $result);

    $result = $strategy->compareCoachTeams($coachTeam1, $coachTeam3);
    $this->assertGreaterThan(0, $result);

    $result = $strategy->compareCoachTeams($coachTeam1, $coachTeam4);
    $this->assertGreaterThan(0, $result);

    $result = $strategy->compareCoachTeams($coachTeam2, $coachTeam4);
    $this->assertGreaterThan(0, $result);
    
  }
}
