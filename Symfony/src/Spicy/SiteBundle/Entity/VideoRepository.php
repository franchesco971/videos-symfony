<?php

namespace Spicy\SiteBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Spicy\SiteBundle\Entity\Video;

/**
 * VideoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class VideoRepository extends EntityRepository
{
    public function getOneAvecArtistes($id)
    {
        $qb = $this->createQueryBuilder('v')
             ->join('v.type_videos', 't')
             ->join('v.genre_musicaux', 'g')
             ->join('v.artistes', 'a')
             ->where('v.id=:id')
             ->setParameter('id', $id)
             ->andWhere('v.etat=1')
             ->addSelect('a')
                ->addSelect('t')
                ->addSelect('g')
             ;
 
        return $qb->getQuery()
                ->getSingleResult();
            //->getResult();
    }
    
    public function getAvecArtistes($nbOccurrences)
    {
        $qb = $this->createQueryBuilder('v')
             ->join('v.artistes', 'a')
             ->andWhere('v.etat=1')
             //->join('v.genre_musicaux', 'g')
                //->where("g.libelle<>'Rétro'")
             ->where("v.id NOT IN (select vi.id from SpicySiteBundle:video vi 
                    JOIN 
                        vi.genre_musicaux ge where ge.libelle='Rétro')")
                
            ->addOrderBy('v.dateVideo','DESC')
                ->setFirstResult(0)
                ->setMaxResults($nbOccurrences)
             ->addSelect('a');
 
        /*return $qb->getQuery()
            ->getResult();*/
        
        $query=$qb->getQuery();
        //$result=$query->getResult();
        
        return new Paginator($query);
    }
    
    public function getSuiteAvecArtistes($page,$premierResultat,$nbOccurrences)
    {
        if( $page < 1 )
        {
            throw $this->createNotFoundException('Page inexistante (page = '.$page.')');
        }
        
        $qb = $this->createQueryBuilder('v')
             ->join('v.artistes', 'a')
             ->andWhere('v.etat=1')
             //->join('v.genre_musicaux', 'g')
             ->where("v.id NOT IN (select vi.id from SpicySiteBundle:video vi 
                    JOIN 
                        vi.genre_musicaux ge where ge.libelle='Rétro')")
            ->addOrderBy('v.dateVideo','DESC')
                //->setFirstResult($premierResultat)
                ->setFirstResult(($page-1)*$nbOccurrences+$premierResultat)
                ->setMaxResults($nbOccurrences)
                ->addSelect('a');
                
                //->setMaxResults($nbOccurrences*3)          
 
        /*return $qb->getQuery()
            ->getResult();*/
        
        $query=$qb->getQuery();
        //$result=$query->getResult();
        
         return new Paginator($query);
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
    
    public function getByGenre($id,$nbOccurrences)
    {
        $qb = $this->createQueryBuilder('v')
             ->join('v.genre_musicaux', 'g')
                ->where('g.id=:id')
                ->setParameter('id', $id)
             ->andWhere('v.etat=1')
                ->addOrderBy('v.dateVideo','DESC')
                ->setFirstResult(0)
                ->setMaxResults($nbOccurrences)
             ->addSelect('g');
        
        return $qb->getQuery()
            ->getResult();
    }
    
    public function getAllRetro($nbOccurrences)
    {
        $qb = $this->createQueryBuilder('v')
             ->join('v.type_videos', 't')
             ->join('v.genre_musicaux', 'g')
             ->join('v.artistes', 'a')
                ->where("g.libelle='Rétro'")
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
             //->where('v.id=:id')
             //->setParameter('id', $id)
                ->where("g.libelle='Rétro'")
             ->andWhere('v.etat=1')
             //->andWhere('v.dateVideo=SELECT MAX(v.dateVideo) from Spicy\SiteBundle\Entity\Video')
             ->setMaxResults(1)
             ->addOrderBy('v.dateVideo','DESC')  
             ->addSelect('a')
                ->addSelect('t')
                ->addSelect('g')
             ;
 
        //return $qb->getQuery()->getSingleResult();
        
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
                ->where('g.id in (:list)')
                ->andWhere('v.id <> :id')
                ->orderBy('v.dateVideo','DESC')
                ->setFirstResult(0)
                ->setMaxResults(4);
                
        //$query->setParameter('list', $idList);
        $sql->setParameter('list', $idList);
        $sql->setParameter('id', $id);
        $query=$sql->getQuery();
        $result=$query->getResult();
        return $result;
    }
}