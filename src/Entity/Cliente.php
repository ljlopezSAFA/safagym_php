<?php

namespace App\Entity;

use App\Repository\ClienteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClienteRepository::class)]
#[ORM\Table(name: "cliente", schema: "safagym")]
class Cliente
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $apellidos = null;

    #[ORM\Column(length: 9)]
    private ?string $dni = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, name: "fecha_nacimiento")]
    private ?\DateTimeInterface $fechaNacimiento = null;

    #[ORM\OneToOne(mappedBy: 'cliente', cascade: ['persist', 'remove'])]
    private ?Abono $abono = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): static
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function setDni(string $dni): static
    {
        $this->dni = $dni;

        return $this;
    }

    public function getFechaNacimiento(): ?\DateTimeInterface
    {
        return $this->fechaNacimiento;
    }

    public function setFechaNacimiento(\DateTimeInterface $fechaNacimiento): static
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    public function getAbono(): ?Abono
    {
        return $this->abono;
    }

    public function setAbono(?Abono $abono): static
    {
        // unset the owning side of the relation if necessary
        if ($abono === null && $this->abono !== null) {
            $this->abono->setCliente(null);
        }

        // set the owning side of the relation if necessary
        if ($abono !== null && $abono->getCliente() !== $this) {
            $abono->setCliente($this);
        }

        $this->abono = $abono;

        return $this;
    }
}
