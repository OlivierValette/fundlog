<?php

namespace App\Repository;

use App\Entity\Alert;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class AlertRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Alert::class);
    }
    
    public function findAlertsByUser($user_id)
    {
        return $this->createQueryBuilder('a')
            ->join('a.portfolio', 'pf')
            ->join('pf.user', 'u')
            ->andWhere('u.id = :uid')
            ->setParameter('uid', $user_id)
            ->orderBy('pf.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

}
