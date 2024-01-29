<?php

namespace App\Dto;

class MonitorDTO
{
    private ?int $id = null;
    private ?string $nombre = null;
    private ?string $apellidos = null;
    private ?string $dni = null;
    private ?\DateTimeInterface $fechaNacimiento = null;
    private ?int $id_turno = null;

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
    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    /**
     * @param string|null $nombre
     */
    public function setNombre(?string $nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return string|null
     */
    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    /**
     * @param string|null $apellidos
     */
    public function setApellidos(?string $apellidos): void
    {
        $this->apellidos = $apellidos;
    }

    /**
     * @return string|null
     */
    public function getDni(): ?string
    {
        return $this->dni;
    }

    /**
     * @param string|null $dni
     */
    public function setDni(?string $dni): void
    {
        $this->dni = $dni;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getFechaNacimiento(): ?\DateTimeInterface
    {
        return $this->fechaNacimiento;
    }

    /**
     * @param \DateTimeInterface|null $fechaNacimiento
     */
    public function setFechaNacimiento(?\DateTimeInterface $fechaNacimiento): void
    {
        $this->fechaNacimiento = $fechaNacimiento;
    }

    /**
     * @return int|null
     */
    public function getIdTurno(): ?int
    {
        return $this->id_turno;
    }

    /**
     * @param int|null $id_turno
     */
    public function setIdTurno(?int $id_turno): void
    {
        $this->id_turno = $id_turno;
    }



}