<?php

namespace App\Entity;

use App\Repository\WorkerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorkerRepository::class)]
class Worker
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'workers')]
    #[ORM\JoinColumn(nullable: false, onDelete:'CASCADE')]
    private ?Post $post = null;

    #[ORM\ManyToOne(inversedBy: 'workers')]
    #[ORM\JoinColumn(nullable: false, onDelete:'CASCADE')]
    private ?Department $department = null;

    #[ORM\OneToMany(mappedBy: 'worker', targetEntity: Receiving::class)]
    private Collection $receivings;

    public function __construct()
    {
        $this->receivings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): self
    {
        $this->department = $department;

        return $this;
    }

    /**
     * @return Collection<int, Receiving>
     */
    public function getReceivings(): Collection
    {
        return $this->receivings;
    }

    public function addReceiving(Receiving $receiving): self
    {
        if (!$this->receivings->contains($receiving)) {
            $this->receivings->add($receiving);
            $receiving->setWorker($this);
        }

        return $this;
    }

    public function removeReceiving(Receiving $receiving): self
    {
        if ($this->receivings->removeElement($receiving)) {
            // set the owning side to null (unless already changed)
            if ($receiving->getWorker() === $this) {
                $receiving->setWorker(null);
            }
        }

        return $this;
    }
}
