<?php

namespace FantasyFootball\TournamentAdminBundle\Util;

use Doctrine\ORM\EntityManager;
use FantasyFootball\TournamentCoreBundle\Entity\Edition;
use FantasyFootball\TournamentCoreBundle\DatabaseConfiguration;

class RandomCoachTeamPairingContext extends CoachTeamPairingContext {

  public function __construct(Edition $edition,EntityManager $em,DatabaseConfiguration $conf){
    parent::__construct($edition, $em,$conf);
  }

  protected function customInit() {
    $toPair = array();
    $coachTeams = $this->em->getRepository('FantasyFootballTournamentCoreBundle:CoachTeam')
        ->findByEditionJoined($this->edition->getId());
    foreach ($coachTeams as $coachTeam)
    {
      $toPair[] = $coachTeam->getId();
      $shuffledCoachs = array();
      foreach ($coachTeam->getCoachs() as $coach) {
        $shuffledCoachs[] = $coach->getId();
      }
      shuffle($shuffledCoachs);
      $this->sortedCoachsByTeamCoachId[$coachTeam->getId()] = $shuffledCoachs;
    }
    shuffle($toPair);

    return ([$toPair,[],[]]);
  }
}
