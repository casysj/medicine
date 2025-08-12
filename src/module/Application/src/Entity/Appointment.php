<?php

declare(strict_types=1);

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: 'Application\Repository\AppointmentRepository')]
#[ORM\Table(name: 'appointments')]
#[ORM\Index(name: 'idx_doctor_date', columns: ['doctor_id', 'dateTime'])]
#[ORM\Index(name: 'idx_patient_email', columns: ['patientEmail'])]
#[ORM\Index(name: 'idx_status', columns: ['status'])]
class Appointment
{
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_COMPLETED = 'completed';

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Doctor::class, inversedBy: 'appointments')]
    #[ORM\JoinColumn(name: 'doctor_id', referencedColumnName: 'id', nullable: false)]
    private Doctor $doctor;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $patientName;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $patientEmail;

    #[ORM\Column(type: 'datetime', nullable: false)]
    private \DateTime $dateTime;

    #[ORM\Column(type: 'string', length: 20, options: ['default' => self::STATUS_CONFIRMED])]
    private string $status = self::STATUS_CONFIRMED;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDoctor(): Doctor
    {
        return $this->doctor;
    }

    public function setDoctor(Doctor $doctor): self
    {
        $this->doctor = $doctor;
        $this->updatedAt = new \DateTimeImmutable();
        
        return $this;
    }

    public function getPatientName(): string
    {
        return $this->patientName;
    }

    public function setPatientName(string $patientName): self
    {
        $this->patientName = $patientName;
        $this->updatedAt = new \DateTimeImmutable();
        
        return $this;
    }

    public function getPatientEmail(): string
    {
        return $this->patientEmail;
    }

    public function setPatientEmail(string $patientEmail): self
    {
        $this->patientEmail = $patientEmail;
        $this->updatedAt = new \DateTimeImmutable();
        
        return $this;
    }

    public function getDateTime(): \DateTime
    {
        return $this->dateTime;
    }

    public function setDateTime(\DateTime $dateTime): self
    {
        $this->dateTime = $dateTime;
        $this->updatedAt = new \DateTimeImmutable();
        
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        if (!in_array($status, self::getValidStatuses())) {
            throw new \InvalidArgumentException(sprintf('Invalid status: %s', $status));
        }
        
        $this->status = $status;
        $this->updatedAt = new \DateTimeImmutable();
        
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public static function getValidStatuses(): array
    {
        return [
            self::STATUS_CONFIRMED,
            self::STATUS_CANCELLED,
            self::STATUS_COMPLETED,
        ];
    }
}