<?php

namespace Application\Service;

use Application\Entity\Doctor;
use Application\Entity\TimeSlot;
use Doctrine\ORM\EntityManager;

class TimeSlotService
{
  public function __construct(protected EntityManager $entityManager) {
  }

  public function getAllTimeSlots(): array
  {
    return $this->entityManager->getRepository(TimeSlot::class)->findAll();
  }
}