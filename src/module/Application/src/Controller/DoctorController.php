<?php

namespace Application\Controller;

use Application\Entity\Doctor;
use Application\Service\DoctorService;
use Doctrine\ORM\EntityManager;
use InvalidArgumentException;

class DoctorController extends ApiController
{
  public function __construct(protected EntityManager $entityManager, protected DoctorService $doctorService)
  {
    
  }

  public function indexAction()
  {
    $doctors = $this->entityManager->getRepository(Doctor::class)->findAll();
    
    return $this->createSuccessResponse($doctors);
  }

  public function getAction()
  {
    /** @var Request $request */
    $request = $this->getRequest();

    if (! $request->isGet()) {
      $method = $request->getMethod();
      return $this->methodNotAllowed(sprintf('%s is not allowed', $method), ['method now allowed.']);
    }
    try {
      $id = $this->params()->fromRoute('id', null);

      if (! is_numeric($id)) {
        throw new InvalidArgumentException('Id is not numeric type');
      }
      $doctor = $this->entityManager->getRepository(Doctor::class)->findById($id);    

      return $this->createSuccessResponse($doctor);
    } catch (\Throwable $th) {
      return $this->handleException($th);
    }
  }

  public function searchAction()
  {
    /** @var Request $request */
    $request = $this->getRequest();

    if (! $request->isGet()) {
      $method = $request->getMethod();
      return $this->methodNotAllowed(sprintf('%s is not allowed', $method), ['method now allowed.']);
    }

    try {
      $searchTerm = $this->params()->fromQuery('s', '');

      $doctors = $this->doctorService->searchDoctorWithName($searchTerm);

      return $this->createSuccessResponse($doctors);
    } catch (\Throwable $th) {
      return $this->handleException($th);
    }
  }
}