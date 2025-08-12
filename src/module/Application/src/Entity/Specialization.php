<?php

declare(strict_types=1);

namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: 'Application\Repository\SpecializationRepository')]
#[ORM\Table(name: 'specializations')]
class Specialization
{
  #[ORM\Id]
  #[ORM\GeneratedValue(strategy: 'IDENTITY')]
  #[ORM\Column(type: 'integer')]
  private int $id;

  #[ORM\Column(type: 'string', length: 255, nullable: false)]
  private string $name;

  #[ORM\OneToMany(mappedBy: 'specialization', targetEntity: Doctor::class)]
  private Collection $doctors;

  #[ORM\Column(type: 'datetime_immutable')]
  private \DateTimeImmutable $createdAt;

  #[ORM\Column(type: 'datetime_immutable', nullable: true)]
  private ?\DateTimeImmutable $updatedAt = null;

  public function __construct()
  {
    $this->doctors = new ArrayCollection();
    $this->createdAt = new \DateTimeImmutable();
  }

  public function getId(): int
  {
    return $this->id;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function setName(string $name): self
  {
    $this->name = $name;
    $this->updatedAt = new \DateTimeImmutable();

    return $this;
  }

  /**
   * @return Collection<int, Doctor>
   */
  public function getDoctors(): Collection
  {
    return $this->doctors;
  }

  public function addDoctor(Doctor $doctor): self
  {
    if (!$this->doctors->contains($doctor)) {
      $this->doctors->add($doctor);
      $doctor->setSpecialization($this);
    }

    return $this;
  }

  public function removeDoctor(Doctor $doctor): self
  {
    if ($this->doctors->removeElement($doctor)) {
      // set the owning side to null (unless already changed)
      if ($doctor->getSpecialization() === $this) {
        $doctor->setSpecialization(null);
      }
    }

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

  public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
  {
    $this->updatedAt = $updatedAt;

    return $this;
  }
}
