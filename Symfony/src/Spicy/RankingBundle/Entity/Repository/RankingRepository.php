<?php

namespace Spicy\RankingBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Spicy\RankingBundle\Entity\RankingType;

/**
 * NewsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RankingRepository extends EntityRepository
{
    public function getLastRanking() 
    {
        $qb = $this->createQueryBuilder('r')
                ->leftJoin('r.videoRankings', 'vr')
                ->leftJoin('vr.video', 'v')
                ->join('v.genre_musicaux', 'g')
                ->addOrderBy('vr.nbVu','DESC')
                ->addSelect('vr')
                ->where('g.id<> :id_retro')
                ->setParameter('id_retro', 2)
                ->andWhere("r.dateRanking in (select max(ra.dateRanking) from SpicyRankingBundle:Ranking ra)"); 
        
        return $qb->getQuery()->getOneOrNullResult();
        //$query=$qb->getQuery();
        //return new Paginator($query);
    }
    
    public function getPreviousRanking($ranking) 
    {
        $qb = $this->createQueryBuilder('r')
                ->leftJoin('r.videoRankings', 'vr')
                ->leftJoin('vr.video', 'v')
                ->join('v.genre_musicaux', 'g')
                ->addOrderBy('r.dateRanking','DESC')
                ->addSelect('vr')
                ->where('g.id<> :id_retro')
                ->setParameter('id_retro', 2)
                ->andWhere("r.id in (select max(ra.id) from SpicyRankingBundle:Ranking ra "
                        . "where ra.id<".$ranking->getId()." AND ra.rankingType=".RankingType::MOIS.")"); 
        
        return $qb->getQuery()->getOneOrNullResult();
        //$query=$qb->getQuery();
        //return new Paginator($query);
    }
    
    public function getRankings($page,$nbOccurences)
    {
        $qb = $this->createQueryBuilder('r')
                ->setFirstResult(($page-1)*$nbOccurences)
                ->setMaxResults($nbOccurences);
        
        $query=$qb->getQuery();
        return new Paginator($query);
    }
    
    public function getOne($id)
    {
        $qb = $this->createQueryBuilder('r')
                ->join('r.videoRankings', 'vr')
                ->join('vr.video', 'v')
                ->where('r.id=:id')
                ->setParameter('id', $id)
                ->addSelect('vr')
                ->addSelect('v')
                ->addSelect('a');
        
        return $qb->getQuery()->getOneOrNullResult();
    }
}
