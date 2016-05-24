<?php
namespace FantasyFootball\TournamentAdminBundle\Util;

use Doctrine\ORM\EntityManager;
use FantasyFootball\TournamentCoreBundle\Entity\Edition;
use FantasyFootball\TournamentCoreBundle\DatabaseConfiguration;

class RandomCoachPairingContext extends CoachPairingContext{

  public function __construct(Edition $edition,EntityManager $em,DatabaseConfiguration $conf){
    parent::__construct($edition, $em, $conf);
  }

  protected function customInit() {
    $toPair = array();

    $coachs = $this->em->getRepository('FantasyFootballTournamentCoreBundle:Coach')
                        ->findByEdition($this->edition->getId());
    foreach ($coachs as $coach){
      $toPair[] = $coach->getId();
    }
    shuffle($toPair);
    return ([$toPair,[],[]]);
  }
}
