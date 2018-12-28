<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ViajesRepository")
 */
class Viajes
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
    private $date;

    /**
     * @ORM\Column(type="time")
     */
    private $salida;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $origen;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $destino;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $llegada;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $monto;

    /**
     * @ORM\Column(type="integer")
     */
    private $estado;

    /**
     * @ORM\Column(type="integer")
     */
    private $chofer;


    public function getId(): ?int
    {
        return $this->id;
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

    public function getSalida(): ?\DateTimeInterface
    {
        return $this->salida;
    }

    public function setSalida(\DateTimeInterface $salida): self
    {
        $this->salida = $salida;

        return $this;
    }

    public function getOrigen(): ?string
    {
        return $this->origen;
    }

    public function setOrigen(?string $origen): self
    {
        $this->origen = $origen;

        return $this;
    }

    public function getDestino(): ?string
    {
        return $this->destino;
    }

    public function setDestino(?string $destino): self
    {
        $this->destino = $destino;

        return $this;
    }

    public function getLlegada(): ?string
    {
        return $this->llegada;
    }

    public function setLlegada(?string $llegada): self
    {
        $this->llegada = $llegada;

        return $this;
    }

    public function getMonto(): ?int
    {
        return $this->monto;
    }

    public function setMonto(?int $monto): self
    {
        $this->monto = $monto;

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


    public function getChofer(): ?int
    {
        return $this->chofer;
    }

    public function setChofer(int $chofer): self
    {
        $this->chofer = $chofer;

        return $this;
    }
}
