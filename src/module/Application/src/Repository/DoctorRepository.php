<?php

declare(strict_types=1);

namespace Application\Repository;

use Application\Entity\Doctor;
use Doctrine\ORM\EntityRepository;

class DoctorRepository extends EntityRepository
{
  public function findAll(): array
  {
    return $this->createQueryBuilder('d')
      ->leftJoin('d.specialization', 's')
      ->addSelect('s')
      ->orderBy('d.name')
      ->getQuery()
      ->getArrayResult();
  }

  /**
   * Undocumented function
   *
   * @param integer $id
   * @param string $returnType 'array' or 'object'
   * @return array
   */
  public function findById(int $id, string $returnType = 'array'): array|Doctor|null
  {
    $qb = $this->createQueryBuilder('d')
      ->leftJoin('d.specialization', 's')
      ->addSelect('s')
      ->where('d.id = :id')
      ->setParameter('id', $id)
      ->orderBy('d.name', 'ASC')
      ->getQuery();
      switch ($returnType) {
        case 'array':
          $result = $qb->getArrayResult();
          break;
        case 'object':
          $result = $qb->getOneOrNullResult();
          break;
        default:
          $result = $qb->getArrayResult();
          break;
      }

      return $result;
  }
}
