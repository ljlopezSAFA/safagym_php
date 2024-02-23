<?php

namespace App\Entity;

use App\Repository\ClienteRepository;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\ManyToMany(targetEntity: Monitor::class, cascade: ['persist'])]
    #[ORM\JoinTable(name: "cliente_entrenador_personal", schema: "safagym")]
    #[ORM\JoinColumn(name: "id_cliente", referencedColumnName: "id")]
    #[ORM\InverseJoinColumn(name: "id_monitor", referencedColumnName: "id")]
    private Collection $monitores;


    #[ORM\OneToOne(targetEntity: Usuario::class, cascade:["persist", "remove"])]
    #[ORM\JoinColumn(name: 'id_usuario')]
    private ?Usuario $usuario = null;

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

    public function setFechaNacimiento(string $fechaNacimiento): static
    {
        $this->fecha = \DateTime::createFromFormat('Y-m-d', $fechaNacimiento);

        return $this;
    }



    /**
     * @return Usuario|null
     */
    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    /**
     * @param Usuario|null $usuario
     */
    public function setUsuario(?Usuario $usuario): void
    {
        $this->usuario = $usuario;
    }

    /**
     * @return Collection
     */
    public function getMonitores(): Collection
    {
        return $this->monitores;
    }

    /**
     * @param Collection $monitores
     */
    public function setMonitores(Collection $monitores): void
    {
        $this->monitores = $monitores;
    }







}
