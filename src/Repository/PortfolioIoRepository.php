<?php

namespace App\Repository;

use App\Entity\PortfolioIo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class PortfolioIoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PortfolioIo::class);
    }
    
    public function findPortfolioIoByUser($user_id)
    {
        return $this->createQueryBuilder('pfio')
            ->join('pfio.portfolio', 'pf')
            ->join('pf.user', 'u')
            ->andWhere('u.id = :uid')
            ->setParameter('uid', $user_id)
            ->orderBy('pfio.creationDate', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    
}
