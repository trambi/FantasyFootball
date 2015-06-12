<?php

namespace FantasyFootball\TournamentCoreBundle\Entity;


use Doctrine\ORM\EntityRepository;

class CoachTeamRepository  extends EntityRepository {

    public function findByEditionJoined($edition)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT c, ct FROM FantasyFootballTournamentCoreBundle:CoachTeam ct
                LEFT JOIN ct.coachs c
                WHERE c.edition = :edition'
            )->setParameter('edition', $edition);

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
    
}
?>