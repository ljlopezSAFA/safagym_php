<?php

namespace App\Entity;

use App\Repository\ClaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClaseRepository::class)]
#[ORM\Table(name: "clase", schema: "safagym")]
class Clase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\Column]
    private ?int $duracion = null;

    #[ORM\Column(name: "aforo_max")]
    private ?int $aforo = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, name:"id_monitor")]
    private ?Monitor $monitor = null;

    #[ORM\ManyToMany(targetEntity: Cliente::class)]
    #[ORM\JoinTable(name: "cliente_clase", schema: "safagym")]
    #[ORM\JoinColumn(name: "id_clase", referencedColumnName: "id")]
    #[ORM\InverseJoinColumn(name: "id_cliente", referencedColumnName: "id")]
    private Collection $clientes;

    public function __construct()
    {
        $this->clientes = new ArrayCollection();
    }

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

    public function getFecha(): ?string
    {
        return $this->fecha->format('d/m/Y H:i:s');
    }

    public function setFecha(String $fecha): static
    {
        $this->fecha = \DateTime::createFromFormat('d/m/Y H:i:s', $fecha);

        return $this;
    }

    public function getDuracion(): ?int
    {
        return $this->duracion;
    }

    public function setDuracion(int $duracion): static
    {
        $this->duracion = $duracion;

        return $this;
    }

    public function getAforo(): ?int
    {
        return $this->aforo;
    }

    public function setAforo(int $aforo): static
    {
        $this->aforo = $aforo;

        return $this;
    }

    public function getMonitor(): ?Monitor
    {
        return $this->monitor;
    }

    public function setMonitor(?Monitor $monitor): static
    {
        $this->monitor = $monitor;

        return $this;
    }

    /**
     * @return Collection<int, Cliente>
     */
    public function getClientes(): Collection
    {
        return $this->clientes;
    }

    public function addCliente(Cliente $cliente): static
    {
        if (!$this->clientes->contains($cliente)) {
            $this->clientes->add($cliente);
        }

        return $this;
    }

    public function removeCliente(Cliente $cliente): static
    {
        $this->clientes->removeElement($cliente);

        return $this;
    }
}
