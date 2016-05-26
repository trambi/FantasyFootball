<?php

namespace FantasyFootball\TournamentCoreBundle\Tests\Util\Rdvbb;

use FantasyFootball\TournamentCoreBundle\Util\Rdvbb\RankingStrategy14;

class RankingStrategy14Test extends \PHPUnit_Framework_TestCase {

  public function testComputePoints() {
    $strategy = new RankingStrategy14();
    $points1 = -1;
    $points2 = -1;

    $strategy->computePoints($points1, $points2, 3, 0, 1, 1);
    $this->assertEquals(1000, $points1);
    $this->assertEquals(0, $points2);

    $strategy->computePoints($points1, $points2, 2, 0, 1, 1);
    $this->assertEquals(1000, $points1);
    $this->assertEquals(0, $points2);

    $strategy->computePoints($points1, $points2, 2, 1, 1, 1);
    $this->assertEquals(1000, $points1);
    $this->assertEquals(0, $points2);

    $strategy->computePoints($points1, $points2, 2, 2, 1, 1);
    $this->assertEquals(500, $points1);
    $this->assertEquals(500, $points2);

    $strategy->computePoints($points1, $points2, 1, 1, 3, 0);
    $this->assertEquals(500, $points1);
    $this->assertEquals(500, $points2);

    $strategy->computePoints($points1, $points2, 1, 2, 3, 0);
    $this->assertEquals(0, $points1);
    $this->assertEquals(1000, $points2);

    $strategy->computePoints($points1, $points2, 1, 3, 3, 0);
    $this->assertEquals(0, $points1);
    $this->assertEquals(1000, $points2);

    $strategy->computePoints($points1, $points2, 0, 3, 3, 0);
    $this->assertEquals(0, $points1);
    $this->assertEquals(1000, $points2);
  }

  public function testComputeCoachTeamPoints() {
    $strategy = new RankingStrategy14();
    $points1 = -1;
    $points2 = -1;


    $cas1Array = array(0, 0, 0);
    $cas2Array = array(0, 0, 0);

    $tds1 = array(2, 2, 2);
    $tds2 = array(0, 0, 0);
    $strategy->computeCoachTeamPoints($points1, $points2, $tds1, $tds2, $cas1Array, $cas2Array);
    $this->assertEquals(1000, $points1);
    $this->assertEquals(0, $points2);

    $tds1 = array(2, 2, 2);
    $tds2 = array(1, 0, 0);
    $strategy->computeCoachTeamPoints($points1, $points2, $tds1, $tds2, $cas1Array, $cas2Array);
    $this->assertEquals(1000, $points1);
    $this->assertEquals(0, $points2);

    $tds1 = array(2, 2, 2);
    $tds2 = array(2, 0, 0);
    $strategy->computeCoachTeamPoints($points1, $points2, $tds1, $tds2, $cas1Array, $cas2Array);
    $this->assertEquals(1000, $points1);
    $this->assertEquals(0, $points2);

    $tds1 = array(2, 2, 2);
    $tds2 = array(3, 0, 0);
    $strategy->computeCoachTeamPoints($points1, $points2, $tds1, $tds2, $cas1Array, $cas2Array);
    $this->assertEquals(1000, $points1);
    $this->assertEquals(0, $points2);

    $tds1 = array(2, 2, 2);
    $tds2 = array(4, 0, 0);
    $strategy->computeCoachTeamPoints($points1, $points2, $tds1, $tds2, $cas1Array, $cas2Array);
    $this->assertEquals(1000, $points1);
    $this->assertEquals(0, $points2);

    $tds1 = array(0, 2, 2);
    $tds2 = array(2, 1, 0);
    $strategy->computeCoachTeamPoints($points1, $points2, $tds1, $tds2, $cas1Array, $cas2Array);
    $this->assertEquals(1000, $points1);
    $this->assertEquals(0, $points2);

    $tds1 = array(0, 2, 2);
    $tds2 = array(2, 2, 0);
    $strategy->computeCoachTeamPoints($points1, $points2, $tds1, $tds2, $cas1Array, $cas2Array);
    $this->assertEquals(500, $points1);
    $this->assertEquals(500, $points2);

    $tds1 = array(0, 2, 2);
    $tds2 = array(2, 3, 0);
    $strategy->computeCoachTeamPoints($points1, $points2, $tds1, $tds2, $cas1Array, $cas2Array);
    $this->assertEquals(0, $points1);
    $this->assertEquals(1000, $points2);

    $tds1 = array(0, 2, 2);
    $tds2 = array(2, 4, 0);
    $strategy->computeCoachTeamPoints($points1, $points2, $tds1, $tds2, $cas1Array, $cas2Array);
    $this->assertEquals(0, $points1);
    $this->assertEquals(1000, $points2);

    $tds1 = array(0, 0, 2);
    $tds2 = array(2, 2, 1);
    $strategy->computeCoachTeamPoints($points1, $points2, $tds1, $tds2, $cas1Array, $cas2Array);
    $this->assertEquals(0, $points1);
    $this->assertEquals(1000, $points2);

    $tds1 = array(0, 0, 2);
    $tds2 = array(2, 2, 2);
    $strategy->computeCoachTeamPoints($points1, $points2, $tds1, $tds2, $cas1Array, $cas2Array);
    $this->assertEquals(0, $points1);
    $this->assertEquals(1000, $points2);

    $tds1 = array(0, 0, 1);
    $tds2 = array(2, 2, 2);
    $strategy->computeCoachTeamPoints($points1, $points2, $tds1, $tds2, $cas1Array, $cas2Array);
    $this->assertEquals(0, $points1);
    $this->assertEquals(1000, $points2);

    $tds1 = array(0, 0, 0);
    $tds2 = array(2, 2, 2);
    $strategy->computeCoachTeamPoints($points1, $points2, $tds1, $tds2, $cas1Array, $cas2Array);
    $this->assertEquals(0, $points1);
    $this->assertEquals(1000, $points2);

    $tds1 = array(1, 1, 1);
    $tds2 = array(1, 1, 1);
    $strategy->computeCoachTeamPoints($points1, $points2, $tds1, $tds2, $cas1Array, $cas2Array);
    $this->assertEquals(500, $points1);
    $this->assertEquals(500, $points2);

    $tds1 = array(1, 2, 1);
    $tds2 = array(1, 1, 2);
    $strategy->computeCoachTeamPoints($points1, $points2, $tds1, $tds2, $cas1Array, $cas2Array);
    $this->assertEquals(500, $points1);
    $this->assertEquals(500, $points2);
  }

  public function testUseTriplettePoints() {
    $strategy = new RankingStrategy14();
    $result = $strategy->useCoachTeamPoints();
    $this->assertTrue($result);
  }

  public function testCompareCoach() {
    $strategy = new RankingStrategy14();
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

    // coach1 has more wins
    $coach1->points = 2;
    $coach1->win = 1;
    $coach2->win = 0;
    $result = $strategy->compareCoachs($coach1, $coach2);
    $this->assertLessThan(0, $result);

    // coach2 has more wins
    $coach2->win = 2;
    $coach1->draw = 1;
    $result = $strategy->compareCoachs($coach1, $coach2);
    $this->assertgreaterThan(0, $result);

    // coach1 has more draws
    $coach1->win = 2;
    $coach2->draw = 0;
    $result = $strategy->compareCoachs($coach1, $coach2);
    $this->assertLessThan(0, $result);

    // coach2 has more draws
    $coach2->draw = 2;
    $result = $strategy->compareCoachs($coach1, $coach2);
    $this->assertgreaterThan(0, $result);
    
    // coach1 has more opponentsPoints
    $coach1->draw = 2;
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
    
    // coach1 and coach2 has same netTd + netCasualties
    $coach1->netCasualties = 1;
    $result = $strategy->compareCoachs($coach1, $coach2);
    $this->assertEquals(0, $result);
    
    // coach1 has more netTd + netCasualties
    $coach1->netCasualties = 2;
    $result = $strategy->compareCoachs($coach1, $coach2);
    $this->assertLessThan(0, $result);
    
    // coach2 has more netTd + netCasualties
    $coach2->netCasualties = 2;
    $result = $strategy->compareCoachs($coach1, $coach2);
    $this->assertGreaterThan(0, $result);
  }

  public function testCompareCoachTeam() {
    $strategy = new RankingStrategy14();
    $coachTeam1 = new \stdClass;
    $coachTeam2 = new \stdClass;
    $coachTeam1->coachTeamPoints = 0;
    $coachTeam2->coachTeamPoints = 0;
    $coachTeam1->win = 0;
    $coachTeam2->win = 0;
    $coachTeam1->draw = 0;
    $coachTeam2->draw = 0;
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

    // coachTeam1 has more wins
    $coachTeam1->coachTeamPoints = 2;
    $coachTeam1->win = 1;
    $coachTeam2->win = 0;
    $result = $strategy->compareCoachTeams($coachTeam1, $coachTeam2);
    $this->assertLessThan(0, $result);

    // coachTeam2 has more wins
    $coachTeam2->win = 2;
    $coachTeam1->draw = 1;
    $result = $strategy->compareCoachTeams($coachTeam1, $coachTeam2);
    $this->assertgreaterThan(0, $result);

    // coachTeam1 has more draws
    $coachTeam1->win = 2;
    $coachTeam2->draw = 0;
    $result = $strategy->compareCoachTeams($coachTeam1, $coachTeam2);
    $this->assertLessThan(0, $result);

    // coachTeam2 has more draws
    $coachTeam2->draw = 2;
    $result = $strategy->compareCoachTeams($coachTeam1, $coachTeam2);
    $this->assertgreaterThan(0, $result);
    
    // coachTeam1 has more opponentCoachTeamPoints
    $coachTeam1->draw = 2;
    $coachTeam1->opponentCoachTeamPoints = 1;
    $result = $strategy->compareCoachTeams($coachTeam1, $coachTeam2);
    $this->assertLessThan(0, $result);

    // coachTeam2 has more opponentCoachTeamPoints
    $coachTeam2->opponentCoachTeamPoints = 2;
    $result = $strategy->compareCoachTeams($coachTeam1, $coachTeam2);
    $this->assertGreaterThan(0, $result);
    
    // coachTeam1 has more opponentsPoints
    $coachTeam1->opponentCoachTeamPoints = 2;
    $coachTeam1->opponentsPoints = 1;
    $result = $strategy->compareCoachTeams($coachTeam1, $coachTeam2);
    $this->assertLessThan(0, $result);

    // coachTeam2 has more opponentsPoints
    $coachTeam2->opponentsPoints = 2;
    $result = $strategy->compareCoachTeams($coachTeam1, $coachTeam2);
    $this->assertGreaterThan(0, $result);

    // coachTeam1 is winner 
    $coachTeam1->special = 2;
    $this->assertLessThan(0, $strategy->compareCoachTeams($coachTeam1, $coachTeam2));
    $coachTeam2->special=1;
    $this->assertLessThan(0, $strategy->compareCoachTeams($coachTeam1, $coachTeam2));
    
    // coachTeam1 is runner-up
    $coachTeam1->special = 1;
    $coachTeam2->special = 0;
    $this->assertLessThan(0, $strategy->compareCoachTeams($coachTeam1, $coachTeam2));
    
    // coachTeam2 is runner-up
    $coachTeam1->special = 0;
    $coachTeam2->special = 1;
    $this->assertGreaterThan(0, $strategy->compareCoachTeams($coachTeam1, $coachTeam2));
    
    // coachTeam2 is winner
    $coachTeam2->special = 2;
    $coachTeam1->special = 1;
    $this->assertGreaterThan(0, $strategy->compareCoachTeams($coachTeam1, $coachTeam2));
    $coachTeam1->special = 0;
    $this->assertGreaterThan(0, $strategy->compareCoachTeams($coachTeam1, $coachTeam2));
  }
  
  public function testRankingOptions(){
    $strategy = new RankingStrategy14();
    $rankings = $strategy->rankingOptions();
    $this->assertGreaterThan(0,count($rankings));
  }

}
