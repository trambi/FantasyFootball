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
    
    public function getQueryBuilderForCoachsWithoutGameByEditionAndRound($editionId,$round)
    {
        $qb = $this->createQueryBuilder('c');
        $games = $this->getEntityManager()->createQuery(
                'SELECT g
                FROM FantasyFootballTournamentCoreBundle:Game g
                WHERE g.edition=:edition
                AND g.round=:round')
            ->setParameter('edition',$editionId)
            ->setParameter('round',$round)
            ->getResult();
        $unavailableCoachs = array();
        foreach($games as $game)
        {
            $unavailableCoachs[] = $game->getCoach1()->getId();
            $unavailableCoachs[] = $game->getCoach2()->getId();
        }
        
        $qb->where($qb->expr()->eq('c.edition', $editionId));
        if ( 0 != count($unavailableCoachs)){
            $qb->andWhere($qb->expr()->notIn('c.id', $unavailableCoachs));
        }
        $qb->orderBy('c.name', 'ASC');
        return $qb;
    }
    
    public function getQueryBuilderFreeCoachsByEditionAndCoachTeam($editionId,$coachTeamId)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->where($qb->expr()->andX(
          $qb->expr()->eq('c.edition', $editionId),
            $qb->expr()->orX(
              $qb->expr()->isNull('c.coachTeam'),
              $qb->expr()->eq('c.coachTeam',$coachTeamId)
          )));
        $qb->orderBy('c.name', 'ASC');
        return $qb;
    }
    
}