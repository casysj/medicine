<?php

namespace Application\Service;


use Application\Entity\Appointment;
use Laminas\Mail\Message;
use Laminas\Mail\Transport\Sendmail as SendmailTransport;

class NotificationService
{
  private $adminMail = 'admin@arbeitsmedizin.de';
  public function __construct() {
  }

  public function sendEmail($to, $from, $subject, $body): void
  {
    // a mailer simulation
    $message = new Message();
    $message->getHeaders()->addHeaderLine('Content-Type', 'text/html; charset=iso-8859-1');
    $message->addTo($to);
    $message->addFrom($from);
    $message->setSubject($subject);
    $message->setBody($body);

    $transport = new SendmailTransport();
    $transport->send($message);
  }

  public function appointmentConfirmMail(Appointment $appointment): void
  {
    $to = $appointment->getPatientEmail();
    $from = $this->adminMail;
    $subject = sprintf('Appointment confirmed on %s', $appointment->getDateTime());
    $message = sprintf('Dear %s, a appointment has been confirmed <br>', $appointment->getPatientName());
    $message .= sprintf('Doctor: %s <br>', $appointment->getDoctor()->getName());
    $message .= sprintf('Date: %s <br>', $appointment->getDateTime());
    $message .= sprintf('Best regards your telemedicine.');

    $this->sendEmail($to, $from, $subject, $message);
  }
}