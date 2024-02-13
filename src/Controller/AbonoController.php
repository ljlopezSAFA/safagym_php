<?php

namespace App\Controller;

use App\Dto\AbonoDTO;
use App\Entity\Abono;
use App\Repository\AbonoRepository;
use App\Repository\ClienteRepository;
use App\Repository\TipoAbonoRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/cliente', name: 'abono_cliente', methods: ["GET"])]
    public function getByCliente(AbonoRepository $abonoRepository,
                                 ClienteRepository $clienteRepository,
                                 UsuarioRepository $usuarioRepository,
                                 JWTTokenManagerInterface $jwtManager,
                                 Request $request): JsonResponse
    {


        // Obtener el token JWT de la cabecera
        $token = $request->headers->get('authorization');
        $formatToken = str_replace('Bearer ', '', $token);
        // Extraer datos del token
        $finaltoken = $jwtManager->parse($formatToken);


        $usuario = $usuarioRepository->findOneBy(["username" =>$finaltoken["username"]]);
        $cliente = $clienteRepository->findOneBy(["usuario" => $usuario]);
        $abono = $abonoRepository->findOneBy(['cliente'=> $cliente]);

        return $this->json($abono,Response::HTTP_OK );

    }

    #[Route('/cliente', name: 'contratar_renovar_abono', methods: ["POST"])]
    public function contratarORenovar(AbonoRepository $abonoRepository,
                                 ClienteRepository $clienteRepository,
                                 UsuarioRepository $usuarioRepository,
                                 JWTTokenManagerInterface $jwtManager,
                                 TipoAbonoRepository $tipoAbonoRepository,
                                 Request $request, EntityManagerInterface $entityManager): JsonResponse
    {


        // Obtener el token JWT de la cabecera
        $token = $request->headers->get('authorization');
        $formatToken = str_replace('Bearer ', '', $token);
        // Extraer datos del token
        $finaltoken = $jwtManager->parse($formatToken);

        $id_tipo_abono =  $request->query->get('tipoAbono');

        $usuario = $usuarioRepository->findOneBy(["username" =>$finaltoken["username"]]);
        $cliente = $clienteRepository->findOneBy(["usuario" => $usuario]);

        $abono = $abonoRepository->findOneBy(['cliente'=> $cliente]);
        $tipoAbono = $tipoAbonoRepository->findOneBy(['id'=> $id_tipo_abono]);

        if($abono == null){
            $nuevoAbono = new Abono();
            $nuevoAbono->setCliente($cliente);
            $nuevoAbono->setTipoAbono($tipoAbono);
            $nuevoAbono->setCodigo("CCA".$cliente->getId().$tipoAbono->getId());
            $fecha = new \DateTime();
            $nuevoAbono->setFechaCaducidad($fecha->modify('+' . $tipoAbono->getNumMeses() . ' months' ));
            $entityManager->persist($nuevoAbono);
        }else{
            $abono->setCodigo("CCA".$cliente->getId().$tipoAbono->getId());
            $fecha = $abono->getFechaCaducidad()->modify('+' . $tipoAbono->getNumMeses() . ' months' );
            $abono->setFechaCaducidad($fecha);
            $abono->setTipoAbono($tipoAbono);

        }

        $entityManager->flush();

        return $this->json($abono,Response::HTTP_OK );

    }


}
