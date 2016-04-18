<?php

namespace FantasyFootball\TournamentCoreBundle\Tests\Util;

use FantasyFootball\TournamentCoreBundle\Util\DataProvider;
use FantasyFootball\TournamentCoreBundle\DatabaseConfiguration;


class DataProviderTest extends \PHPUnit_Framework_TestCase {

    public function testGetCoachsByEdition() {
        $conf = new DatabaseConfiguration('localhost', 'test_tournament', 'test_tournament', 'test_tournament');
        $data = new DataProvider($conf);
        $coaches = $data->getCoachsByEdition(0);
        $this->assertEquals(0, count($coaches));

        $coaches = $data->getCoachsByEdition(1);
        $this->assertEquals(12, count($coaches));
        $tripId = 1;

        for ($i = 1; $i < 13; $i++) {
            $coach = $coaches[$i];
            $this->assertEquals($i, $coach->id);
            $this->assertEquals('Team ' . chr(64 + $i), $coach->teamName);
            $this->assertEquals('Coach ' . chr(64 + $i), $coach->name);
            $this->assertEquals('coach' . chr(96 + $i) . '@test.org', $coach->email);
            $this->assertEquals($i, $coach->raceId);
            $this->assertEquals(100 + $i, $coach->nafNumber);
            $this->assertEquals($tripId, $coach->coachTeamId);
            $this->assertEquals('Triplette ' . chr(64 + $tripId), $coach->coachTeamName);
            if (0 === ( $i % 3 )) {
                $tripId ++;
            }
        }
    }

    public function testGetCoachById() {
        $conf = new DatabaseConfiguration('localhost', 'test_tournament', 'test_tournament', 'test_tournament');
        $data = new DataProvider($conf);
        $coach = $data->getCoachById(1);

        $this->assertEquals(1, $coach->id);
        $this->assertEquals('Team A', $coach->teamName);
        $this->assertEquals('Coach A', $coach->name);
        $this->assertEquals('coacha@test.org', $coach->email);
        $this->assertEquals(1, $coach->raceId);
        $this->assertEquals(101, $coach->nafNumber);
        $this->assertEquals(1, $coach->coachTeamId);
        $this->assertEquals('Triplette A', $coach->coachTeamName);
    }

    public function testGetCoachTeamById() {
        $conf = new DatabaseConfiguration('localhost', 'test_tournament', 'test_tournament', 'test_tournament');
        $data = new DataProvider($conf);
        $coachTeam = $data->getCoachTeamById(1);

        $this->assertEquals(1, $coachTeam->id);
        $this->assertEquals('Triplette A', $coachTeam->name);
        $this->assertEquals(3, count($coachTeam->coachTeamMates));
        for ($i = 0; $i < 3; $i++) {
            $coach = $coachTeam->coachTeamMates[$i];
            $this->assertEquals($i + 1, $coach->teamId);
            $this->assertEquals('Team ' . chr(65 + $i), $coach->teamName);
            $this->assertEquals('Coach ' . chr(65 + $i), $coach->coach);
            $this->assertEquals(1, $coach->coachTeamId);
            $this->assertEquals('Triplette A', $coach->coachTeamName);
        }
    }

    public function testGetCoachTeamsByEdition() {
        $conf = new DatabaseConfiguration('localhost', 'test_tournament', 'test_tournament', 'test_tournament');
        $data = new DataProvider($conf);
        $coachTeams = $data->getCoachTeamsByEdition(0);
        $this->assertEquals(0, count($coachTeams));

        $coachTeams = $data->getCoachTeamsByEdition(1);
        $this->assertEquals(4, count($coachTeams));

        for ($i = 1; $i < 5; $i++) {
            $coachTeam = $coachTeams[$i];
            $this->assertEquals($i, $coachTeam->id);
            $this->assertEquals('Triplette ' . chr(64 + $i), $coachTeam->name);
            $this->assertEquals(3, count($coachTeam->coachTeamMates));
            for ($j = 0; $j < 3; $j++) {
                $id = ($i - 1) * 3 + $j + 1;
                $coach = $coachTeam->coachTeamMates[$j];
                $this->assertEquals($id, $coach->teamId);
                $this->assertEquals('Team ' . chr(64 + $id), $coach->teamName);
                $this->assertEquals('Coach ' . chr(64 + $id), $coach->coach);
                $this->assertEquals($i, $coach->coachTeamId);
                $this->assertEquals('Triplette ' . chr(64 + $i), $coach->coachTeamName);
            }
        }
    }

}

?>