<?php

namespace FantasyFootball\TournamentCoreBundle\Entity;


use Doctrine\ORM\EntityRepository;

class CoachRepository  extends EntityRepository {

    public function findOneByIdJoined($id)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT r, c, ct FROM FantasyFootballTournamentCoreBundle:Coach c
                LEFT JOIN c.race r
                LEFT JOIN c.coachTeam ct
                WHERE c.id = :id'
            )->setParameter('id', $id);

        try {
            return $query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
    
}
?>