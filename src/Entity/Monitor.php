<?php

namespace App\Entity;

use App\Repository\MonitorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MonitorRepository::class)]
#[ORM\Table(name: "monitor", schema: "safagym")]
class Monitor
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

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fechaNacimiento = null;

    #[ORM\Column]
    private ?string $foto = null;


    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, name: "id_turno")]
    private ?Turno $turno = null;

    #[ORM\ManyToMany(targetEntity: TipoMonitor::class)]
    #[ORM\JoinTable(name: "monitor_tipo", schema: "safagym")]
    #[ORM\JoinColumn(name: "id_monitor", referencedColumnName: "id")]
    #[ORM\InverseJoinColumn(name: "id_tipo", referencedColumnName: "id")]
    private Collection $tipo;

    #[ORM\OneToOne(targetEntity: Usuario::class, cascade:["persist", "remove"])]
    #[ORM\JoinColumn(name: 'id_usuario')]
    private ?Usuario $usuario = null;

    public function __construct()
    {
        $this->tipo = new ArrayCollection();
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

    public function getFechaNacimiento(): ?string
    {
        return $this->fechaNacimiento?->format('d/m/Y');
    }

    public function setFechaNacimiento(string $fechaNacimiento): static
    {
        $this->fechaNacimiento = \DateTime::createFromFormat('d/m/Y', $fechaNacimiento);

        return $this;
    }

    public function getTurno(): ?Turno
    {
        return $this->turno;
    }

    public function setTurno(?Turno $turno): static
    {
        $this->turno = $turno;

        return $this;
    }

    /**
     * @return Collection<int, TipoMonitor>
     */
    public function getTipo(): Collection
    {
        return $this->tipo;
    }

    public function addTipo(TipoMonitor $tipo): static
    {
        if (!$this->tipo->contains($tipo)) {
            $this->tipo->add($tipo);
        }

        return $this;
    }

    public function removeTipo(TipoMonitor $tipo): static
    {
        $this->tipo->removeElement($tipo);

        return $this;
    }

    public function vaciarTipos(): static
    {
        $this->tipo->clear();
        return $this;
    }

    public function setTipos($tipos): static
    {
        forEach ($tipos as $t){
            $this-> addTipo($t);
         }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFoto(): ?string
    {
        return $this->foto;
    }

    /**
     * @param string|null $foto
     */
    public function setFoto(?string $foto): void
    {
        $this->foto = $foto;
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


}
