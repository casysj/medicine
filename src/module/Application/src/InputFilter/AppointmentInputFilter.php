<?php

namespace Application\InputFilter;

use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\Input;
use Laminas\Validator;
use Laminas\Filter;

class AppointmentInputFilter extends InputFilter
{
  public function __construct()
  {
    // doctorId validation
    $doctorId = new Input('doctorId');
    $doctorId->setRequired(true)
      ->getFilterChain()
      ->attach(new Filter\StringTrim())
      ->attach(new Filter\StripTags());
    $doctorId->getValidatorChain()
      ->attach(new Validator\NotEmpty())
      ->attach(new Validator\Digits());

    // patientName validation
    $patientName = new Input('patientName');
    $patientName->setRequired(true)
      ->getFilterChain()
      ->attach(new Filter\StringTrim())
      ->attach(new Filter\StripTags());
    $patientName->getValidatorChain()
      ->attach(new Validator\NotEmpty())
      ->attach(new Validator\StringLength(['min' => 2, 'max' => 100]));

    // patientEmail validation
    $patientEmail = new Input('patientEmail');
    $patientEmail->setRequired(true)
      ->getFilterChain()
      ->attach(new Filter\StringTrim())
      ->attach(new Filter\StringToLower());
    $patientEmail->getValidatorChain()
      ->attach(new Validator\NotEmpty())
      ->attach(new Validator\EmailAddress([
        'useDomainCheck' => false
      ]));

    // dateTime validation
    $dateTime = new Input('dateTime');
    $dateTime->setRequired(true)
      ->getValidatorChain()
      ->attach(new Validator\NotEmpty())
      ->attach(new Validator\Date(['format' => 'd-m-Y H:i']));

    $this->add($patientName);
    $this->add($patientEmail);
    $this->add($dateTime);
    $this->add($doctorId);
  }
}
