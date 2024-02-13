<?php

namespace App\Controller;

use App\Repository\TipoAbonoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/tipo/abono')]
class TipoAbonoController extends AbstractController
{
    #[Route('', name: 'api_tipo_abonos_list', methods: ['GET'])]
    public function list(TipoAbonoRepository $tipoAbonoRepository): JsonResponse
    {
        $tipos = $tipoAbonoRepository->findBy([],["descripcion"=> "asc"]);

        return $this->json($tipos);
    }
}
