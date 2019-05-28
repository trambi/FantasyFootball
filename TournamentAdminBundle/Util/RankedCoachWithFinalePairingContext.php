<?php
/*
    FantasyFootball Symfony2 bundles - Symfony2 bundles collection to handle fantasy football tournament 
    Copyright (C) 2017  Bertrand Madet

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
namespace FantasyFootball\TournamentAdminBundle\Util;

use Doctrine\ORM\EntityManager;
use FantasyFootball\TournamentCoreBundle\Entity\Edition;
use FantasyFootball\TournamentCoreBundle\DatabaseConfiguration;
use FantasyFootball\TournamentCoreBundle\Util\DataProvider;
use FantasyFootball\TournamentCoreBundle\Util\RankingStrategyFabric;

class RankedCoachWithFinalePairingContext extends CoachPairingContext {

  public function __construct(Edition $edition,EntityManager $em,DatabaseConfiguration $conf){
    parent::__construct($edition,$em,$conf);
  }

  protected function customInit()
  {
    $data = new DataProvider($this->conf);
    $strategy = $this->edition->getRankingStrategy();

    $coachRanking = $data->getMainCoachRanking($this->edition);
    $toPair = array_keys($coachRanking);

    $constraints = $data->getCoachGamesByEdition($this->edition->getId());
    $alreadyPairedGames = [array_shift($toPair),array_shift($toPair)];
    return ([$toPair, $constraints,$alreadyPairedGames]);
  }
}
