<?php

namespace App\Dto;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class MonitoresPorTipoDTO
{
    private string $etiqueta;
    private Collection $monitores;



    public function __construct() {
        $this->monitores = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getEtiqueta(): string
    {
        return $this->etiqueta;
    }

    /**
     * @param string $etiqueta
     */
    public function setEtiqueta(string $etiqueta): void
    {
        $this->etiqueta = $etiqueta;
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

    public function agregarMonitores(array $monitores) {
        foreach ($monitores as $monitor) {
            $this->monitores->add($monitor);
        }
    }


}