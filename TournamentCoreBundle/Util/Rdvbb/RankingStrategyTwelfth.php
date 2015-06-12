<?php

namespace FantasyFootball\TournamentCoreBundle\Util\Rdvbb;

use FantasyFootball\TournamentCoreBundle\Util\IRankingStrategy;

class RankingStrategyTwelfth implements IRankingStrategy {

    public function useCoachTeamPoints() {
        return true;
    }

    public function computePoints(&$points1, &$points2, $td1, $td2, $cas1, $cas2) {
        if ($td1 == $td2) {
            $points1 = 4;
            $points2 = 4;
        } else if ($td1 == ($td2 + 1)) {
            $points1 = 8;
            $points2 = 1;
        } else if ($td1 > ($td2 + 1)) {
            $points1 = 9;
            $points2 = 0;
        } else if ($td2 == ($td1 + 1)) {
            $points1 = 1;
            $points2 = 8;
        } else if ($td2 > ($td1 + 1)) {
            $points1 = 0;
            $points2 = 9;
        } else if (-1 == $td1) {
            $points1 = -1;
            $points2 = 9;
            $td2 = 2;
        } else if (-1 == $td2) {
            $points2 = -1;
            $points1 = 9;
            $td1 = 2;
        }
    }

    public function compareCoachs($coach1, $coach2) {
        $retour = 1;

        $points1 = $coach1->points;
        $points2 = $coach2->points;
        $opponentPoints1 = $coach1->opponentsPoints;
        $opponentPoints2 = $coach2->opponentsPoints;
        $netTd1 = $coach1->netTd;
        $netTd2 = $coach2->netTd;
        $cas1 = $coach1->casualties;
        $cas2 = $coach2->casualties;

        if (($points1 == $points2) && ($opponentPoints1 == $opponentPoints2) && ($netTd1 == $netTd2) && ($cas1 == $cas2)) {
            $retour = 0;
        } else {
            if ($points1 > $points2) {
                $retour = 1;
            }
            if ($points1 < $points2) {
                $retour = -1;
            }
            if ($points1 == $points2) {
                if ($opponentPoints1 > $opponentPoints2) {
                    $retour = 1;
                }
                if ($opponentPoints1 < $opponentPoints2) {
                    $retour = -1;
                }
                if ($opponentPoints1 == $opponentPoints2) {
                    if ($netTd1 > $netTd2) {
                        $retour = 1;
                    }
                    if ($netTd1 < $netTd2) {
                        $retour = -1;
                    }
                    if ($netTd1 == $netTd2) {
                        if ($cas1 > $cas2) {
                            $retour = 1;
                        }
                        if ($cas1 < $cas2) {
                            $retour = -1;
                        }
                    }
                }
            }
        }
        return -$retour;
    }

    public function compareCoachTeams($coachTeam1, $coachTeam2) {
        $points1 = $coachTeam1->coachTeamPoints;
        $points2 = $coachTeam2->coachTeamPoints;
        if ($points1 > $points2) {
            return -1;
        } elseif ($points1 < $points2) {
            return 1;
        } else {
            return $this->compareCoachs($coachTeam1, $coachTeam2);
        }
    }

    public function computeCoachTeamPoints(&$points1, &$points2, $td1Array, $td2Array, $cas1Array, $cas2Array) {
        $points1 = 0;
        $points2 = 0;
        $sum1 = 0;
        $sum2 = 0;
        for ($i = 0; $i < 3; $i++) {
            $tempPoints1 = 0;
            $tempPoints2 = 0;
            $this->computePoints($tempPoints1, $tempPoints2, $td1Array[$i], $td2Array[$i], $cas1Array[$i], $cas2Array[$i]);
            $sum1 += $tempPoints1;
            $sum2 += $tempPoints2;
        }
        if ($sum1 < $sum2) {
            $points1 = 0;
            $points2 = 2;
        } elseif ($sum1 === $sum2) {
            $points1 = 1;
            $points2 = 1;
        } else {
            $points1 = 2;
            $points2 = 0;
        }
    }

    public function useOpponentPointsOfYourOwnMatch() {
        return false;
    }

}

?>
