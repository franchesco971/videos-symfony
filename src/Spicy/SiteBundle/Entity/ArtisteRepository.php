<?php

namespace Spicy\SiteBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * ArtisteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArtisteRepository extends EntityRepository
{
    public function getAll($page,$nbOccurences)
    {
        $qb = $this->createQueryBuilder('a')
                ->setFirstResult(($page-1)*$nbOccurences)
                ->setMaxResults($nbOccurences)
                ->orderBy('a.libelle')
                ->addOrderBy('a.dateArtiste');
        
        $query=$qb->getQuery();
        return new Paginator($query);
    }
    
    public function getFlux()
    {
        $qb = $this->createQueryBuilder('a')
                //->setFirstResult(0)
                //->setMaxResults($nbOccurences)
                ->orderBy('a.libelle')
                ->addOrderBy('a.dateArtiste');
        
        $query=$qb->getQuery()->getResult();
        return $query;
    }
    
    public function getAlpha($txt)
    {
        $qb = $this->createQueryBuilder('a')
                ->where('a.libelle LIKE :txt')
                ->orderBy('a.libelle')
                ->addOrderBy('a.dateArtiste')
                ->setParameter('txt', $txt.'%');
        
        $query=$qb->getQuery()->getResult();
        return $query;
    }
    
    public function getWithTags($id) 
    {
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.hashtags', 'h')
            ->where('a.id=:id')
            ->setParameter('id', $id);
        
        return $qb->getQuery()->getOneOrNullResult();
    }
    
    public function getByTag($idTag) 
    {
        $qb = $this->createQueryBuilder('a')
            ->join('a.hashtags', 'h')
            ->where('h.id=:id')
            ->setParameter('id', $idTag);
        
        return $qb->getQuery()
            ->getResult();
    }
}
