<?php

namespace Application\Controller;

use Application\Entity\TimeSlot;
use Doctrine\Common\Cache\Psr6\InvalidArgument;
use Doctrine\ORM\EntityManager;
use InvalidArgumentException;

class TimeSlotController extends ApiController
{
  public function __construct(protected EntityManager $entityManager) {}

  public function indexAction()
  {
    $query = $this->params()->fromQuery('available', false);

    $isAvailable = false;
    if (
      ! empty($query)
      && ($query === 'true' || $query === '1')
    ) {
      $isAvailable = true;
    }
    $timeslots = $this->entityManager->getRepository(TimeSlot::class)->findAll($isAvailable);

    return $this->createSuccessResponse($timeslots);
  }

  public function getAction()
  {
    try {
      $id = $this->params()->fromRoute('id', null);

      if (! is_numeric($id)) {
        throw new InvalidArgumentException('Id is numeric type');
      }
      $doctor = $this->entityManager->getRepository(TimeSlot::class)->findById($id);

      return $this->createSuccessResponse($doctor);
    } catch (\Throwable $th) {
      return $this->handleException($th);
    }
  }
}
