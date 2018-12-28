<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MovDiariaRepository")
 */
class MovDiaria
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
    private $egreso;

    /**
     * @ORM\Column(type="integer")
     */
    private $suma;

    /**
     * @ORM\Column(type="integer")
     */
    private $deuda;

    /**
     * @ORM\Column(type="integer")
     */
    private $pago;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEgreso(): ?\DateTimeInterface
    {
        return $this->egreso;
    }

    public function setEgreso(\DateTimeInterface $egreso): self
    {
        $this->egreso = $egreso;

        return $this;
    }

    public function getSuma(): ?int
    {
        return $this->suma;
    }

    public function setSuma(int $suma): self
    {
        $this->suma = $suma;

        return $this;
    }

    public function getDeuda(): ?int
    {
        return $this->deuda;
    }

    public function setDeuda(int $deuda): self
    {
        $this->deuda = $deuda;

        return $this;
    }

    public function getPago(): ?int
    {
        return $this->pago;
    }

    public function setPago(int $pago): self
    {
        $this->pago = $pago;

        return $this;
    }
}
