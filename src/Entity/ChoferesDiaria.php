<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChoferesDiariaRepository")
 */
class ChoferesDiaria
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $ingreso;

    /**
     * @ORM\Column(type="integer")
     */
    private $choferId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIngreso(): ?\DateTimeInterface
    {
        return $this->ingreso;
    }

    public function setIngreso(\DateTimeInterface $ingreso): self
    {
        $this->ingreso = $ingreso;

        return $this;
    }

    public function getChoferId(): ?int
    {
        return $this->choferId;
    }

    public function setChoferId(int $choferId): self
    {
        $this->choferId = $choferId;

        return $this;
    }
}
