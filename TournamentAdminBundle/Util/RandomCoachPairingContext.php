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
 * Description of RandomCoachPairingContext
 *
 * @author Trambi
 */
class RandomCoachPairingContext extends CoachPairingContext{
    
    
    public function __construct(Edition $edition,
            EntityManager $em,
            DatabaseConfiguration $conf){
        parent::__construct($edition, $em, $conf);
    }
    
    protected function customInit() {
        $toPair = array();

        $coachs = $this->em
                ->getRepository('FantasyFootballTournamentCoreBundle:Coach')
                ->findByEdition($this->edition->getId());
        foreach ($coachs as $coach)
        {
            $toPair[] = $coach->getId();
        }
        shuffle($toPair);
        
        return ([$toPair,array()]);
    }
}
