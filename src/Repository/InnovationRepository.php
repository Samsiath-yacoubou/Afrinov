<?php

namespace App\Repository;

use App\Entity\Innovation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Innovation>
 *
 * @method Innovation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Innovation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Innovation[]    findAll()
 * @method Innovation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InnovationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Innovation::class);
    }

    /**
     * Recherche les innovations avec des filtres optionnels par pays et secteur
     *
     * @param array $pays Tableau des pays à filtrer
     * @param array $secteurs Tableau des secteurs à filtrer
     * @return Innovation[]
     */
    public function findByFilters(array $pays = [], array $secteurs = []): array
{
    $qb = $this->createQueryBuilder('i');
    
    if (!empty($pays)) {
        $qb->andWhere('i.pays IN (:pays)')
           ->setParameter('pays', $pays);
    }
    
    if (!empty($secteurs)) {
        $qb->andWhere('i.secteur IN (:secteurs)')
           ->setParameter('secteurs', $secteurs);
    }
    
    // Debug: Affiche la requête finale
    // dump($qb->getQuery()->getSQL());
    // dump($qb->getQuery()->getParameters());
    
    return $qb->getQuery()->getResult();
}

    /**
     * Compte le nombre total d'innovations avec des filtres optionnels
     *
     * @param array $pays Tableau des pays à filtrer
     * @param array $secteurs Tableau des secteurs à filtrer
     * @return int
     */
    public function countByFilters(array $pays = [], array $secteurs = []): int
    {
        $qb = $this->createQueryBuilder('i')
                    ->select('COUNT(i.id)');

        if (!empty($pays)) {
            $qb->andWhere('i.pays IN (:pays)')
               ->setParameter('pays', $pays);
        }

        if (!empty($secteurs)) {
            $qb->andWhere('i.secteur IN (:secteurs)')
               ->setParameter('secteurs', $secteurs);
        }

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function findLastThree()
   {
    return $this->createQueryBuilder('i')
        ->orderBy('i.id', 'DESC')
        ->setMaxResults(3)
        ->getQuery()
        ->getResult();
    }

}