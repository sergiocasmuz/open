<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrdenDiariaRepository")
 */
class OrdenDiaria
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date", length=255)
     */
    private $fecha;

    /**
     * @ORM\Column(type="integer")
     */
    private $ingresos;

    /**
     * @ORM\Column(type="integer")
     */
    private $salidas;

    /**
     * @ORM\Column(type="integer")
     */
    private $total;

    /**
     * @ORM\Column(type="integer")
     */
    private $estado;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getIngresos(): ?int
    {
        return $this->ingresos;
    }

    public function setIngresos(int $ingresos): self
    {
        $this->ingresos = $ingresos;

        return $this;
    }

    public function getSalidas(): ?int
    {
        return $this->salidas;
    }

    public function setSalidas(int $salidas): self
    {
        $this->salidas = $salidas;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getEstado(): ?int
    {
        return $this->estado;
    }

    public function setEstado(int $estado): self
    {
        $this->estado = $estado;

        return $this;
    }
}
