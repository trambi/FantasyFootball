<?php

namespace FantasyFootball\TournamentCoreBundle\Util;

use FantasyFootball\TournamentCoreBundle\DatabaseConfiguration;
use FantasyFootball\TournamentCoreBundle\Entity\Edition;

class DataProvider {

  protected $mySQL;

  public function __construct($DbConf) {
    if (FALSE == class_exists('mysqli')) {
      $this->link = mysql_connect($DbConf->getHost(), $DbConf->getUser(), $DbConf->getPassword());
      mysql_select_db($DbConf->getName(), $this->link);
      //echo "print_r($this->link)";
      $this->mySQL = NULL;
    } else {
      $this->mySQL = new \mysqli($DbConf->getHost(), $DbConf->getUser(), $DbConf->getPassword(), $DbConf->getName());
    }
  }

  public function __destruct() {
    if (NULL != $this->mySQL) {
      $this->mySQL->close();
    } else {
      mysql_close($this->link);
    }
  }

  protected function query($query) {
    if (NULL != $this->mySQL) {
      return $this->mySQL->query($query);
    } else {
      return mysql_query($query, $this->link);
    }
  }

  protected function resultFetchObject($result) {
    if (NULL != $this->mySQL) {
      return $result->fetch_object();
    } else {
      return mysql_fetch_object($result);
    }
  }

  protected function resultFetchRow($result) {
    if (NULL != $this->mySQL) {
      return $result->fetch_row();
    } else {
      return mysql_fetch_row($result);
    }
  }

  protected function resultClose($result) {
    if (NULL != $this->mySQL) {
      return $result->close();
    } else {
      return mysql_free_result($result);
    }
  }

  const raceQuery = 'SELECT r.edition,r.id_race as id,r.nom_fr,r.nom_en,r.nom_en_2,r.nom_fr_2,r.reroll FROM tournament_race r';
  const coachQuery = 'SELECT c.id, c.team_name, c.name, c.id_race,c.email,c.fan_factor,c.reroll,c.apothecary,c.assistant_coach,c.cheerleader,c.edition,c.naf_number, c.id_coach_team,r.nom_fr,ct.name,c.ready FROM tournament_coach c INNER JOIN tournament_race r ON c.id_race=r.id_race LEFT JOIN tournament_coach_team ct ON c.id_coach_team=ct.id';
  const setReadyCoachQuery = 'UPDATE tournament_coach SET ready = 1';
  const preCoachQuery = 'SELECT p.id as id, p.name as coach, p.team_name as name,p.id_race as raceId,p.email as email,p.edition as edition,p.naf_number,p.id_coach_team,r.nom_fr,ct.name FROM tournament_precoach p INNER JOIN tournament_race r ON p.id_race=r.id_race LEFT JOIN tournament_coach_team tc ON p.id_coach_team=tc.id';
  const coachTeamQuery = 'SELECT c.name,c.id_coach_team,ct.name,c.id,c.team_name FROM tournament_coach c INNER JOIN tournament_coach_team ct ON c.id_coach_team=ct.id';
  const coachTeamPreCoachQuery = 'SELECT p.name,p.id_coach_team,ct.name,p.id,\'\',0,0,0,0 FROM tournament_precoach p INNER JOIN tournament_coach_team ct ON p.id_coach_team=ct.id';
  const matchQuery = 'SELECT c1.name,c1.team_name,c1.id,m.td_1,m.casualties_1,m.completions_1,m.fouls_1,m.special_1,
  m.points_1,c2.name,c2.team_name,c2.id,m.td_2,m.casualties_2,m.completions_2,m.fouls_2,m.special_2,m.points_2,
  m.id,m.table_number,m.status,m.edition,m.round,m.finale FROM tournament_match m INNER JOIN tournament_coach c1 ON m.id_coach_1 = c1.id INNER JOIN tournament_coach c2 ON m.id_coach_2 = c2.id';
  const insertMatchQuery = 'INSERT INTO tournament_match (id_coach_1,id_coach_2,round,edition,table_number) VALUES(?,?,?,?,?)';
  const resumeMatchQuery = 'UPDATE tournament_match SET td_1=?,td_2=?,sortie_1=?,sortie_2=?,points_1=?,points_2=?,status=\'resume\'	WHERE id_match=?';
  const deleteMatchQuery = 'DELETE FROM tournament_match';
  const updateRankingQuery = 'UPDATE INTO tournament_coach SET points=?,opponents_points=?,net_td=?,casualties=? WHERE id=?';
  const editionQuery = 'SELECT id,day_1 as day1, day_2 as day2, round_number as roundNumber, current_round as currentRound, use_finale as useFinale, ranking_strategy as rankingStrategy, first_day_round as firstDayRound, full_triplette as fullTriplette FROM tournament_edition';
  const deletepreCoachQuery = 'DELETE FROM tournament_precoach';
  const updateTeamQuery = 'UPDATE INTO tournament_coach SET team_name=?,name=?,id_race=?,email=?,fan_factor=?,naf_number=?,edition=?,ready=? WHERE id=?';
  const coachTeamGames = 'SELECT DISTINCT c1.id_coach_team as id1, c2.id_coach_team as id2 FROM tournament_match m INNER JOIN tournament_coach c1 ON m.id_coach_1 = c1.id INNER JOIN tournament_coach c2 ON m.id_coach_2 = c2.id';

  public function getRacesByEdition($edition) {
    $query = self::raceQuery;
    $query .= ' WHERE r.edition < ' . intval($edition);
    $query .= ' ORDER BY r.nom_fr ASC';
    $result = $this->query($query);
    $races = array();
    if ($result) {
      $race = $this->resultFetchObject($result);
      while (null != $race) {
        $race->nom_fr = mb_convert_encoding($race->nom_fr, 'UTF-8');
        $race->nom_fr_2 = mb_convert_encoding($race->nom_fr_2, 'UTF-8');
        $races[$race->id] = $race;
        $race = $this->resultFetchObject($result);
      }
      $this->resultClose($result);
    }
    return $races;
  }

  protected function convertRowInCoach($row) {
    $coach = (object) array();
    $coach->id = intval($row[0]);
    $coach->teamName = mb_convert_encoding($row[1], 'UTF-8');
    $coach->name = mb_convert_encoding($row[2], 'UTF-8');
    $coach->raceId = intval($row[3]);
    $coach->email = $row[4];
    $coach->ff = intval($row[5]);
    $coach->reroll = intval($row[6]);
    $coach->apothecary = intval($row[7]);
    $coach->assistants = intval($row[8]);
    $coach->cheerleaders = intval($row[9]);
    $coach->edition = intval($row[10]);
    $coach->nafNumber = intval($row[11]);
    $coach->coachTeamId = intval($row[12]);
    $coach->raceName = mb_convert_encoding($row[13], 'UTF-8');
    $coach->coachTeamName = mb_convert_encoding($row[14], 'UTF-8');
    $coach->ready = intval($row[15]);
    return $coach;
  }

  public function getPreCoachsByEdition($edition) {
    $query = self::preCoachQuery;
    $query .= ' WHERE p.edition = ' . intval($edition);
    $query .= ' ORDER BY p.name ASC';
    //echo 'query : [',$query,']<br />';
    $result = $this->query($query);
    $preteams = array();

    if ($result) {
      $preteam = $this->resultFetchObject($result);

      while (null != $preteam) {
        //print_r($preteam);
        $preteam->coach = mb_convert_encoding($preteam->coach, 'UTF-8');
        $preteam->name = mb_convert_encoding($preteam->name, 'UTF-8');
        $preteams[$preteam->id] = $preteam;
        $preteam = $this->resultFetchObject($result);
      }
      $this->resultClose($result);
    }
    return $preteams;
  }

  public function getCoachsByEdition($edition, $resetRankingStuff = false) {
    $query = self::coachQuery;
    $query .= ' WHERE c.edition = ' . intval($edition);
    $query .= ' ORDER BY c.name ASC';
    //echo 'request : [',$query,']<br />';
    $result = $this->query($query);
    $coachs = array();

    if ($result) {
      $row = $this->resultFetchRow($result);
      while (null != $row) {
        $coach = $this->convertRowInCoach($row);
        if (true == $resetRankingStuff) {
          $coach->points = 0;
          $coach->opponentsPoints = 0;
          $coach->tdFor = 0;
          $coach->tdAgainst = 0;
          $coach->netTd = 0;
          $coach->casualtiesFor = 0;
          $coach->casualtiesAgainst = 0;
          $coach->netCasualties = 0;
          $coach->completionsFor = 0;
          $coach->completionsAgainst = 0;
          $coach->netCompletions = 0;
          $coach->foulsFor = 0;
          $coach->foulsAgainst = 0;
          $coach->netFouls = 0;

          $coach->special = 0;
          $coach->opponentDirectPoints = 0;
          $coach->opponents = array();
          $coach->win = 0;
          $coach->draw = 0;
          $coach->loss = 0;
        }
        $coachs[$coach->id] = $coach;
        $row = $this->resultFetchRow($result);
      }
      $this->resultClose($result);
    }
    return $coachs;
  }

  public function getCoachById($id) {
    $query = self::coachQuery;
    $query .= ' WHERE c.id = ' . intval($id);
    //echo 'request : [',$query,']<br />';
    $result = $this->query($query);

    if ($result) {
      $row = $this->resultFetchRow($result);
      while (null != $row) {
        $coach = $this->convertRowInCoach($row);
        $row = $this->resultFetchRow($result);
      }
      $this->resultClose($result);
    }
    return $coach;
  }

  public function setReadyCoachById($id) {
    $clause = ' WHERE c.id = ' . intval($id);
    return $this->setReadyCoach($clause);
  }

  protected function setReadyCoach($clause) {
    $query = self::setReadyCoachQuery;
    $query .= $clause;
    //echo 'request : [',$query,']<br />';
    return $this->query($query);
  }

  protected function convertRowInCoachTeamElement($row) {
    $coachTeamElt = (object) array();
    $coachTeamElt->coach = mb_convert_encoding($row[0], 'UTF-8');
    $coachTeamElt->coachTeamId = intval($row[1]);
    $coachTeamElt->coachTeamName = mb_convert_encoding($row[2], 'UTF-8');
    $coachTeamElt->teamId = intval($row[3]);
    $coachTeamElt->teamName = mb_convert_encoding($row[4], 'UTF-8');
    $coachTeamElt->isPrebooking = 0;
    return $coachTeamElt;
  }

  public function getCoachTeamById($coachTeamId) {
    $clause = ' ct.id = ' . intval($coachTeamId);
    $coachTeams = $this->getCoachTeamsByClause($clause);
    $coachTeam = array_shift($coachTeams);
    return $coachTeam;
  }

  public function getCoachTeamsByEdition($edition) {
    $clause = ' c.edition = ' . intval($edition);
    $coachTeams = $this->getCoachTeamsByClause($clause);
    return $coachTeams;
  }

  protected function getCoachTeamsByClause($clause) {
    $query = self::coachTeamQuery;
    $query .= ' WHERE ' . $clause;
    $query .= ' ORDER BY c.id_coach_team,c.id ASC';
    //echo 'request : [',$query,']<br />';
    $result = $this->query($query);
    $coachTeams = array();
    $currentCoachTeam = (object) array();
    $currentCoachTeam->id = 0;
    if ($result) {
      $row = $this->resultFetchRow($result);
      $id = 0;
      while (null != $row) {
        $coachTeamElt = $this->convertRowInCoachTeamElement($row);
        if ($currentCoachTeam->id != $coachTeamElt->coachTeamId) {
          if (0 != $currentCoachTeam->id) {
            $coachTeams[$currentCoachTeam->id] = $currentCoachTeam;
          }
          $id = $coachTeamElt->coachTeamId;

          $currentCoachTeam = (object) array();
          $currentCoachTeam->name = $coachTeamElt->coachTeamName;
          $currentCoachTeam->id = $id;
          $currentCoachTeam->coachTeamMates = array();
        }
        $currentCoachTeam->coachTeamMates[] = $coachTeamElt;
        $row = $this->resultFetchRow($result);
      }
      $this->resultClose($result);
      if (0 != $currentCoachTeam->id) {
        $coachTeams[$currentCoachTeam->id] = $currentCoachTeam;
      }
    }
    $query = self::coachTeamPreCoachQuery;
    $query .= ' WHERE ' . $clause;
    $query .= ' ORDER BY p.id_coach_team ASC';
    $result = $this->query($query);
    if ($result) {
      $row = $this->resultFetchRow($result);
      $id = 0;
      $currentCoachTeam = (object) array();
      $currentCoachTeam->id = 0;
      while (null != $row) {
        $coachTeamElt = $this->convertRowInCoachTeamElement($row);
        $coachTeamElt->isPrebooking = 1;
        if ($id != $coachTeamElt->coachTeamId) {
          if (0 != $id) {
            $coachTeams[$id] = $currentCoachTeam;
          }
          $id = $coachTeamElt->coachTeamId;
          if (true == isset($coachTeams[$id])) {
            $currentCoachTeam = $coachTeams[$id];
          } else {
            $currentCoachTeam = (object) array();
            $currentCoachTeam->name = $coachTeamElt->coachTeamName;
            $currentCoachTeam->id = $id;
            $currentCoachTeam->coachTeamMates = array();
          }
        }
        $currentCoachTeam->coachTeamMates[] = $coachTeamElt;
        $row = $this->resultFetchRow($result);
      }
      $this->resultClose($result);
      if (0 != $id) {
        $coachTeams[$id] = $currentCoachTeam;
      }
    }
    return $coachTeams;
  }

  protected function convertRowInMatch($row) {
    $match = (object) array();
    $match->coach1 = mb_convert_encoding($row[0], "UTF-8");
    $match->teamName1 = mb_convert_encoding($row[1], "UTF-8");
    $match->teamId1 = intval($row[2]);
    $match->td1 = intval($row[3]);
    $match->casualties1 = intval($row[4]);
    $match->completions1 = intval($row[5]);
    $match->fouls1 = intval($row[6]);
    $match->special1 = mb_convert_encoding($row[7], "UTF-8");
    $match->points1 = intval($row[8]);
    $match->coach2 = mb_convert_encoding($row[9], "UTF-8");
    $match->teamName2 = mb_convert_encoding($row[10], "UTF-8");
    $match->teamId2 = intval($row[11]);
    $match->td2 = intval($row[12]);
    $match->casualties2 = intval($row[13]);
    $match->completions2 = intval($row[14]);
    $match->fouls2 = intval($row[15]);
    $match->special2 = mb_convert_encoding($row[16], "UTF-8");
    $match->points2 = intval($row[17]);
    $match->id = intval($row[18]);
    $match->table = intval($row[19]);
    $match->status = $row[20];
    $match->edition = intval($row[21]);
    $match->round = intval($row[22]);

    if ("true" === $row[23]) {
      $match->finale = true;
    } else {
      $match->finale = false;
    }
    return $match;
  }

  protected function convertRowInInvertedMatch($row) {
    $match = $this->convertRowInMatch($row);
    $toSwap = array('coach', 'teamName', 'teamId', 'td', 'casualties', 'points','completions','fouls','special');
    foreach ($toSwap as $var) {
      $toSwap1 = $var . '1';
      $toSwap2 = $var . '2';
      $tmp = $match->$toSwap1;
      $match->$toSwap1 = $match->$toSwap2;
      $match->$toSwap2 = $tmp;
    }
    return $match;
  }

  public function getPlayedMatchsByEditionAndRound($edition, $round) {
    return $this->getMatchsByEditionAndRound($edition, $round, '!programme');
  }

  public function getToPlayMatchsByEditionAndRound($edition, $round) {
    return $this->getMatchsByEditionAndRound($edition, $round, 'programme');
  }

  public function getMatchsByEditionAndRound($edition, $round, $state = '') {
    $roundConverted = intval($round);
    $query = self::matchQuery;
    $query .= ' WHERE m.edition=' . intval($edition);

    if (0 !== $roundConverted) {
      $query .= ' AND m.round=' . $roundConverted;
    }
    if ('programme' === $state) {
      $query .= ' AND m.status = \'programme\'';
    } elseif ('!programme' === $state) {
      $query .= ' AND m.status != \'programme\'';
    }
    $query .= ' ORDER BY m.round, m.table_number ASC';
    //echo 'query : |',$query,"|<br />\n";
    $result = $this->query($query);
    $matches = array();
    if ($result) {
      $row = $this->resultFetchRow($result);
      while (null != $row) {
        $match = $this->convertRowInMatch($row);
        $matches[] = $match;
        $row = $this->resultFetchRow($result);
      }
      $this->resultClose($result);
    }
    return $matches;
  }

  public function getMatchById($id) {
    $query = self::matchQuery;
    $query .= ' WHERE m.id_match =' . intval($id);
    //echo 'request : [',$query,']<br />';
    $result = $this->query($query);
    $match = NULL;
    $row = $this->resultFetchRow($result);
    if (NULL != $row) {
      $match = $this->convertRowInMatch($row);
    }
    $this->resultClose($result);
    return $match;
  }

  public function compareMatchByRound($match1, $match2) {
    $round1 = $match1->round;
    $round2 = $match2->round;
    if ($round1 === $round2) {
      $return = 0;
    } elseif ($round1 > $round2) {
      $return = -1;
    } else {
      $return = 1;
    }
    return $return;
  }

  public function getMatchsByCoach($coachId) {
    $convertedCoachId = intval($coachId);
    $query = self::matchQuery;
    $query .= ' WHERE m.id_coach_1=' . $convertedCoachId;
    $query .= ' ORDER BY m.edition, m.round ASC';
    $result = $this->query($query);
    $matches = array();
    if ($result) {
      $row = $this->resultFetchRow($result);
      while (null != $row) {
        $match = $this->convertRowInMatch($row);
        $matches[] = $match;
        $row = $this->resultFetchRow($result);
      }
      $this->resultClose($result);
    }
    $query = self::matchQuery;
    $query .= ' WHERE m.id_coach_2=' . $convertedCoachId;
    $query .= ' ORDER BY m.edition, m.round ASC';
    $result = $this->query($query);
    if ($result) {
      $row = $this->resultFetchRow($result);
      while (null != $row) {
        $match = $this->convertRowInInvertedMatch($row);
        $matches[] = $match;
        $row = $this->resultFetchRow($result);
      }
      $this->resultClose($result);
    }
    usort($matches, array($this, 'compareMatchByRound'));
    return $matches;
  }

  public function getMatchsByCoachTeam($coachTeamId) {
    $convertedCoachTeamId = intval($coachTeamId);
    $query = self::matchQuery;
    $query .= ' WHERE c1.id_coach_team=' . $convertedCoachTeamId;
    //echo "query : |",$query,"|<br />\n";
    $result = $this->query($query);
    $matches = array();
    if ($result) {
      $row = $this->resultFetchRow($result);
      while (null != $row) {
        $match = $this->convertRowInMatch($row);
        $matches[] = $match;
        $row = $this->resultFetchRow($result);
      }
      $this->resultClose($result);
    }
    $query = self::matchQuery;
    $query .= ' WHERE c2.id_coach_team=' . $convertedCoachTeamId;
    //echo "query : |",$query,"|<br />\n";
    $result = $this->query($query);
    if ($result) {
      $row = $this->resultFetchRow($result);
      while (null != $row) {
        $match = $this->convertRowInInvertedMatch($row);
        $matches[] = $match;
        $row = $this->resultFetchRow($result);
      }
      $this->resultClose($result);
    }
    usort($matches, array($this, 'compareMatchByRound'));
    return $matches;
  }

  public function getMainCoachRanking($edition) {
    $mainRanking = $this->getCoachStatisticsBetweenRounds($edition->getId(), 0, $edition->getCurrentRound());
    usort($mainRanking, array($edition->getRankingStrategy(), 'compareCoachs'));
    return $mainRanking;
  }

  public function compareCoachsByTouchdown($coach1, $coach2) {
    return ( $coach2->tdFor - $coach1->tdFor );
  }
  
  public function compareByTouchdownAgainst($item1, $item2) {
    return ( $item2->tdAgainst - $item1->tdAgainst );
  }

  public function getCoachRankingByTouchdown($edition) {
    $tdRanking = $this->getCoachStatisticsBetweenRounds($edition->getId(), 0, $edition->getCurrentRound());
    usort($tdRanking, array($this, 'compareCoachsByTouchdown'));
    return $tdRanking;
  }

  public function compareCoachsByCasualties($coach1, $coach2) {
    return ( $coach2->casualtiesFor - $coach1->casualtiesFor );
  }

  public function getCoachRankingByCasualties($edition) {
    $casualtiesRanking = $this->getCoachStatisticsBetweenRounds($edition->getId(), 0, $edition->getCurrentRound());
    usort($casualtiesRanking, array($this, 'compareCoachsByCasualties'));
    return $casualtiesRanking;
  }

  public function compareCoachsByFouls($coach1, $coach2) {
    return ( $coach2->foulsFor - $coach1->foulsFor ) ;
  }

  public function getCoachRankingByFouls($edition) {
    $casualtiesRanking = $this->getCoachStatisticsBetweenRounds($edition->getId(), 0, $edition->getCurrentRound());
    usort($casualtiesRanking, array($this, 'compareCoachsByFouls'));
    return $casualtiesRanking;
  }
  
  public function compareCoachsByCompletions($coach1, $coach2) {
    return ( $coach2->completionsFor - $coach1->completionsFor ) ;
  }
  
  public function getCoachRankingByCompletions($edition) {
    $casualtiesRanking = $this->getCoachStatisticsBetweenRounds($edition->getId(), 0, $edition->getCurrentRound());
    usort($casualtiesRanking, array($this, 'compareCoachsByCompletions'));
    return $casualtiesRanking;
  }
  
  public function getCoachRankingByDefense($edition) {
    $ranking = $this->getCoachStatisticsBetweenRounds($edition->getId(), 0, $edition->getCurrentRound());
    usort($ranking, array($this, 'compareByTouchdownAgainst'));
    return array_reverse($ranking);
  }
    
  public function getAllRanking($edition){
    $ranking = $this->getCoachStatisticsBetweenRounds($edition->getId(), 0, $edition->getCurrentRound());
    
    $casualtiesRanking = $ranking;
    usort($casualtiesRanking, array($this, 'compareCoachsByCasualties'));

    $tdRanking = $ranking;
    usort($tdRanking, array($this, 'compareCoachsByTouchdown'));
    
    $comebackRanking = $this->getCoachRankingByComeback($edition);
    
    $coachTeamRaning = $this->getCoachTeamRanking($edition);

    usort($ranking,array($edition->getRankingStrategy(), 'compareCoachs'));

    return ([
      'ranking' => $ranking,
      'casRanking' => $casualtiesRanking,
      'tdRanking' => $tdRanking,
      'comebackRanking' => $comebackRanking,
      'coachTeamRanking' => $coachTeamRaning
    ]);
  }
    
  public function compareByComeback($item1, $item2) {
    $finalRanking1 = $item1->finalRanking;
    $finalRanking2 = $item2->finalRanking;
    $diffRanking1 = $item1->diffRanking;
    $diffRanking2 = $item2->diffRanking;
    if ($diffRanking1 === $diffRanking2) {
      if ($finalRanking1 > $finalRanking2) {
        $return = -1;
      } else {
        $return = 1;
      }
    } elseif ($diffRanking1 > $diffRanking2) {
      $return = -1;
    } else {
      $return = 1;
    }
    return $return;
  }

  public function getCoachRankingByComeback($edition) {
    $finalRanking = $this->getMainCoachRanking($edition);
    $firstDayRanking = $this->getCoachStatisticsBetweenRounds($edition->id, 0, $edition->firstDayRound);
    usort($firstDayRanking, array($edition->getRankingStrategy(), 'compareCoachs'));
    $coachNumber = count($finalRanking);
    for ($i = 0; $i < $coachNumber; $i++) {
      $finalCoach = $finalRanking[$i];
      $idToFind = $finalCoach->id;
      $finalCoach->finalRanking = $i + 1;
      for ($j = 0; $j < $coachNumber; $j++) {
        $firstDayCoach = $firstDayRanking[$j];
        if ($firstDayCoach->id === $idToFind) {
          break;
        }
      }
      // We find it !
      if ($j !== $coachNumber) {
        $finalCoach->firstDayRanking = $j + 1;
      }
      $finalCoach->diffRanking = $j - $i;
      $finalRanking[$i] = $finalCoach;
    }
    usort($finalRanking, array($this, 'compareByComeback'));
    return $finalRanking;
  }

  protected function getCoachStatisticsBetweenRounds($edition, $beginRound, $endRound) {
    $coachs = $this->getCoachsByEdition($edition, true);

    $query = self::matchQuery;
    $query .= ' WHERE m.edition=' . intval($edition) . ' AND m.round <= ' . intval($endRound) . ' AND m.round >= ' . intval($beginRound);
    $query .= ' AND m.status <> \'programme\'';
    $result = $this->query($query);
    $row = $this->resultFetchRow($result);
    $first = 1;
    while (null != $row) {
      $match = $this->convertRowInMatch($row);

      $coach1 = $coachs[$match->teamId1];
      $coach1->points += $match->points1;
      $coach1->tdFor += $match->td1;
      $coach1->tdAgainst += $match->td2;
      $coach1->casualtiesFor += $match->casualties1;
      $coach1->casualtiesAgainst += $match->casualties2;
      $coach1->completionsFor += $match->completions1;
      $coach1->completionsAgainst += $match->completions2;
      $coach1->foulsFor += $match->fouls1;
      $coach1->foulsAgainst += $match->fouls2;
      $coach1->opponentDirectPoints = $match->points2;
      $coach1->opponents[] = $match->teamId2;

      $coach2 = $coachs[$match->teamId2];
      $coach2->points += $match->points2;
      $coach2->tdFor += $match->td2;
      $coach2->tdAgainst += $match->td1;
      $coach2->casualtiesFor += $match->casualties2;
      $coach2->casualtiesAgainst += $match->casualties1;
      $coach2->completionsFor += $match->completions2;
      $coach2->completionsAgainst += $match->completions1;
      $coach2->foulsFor += $match->fouls2;
      $coach2->foulsAgainst += $match->fouls1;
      $coach2->opponentDirectPoints = $match->points1;
      $coach2->opponents[] = $match->teamId1;
 
      if (true === $match->finale) {
        if ($match->td1 > $match->td2) {
          $coach1->special = 2;
          $coach2->special = 1;
        } else {
          $coach1->special = 1;
          $coach2->special = 2;
        }
      }
      if ( $match->td1 > $match->td2 ){
        $coach1->win = $coach1->win + 1;
        $coach2->loss = $coach2->loss + 1;
      }elseif ( $match->td1 < $match->td2){
        $coach1->loss = $coach1->loss + 1;
        $coach2->win = $coach2->win + 1;
      }else{
        $coach1->draw = $coach1->draw + 1;
        $coach2->draw = $coach2->draw + 1;
      }
      $coachs[$match->teamId1] = $coach1;
      $coachs[$match->teamId2] = $coach2;
      $row = $this->resultFetchRow($result);
    }
    $this->resultClose($result);

    foreach ($coachs as $key => $coach) {
      $OpponentsPoints = 0;
      if ((true == property_exists($coach, 'opponents')) && (true == is_array($coach->opponents))) {
        foreach ($coach->opponents as $opponent) {
          $opponentTeam = $coachs[$opponent];
          $OpponentsPoints += $opponentTeam->points;
        }
      }
      if (true == property_exists($coach, 'opponentDirectPoints')) {
        $OpponentsPoints -= $coach->opponentDirectPoints;
      }
      $coach->opponentsPoints = $OpponentsPoints;
      $coach->netTd = $coach->tdFor - $coach->tdAgainst;
      $coach->netCasualties = $coach->casualtiesFor - $coach->casualtiesAgainst;
      $coach->netCompletions = $coach->completionsFor - $coach->completionsAgainst;
      $coach->netFouls = $coach->foulsFor - $coach->foulsAgainst;
    }
    return $coachs;
  }

  protected function initCoachForRanking() {
    $coach = (object) array();
    $coach->opponentIdArray = array();
    $coach->coach = "";
    $coach->id = 0;
    $coach->points = 0;
    $coach->opponentsPoints = 0;
    $coach->tdFor = 0;
    $coach->tdAgainst = 0;
    $coach->netTd = 0;
    $coach->casualtiesFor = 0;
    $coach->casualtiesAgainst = 0;
    $coach->netCasualties = 0;
    $coach->completionsFor = 0;
    $coach->completionsAgainst = 0;
    $coach->netCompletions = 0;
    $coach->foulsFor = 0;
    $coach->foulsAgainst = 0;
    $coach->netFouls = 0;
    $coach->win = 0;
    $coach->draw = 0;
    $coach->loss = 0;
    return $coach;
  }

  protected function initCoachTeamForRanking() {
    $coachTeam = (object) array();
    $coachTeam->id = 0;
    $coachTeam->name = "";
    $coachTeam->coachTeamPoints = 0;
    $coachTeam->points = 0;
    $coachTeam->tdFor = 0;
    $coachTeam->tdAgainst = 0;
    $coachTeam->netTd = 0;
    $coachTeam->casualtiesFor = 0;
    $coachTeam->casualtiesAgainst = 0;
    $coachTeam->netCasualties = 0;
    $coachTeam->completionsFor = 0;
    $coachTeam->completionsAgainst = 0;
    $coachTeam->netCompletions = 0;
    $coachTeam->foulsFor = 0;
    $coachTeam->foulsAgainst = 0;
    $coachTeam->netFouls = 0;
    $coachTeam->opponentIdArray = array();
    $coachTeam->opponentCoachTeamIdArray = array();
    $coachTeam->teams = array();
    $coachTeam->opponentsPoints = 0;
    $coachTeam->libre = 1;
    $coachTeam->opponentCoachTeamPoints  = 0;
    $coachTeam->win = 0;
    $coachTeam->draw = 0;
    $coachTeam->loss = 0;
    $coachTeam->coachTeamWin = 0;
    $coachTeam->coachTeamDraw = 0;
    $coachTeam->coachTeamLoss = 0;
    $coachTeam->finale = false;
    return $coachTeam;
  }

  public function getCoachTeamStatisticsBetweenRounds($edition,$beginRound,$endRound) {
    $editionId = $edition->getId();
    $rankingStrategy = $edition->getRankingStrategy();
    // Attention requete de la mort pour recuperer tous les matchs du point de vue d'une coach_team
    $query = '(SELECT c.id_coach_team AS coachTeam, m.id_coach_1 AS team,';
    $query .= ' m.round AS round, ct.name AS coachTeamName,';
    $query .= ' m.points_1 AS points, m.points_2 AS opponentPoints,';
    $query .= ' m.td_1 AS tdFor,m.td_2 AS tdAgainst, m.casualties_1 AS casualtiesFor, m.casualties_2 AS casualtiesAgainst,';
    $query .= ' m.completions_1 AS completionsFor,m.completions_2 AS completionsAgainst,';
    $query .= ' m.fouls_1 AS foulsFor, m.fouls_2 AS foulsAgainst,m.finale AS finale,';
    $query .= ' m.id_coach_2 AS opponentTeam,oc.id_coach_team AS opponentCoachTeam, c.name AS coach';
    $query .= ' FROM tournament_match m';
    $query .= ' INNER JOIN tournament_coach c ON c.id = m.id_coach_1';
    $query .= ' INNER JOIN tournament_coach_team ct ON ct.id = c.id_coach_team ';
    $query .= ' INNER JOIN tournament_coach oc ON oc.id = m.id_coach_2 ';
    $query .= ' WHERE m.edition= ' . intval($editionId) . ' AND m.round <= ' . intval($endRound);
    $query .= ' AND m.round >= ' . intval($beginRound) . ' AND m.status <> \'programme\' )';
    $query .= " UNION ";
    $query .= "(SELECT c.id_coach_team AS coachTeam, m.id_coach_2 AS team,";
    $query .= " m.round AS round, ct.name AS coachTeamName,";
    $query .= " m.points_2 AS points, m.points_1 AS opponentPoints,";
    $query .= " m.td_2 AS tdFor,m.td_1 AS tdAgainst, m.casualties_2 AS casualtiesFor, m.casualties_1 AS casualtiesAgainst,";
    $query .= " m.completions_2 AS completionsFor,m.completions_1 AS completionsAgainst,";
    $query .= " m.fouls_2 AS foulsFor, m.fouls_1 AS foulsAgainst,m.finale AS finale,";
    $query .= " m.id_coach_1 AS opponentTeam, oc.id_coach_team AS opponentCoachTeam, c.name AS coach";
    $query .= " FROM tournament_match m";
    $query .= " INNER JOIN tournament_coach c ON c.id = m.id_coach_2";
    $query .= " INNER JOIN tournament_coach_team ct ON ct.id = c.id_coach_team ";
    $query .= " INNER JOIN tournament_coach oc ON oc.id = m.id_coach_1 ";
    $query .= ' WHERE m.edition= ' . intval($editionId) . ' AND m.round <= ' . intval($endRound);
    $query .= ' AND m.round >= ' . intval($beginRound) . ' AND m.status <> \'programme\' )';
    $query .= " ORDER BY coachTeam,round";
    $result = $this->query($query);

    $coachTeams = array(); // List of coachTeams
    $pointsByTeamId = array();
    $pointsByCoachTeamId = array();

    $currentCoachTeamId = 0;
    $currentRound = 0;
    $td1Array = array();
    $td2Array = array();
    $cas1Array = array();
    $cas2Array = array();
    $tempCoachTeamPoints1 = 0;
    $tempCoachTeamPoints2 = 0;

    $coachTeam = $this->initCoachTeamForRanking();
    $coachTeamMatchElt = $this->resultFetchObject($result);

    while (null !== $coachTeamMatchElt) {
      $coachTeamMatchElt->coachTeam = mb_convert_encoding($coachTeamMatchElt->coachTeam, "UTF-8");
      $coachTeamMatchElt->coachTeamName = mb_convert_encoding($coachTeamMatchElt->coachTeamName, "UTF-8");
      $coachTeamMatchElt->coach = mb_convert_encoding($coachTeamMatchElt->coach, "UTF-8");
      if (( ( 0 !== $currentCoachTeamId ) && ( $currentCoachTeamId !== $coachTeamMatchElt->coachTeam ) ) || ( $currentRound !== $coachTeamMatchElt->round )) {
        if (0 !== $currentRound) {
          $tempCoachTeamPoints1 = 0;
          $tempCoachTeamPoints2 = 0;
          $rankingStrategy->computeCoachTeamPoints($tempCoachTeamPoints1, $tempCoachTeamPoints2, $td1Array, $td2Array, $cas1Array, $cas2Array);
          if ($tempCoachTeamPoints1 > $tempCoachTeamPoints2){
            $coachTeam->coachTeamWin += 1;
            if( true === $coachTeam->finale ){
              $coachTeam->special = 2;
            }
          }elseif ($tempCoachTeamPoints1 === $tempCoachTeamPoints2){
            $coachTeam->coachTeamDraw += 1;
          }else{
            $coachTeam->coachTeamLoss += 1;
            if( true === $coachTeam->finale ){
              $coachTeam->special = 1;
            }
          }
          $coachTeam->coachTeamPoints += $tempCoachTeamPoints1;
          //$coachTeam->opponentCoachTeamPoints += $tempCoachTeamPoints2;
        }
        $td1Array = array();
        $td2Array = array();
        $cas1Array = array();
        $cas2Array = array();
        $currentRound = $coachTeamMatchElt->round;
      }
      if ($currentCoachTeamId !== $coachTeamMatchElt->coachTeam) {
        if (0 !== $currentCoachTeamId) {
          $coachTeams[] = $coachTeam;
        }
        $coachTeam = $this->initCoachTeamForRanking();
        $currentCoachTeamId = $coachTeamMatchElt->coachTeam;
        $coachTeam->id = $currentCoachTeamId;
        $coachTeam->name = $coachTeamMatchElt->coachTeamName;

        $currentRound = $coachTeamMatchElt->round;
      }
      if (false === in_array($coachTeamMatchElt->opponentCoachTeam, $coachTeam->opponentCoachTeamIdArray)) {
        $coachTeam->opponentCoachTeamIdArray[] = $coachTeamMatchElt->opponentCoachTeam;
      }
      $coachTeam->tdFor += $coachTeamMatchElt->tdFor;
      $coachTeam->tdAgainst += $coachTeamMatchElt->tdAgainst;
      $coachTeam->casualtiesFor += $coachTeamMatchElt->casualtiesFor;
      $coachTeam->casualtiesAgainst += $coachTeamMatchElt->casualtiesAgainst;
      $coachTeam->completionsFor += $coachTeamMatchElt->completionsFor;
      $coachTeam->completionsAgainst += $coachTeamMatchElt->completionsAgainst;
      $coachTeam->foulsFor += $coachTeamMatchElt->foulsFor;
      $coachTeam->foulsAgainst += $coachTeamMatchElt->foulsAgainst;

      $tempPoints1 = 0;
      $tempPoints2 = 0;
      $rankingStrategy->computePoints($tempPoints1, $tempPoints2, $coachTeamMatchElt->tdFor, $coachTeamMatchElt->tdAgainst, $coachTeamMatchElt->casualtiesFor, $coachTeamMatchElt->casualtiesAgainst);
      $coachTeam->points += $tempPoints1;
      if( "true" === $coachTeamMatchElt->finale ){
        $coachTeam->finale = true;
      }
      
      if (false === array_key_exists($coachTeamMatchElt->team, $pointsByTeamId)) {
        $pointsByTeamId[$coachTeamMatchElt->team] = $tempPoints1;
      } else {
        $pointsByTeamId[$coachTeamMatchElt->team] += $tempPoints1;
      }
      if (false === $rankingStrategy->useOpponentPointsOfYourOwnMatch()) {
        $coachTeam->opponentsPoints -= $tempPoints2;
      }
      $td1Array[] = $coachTeamMatchElt->tdFor;
      $td2Array[] = $coachTeamMatchElt->tdAgainst;
      $cas1Array[] = $coachTeamMatchElt->casualtiesFor;
      $cas2Array[] = $coachTeamMatchElt->casualtiesAgainst;
      if (false === array_key_exists($coachTeamMatchElt->team, $coachTeam->teams)) {
        $coach = $this->initCoachForRanking();
        $coach->coach = $coachTeamMatchElt->coach;
        $coach->id = $coachTeamMatchElt->team;
        $coachTeam->teams[$coachTeamMatchElt->team] = $coach;
      }
      $coach = $coachTeam->teams[$coachTeamMatchElt->team];
      $coach->points += $tempPoints1;
      if (false === $rankingStrategy->useOpponentPointsOfYourOwnMatch()) {
        $coach->opponentsPoints -= $tempPoints2;
      }
      $coach->opponentIdArray[] = $coachTeamMatchElt->opponentTeam;
      $coach->tdFor += $coachTeamMatchElt->tdFor;
      $coach->tdAgainst += $coachTeamMatchElt->tdAgainst;
      $coach->casualtiesFor += $coachTeamMatchElt->casualtiesFor;
      $coach->casualtiesAgainst += $coachTeamMatchElt->casualtiesAgainst;
      $coach->completionsFor += $coachTeamMatchElt->completionsFor;
      $coach->completionsAgainst += $coachTeamMatchElt->completionsAgainst;
      $coach->foulsFor += $coachTeamMatchElt->foulsFor;
      $coach->foulsAgainst += $coachTeamMatchElt->foulsAgainst;
      if( $coachTeamMatchElt->tdFor > $coachTeamMatchElt->tdAgainst  ){
        $coach->win +=1;
        $coachTeam->win ++;
      }elseif( $coachTeamMatchElt->tdFor == $coachTeamMatchElt->tdAgainst ){
        $coach->draw +=1;
        $coachTeam->draw ++;
      }else{
        $coach->loss +=1;
        $coachTeam->loss ++;
      }
      $coachTeam->teams[$coachTeamMatchElt->team] = $coach;
      $coachTeamMatchElt = $this->resultFetchObject($result);
    }
    if (0 !== $currentCoachTeamId) {
      $tempCoachTeamPoints1 = 0;
      $tempCoachTeamPoints2 = 0;
      $rankingStrategy->computeCoachTeamPoints($tempCoachTeamPoints1, $tempCoachTeamPoints2, $td1Array, $td2Array, $cas1Array, $cas2Array);
      if ($tempCoachTeamPoints1 > $tempCoachTeamPoints2){
        $coachTeam->coachTeamWin += 1;
        if( true === $coachTeam->finale ){
          $coachTeam->special = 2;
        }
      }elseif ($tempCoachTeamPoints1 === $tempCoachTeamPoints2){
        $coachTeam->coachTeamDraw += 1;
      }else{
        $coachTeam->coachTeamLoss += 1;
        if( true === $coachTeam->finale ){
          $coachTeam->special = 1;
        }
      }
      $coachTeam->coachTeamPoints += $tempCoachTeamPoints1;
      $coachTeams[] = $coachTeam;
    }

    foreach ($coachTeams as $coachTeam) {
      $pointsByCoachTeamId[$coachTeam->id] = $coachTeam->coachTeamPoints;
    }
    
    //Calcul des points adversaires			
    foreach ($coachTeams as $coachTeam) {
      $coachTeam->netTd = $coachTeam->tdFor - $coachTeam->tdAgainst;
      $coachTeam->netCasualties = $coachTeam->casualtiesFor - $coachTeam->casualtiesAgainst;
      $coachTeam->netCompletions = $coachTeam->completionsFor - $coachTeam->completionsAgainst;
      $coachTeam->netFouls = $coachTeam->foulsFor - $coachTeam->foulsAgainst;
      foreach ($coachTeam->teams as $coach) {
        $coach->netTd = $coach->tdFor - $coach->tdAgainst;
        $coach->netCasualties = $coach->casualtiesFor - $coach->casualtiesAgainst;
        $coach->netCompletions = $coach->completionsFor - $coach->completionsAgainst;
        $coach->netFouls = $coach->foulsFor - $coach->foulsAgainst;
        foreach ($coach->opponentIdArray as $opponentId) {
          if (true === array_key_exists($opponentId, $pointsByTeamId)) {
            $coach->opponentsPoints += $pointsByTeamId[$opponentId];
            $coachTeam->opponentsPoints += $pointsByTeamId[$opponentId];
          }
        }
      }
      foreach ($coachTeam->opponentCoachTeamIdArray as $opponentId){
        if (true === array_key_exists($opponentId, $pointsByCoachTeamId)) {
          $coachTeam->opponentCoachTeamPoints += $pointsByCoachTeamId[$opponentId];
        }
      }
    }
    return $coachTeams;
  }
  
  public function getCoachTeamRanking($edition){
    $rankingStrategy = $edition->getRankingStrategy();
    $coachTeams = $this->getCoachTeamStatisticsBetweenRounds($edition,0,$edition->getCurrentRound());
    // Tri des participants de la coach_team dans l'ordre du classement
    foreach (array_keys($coachTeams) as $id) {
      $coachTeam = $coachTeams[$id];
      usort($coachTeam->teams, array($rankingStrategy, 'compareCoachs'));
      $coachTeams[$id] = $coachTeam;
    }
    usort($coachTeams, array($rankingStrategy, 'compareCoachTeams'));
    return $coachTeams;
  }
  
  public function getCoachTeamRankingByTouchdown($edition){
    return $this->getCoachTeamRankingByFunction($edition,'compareCoachsByTouchdown');
  }
  
  public function getCoachTeamRankingByCasualties($edition){
    return $this->getCoachTeamRankingByFunction($edition,'compareCoachsByCasualties');
  }
  
  public function getCoachTeamRankingByCompletions($edition){
    return $this->getCoachTeamRankingByFunction($edition,'compareCoachsByCompletions');
  }
  
  public function getCoachTeamRankingByFouls($edition){
    return $this->getCoachTeamRankingByFunction($edition,'compareCoachsByFouls');
  }

  public function getCoachTeamRankingByDefense($edition){
    return array_reverse($this->getCoachTeamRankingByFunction($edition,'compareByTouchdownAgainst'));
  }
  
  public function getCoachTeamRankingByComeback($edition) {
    $rankingStrategy = $edition->getRankingStrategy();
    $finalRanking = $this->getCoachTeamRanking($edition);
    $firstDayRanking = $this->getCoachTeamStatisticsBetweenRounds($edition, 0, $edition->firstDayRound);
    usort($firstDayRanking, array($rankingStrategy,'compareCoachTeams'));
    $coachTeamNumber = count($finalRanking);
    for ($i = 0; $i < $coachTeamNumber; $i++) {
      $finalCoachTeam = $finalRanking[$i];
      $idToFind = $finalCoachTeam->id;
      $finalCoachTeam->finalRanking = $i + 1;
      for ($j = 0; $j < $coachTeamNumber; $j++) {
        $firstDayCoachTeam = $firstDayRanking[$j];
        if ($firstDayCoachTeam->id === $idToFind) {
          break;
        }
      }
      // We find it !
      if ($j !== $coachTeamNumber) {
        $finalCoachTeam->firstDayRanking = $j + 1;
      }
      $finalCoachTeam->diffRanking = $j - $i;
      $finalRanking[$i] = $finalCoachTeam;
    }
    usort($finalRanking, array($this, 'compareByComeback'));
    return $finalRanking;
  }

  
  protected function getCoachTeamRankingByFunction($edition,$functionName){
    $rankingStrategy = $edition->getRankingStrategy();
    $coachTeams = $this->getCoachTeamStatisticsBetweenRounds($edition,0,$edition->getCurrentRound());
    foreach (array_keys($coachTeams) as $id) {
      $coachTeam = $coachTeams[$id];
      usort($coachTeam->teams, array($this, $functionName));
      $coachTeams[$id] = $coachTeam;
    }
    usort($coachTeams, array($this,$functionName));
    return $coachTeams;
  }

  public function getEditionById($id) {
    $clause = 'id = ' . intval($id);
    $editions = $this->getEditions($clause);
    $edition = NULL;
    if (0 < count($editions)) {
      $edition = reset($editions);
    }
    return $edition;
  }
  
  public function getCurrentEdition() {
    $clause = 'id = (SELECT max(id) FROM tournament_edition)';
    $editions = $this->getEditions($clause);
    $edition = NULL;
    if (0 < count($editions)) {
      $edition = reset($editions);
    }
    return $edition;
  }

  public function getEditions($clause='1=1') {
    $query = self::editionQuery;
    $query .= ' WHERE ' . $clause;
    //echo 'request : [',$query,']<br />';
    $result = $this->query($query);
    $editions = array();
    $edition = $this->convertResultIntoEdition($result);
    while (null != $edition) {
      $editions[] = $edition;
      $edition = $this->convertResultIntoEdition($result);
    }
    $this->resultClose($result);
    return $editions;
  }
  
  public function convertResultIntoEdition($result){
    $edition = null;
    $obj = $this->resultFetchObject($result);
    if(null != $obj){
      $edition = new Edition();
      $edition->setId($obj->id);
      $edition->setDay1($obj->day1);
      $edition->setDay2($obj->day2);
      $edition->setRoundNumber($obj->roundNumber);
      $edition->setCurrentRound($obj->currentRound);
      $edition->setUseFinale($obj->useFinale);
      $edition->setFullTriplette($obj->fullTriplette);
      $edition->setRankingStrategyName($obj->rankingStrategy);
      $edition->setFirstDayRound($obj->firstDayRound);
    }
    return $edition;
  }

  private static function hydrateGames(Array $games, $id1, $id2)
  {
    if ( array_key_exists($id1, $games) )
    {
      $tmpArray = $games[$id1];
      $tmpArray[] = $id2;
      $games[$id1] = $tmpArray;
    }
    else
    {
      $games[$id1] = array($id2);
    }
    if ( array_key_exists($id2, $games) )
    {
      $tmpArray = $games[$id2];
      $tmpArray[] = $id1;
      $games[$id2] = $tmpArray;
    }
    else
    {
      $games[$id2] = array($id1);
    }
    return $games;
  }

  protected function getCoachTeamGames($clause)
  {
    $query = self::coachTeamGames;
    $query .= ' WHERE ' . $clause;
    //echo 'request : [',$query,']<br />';
    $result = $this->query($query);
    $coachTeamGames = array();
    $row = $this->resultFetchRow($result);
    while (null != $row) {
      $id1 = $row[0];
      $id2 = $row[1];
      $coachTeamGames = self::hydrateGames($coachTeamGames,$id1,$id2);
      $row = $this->resultFetchRow($result);
    }
    $this->resultClose($result);
    return $coachTeamGames;
  }

  public function getCoachTeamGamesByEdition($edition)
  {
    $clause = 'm.edition = '.intval($edition);
    return $this->getCoachTeamGames($clause);
  }
  
  public function getCoachGamesByEdition($edition)
  {
    $matchs = $this->getMatchsByEditionAndRound($edition,0);
    $games = array();
    foreach($matchs as $match)
    {
      $id1 = $match->teamId1;
      $id2 = $match->teamId2;
      $games = self::hydrateGames($games,$id1,$id2);
    }
    return $games;
  }

}

?>
