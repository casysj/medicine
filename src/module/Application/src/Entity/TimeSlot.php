<?php

declare(strict_types=1);

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: 'Application\Repository\TimeSlotRepository')]
#[ORM\Table(name: 'time_slots')]
#[ORM\Index(name: 'idx_doctor_available', columns: ['doctor_id', 'isAvailable'])]
#[ORM\Index(name: 'idx_start_time', columns: ['startTime'])]
class TimeSlot
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Doctor::class, inversedBy: 'timeSlots')]
    #[ORM\JoinColumn(name: 'doctor_id', referencedColumnName: 'id', nullable: false)]
    private Doctor $doctor;

    #[ORM\Column(type: 'datetime', nullable: false)]
    private \DateTime $startTime;

    #[ORM\Column(type: 'datetime', nullable: false)]
    private \DateTime $endTime;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    private bool $isAvailable = true;

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

    public function getStartTime(): \DateTime
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTime $startTime): self
    {
        $this->startTime = $startTime;
        $this->updatedAt = new \DateTimeImmutable();
        
        return $this;
    }

    public function getEndTime(): \DateTime
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTime $endTime): self
    {
        $this->endTime = $endTime;
        $this->updatedAt = new \DateTimeImmutable();
        
        return $this;
    }

    public function isAvailable(): bool
    {
        return $this->isAvailable;
    }

    public function setIsAvailable(bool $isAvailable): self
    {
        $this->isAvailable = $isAvailable;
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
}