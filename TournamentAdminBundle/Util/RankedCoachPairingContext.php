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
use FantasyFootball\TournamentCoreBundle\Util\DataProvider;
use FantasyFootball\TournamentCoreBundle\Util\RankingStrategyFabric;

/**
 * Description of RandomCoachPairingContext
 *
 * @author Trambi
 */
class RandomCoachPairingContext extends CoachPairingContext {
    
    public function __construct(Edition $edition,
            EntityManager $em,
            DatabaseConfiguration $conf){
        parent::__construct($edition,$em,$conf);
    }
    
    protected function customInit()
    {
        $data = new DataProvider($this->conf);
        $strategy = RankingStrategyFabric::getByName($this->edition->getRankingStrategy());

        $coachRanking = $data->getMainCoachRanking($this->edition->getId(), $strategy);
        $toPair = array_keys($coachRanking);
        
        $constraints = $data->getCoachGamesByEdition($this->edition->getId());

        return ([$toPair, $constraints]);
    }
}
