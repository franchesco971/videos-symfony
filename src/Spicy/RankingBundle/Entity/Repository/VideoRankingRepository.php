<?php

namespace Spicy\RankingBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Spicy\RankingBundle\Entity\Ranking;
use Spicy\RankingBundle\Entity\RankingType;
use Spicy\SiteBundle\Entity\Video;

/**
 * NewsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class VideoRankingRepository extends EntityRepository
{
    public function getOne(Video $video, Ranking $ranking) 
    {
        $qb = $this->createQueryBuilder('vr')
                ->where('vr.video=:video')
                ->andWhere('vr.ranking=:ranking')
                ->setParameter('video', $video->getId())
                ->setParameter('ranking', $ranking->getId());
        
        return $qb->getQuery()->getOneOrNullResult();
    }
    
    public function getOneOfLastRanking(Video $video) 
    {
        $qb = $this->createQueryBuilder('vr')
                ->join('vr.ranking', 'r')
                ->where('vr.video=:video')
                ->andWhere('r.id in (select max(ra.id) from SpicyRankingBundle:Ranking ra)')
                ->setParameter('video', $video->getId());
        
        return $qb->getQuery()->getOneOrNullResult();
    }
    
    public function getCountForVideo(Video $video) 
    {
        $qb = $this->createQueryBuilder('vr')
                ->select('SUM(vr.nbVu) as total')
                ->leftJoin('vr.ranking', 'r')
                ->leftJoin('r.rankingType', 'rt')
                ->where('vr.video=:video')
                ->andWhere('rt.id=:rtid')
                ->setParameter('rtid', RankingType::MOIS)
                ->setParameter('video', $video->getId());
        
        return $qb->getQuery()->getOneOrNullResult();
    }
}
