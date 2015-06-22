<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FantasyFootball\TournamentAdminBundle\Util;

use Doctrine\ORM\EntityManager;
use FantasyFootball\TournamentCoreBundle\Entity\Edition;
use FantasyFootball\TournamentCoreBundle\DatabaseConfiguration;

/**
 * Description of PairingContext
 *
 * @author Trambi
 */
abstract class PairingContext{
    protected $edition;
    protected $em;
    protected $conf;
    
    public function __construct(Edition $edition,
            EntityManager $em ,
            DatabaseConfiguration $conf ){
        $this->edition = $edition;
        $this->em = $em;
        $this->conf = $conf;
    }
}
