<?php

namespace App\Dto;

class AbonoDTO
{

    private ?int $id = null;
    private ?string $codigo = null;
    private ?\DateTimeInterface $fechaCaducidad = null;
    private ?string $nombre_cliente = null;
    private ?string $tipo_abono = null;
    private ?float $precio = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(?string $codigo): void
    {
        $this->codigo = $codigo;
    }

    public function getFechaCaducidad(): ?\DateTimeInterface
    {
        return $this->fechaCaducidad;
    }

    public function setFechaCaducidad(?\DateTimeInterface $fechaCaducidad): void
    {
        $this->fechaCaducidad = $fechaCaducidad;
    }

    public function getNombreCliente(): ?string
    {
        return $this->nombre_cliente;
    }

    public function setNombreCliente(?string $nombre_cliente): void
    {
        $this->nombre_cliente = $nombre_cliente;
    }

    public function getTipoAbono(): ?string
    {
        return $this->tipo_abono;
    }

    public function setTipoAbono(?string $tipo_abono): void
    {
        $this->tipo_abono = $tipo_abono;
    }

    public function getPrecio(): ?float
    {
        return $this->precio;
    }

    public function setPrecio(?float $precio): void
    {
        $this->precio = $precio;
    }




}