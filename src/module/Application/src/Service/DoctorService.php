<?php

namespace Application\Service;

use Application\Entity\Doctor;
use Application\Entity\Specialization;
use Container5smhAPa\getRedirectControllerService;
use Doctrine\ORM\EntityManager;

class DoctorService
{
  public function __construct(protected EntityManager $entityManager) {
  }

  public function getAllDoctors(): array
  {
    return $this->entityManager->getRepository(Doctor::class)->findAll();
  }

  public function getDoctor(string $id, string $returnType = 'array'): Doctor|array|null
  {
    $results = $this->entityManager->getRepository(Doctor::class)->findById($id, $returnType);
    if (is_object($results)) {
      return $results;
    } elseif (empty($results)) {
      return null;
    }
    return $results[0];
  }

  public function searchDoctorWithName(string $name): array
  {
    $lowName = strtolower($name);
    $queryBuilder = $this->entityManager->createQueryBuilder();
    $queryBuilder->select('d, s')
    ->from(Doctor::class, 'd')
    ->leftJoin('d.specialization', 's')
    ->where('LOWER(d.name) LIKE :name')
    ->setParameter('name', '%'.$name.'%');

    return $queryBuilder->getQuery()->getArrayResult();
  }
}