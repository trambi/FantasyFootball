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
