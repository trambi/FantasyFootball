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

    public function deleteByEdition($edition){
        $builder = $this->getEntityManager()
            ->createQueryBuilder()
            ->delete('FantasyFootballTournamentCoreBundle:Coach c')
            ->where('c.edition = :edition')
            ->setParameter(':edition',$edition);
        $builder->getQuery()->execute();
    }
    
}