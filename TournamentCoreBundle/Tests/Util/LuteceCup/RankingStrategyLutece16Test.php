<?php
/*
    FantasyFootball Symfony2 bundles - Symfony2 bundles collection to handle fantasy football tournament 
    Copyright (C) 2018  Bertrand Madet

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
namespace FantasyFootball\TournamentCoreBundle\Tests\Util\LuteceCup;

use FantasyFootball\TournamentCoreBundle\Util\LuteceCup\RankingStrategyLutece16;
use FantasyFootball\TournamentCoreBundle\Util\Rdvbb\PointsComputor;
use FantasyFootball\TournamentCoreBundle\Entity\Game;

class RankingStrategy2017Test extends \PHPUnit_Framework_TestCase {

  public function testComputePoints() {
    $strategy = new RankingStrategyLutece16();

    $points = $strategy->computePoints(new Game(1,0));
    $this->assertEquals(1003, $points[0]);
    $this->assertEquals(0, $points[1]);

    $points = $strategy->computePoints(new Game(2,0));
    $this->assertEquals(1006, $points[0]);
    $this->assertEquals(0, $points[1]);

    $points = $strategy->computePoints(new Game(2,1));
    $this->assertEquals(1006, $points[0]);
    $this->assertEquals(3, $points[1]);

    $points = $strategy->computePoints(new Game(2,2));
    $this->assertEquals(406, $points[0]);
    $this->assertEquals(406, $points[1]);

    $points = $strategy->computePoints(new Game(1,1));
    $this->assertEquals(403, $points[0]);
    $this->assertEquals(403, $points[1]);

    $points = $strategy->computePoints(new Game(1,2));
    $this->assertEquals(3, $points[0]);
    $this->assertEquals(1006, $points[1]);

    $points = $strategy->computePoints(new Game(1,3));
    $this->assertEquals(3, $points[0]);
    $this->assertEquals(1009, $points[1]);

    $points = $strategy->computePoints(new Game(0,3));
    $this->assertEquals(0, $points[0]);
    $this->assertEquals(1009, $points[1]);

    $game = new Game(1,0);
    $game->setCasualties1(1);
    $points = $strategy->computePoints($game);
    $this->assertEquals(1005, $points[0]);
    $this->assertEquals(0, $points[1]);

    $game->setCasualties1(0);
    $game->setCasualties2(1);
    $points = $strategy->computePoints($game);
    $this->assertEquals(1003, $points[0]);
    $this->assertEquals(2, $points[1]);
  }

  public function testComputeCoachTeamPoints() {
    $strategy = new RankingStrategyLutece16();

    $input = [];
    $expected = [];
    $input["all_win"] = [new Game(1,0),new Game(1,0),new Game(1,0),new Game(1,0),new Game(1,0)];
    $output["all_win"] = [10015,0];
    $input["all_draw"] = [new Game(0,0),new Game(0,0),new Game(0,0),new Game(0,0),new Game(0,0)];
    $output["all_draw"] = [4000,4000];
    $input["all_loss"] = [new Game(0,1),new Game(0,1),new Game(0,1),new Game(0,1),new Game(0,1)];
    $output["all_loss"] = [0,10015];
    $input["true_draw"] = [new Game(0,1),new Game(0,1),new Game(0,0),new Game(1,0),new Game(1,0)];
    $output["true_draw"] = [4406,4406];
    $input["almost_draw"] = [new Game(0,2),new Game(0,1),new Game(0,0),new Game(1,0),new Game(1,0)];
    $output["almost_draw"] = [4406,4409];

    foreach ($input as $testName => $games){
        $points = $strategy->computeCoachTeamPoints($games);
        $expected = $output[$testName];
        $this->assertEquals($expected[0],$points[0],$testName);
        $this->assertEquals($expected[1],$points[1],$testName);
    }
    


  }

  public function testUseTriplettePoints() {
    $strategy = new RankingStrategyLutece16();
    $result = $strategy->useCoachTeamPoints();
    $this->assertTrue($result);
  }

  public function testCompareCoach() {
    $strategy = new RankingStrategyLutece16();
    $coach1 = new \stdClass;
    $coach2 = new \stdClass;
    $coach1->points = 0;
    $coach2->points = 0;
    
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
  }

  public function testCompareCoachTeam() {
    $strategy = new RankingStrategyLutece16();
    $coachTeam1 = new \stdClass;
    $coachTeam2 = new \stdClass;
    $coachTeam1->coachTeamPoints = 0;
    $coachTeam2->coachTeamPoints = 0;

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
  }
  
  public function testRankingOptions(){
    $strategy = new RankingStrategyLutece16();
    $rankings = $strategy->rankingOptions();
    $this->assertGreaterThan(0,count($rankings));
  }
}
