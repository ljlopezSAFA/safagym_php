<?php

namespace App\Dto;

class MensajeDTO
{
    private ?int $id = null;
    private ?string $texto = null;
    private ?\DateTimeInterface $fecha = null;
    private ?int $emisor = null;
    private ?int $receptor = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getTexto(): ?string
    {
        return $this->texto;
    }

    /**
     * @param string|null $texto
     */
    public function setTexto(?string $texto): void
    {
        $this->texto = $texto;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    /**
     * @param \DateTimeInterface|null $fecha
     */
    public function setFecha(?\DateTimeInterface $fecha): void
    {
        $this->fecha = $fecha;
    }

    /**
     * @return int|null
     */
    public function getEmisor(): ?int
    {
        return $this->emisor;
    }

    /**
     * @param int|null $emisor
     */
    public function setEmisor(?int $emisor): void
    {
        $this->emisor = $emisor;
    }

    /**
     * @return int|null
     */
    public function getReceptor(): ?int
    {
        return $this->receptor;
    }

    /**
     * @param int|null $receptor
     */
    public function setReceptor(?int $receptor): void
    {
        $this->receptor = $receptor;
    }


}