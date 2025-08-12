<?php

declare(strict_types=1);

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

class AppointmentRepository extends EntityRepository
{
  public function findAll(): array
  {
    return $this->createQueryBuilder('a')
      ->leftJoin('a.doctor', 'd')
      ->addSelect('d')
      ->orderBy('a.id', 'DESC')
      ->getQuery()
      ->getArrayResult();
  }

  public function findById(int $id): array
  {
    return $this->createQueryBuilder('a')
      ->leftJoin('a.doctor', 'd')
      ->addSelect('d')
      ->where('a.id = :id')
      ->setParameter('id', $id)
      ->getQuery()
      ->getArrayResult();
  }
}
