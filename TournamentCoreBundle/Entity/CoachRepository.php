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
    
    public function qbForFreeCoachsCurrentEditionAndCurrentRound()
    {
        $qb = $this->createQueryBuilder('c');
        $edition = $this->getEntityManager()
            ->createQuery('
                SELECT e FROM FantasyFootballTournamentCoreBundle:Edition e
                ORDER BY e.day1 DESC')
             ->setMaxResults(1)
            ->getSingleResult();
        $editionId = $edition->getId();
        $currentRound = $edition->getCurrentRound();
        $games = $this->getEntityManager()
            ->createQuery(
                'SELECT g
                FROM FantasyFootballTournamentCoreBundle:Game g
                WHERE g.edition=:edition
                AND g.round=:round')
            ->setParameter('edition',$editionId)
            ->setParameter('round',$currentRound)
            ->getResult();

        foreach($games as $game)
        {
            $unavailableCoachs[] = $game->getCoach1()->getId();
            $unavailableCoachs[] = $game->getCoach2()->getId();
        }
        
        return $qb
            ->where($qb->expr()->eq('c.edition', $editionId))
            ->andWhere($qb->expr()->notIn('c.id', $unavailableCoachs));
    }
    
}
?>