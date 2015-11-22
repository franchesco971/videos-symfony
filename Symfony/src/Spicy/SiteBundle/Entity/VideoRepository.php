<?php

namespace Spicy\SiteBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Spicy\SiteBundle\Entity\Video;
use Spicy\RankingBundle\Entity\Ranking;

/**
 * VideoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class VideoRepository extends EntityRepository
{
    private $retro=2;//2 en prod, 6 en dev
    
    public function getOneAvecArtistes($id)
    {
        $qb = $this->createQueryBuilder('v')
             ->join('v.type_videos', 't')
             ->join('v.genre_musicaux', 'g')
             ->join('v.artistes', 'a')
             ->leftJoin('v.hashtags', 'vh')
             ->leftJoin('a.hashtags', 'ah')
             ->where('v.id=:id')
             ->setParameter('id', $id)
             ->andWhere('v.etat=1')
             ->addSelect('a')
                ->addSelect('t')
                ->addSelect('g')
                ->addSelect('vh')
                ->addSelect('ah')
             ;
 
        return $qb->getQuery()
                ->getSingleResult();
    }
    
    public function getAvecArtistes($nbOccurrences,$top=false)
    {
        $qb = $this->createQueryBuilder('v')
            ->join('v.artistes', 'a')
            ->join('v.genre_musicaux', 'g')
            ->where('g.id<> :id_retro')
            ->setParameter('id_retro', $this->retro)
            ->andWhere('v.etat=1')           
            ->setFirstResult(0)
            ->setMaxResults($nbOccurrences)
            ->addSelect('a');
                
        if($top)
        {
            $qb->addOrderBy('v.onTop','DESC');
        }
        
        $qb->addOrderBy('v.dateVideo','DESC');
                
        $query=$qb->getQuery();
        
        return new Paginator($query);
    }
    
    public function getSuiteAvecArtistes($page,$premierResultat,$nbOccurrences,$videoIdsList)
    {
        if( $page < 1 )
        {
            throw $this->createNotFoundException('Page inexistante (page = '.$page.')');
        }
        
        $qb = $this->createQueryBuilder('v')
             ->join('v.artistes', 'a')  
             ->join('v.genre_musicaux', 'g')
             ->where('g.id<> :id_retro')
             ->setParameter('id_retro', $this->retro)
             ->andWhere('v.etat=1')
             ->andWhere('v.id NOT IN (:list)')
            ->setParameter('list', $videoIdsList)
            ->setFirstResult(($page-1)*$nbOccurrences)
            ->setMaxResults($nbOccurrences)
            ->addSelect('a')
            ->orderBy('v.dateVideo','DESC')
            ;
        $query=$qb->getQuery();
        
        return new Paginator($query);
        //return $query->getResult();
    }
    
    public function getSuite($page,$nbOccurrences,$videoIdsList)
    {
        if( $page < 1 )
        {
            throw $this->createNotFoundException('Page inexistante (page = '.$page.')');
        }
        
        $qb = $this->createQueryBuilder('v')
             ->join('v.genre_musicaux', 'g')
             ->where('g.id<> :id_retro')
             ->setParameter('id_retro', $this->retro)
             ->andWhere('v.etat=1')
             ->andWhere('v.id NOT IN (:list)')
            ->setParameter('list', $videoIdsList)
            ->setFirstResult(($page-1)*$nbOccurrences)
            ->setMaxResults($nbOccurrences)
            ->orderBy('v.dateVideo','DESC')
            ;
        $query=$qb->getQuery();
        
        return new Paginator($query);
    }
    
    public function getSuiteAjax($videoIdsList)
    {        
        $qb = $this->createQueryBuilder('v')
             ->join('v.genre_musicaux', 'g')
             ->where('g.id<> :id_retro')
             ->setParameter('id_retro', $this->retro)
             ->andWhere('v.etat=1')
             ->andWhere('v.id NOT IN (:list)')
            ->setParameter('list', $videoIdsList)
            ->orderBy('v.dateVideo','DESC')
            ;
        $query=$qb->getQuery();
        
        return $query->getResult();
    }
    
    public function getByArtiste($id,$nbOccurrences)
    {
        $qb = $this->createQueryBuilder('v')
             ->join('v.artistes', 'a')
                ->where('a.id=:id')
                ->setParameter('id', $id)
             ->andWhere('v.etat=1')
                ->addOrderBy('v.dateVideo','DESC')
                ->setFirstResult(0)
                ->setMaxResults($nbOccurrences)
             ->addSelect('a');
        
        return $qb->getQuery()
            ->getResult();
    }
    
    public function getByGenre($id,$nbOccurrences,$page)
    {
        if( $page < 1 )
        {
            throw $this->createNotFoundException('Page inexistante (page = '.$page.')');
        }
        
        $qb = $this->createQueryBuilder('v')
             ->join('v.genre_musicaux', 'g')
             ->join('v.artistes', 'a')
                ->where('g.id=:id')
                ->setParameter('id', $id)
             ->andWhere('v.etat=1')
                ->addOrderBy('v.dateVideo','DESC')
                ->setFirstResult(($page-1)*$nbOccurrences)
                ->setMaxResults($nbOccurrences)
             ->addSelect('g')
            ->addSelect('a');
                
        $query=$qb->getQuery();
        return new Paginator($query);
    }
    
    public function getAllRetro($nbOccurrences)
    {
        $qb = $this->createQueryBuilder('v')
             ->join('v.type_videos', 't')
             ->join('v.genre_musicaux', 'g')
             ->join('v.artistes', 'a')
             ->where("g.id=".$this->retro)
             ->andWhere('v.etat=1')
             ->setFirstResult(0)
             ->setMaxResults($nbOccurrences)
             ->addOrderBy('v.dateVideo','DESC')  
             ->addSelect('a')
                ->addSelect('t')
                ->addSelect('g')
             ;
 
        return $qb->getQuery()->getResult();
            
    }
    
    public function getRetro()
    {
        $qb = $this->createQueryBuilder('v')
             ->join('v.type_videos', 't')
             ->join('v.genre_musicaux', 'g')
             ->join('v.artistes', 'a')
                ->where("g.id=".$this->retro)
             ->andWhere('v.etat=1')
             ->andWhere("v.dateVideo in (select max(vi.dateVideo) from SpicySiteBundle:video vi 
                        JOIN vi.genre_musicaux ge 
                        where ge.id=".$this->retro.")")
             ->addOrderBy('v.dateVideo','DESC')  
             ->addSelect('a')
                ->addSelect('t')
                ->addSelect('g')
             ;
        
        try {
            $result=$qb->getQuery()->getSingleResult();
        } catch (Doctrine\ORM\NoResultException $e) {
            $result=null;
        }
        
        return $result;
    }
    
    public function getAll($page,$nbOccurences)
    {
        $qb = $this->createQueryBuilder('v')
                ->setFirstResult(($page-1)*$nbOccurences)
                ->setMaxResults($nbOccurences)
                ->orderBy('v.titre')
                ->addOrderBy('v.dateVideo');
        
        $query=$qb->getQuery();
        return new Paginator($query);
    }
    
    public function getSuggestions($idList,$id)
    {        
        /*$query=  $this->_em->createQuery("SELECT v
            FROM Spicy\SiteBundle\Entity\Video v
            INNER join Spicy\SiteBundle\Entity\GenreMusical on id=video_id
            where genremusical_id IN :list
            ORDER BY rand()
            LIMIT 0 , 30");*/
        
        $sql=$this->createQueryBuilder('v')
                ->join('v.genre_musicaux', 'g')
                ->where('g.id IN (:list)')
                ->andWhere('v.id <> :id')
                ->orderBy('v.dateVideo','DESC')
                ->setFirstResult(0)
                ->setMaxResults(4);
                
        $sql->setParameter('list', $idList);
        $sql->setParameter('id', $id);
        $query=$sql->getQuery();
        $result=$query->getResult();
        return $result;
    }
    
    public function getSuggestionsArtistes($idList)
    {        
        if(empty($idList))
        $idList[]=0;
        
        $qb=$this->createQueryBuilder('v')
                ->join('v.artistes', 'a')
                ->join('v.genre_musicaux', 'g')
                ->where('g.id in ('.implode(',', $idList).')')
                ->andWhere('v.etat=1')                
                ->setFirstResult(0)
                ->setMaxResults(20)
                ->addSelect('a');
        
        return $qb->getQuery()->getResult();
    }
    
    public function getByTag($id,$page,$nbOccurrences) 
    {
        $qb = $this->createQueryBuilder('v')
             ->join('v.genre_musicaux', 'g')
             ->join('v.artistes', 'a')
             ->join('v.hashtags','h')
                ->where('h.id=:id')
                ->setParameter('id', $id)
             ->andWhere('v.etat=1')
                ->addOrderBy('v.dateVideo','DESC')
                ->setFirstResult(($page-1)*$nbOccurrences)
                ->setMaxResults($nbOccurrences)
             ->addSelect('g')
            ->addSelect('a');
        
        $query=$qb->getQuery();
        return new Paginator($query);
    }
    
    public function getTops($page,$nbOccurences)
    {
        $qb = $this->createQueryBuilder('v')
                ->join('v.artistes', 'a')
                ->join('v.type_videos', 't')
                ->setFirstResult(($page-1)*$nbOccurences)
                ->setMaxResults($nbOccurences)
                ->where("t.libelle='Top'")
                ->orderBy('v.dateVideo','DESC')
                ->addOrderBy('v.dateVideo')
                ->addSelect('a');
        
        $query=$qb->getQuery();
        return new Paginator($query);
    }
    
    public function getTopByMonth(Ranking $ranking,$max=10) 
    {
        $qb=  $this->createQueryBuilder('v')
                ->join('v.videoRankings', 'vr')
                ->where('vr.ranking=:ranking')
                ->join('v.genre_musicaux', 'g', 'WITH', 'g.id<> :id_retro')
                ->join('v.type_videos', 't', 'WITH', 't.id= :id_type')
                ->setParameter('ranking', $ranking->getId())
                ->setParameter('id_retro', 2)
                ->setParameter('id_type', 1)
                ->orderBy('vr.nbVu','DESC')
                ->addOrderBy('v.dateVideo','DESC')
                ->setMaxResults($max)
                ->addSelect('vr')
                ->addSelect('t');
        
        //return $qb->getQuery()->getResult();
        $query=$qb->getQuery();
        return new Paginator($query);
    }
    
    public function getAvecArtistesFlux($nbOccurrences,$top=false)
    {
        $qb = $this->createQueryBuilder('v')
            ->join('v.artistes', 'a')
            ->join('v.genre_musicaux', 'g')
            ->where('g.id<> :id_retro')
            ->setParameter('id_retro', $this->retro)
            ->andWhere('v.etat=1')           
            ->setFirstResult(0)
            ->setMaxResults($nbOccurrences)
            ->addSelect('a');
                
        if($top)
        {
            $qb->leftJoin('v.videoRankings', 'vr')
                    ->andWhere('v.onTop=1')
                    ->orderBy('vr.nbVu');
        }
        
        $qb->addOrderBy('v.dateVideo','DESC');
                
        $query=$qb->getQuery();
        
        return $query->getResult();
    }
    
    public function getFlux($nbOccurrences,$top=false)
    {
        $qb = $this->createQueryBuilder('v')
            ->join('v.genre_musicaux', 'g')
            ->where('g.id<> :id_retro')
            ->setParameter('id_retro', $this->retro)
            ->andWhere('v.etat=1')           
            ->setFirstResult(0)
            ->setMaxResults($nbOccurrences)
            ->orderBy('v.dateVideo','DESC');
                
        if($top)
        {
            $qb->leftJoin('v.videoRankings', 'vr')
                    ->andWhere('v.onTop=1');
        }
                
        $query=$qb->getQuery();
        
        return $query->getResult();
    }
}