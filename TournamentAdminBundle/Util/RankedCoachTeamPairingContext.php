<?php
namespace FantasyFootball\TournamentAdminBundle\Util;

use Doctrine\ORM\EntityManager;
use FantasyFootball\TournamentCoreBundle\Entity\Edition;
use FantasyFootball\TournamentCoreBundle\DatabaseConfiguration;
use FantasyFootball\TournamentCoreBundle\Util\DataProvider;
use FantasyFootball\TournamentCoreBundle\Util\RankingStrategyFabric;

class RankedCoachTeamPairingContext extends CoachTeamPairingContext {
    
  public function __construct(Edition $edition,EntityManager $em,DatabaseConfiguration $conf){
    parent::__construct($edition,$em,$conf);
  }

  public function customInit(){
    $data = new DataProvider($this->conf);
    $toPair = array();
    $strategy = RankingStrategyFabric::getByName($this->edition->getRankingStrategy());
    $coachTeamRanking = $data->getCoachTeamRanking($this->edition);
    foreach ($coachTeamRanking as $coachTeam){
      $id = $coachTeam->id;
      $toPair[] = $id;
      $tempSortedCoach = array();
      foreach ($coachTeam->teams as $coach){
          $tempSortedCoach[] = $coach->id;
      }
      $this->sortedCoachsByTeamCoachId[$id] = $tempSortedCoach;
    }
    $constraints = $data->getCoachTeamGamesByEdition($this->edition->getId());

    return ([$toPair, $constraints,[]]);
  }
}
