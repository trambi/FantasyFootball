<?php

namespace FantasyFootball\TournamentCoreBundle\Util\Rdvbb;

use FantasyFootball\TournamentCoreBundle\Util\IRankingStrategy;

class RankingStrategyThirhteenth implements IRankingStrategy {

    const DRAW = 100;
    const WIN = 300;
    const LOSS = 0;
    const TEAM_DRAW = 50;
    const TEAM_WIN = 150;
    const TEAM_LOSS = 0;
    
    
    public function useCoachTeamPoints() {
        return true;
    }

    public function computePoints(&$points1, &$points2, $td1, $td2, $cas1, $cas2) {
        if ($td1 == $td2) {
            $points1 = self::DRAW;
            $points2 = self::DRAW;
        } else if ( $td1 > $td2 ) {
            $points1 = self::WIN;
            $points2 = self::LOSS;
        } else {
            $points2 = self::WIN;
            $points1 = self::LOSS;
        }
    }

    public function compareCoachs($coach1, $coach2) {
        $retour = 1;

        $points1 = $coach1->points;
        $points2 = $coach2->points;
        $opponentPoints1 = $coach1->opponentsPoints;
        $opponentPoints2 = $coach2->opponentsPoints;
        $mixed1 = $coach1->td + $coach1->casualties;
        $mixed2 = $coach2->td + $coach2->casualties;
        
        if ( ($points1 == $points2) 
                && ($opponentPoints1 == $opponentPoints2) 
                && ($mixed1 == $mixed2) ) {
            $retour = 0;
        } else {
            if ( $points1 > $points2 ) {
                $retour = 1;
            }
            if ( $points1 < $points2 ) {
                $retour = -1;
            }
            if ( $points1 == $points2 ) {
                if ( $opponentPoints1 > $opponentPoints2 ) {
                    $retour = 1;
                }
                if ( $opponentPoints1 < $opponentPoints2 ) {
                    $retour = -1;
                }
                if ( $opponentPoints1 == $opponentPoints2 ) {
                    if ( $mixed1 > $mixed2 ) {
                        $retour = 1;
                    }
                    if ( $mixed1 < $mixed2 ) {
                        $retour = -1;
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
            $points1 = self::TEAM_LOSS;
            $points2 = self::TEAM_WIN;
        } elseif ($sum1 === $sum2) {
            $points1 = self::TEAM_DRAW;
            $points2 = self::TEAM_DRAW;
        } else {
            $points1 = self::TEAM_WIN;
            $points2 = self::TEAM_LOSS;
        }
        $points1 += $sum1;
        $points2 += $sum2;
    }

    public function useOpponentPointsOfYourOwnMatch() {
        return true;
    }

}
