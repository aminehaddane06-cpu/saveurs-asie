<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class StockItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $name = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?string $currentQuantity = '0.00';

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?string $minAlertThreshold = '5.00';

    #[ORM\Column(length: 20)]
    private ?string $unit = 'kg';

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }
    public function getCurrentQuantity(): ?string { return $this->currentQuantity; }
    public function setCurrentQuantity(string $currentQuantity): static { $this->currentQuantity = $currentQuantity; return $this; }
    public function getMinAlertThreshold(): ?string { return $this->minAlertThreshold; }
    public function setMinAlertThreshold(string $minAlertThreshold): static { $this->minAlertThreshold = $minAlertThreshold; return $this; }
    public function getUnit(): ?string { return $this->unit; }
    public function setUnit(string $unit): static { $this->unit = $unit; return $this; }

    public function getStatus(): string
    {
        if ((float)$this->currentQuantity <= 0) {
            return 'Rupture';
        }
        if ((float)$this->currentQuantity <= (float)$this->minAlertThreshold) {
            return 'Stock faible';
        }
        return 'En stock';
    }
}