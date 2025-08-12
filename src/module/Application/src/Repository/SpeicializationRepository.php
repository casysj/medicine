<?php

declare(strict_types=1);

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

class SpeicializationRepository extends EntityRepository
{

  public function findAll(): array
  {
    return $this->createQueryBuilder('s')
      ->orderBy('s.name', 'ASC')
      ->getQuery()
      ->getResult();
  }

  public function findById(int $id)
  {
    return $this->find($id);
  }
}
