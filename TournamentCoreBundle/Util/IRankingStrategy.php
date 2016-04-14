<?php

namespace FantasyFootball\TournamentCoreBundle\Util;

interface IRankingStrategy{
  public function computePoints(&$points1,&$points2,$td1,$td2,$cas1,$cas2);
  public function computeCoachTeamPoints(&$points1,&$points2,$td1Array,$td2Array,$cas1Array,$cas2Array);
  public function compareCoachs($coach1,$coach2);
  public function compareCoachTeams($coachTeam1,$coachTeam2);
  public function useCoachTeamPoints();
  public function useOpponentPointsOfYourOwnMatch();
  public function rankingOptions();
}


?>
