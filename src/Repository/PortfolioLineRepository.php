<?php

namespace App\Repository;

use App\Entity\PortfolioLine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PortfolioLine|null find($id, $lockMode = null, $lockVersion = null)
 * @method PortfolioLine|null findOneBy(array $criteria, array $orderBy = null)
 * @method PortfolioLine[]    findAll()
 * @method PortfolioLine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PortfolioLineRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PortfolioLine::class);
    }
    
    public function findActiveLines($pf): array
    {
    
        // Create query
        $qb = $this->createQueryBuilder('pfl');
        $qb = $qb->select('pfl')
            ->andWhere($qb->expr()->eq('pfl.portfolio', ':pf'))
            ->andWhere($qb->expr()->neq('pfl.qty', ':val'))
            ->orderBy('pfl.id', 'ASC');
    
        // Give parameters values
        $qb->setParameter(':pf', $pf);
        $qb->setParameter(':val', 0.0);
    
        return $qb->getQuery()->getResult();
    }
    
    public function findIoLines($pf): array
    {
        
        // Create query
        $qb = $this->createQueryBuilder('pfl');
        $qb = $qb->select('pfl')
            ->andWhere($qb->expr()->eq('pfl.portfolio', ':pf'))
            ->andWhere($qb->expr()->neq('pfl.ioValue', ':val'))
            ->orderBy('pfl.id', 'ASC');
        
        // Give parameters values
        $qb->setParameter(':pf', $pf);
        $qb->setParameter(':val', 0.0);
        
        return $qb->getQuery()->getResult();
    }
    
    
    public function ioTotalAmount($pf): float
    {
        // Create query
        $qb = $this->createQueryBuilder('pfl');
        $qb = $qb->select('SUM(pfl.ioValue) as ioTotalAmount')
            ->andWhere($qb->expr()->eq('pfl.portfolio', ':pf'));
        
        // Give parameters values
        $qb->setParameter(':pf', $pf);
        
        return $qb->getQuery()->getSingleScalarResult();
    }
    
}
