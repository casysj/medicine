<?php

declare(strict_types=1);

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

class TimeSlotRepository extends EntityRepository
{
  public function findAll(?bool $isAvailable = null): array
  {
    $qb = $this->createQueryBuilder('ts')
      ->leftJoin('ts.doctor', 'd')
      ->addSelect('d');

    if ($isAvailable !== null) {
      $qb->where('ts.isAvailable = :isAvailable')
        ->setParameter('isAvailable', $isAvailable);
    }
    
    return $qb->getQuery()
      ->getArrayResult();
  }
}
