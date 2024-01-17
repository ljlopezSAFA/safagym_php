<?php

namespace App\Entity;

use App\Repository\TipoAbonoRepository;
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
}
