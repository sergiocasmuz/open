<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CuentasRepository")
 */
class Cuentas
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $nroCuenta;

    /**
     * @ORM\GeneratedValue()
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    /**
     * @ORM\Column(type="integer")
     */
    private $monto;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $oDiaria;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $detalle;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $idViaje;




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNroCuenta(): ?int
    {
        return $this->nroCuenta;
    }

    public function setNroCuenta(int $nroCuenta): self
    {
        $this->nroCuenta = $nroCuenta;

        return $this;
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


    public function getMonto(): ?int
    {
        return $this->monto;
    }

    public function setMonto(int $monto): self
    {
        $this->monto = $monto;

        return $this;
    }

    public function getODiaria(): ?int
    {
        return $this->oDiaria;
    }

    public function setODiaria(?int $oDiaria): self
    {
        $this->oDiaria = $oDiaria;

        return $this;
    }


    public function getDetalle(): ?string
    {
        return $this->detalle;
    }

    public function setDetalle(?string $detalle): self
    {
        $this->detalle = $detalle;

        return $this;
    }


    public function getIdViaje(): ?int
    {
        return $this->idViaje;
    }

    public function setIdViaje(int $idViaje): self
    {
        $this->idViaje = $idViaje;
        return $this;
    }


}
