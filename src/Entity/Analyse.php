<?php

// src/Entity/Analyse.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: 'App\Repository\AnalyseRepository')]
class Analyse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'json')]
    private $data = [];

    // Getters/Setters
    public function getId(): ?int { return $this->id; }
    public function getData(): array { return $this->data; }
    public function setData(array $data): self { $this->data = $data; return $this; }
}
