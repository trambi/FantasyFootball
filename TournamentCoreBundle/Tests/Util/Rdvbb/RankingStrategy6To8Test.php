<?php
namespace FantasyFootball\TournamentCoreBundle\Tests\Util\Rdvbb;

use FantasyFootball\TournamentCoreBundle\Util\Rdvbb\RankingStrategySixthToEighth;

class RankingStrategySixthToEighthTest extends \PHPUnit_Framework_TestCase
{
	public function testComputePoints(){
    $strategy = new RankingStrategySixthToEighth();
    $points1 = -1;
    $points2 = -1;

    $strategy->computePoints($points1,$points2,3,0,1,1);
    $this->assertEquals(5, $points1);
    $this->assertEquals(0, $points2);

    $strategy->computePoints($points1,$points2,2,0,1,1);
    $this->assertEquals(5, $points1);
    $this->assertEquals(0, $points2);

    $strategy->computePoints($points1,$points2,2,1,1,1);
    $this->assertEquals(5, $points1);
    $this->assertEquals(1, $points2);

    $strategy->computePoints($points1,$points2,2,2,1,1);
    $this->assertEquals(3, $points1);
    $this->assertEquals(3, $points2);

    $strategy->computePoints($points1,$points2,1,1,3,0);
    $this->assertEquals(3, $points1);
    $this->assertEquals(3, $points2);

    $strategy->computePoints($points1,$points2,1,2,3,0);
    $this->assertEquals(1, $points1);
    $this->assertEquals(5, $points2);

    $strategy->computePoints($points1,$points2,1,3,3,0);
    $this->assertEquals(0, $points1);
    $this->assertEquals(5, $points2);

    $strategy->computePoints($points1,$points2,0,3,3,0);
    $this->assertEquals(0, $points1);
    $this->assertEquals(5, $points2);        
	}

	public function testComputeCoachTeamPoints(){
    $strategy = new RankingStrategySixthToEighth();
    $points1 = -1;
    $points2 = -1;


    $cas1Array = array(0,0,0);
    $cas2Array = array(0,0,0);

    $tds1 = array(2,2,2);
    $tds2= array(0,0,0);		
    $strategy->computeCoachTeamPoints($points1,$points2,$tds1,$tds2,$cas1Array,$cas2Array);
    $this->assertEquals(15, $points1);
    $this->assertEquals(0, $points2);

    $tds1 = array(2,2,2);
    $tds2= array(1,0,0);
    $strategy->computeCoachTeamPoints($points1,$points2,$tds1,$tds2,$cas1Array,$cas2Array);
    $this->assertEquals(15, $points1);
    $this->assertEquals(1, $points2);

    $tds1 = array(2,2,2);
    $tds2= array(2,0,0);
    $strategy->computeCoachTeamPoints($points1,$points2,$tds1,$tds2,$cas1Array,$cas2Array);
    $this->assertEquals(13, $points1);
    $this->assertEquals(3, $points2);

    $tds1 = array(2,2,2);
    $tds2= array(3,0,0);
    $strategy->computeCoachTeamPoints($points1,$points2,$tds1,$tds2,$cas1Array,$cas2Array);
    $this->assertEquals(11, $points1);
    $this->assertEquals(5, $points2);      

    $tds1 = array(2,2,2);
    $tds2= array(4,0,0);
    $strategy->computeCoachTeamPoints($points1,$points2,$tds1,$tds2,$cas1Array,$cas2Array);
    $this->assertEquals(10, $points1);
    $this->assertEquals(5, $points2);      

    $tds1 = array(0,2,2);
    $tds2= array(2,1,0);
    $strategy->computeCoachTeamPoints($points1,$points2,$tds1,$tds2,$cas1Array,$cas2Array);
    $this->assertEquals(10, $points1);
    $this->assertEquals(6, $points2);

    $tds1 = array(0,2,2);
    $tds2= array(2,2,0);
    $strategy->computeCoachTeamPoints($points1,$points2,$tds1,$tds2,$cas1Array,$cas2Array);
    $this->assertEquals(8, $points1);
    $this->assertEquals(8, $points2);      

    $tds1 = array(0,2,2);
    $tds2= array(2,3,0);
    $strategy->computeCoachTeamPoints($points1,$points2,$tds1,$tds2,$cas1Array,$cas2Array);
    $this->assertEquals(6, $points1);
    $this->assertEquals(10, $points2);

    $tds1 = array(0,2,2);
    $tds2= array(2,4,0);
    $strategy->computeCoachTeamPoints($points1,$points2,$tds1,$tds2,$cas1Array,$cas2Array);
    $this->assertEquals(5, $points1);
    $this->assertEquals(10, $points2);

    $tds1 = array(0,0,2);
    $tds2= array(2,2,1);
    $strategy->computeCoachTeamPoints($points1,$points2,$tds1,$tds2,$cas1Array,$cas2Array);
    $this->assertEquals(5, $points1);
    $this->assertEquals(11, $points2);

    $tds1 = array(0,0,2);
    $tds2= array(2,2,2);
    $strategy->computeCoachTeamPoints($points1,$points2,$tds1,$tds2,$cas1Array,$cas2Array);
    $this->assertEquals(3, $points1);
    $this->assertEquals(13, $points2);      

    $tds1 = array(0,0,1);
    $tds2= array(2,2,2);
    $strategy->computeCoachTeamPoints($points1,$points2,$tds1,$tds2,$cas1Array,$cas2Array);
    $this->assertEquals(1, $points1);
    $this->assertEquals(15, $points2);

    $tds1 = array(0,0,0);
    $tds2= array(2,2,2);
    $strategy->computeCoachTeamPoints($points1,$points2,$tds1,$tds2,$cas1Array,$cas2Array);
    $this->assertEquals(0, $points1);
    $this->assertEquals(15, $points2);

    $tds1 = array(1,1,1);
    $tds2= array(1,1,1);
    $strategy->computeCoachTeamPoints($points1,$points2,$tds1,$tds2,$cas1Array,$cas2Array);
    $this->assertEquals(9, $points1);
    $this->assertEquals(9, $points2);

    $tds1 = array(1,2,1);
    $tds2= array(1,1,2);
    $strategy->computeCoachTeamPoints($points1,$points2,$tds1,$tds2,$cas1Array,$cas2Array);
    $this->assertEquals(9, $points1);
    $this->assertEquals(9, $points2);
	}
	
	public function testUseTriplettePoints(){
    $strategy = new RankingStrategySixthToEighth();
    $result = $strategy->useCoachTeamPoints();
    $this->assertFalse($result);
	}
	
	public function testCompareCoach(){
    $strategy = new RankingStrategySixthToEighth();
    $coach1 = new \stdClass;
    $coach2 = new \stdClass;
    $coach1->points = 0;
    $coach2->points = 0;
    $coach1->opponentsPoints = 0;
    $coach2->opponentsPoints = 0;
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
    $coach1->casualtiesFor = 2;
    $coach2->casualtiesFor = 1;
    $result = $strategy->compareCoachs($coach1,$coach2);
    $this->assertLessThan(0,$result);

    $coach2->casualtiesFor = 3;
    $result = $strategy->compareCoachs($coach1,$coach2);
    $this->assertGreaterThan(0,$result);
	}
  
  public function testRankingOptions(){
    $strategy = new RankingStrategySixthToEighth();
    $rankings = $strategy->rankingOptions();
    $this->assertGreaterThan(0,count($rankings));
  }
}
?>