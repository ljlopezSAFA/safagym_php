<?php

namespace App\Controller;

use App\Dto\AbonoDTO;
use App\Entity\Abono;
use App\Repository\AbonoRepository;
use App\Repository\ClienteRepository;
use App\Repository\TipoAbonoRepository;
use App\Repository\UsuarioRepository;
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

        return $this->json($listaAbonoDTOs,Response::HTTP_OK );

    }

    #[Route('/cliente', name: 'abono_cliente_actual', methods: ["GET"])]
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
        $abono = $abonoRepository->findAbonoActual($cliente);

        return $this->json($abono,Response::HTTP_OK );
    }



    #[Route('/cliente/last', name: 'ultimo_contratado_pasado', methods: ["GET"])]
    public function getUltimoCliente(AbonoRepository $abonoRepository,
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
        $abono = $abonoRepository->findUltimoContratado($cliente);

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

        $abonoActual = $abonoRepository->findAbonoActual($cliente);
        $tipoAbono = $tipoAbonoRepository->findOneBy(['id'=> $id_tipo_abono]);

        if($abonoActual == null){
            $nuevoAbono = new Abono();
            $nuevoAbono->setCliente($cliente);
            $nuevoAbono->setTipoAbono($tipoAbono);
            $nuevoAbono->setCodigo("CCA".$cliente->getId().$tipoAbono->getId());
            $fecha = new \DateTime();
            $nuevoAbono->setFechaInicio($fecha);
            $fechaCaducidad = new \DateTime();
            $fechaCaducidad = $fechaCaducidad->modify('+' . $tipoAbono->getNumMeses() . ' months' );
            $nuevoAbono->setFechaCaducidad($fechaCaducidad);
            $entityManager->persist($nuevoAbono);
            $entityManager->flush();

            return $this->json($nuevoAbono,Response::HTTP_OK );
        }else{
            return $this->json("Ya cuenta con un abono vigente", Response::HTTP_NO_CONTENT);
        }





    }


}
