<?php

namespace App\Controller;

use App\Dto\AbonoDTO;
use App\Repository\AbonoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/abono')]
class AbonoController extends AbstractController
{
    #[Route('', name: 'listar_abono', methods: ["GET"])]
    public function listar(AbonoRepository $abonoRepository): JsonResponse
    {

        $listaAbonos = $abonoRepository->findAll();

        $listaAbonoDTOs = [];

        foreach ($listaAbonos as $abono){
            $abonoDTO = new AbonoDTO();
            $abonoDTO-> setId($abono->getId());
            $abonoDTO-> setCodigo($abono->getCodigo());
            $abonoDTO->setFechaCaducidad($abono-> getFechaCaducidad());
            $abonoDTO-> setPrecio($abono->getTipoAbono()->getPrecio());
            $abonoDTO-> setTipoAbono($abono->getTipoAbono()->getDescripcion());
            $abonoDTO->setNombreCliente($abono->getCliente()->getNombre());

            $listaAbonoDTOs[] = $abonoDTO;
        }



        return $this->json($listaAbonos,Response::HTTP_OK );

    }
}
