<?php

namespace App\Entity;

use App\Repository\AbonoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AbonoRepository::class)]
#[ORM\Table(name: "abono", schema: "safagym")]
class Abono
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $codigo = null;

    #[ORM\Column(name: "fecha_caducidad",type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fechaCaducidad = null;

    #[ORM\ManyToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(name: "id_tipo_abono" , nullable: false)]
    private ?TipoAbono $tipoAbono = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: "id_cliente", nullable: false)]
    private ?Cliente $cliente = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(string $codigo): static
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getFechaCaducidad(): ?\DateTimeInterface
    {
        return $this->fechaCaducidad;
    }

    public function setFechaCaducidad(\DateTimeInterface $fechaCaducidad): static
    {
        $this->fechaCaducidad = $fechaCaducidad;

        return $this;
    }

    public function getTipoAbono(): ?TipoAbono
    {
        return $this->tipoAbono;
    }

    public function setTipoAbono(?TipoAbono $tipoAbono): static
    {
        $this->tipoAbono = $tipoAbono;

        return $this;
    }

    public function getCliente(): ?Cliente
    {
        return $this->cliente;
    }

    public function setCliente(?Cliente $cliente): static
    {
        $this->cliente = $cliente;

        return $this;
    }



}
