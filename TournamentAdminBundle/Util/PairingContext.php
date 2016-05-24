<?php

namespace FantasyFootball\TournamentAdminBundle\Util;

use Doctrine\ORM\EntityManager;
use FantasyFootball\TournamentCoreBundle\Entity\Edition;
use FantasyFootball\TournamentCoreBundle\DatabaseConfiguration;

abstract class PairingContext{
  protected $edition;
  protected $em;
  protected $conf;
  
  public function __construct(Edition $edition,EntityManager $em ,DatabaseConfiguration $conf ){
    $this->edition = $edition;
    $this->em = $em;
    $this->conf = $conf;
  }
}
