<?php

namespace App\Entity;

use App\Repository\DepartmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepartmentRepository::class)]
class Department
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $departmentId = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $chiefName = null;

    #[ORM\OneToMany(mappedBy: 'department', targetEntity: Worker::class)]
    private Collection $workers;

    public function __construct()
    {
        $this->workers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepartmentId(): ?int
    {
        return $this->departmentId;
    }

    public function setDepartmentId(int $departmentId): self
    {
        $this->departmentId = $departmentId;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getChiefName(): ?string
    {
        return $this->chiefName;
    }

    public function setChiefName(string $chiefName): self
    {
        $this->chiefName = $chiefName;

        return $this;
    }

    /**
     * @return Collection<int, Worker>
     */
    public function getWorkers(): Collection
    {
        return $this->workers;
    }

    public function addWorker(Worker $worker): self
    {
        if (!$this->workers->contains($worker)) {
            $this->workers->add($worker);
            $worker->setDepartment($this);
        }

        return $this;
    }

    public function removeWorker(Worker $worker): self
    {
        if ($this->workers->removeElement($worker)) {
            // set the owning side to null (unless already changed)
            if ($worker->getDepartment() === $this) {
                $worker->setDepartment(null);
            }
        }

        return $this;
    }
}
