<?php

namespace Application\Service;

use Application\Entity\Appointment;
use Application\Entity\TimeSlot;
use DateTime;
use Doctrine\ORM\EntityManager;
use Exception;

class AppointmentService
{
  public function __construct(protected EntityManager $entityManager, protected DoctorService $doctorService) {}

  public function getAllAppointments(): array
  {
    return $this->entityManager->getRepository(Appointment::class)->findAll();
  }

  public function getAppointment(string $id): array
  {
    $results = $this->entityManager->getRepository(Appointment::class)->findById($id);
    if (empty($results)) {
      return [];
    }
    return $results[0];
  }

  public function createAppointment(array $data, bool $doFlush = false): Appointment
  {
    $appointment = new Appointment();
    $appointment->setPatientName($data['patientName']->getValue());
    $appointment->setPatientEmail($data['patientEmail']->getValue());
    $doctor = $this->doctorService->getDoctor($data['doctorId']->getValue(), 'object');
    if ($doctor === null) {
      throw new Exception('Doctor not found');
    }
    $appointment->setDoctor($doctor);
    $dateTime = new DateTime($data['dateTime']->getValue());
    /** @var TimeSlot */
    $timeSlot = $this->entityManager->getRepository(TimeSlot::class)->findOneBy(['doctor' => $doctor, 'startTime' => $dateTime, 'isAvailable' => 1]);
    if ($timeSlot === null) {
      throw new Exception('Datetime not found');
    }
    $appointment->setDateTime($dateTime);
    $timeSlot->setIsAvailable(0);
    $appointment->setStatus(Appointment::STATUS_CONFIRMED);

    $this->entityManager->persist($appointment);
    if ($doFlush) {
      $this->entityManager->flush();
    }
    return $appointment;
  }

  public function cancelAppointment(Appointment $appointment): Appointment
  {
    if ($appointment->getStatus() !== Appointment::STATUS_CONFIRMED) {
      throw new Exception('Appointment is not able to be cancelled');
    }
    $appointment->setStatus(Appointment::STATUS_CANCELLED);
    $doctor = $appointment->getDoctor();

    /** @var TimeSlot $timeSlot */
    $timeSlot = $this->entityManager->getRepository(TimeSlot::class)->findOneBy(['doctor' => $doctor, 'startTime' => $appointment->getDateTime()]);
    if (!isset($timeSlot)) {
      throw new Exception('Time slot not found');
    }
    $timeSlot->setIsAvailable(1);

    $this->entityManager->flush();
    return $appointment;
  }
}
