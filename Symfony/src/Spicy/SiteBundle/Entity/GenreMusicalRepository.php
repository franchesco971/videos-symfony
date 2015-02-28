<?php

namespace Spicy\SiteBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * GenreMusicalRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GenreMusicalRepository extends EntityRepository
{
    public function getAll($page,$nbOccurences)
    {
        $qb = $this->createQueryBuilder('g')
                ->setFirstResult(($page-1)*$nbOccurences)
                ->setMaxResults($nbOccurences)
                ->orderBy('g.libelle')
                ->addOrderBy('g.dateGenreMusical');
        
        $query=$qb->getQuery();
        return new Paginator($query);
    }
    
    public function getAllGenres()
    {
        $qb = $this->createQueryBuilder('g')
                ->orderBy('g.libelle');
        
        $query=$qb->getQuery()->getResult();
        return $query;
    }
    
    public function getGenresByArtiste($idArtiste) 
    {
        //$em=  $$this->getDoctrine()->getEntityManager();
        $q= $this->_em->createQuery(
            'SELECT g
            FROM SpicySiteBundle:GenreMusical g JOIN g.SpicySiteBundle:Video v'
        );
        
        $query=$q->getResult();
        return $query;
    } 
}
