<?php

namespace App\Entity;

use App\Repository\WorkClothingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorkClothingRepository::class)]
class WorkClothing
{
    #[ORM\Id]
    #[ORM\Column(length: 6)]
    private ?string $id = null;

    #[ORM\Column(length: 100)]
    private ?string $type = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $wearTime = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\OneToOne(mappedBy: 'workClothing', cascade: ['persist', 'remove'])]
    private ?Receiving $receiving = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getWearTime(): ?int
    {
        return $this->wearTime;
    }

    public function setWearTime(int $wearTime): self
    {
        $this->wearTime = $wearTime;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getReceiving(): ?Receiving
    {
        return $this->receiving;
    }

    public function setReceiving(Receiving $receiving): self
    {
        // set the owning side of the relation if necessary
        if ($receiving->getWorkClothing() !== $this) {
            $receiving->setWorkClothing($this);
        }

        $this->receiving = $receiving;

        return $this;
    }
}
