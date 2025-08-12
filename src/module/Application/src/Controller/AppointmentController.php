<?php

namespace Application\Controller;

use Application\Entity\Appointment;
use Application\InputFilter\AppointmentInputFilter;
use Application\Service\AppointmentService;
use Application\Service\NotificationService;
use Doctrine\ORM\EntityManager;
use Exception;
use InvalidArgumentException;
use JsonException;
use Laminas\Http\Request;

class AppointmentController extends ApiController
{
  public function __construct(
    protected EntityManager $entityManager,
    protected AppointmentService $appointmentService,
    protected NotificationService $notificationService,
  ) {}

  /**
   * GET, retrieve all appointments
   *
   * @return void
   */
  public function indexAction()
  {
    /** @var Request $request */
    $request = $this->getRequest();
    if (! $request->isGet()) {
      $method = $request->getMethod();
      return $this->methodNotAllowed(sprintf('%s is not allowed', $method), ['method now allowed.']);
    }
    try {
      $reuslts = $this->appointmentService->getAllAppointments();
      return $this->createSuccessResponse($reuslts);
    } catch (\Throwable $th) {
      return $this->handleException($th);
    }
  }

  /**
   * GET, retrieve a appointment
   *
   * @return void
   */
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
        throw new InvalidArgumentException('Id is not valid');
      }

      $result = $this->appointmentService->getAppointment($id);

      return $this->createSuccessResponse($result);
    } catch (\Throwable $th) {
      return $this->handleException($th);
    }
  }

  /**
   * POST, create a appointment
   *
   * @return Response
   */
  public function createAction()
  {
    /** @var Request $request */
    $request = $this->getRequest();
    if (! $request->isPost()) {
      $method = $request->getMethod();
      return $this->methodNotAllowed(sprintf('%s is not allowed', $method), ['method now allowed.']);
    }
    try {
      $jsonData = json_decode($request->getContent(), true, flags: JSON_THROW_ON_ERROR);

      $inputFilter = new AppointmentInputFilter();
      $inputFilter->setData($jsonData);

      if (!$inputFilter->isValid()) {
        return $this->createErrorResponse('Validation failed', errors: $inputFilter->getMessages());
      }
      $validData = $inputFilter->getValidInput();
      // create appointment
      /** @var Appointment */
      $appointment = $this->appointmentService->createAppointment($validData, true);
      // notification mail
      $this->notificationService->appointmentConfirmMail($appointment);
      
      $data = [
        'id' => $appointment->getId(),
        'patientName' => $appointment->getPatientName(),
        'patientEmail' => $appointment->getPatientEmail(),
        'dateTime' => $appointment->getDateTime(),
        'doctorId' => $appointment->getDoctor()->getId()
      ];
      return $this->createSuccessResponse($data);
    } catch (\Throwable $th) {
      return $this->handleException($th);
    }
  }

  public function cancelAction()
  {
    /** @var Request $request */
    $request = $this->getRequest();
    if (! $request->isPatch()) {
      $method = $request->getMethod();
      return $this->methodNotAllowed(sprintf('%s is not allowed', $method), ['method now allowed.']);
    }

    try {
      $appointmentId = $this->params()->fromRoute('id');
      if (! is_numeric($appointmentId)) {
        throw new InvalidArgumentException('Id is not valid');
      }
      // set appointment status to cancelled and the time slot available
      /** @var Appointment $appointment  */
      $appointment = $this->entityManager->getRepository(Appointment::class)->find($appointmentId);
      if (empty($appointment)) {
        throw new Exception('Appointment not found');
      }

      $this->appointmentService->cancelAppointment($appointment);
      $responseData = [
        'id' => $appointment->getId(),
        'patientName' => $appointment->getPatientName(),
        'patientEmail' => $appointment->getPatientEmail(),
        'dateTime' => $appointment->getDateTime(),
        'doctor' => $appointment->getDoctor()->getName(),
        'status' => $appointment->getStatus(),
      ];

      return $this->createSuccessResponse($responseData);
    } catch (\Throwable $th) {
      return $this->handleException($th);
    }
  }
}
