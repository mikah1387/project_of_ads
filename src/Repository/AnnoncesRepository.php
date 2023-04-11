<?php

namespace App\Repository;

use App\Entity\Annonces;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Annonces>
 *
 * @method Annonces|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonces|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonces[]    findAll()
 * @method Annonces[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnoncesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonces::class);
    }

    public function save(Annonces $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Annonces $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function search($mots =null, $categorie = null){


         $query = $this->createQueryBuilder('a');  
                       $query-> Where('a.is_active = true');  
                   if($mots != null){
                    $query->andWhere('MATCH_AGAINST(a.title,a.content) AGAINST (:mots boolean)>0')
                           ->setParameter('mots', $mots);
                   }
                   if($categorie !=null){
                    $query->leftJoin('a.categories','c')
                          ->andwhere('c.id = :id') 
                          ->setParameter('id', $categorie); 

                   }
                  
                   return $query ->getQuery()->getResult();
                          
        
    }

    // public function selectInterval ($from, $to,$cat = null){

     
    //     $query =  $this->createQueryBuilder('a')
               
    //                ->Where('a.created_at > :from') 
    //                ->andWhere('a.created_at < :to ')
    //                ->setParameter('from',$from)
    //                ->setParameter('to',$to)
                  
    //                ->orderBy('a.created_at', 'ASC');
    //                if($cat != null){

    //                 $query->join('a.categories','c')
    //                       ->andwhere('c.id = :cat')
    //                       ->setParameter('cat',$cat);
    //                }
                  
    //               return  $query->getQuery()->getResult();

    // }

    public function getPaginatedAnnonces($page, $limit,$filters =null){
 
          $query = $this->createQueryBuilder('a')
                        ->where('a.is_active = true'); 
           if($filters != null){
                $query->leftJoin('a.categories','c')
                      ->andwhere('c.parent IN (:cats)') 
                      ->setParameter(':cats', array_values($filters)); 
             
           }             
                  $query->orderBy('a.title','asc')
                        ->setFirstResult(($page * $limit)- $limit)
                        ->setMaxResults($limit);

                        return $query->getQuery()->getResult();

    }

    public function countPages($filters){

        $query = $this->createQueryBuilder('a')
                      ->select('count(a)')
                      ->where('a.is_active = 1 ');
                      if($filters != null){
                        $query->leftJoin('a.categories','c')
                              ->andwhere('c.parent IN (:cats)') 
                              ->setParameter(':cats', array_values($filters)); 
                     
                   }  
                     

                      return $query->getQuery()->getSingleScalarResult();

  }

  

//    /**
//     * @return Annonces[] Returns an array of Annonces objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Annonces
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
