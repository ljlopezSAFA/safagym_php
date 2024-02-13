<?php

namespace App\Entity;

use App\Repository\TipoAbonoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TipoAbonoRepository::class)]
#[ORM\Table(name: "tipo_abono", schema: "safagym")]
class TipoAbono
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $descripcion = null;

    #[ORM\Column]
    private ?float $precio = null;


    #[ORM\ManyToMany(targetEntity: Servicio::class)]
    #[ORM\JoinTable(name: "tipo_abono_servicio", schema: "safagym")]
    #[ORM\JoinColumn(name: "id_tipo_abono", referencedColumnName: "id")]
    #[ORM\InverseJoinColumn(name: "id_servicio", referencedColumnName: "id")]
    private Collection $servicios;


    #[ORM\Column(name: "num_meses")]
    private ?int $numMeses = null;

    public function __construct()
    {
        $this->servicios = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getPrecio(): ?float
    {
        return $this->precio;
    }

    public function setPrecio(float $precio): static
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getServicios(): Collection
    {
        return $this->servicios;
    }

    /**
     * @param Collection $servicios
     */
    public function setServicios(Collection $servicios): void
    {
        $this->servicios = $servicios;
    }


    /**
     * @return int|null
     */
    public function getNumMeses(): ?int
    {
        return $this->numMeses;
    }

    /**
     * @param int|null $numMeses
     */
    public function setNumMeses(?int $numMeses): void
    {
        $this->numMeses = $numMeses;
    }



}
