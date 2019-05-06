<?php

namespace App\Repository;

use App\Entity\FinInfo;
use App\Entity\Portfolio;
use App\Entity\PortfolioLine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
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
    
    /** findActiveLines : retrieve real lines of portfolio (with qty > 0)
     *                    (other lines are new lines in a transaction before confirmation)
     * @param $pf       Portfolio object
     * @return array
     */
    public function findActiveLines(Portfolio $pf): array
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
    
    /** findIoLines : retrieve lines concerned by a transaction
     *                - lines with pending transaction (ioValue != 0.0)
     *                - lines confirmed (thus with ioValue = 0.0 ) but whole transaction not confirmed
     * @param $pf       Portfolio object
     * @return array
     */
    public function findIoLines(Portfolio $pf): array
    {
        
        // Create query
        $qb = $this->createQueryBuilder('pfl');
        $qb = $qb->select('pfl')
            ->andWhere($qb->expr()->eq('pfl.portfolio', ':pf'))
            ->andWhere($qb->expr()->orX(
                $qb->expr()->neq('pfl.ioValue', ':value'),
                $qb->expr()->eq('pfl.ioConfirm', ':confirm')
                )
            )
            ->orderBy('pfl.id', 'ASC');
        
        // Give parameters values
        $qb->setParameter(':pf', $pf);
        $qb->setParameter(':value', 0.0);
        $qb->setParameter(':confirm', true);
        
        return $qb->getQuery()->getResult();
    }
    
    /** totalAmount : compute the total amount of a portfolio
     * @param $pf       Portfolio object
     * @return float
     * @throws NonUniqueResultException
     */
    public function totalAmount(Portfolio $pf): float
    {
        // Create query
        $qb = $this->createQueryBuilder('pfl');
        $qb = $qb->select('SUM(pfl.qty*pfl.lvalue) as totalAmount')
            ->andWhere($qb->expr()->eq('pfl.portfolio', ':pf'));
        
        // Give parameters values
        $qb->setParameter(':pf', $pf);
        $result = $qb->getQuery()->getSingleScalarResult();
        
        return $result ? $result : 0.0 ;
    }

    /** ioTotalAmount : compute the total amount of a transaction
     * @param $pf       Portfolio object
     * @return float
     * @throws NonUniqueResultException
     */
    public function ioTotalAmount(Portfolio $pf): float
    {
        // Create query
        $qb = $this->createQueryBuilder('pfl');
        $qb = $qb->select('SUM(pfl.ioValue) as ioTotalAmount')
            ->andWhere($qb->expr()->eq('pfl.portfolio', ':pf'));
        
        // Give parameters values
        $qb->setParameter(':pf', $pf);
        $result = $qb->getQuery()->getSingleScalarResult();
        
        return $result ? $result : 0.0 ;
    }
    
}
