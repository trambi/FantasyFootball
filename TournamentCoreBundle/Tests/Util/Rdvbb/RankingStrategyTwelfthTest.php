<?php
namespace FantasyFootball\TournamentCoreBundle\Tests\Util\Rdvbb;

use FantasyFootball\TournamentCoreBundle\Util\Rdvbb\RankingStrategyTwelfth;

class RankingStrategyTwelfthTest extends \PHPUnit_Framework_TestCase
{
  public function testComputePoints(){
    $strategy = new RankingStrategyTwelfth();
    $points1 = -1;
    $points2 = -1;

    $strategy->computePoints($points1,$points2,3,0,1,1);
    $this->assertEquals(9, $points1);
    $this->assertEquals(0, $points2);

    $strategy->computePoints($points1,$points2,2,0,1,1);
    $this->assertEquals(9, $points1);
    $this->assertEquals(0, $points2);

    $strategy->computePoints($points1,$points2,2,1,1,1);
    $this->assertEquals(8, $points1);
    $this->assertEquals(1, $points2);

    $strategy->computePoints($points1,$points2,2,2,1,1);
    $this->assertEquals(4, $points1);
    $this->assertEquals(4, $points2);

    $strategy->computePoints($points1,$points2,1,1,3,0);
    $this->assertEquals(4, $points1);
    $this->assertEquals(4, $points2);

    $strategy->computePoints($points1,$points2,1,2,3,0);
    $this->assertEquals(1, $points1);
    $this->assertEquals(8, $points2);

    $strategy->computePoints($points1,$points2,1,3,3,0);
    $this->assertEquals(0, $points1);
    $this->assertEquals(9, $points2);

    $strategy->computePoints($points1,$points2,0,3,3,0);
    $this->assertEquals(0, $points1);
    $this->assertEquals(9, $points2);
  }

  public function testComputeCoachTeamPoints(){
    $strategy = new RankingStrategyTwelfth();
    $points1 = -1;
    $points2 = -1;
    $cas1Array = array(0,0,0);
    $cas2Array = array(0,0,0);

    $tds1 = array(2,2,2);
    $tds2= array(0,0,0);
    $strategy->computeCoachTeamPoints($points1,$points2,$tds1,$tds2,$cas1Array,$cas2Array);
    $this->assertEquals(2, $points1);
    $this->assertEquals(0, $points2);

    $tds1 = array(2,2,2);
    $tds2= array(1,0,0);
    $strategy->computeCoachTeamPoints($points1,$points2,$tds1,$tds2,$cas1Array,$cas2Array);
    $this->assertEquals(2, $points1);
    $this->assertEquals(0, $points2);

    $tds1 = array(2,2,2);
    $tds2= array(2,0,0);
    $strategy->computeCoachTeamPoints($points1,$points2,$tds1,$tds2,$cas1Array,$cas2Array);
    $this->assertEquals(2, $points1);
    $this->assertEquals(0, $points2);

    $tds1 = array(2,2,2);
    $tds2= array(3,0,0);
    $strategy->computeCoachTeamPoints($points1,$points2,$tds1,$tds2,$cas1Array,$cas2Array);
    $this->assertEquals(2, $points1);
    $this->assertEquals(0, $points2);

    $tds1 = array(2,2,2);
    $tds2= array(4,0,0);
    $strategy->computeCoachTeamPoints($points1,$points2,$tds1,$tds2,$cas1Array,$cas2Array);
    $this->assertEquals(2, $points1);
    $this->assertEquals(0, $points2);

    $tds1 = array(0,2,2);
    $tds2= array(2,1,0);
    $strategy->computeCoachTeamPoints($points1,$points2,$tds1,$tds2,$cas1Array,$cas2Array);
    $this->assertEquals(2, $points1);
    $this->assertEquals(0, $points2);

    $tds1 = array(0,2,2);
    $tds2= array(2,2,0);
    $strategy->computeCoachTeamPoints($points1,$points2,$tds1,$tds2,$cas1Array,$cas2Array);
    $this->assertEquals(1, $points1);
    $this->assertEquals(1, $points2);

    $tds1 = array(0,2,2);
    $tds2= array(2,3,0);
    $strategy->computeCoachTeamPoints($points1,$points2,$tds1,$tds2,$cas1Array,$cas2Array);
    $this->assertEquals(0, $points1);
    $this->assertEquals(2, $points2);

    $tds1 = array(0,2,2);
    $tds2= array(2,4,0);
    $strategy->computeCoachTeamPoints($points1,$points2,$tds1,$tds2,$cas1Array,$cas2Array);
    $this->assertEquals(0, $points1);
    $this->assertEquals(2, $points2);

    $tds1 = array(0,0,2);
    $tds2= array(2,2,1);
    $strategy->computeCoachTeamPoints($points1,$points2,$tds1,$tds2,$cas1Array,$cas2Array);
    $this->assertEquals(0, $points1);
    $this->assertEquals(2, $points2);

    $tds1 = array(0,0,2);
    $tds2= array(2,2,2);
    $strategy->computeCoachTeamPoints($points1,$points2,$tds1,$tds2,$cas1Array,$cas2Array);
    $this->assertEquals(0, $points1);
    $this->assertEquals(2, $points2);

    $tds1 = array(0,0,1);
    $tds2= array(2,2,2);
    $strategy->computeCoachTeamPoints($points1,$points2,$tds1,$tds2,$cas1Array,$cas2Array);
    $this->assertEquals(0, $points1);
    $this->assertEquals(2, $points2);

    $tds1 = array(0,0,0);
    $tds2= array(2,2,2);
    $strategy->computeCoachTeamPoints($points1,$points2,$tds1,$tds2,$cas1Array,$cas2Array);
    $this->assertEquals(0, $points1);
    $this->assertEquals(2, $points2);

    $tds1 = array(1,1,1);
    $tds2= array(1,1,1);
    $strategy->computeCoachTeamPoints($points1,$points2,$tds1,$tds2,$cas1Array,$cas2Array);
    $this->assertEquals(1, $points1);
    $this->assertEquals(1, $points2);

    $tds1 = array(1,2,1);
    $tds2= array(1,1,2);
    $strategy->computeCoachTeamPoints($points1,$points2,$tds1,$tds2,$cas1Array,$cas2Array);
    $this->assertEquals(1, $points1);
    $this->assertEquals(1, $points2);
  }

  public function testUseTriplettePoints(){
    $strategy = new RankingStrategyTwelfth();
    $result = $strategy->useCoachTeamPoints();
    $this->assertTrue($result);
  }

  public function testCompareCoach(){
    $strategy = new RankingStrategyTwelfth();
    $coach1 = new \stdClass;
    $coach2 = new \stdClass;
    $coach1->points = 0;
    $coach2->points = 0;
    $coach1->opponentsPoints = 0;
    $coach2->opponentsPoints = 0;
    $coach1->netTd = 0;
    $coach2->netTd = 0;
    $coach1->casualties = 0;
    $coach2->casualties = 0;

    $result = $strategy->compareCoachs($coach1,$coach2);
    $this->assertEquals(0,$result);

    $coach1->points = 1;
    $result = $strategy->compareCoachs($coach1,$coach2);
    $this->assertLessThan(0,$result);

    $coach2->points = 2;
    $result = $strategy->compareCoachs($coach1,$coach2);
    $this->assertGreaterThan(0,$result);

    $coach1->points = 2;
    $coach1->opponentsPoints = 1;
    $coach2->opponentsPoints = 0;
    $coach2->netTd = 1;
    $result = $strategy->compareCoachs($coach1,$coach2);
    $this->assertLessThan(0,$result);

    $coach2->opponentsPoints = 2;
    $coach1->netTd = 1;
    $result = $strategy->compareCoachs($coach1,$coach2);
    $this->assertgreaterThan(0,$result);

    $coach2->opponentsPoints = 1;
    $coach1->netTd = 2;
    $coach2->netTd = 1;
    $result = $strategy->compareCoachs($coach1,$coach2);
    $this->assertLessThan(0,$result);

    $coach1->netTd = 1;
    $coach2->netTd = 2;
    $result = $strategy->compareCoachs($coach1,$coach2);
    $this->assertGreaterThan(0,$result);

    $coach2->netTd = 1;
    $coach1->casualties = 2;
    $coach2->casualties = 1;
    $result = $strategy->compareCoachs($coach1,$coach2);
    $this->assertLessThan(0,$result);

    $coach2->casualties = 3;
    $result = $strategy->compareCoachs($coach1,$coach2);
    $this->assertGreaterThan(0,$result);
  }
  
  public function testCompareCoachTeams(){
    $strategy = new RankingStrategyTwelfth();
    $coachTeam1 = new \stdClass;
    $coachTeam2 = new \stdClass;
    $coachTeam1->coachTeamPoints = 0;
    $coachTeam2->coachTeamPoints = 0;
    $coachTeam1->points = 0;
    $coachTeam2->points = 0;
    $coachTeam1->opponentsPoints = 0;
    $coachTeam2->opponentsPoints = 0;
    $coachTeam1->netTd = 0;
    $coachTeam2->netTd = 0;
    $coachTeam1->casualties = 0;
    $coachTeam2->casualties = 0;
    
    $result = $strategy->compareCoachTeams($coachTeam1,$coachTeam2);
    $this->assertEquals(0,$result);
    
    $coachTeam1->coachTeamPoints = 1;
    $result = $strategy->compareCoachTeams($coachTeam1,$coachTeam2);
    $this->assertLessThan(0,$result);
    
    $coachTeam2->coachTeamPoints = 2;
    $result = $strategy->compareCoachTeams($coachTeam1,$coachTeam2);
    $this->assertGreaterThan(0,$result);
    
    $coachTeam1->coachTeamPoints = 2;
    $coachTeam1->points = 1;
    $result = $strategy->compareCoachTeams($coachTeam1,$coachTeam2);
    $this->assertLessThan(0,$result);

    $coachTeam2->points = 2;
    $result = $strategy->compareCoachTeams($coachTeam1,$coachTeam2);
    $this->assertGreaterThan(0,$result);

    $coachTeam1->points = 2;
    $coachTeam1->opponentsPoints = 1;
    $coachTeam2->opponentsPoints = 0;
    $coachTeam2->netTd = 1;
    $result = $strategy->compareCoachTeams($coachTeam1,$coachTeam2);
    $this->assertLessThan(0,$result);

    $coachTeam2->opponentsPoints = 2;
    $coachTeam1->netTd = 1;
    $result = $strategy->compareCoachTeams($coachTeam1,$coachTeam2);
    $this->assertgreaterThan(0,$result);

    $coachTeam2->opponentsPoints = 1;
    $coachTeam1->netTd = 2;
    $coachTeam2->netTd = 1;
    $result = $strategy->compareCoachTeams($coachTeam1,$coachTeam2);
    $this->assertLessThan(0,$result);

    $coachTeam1->netTd = 1;
    $coachTeam2->netTd = 2;
    $result = $strategy->compareCoachTeams($coachTeam1,$coachTeam2);
    $this->assertGreaterThan(0,$result);

    $coachTeam2->netTd = 1;
    $coachTeam1->casualties = 2;
    $coachTeam2->casualties = 1;
    $result = $strategy->compareCoachTeams($coachTeam1,$coachTeam2);
    $this->assertLessThan(0,$result);

    $coachTeam2->casualties = 3;
    $result = $strategy->compareCoachTeams($coachTeam1,$coachTeam2);
    $this->assertGreaterThan(0,$result);
  }
  
    public function testRankingOptions(){
    $strategy = new RankingStrategyTwelfth();
    $rankings = $strategy->rankingOptions();
    $this->assertGreaterThan(0,count($rankings));
  }
}
?>