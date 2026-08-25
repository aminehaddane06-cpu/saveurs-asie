<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $name = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'decimal', precision: 8, scale: 2)]
    private ?string $priceHt = null;

    #[ORM\Column(type: 'decimal', precision: 4, scale: 2)]
    private ?string $vatRate = '10.00';

    #[ORM\Column]
    private ?bool $isActive = true;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageUrl = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): static { $this->description = $description; return $this; }
    public function getPriceHt(): ?string { return $this->priceHt; }
    public function setPriceHt(string $priceHt): static { $this->priceHt = $priceHt; return $this; }
    public function getVatRate(): ?string { return $this->vatRate; }
    public function setVatRate(string $vatRate): static { $this->vatRate = $vatRate; return $this; }
    public function isIsActive(): ?bool { return $this->isActive; }
    public function setIsActive(bool $isActive): static { $this->isActive = $isActive; return $this; }
    public function getImageUrl(): ?string { return $this->imageUrl; }
    public function setImageUrl(?string $imageUrl): static { $this->imageUrl = $imageUrl; return $this; }
    public function getCategory(): ?Category { return $this->category; }
    public function setCategory(?Category $category): static { $this->category = $category; return $this; }

    public function getPriceTtc(): float
    {
        return round((float)$this->priceHt * (1 + (float)$this->vatRate / 100), 2);
    }
}
