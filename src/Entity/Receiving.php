<?php

namespace App\Entity;

use App\Repository\ReceivingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReceivingRepository::class)]
class Receiving
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'receivings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Worker $worker = null;

    #[ORM\OneToOne(inversedBy: 'receiving', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?WorkClothing $workClothing = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $signature = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWorker(): ?Worker
    {
        return $this->worker;
    }

    public function setWorker(?Worker $worker): self
    {
        $this->worker = $worker;

        return $this;
    }

    public function getWorkClothing(): ?WorkClothing
    {
        return $this->workClothing;
    }

    public function setWorkClothing(WorkClothing $workClothing): self
    {
        $this->workClothing = $workClothing;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getSignature(): ?string
    {
        return $this->signature;
    }

    public function setSignature(string $signature): self
    {
        $this->signature = $signature;

        return $this;
    }
}
