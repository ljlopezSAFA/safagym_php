<?php

namespace App\Controller;

use App\Dto\MensajeDTO;
use App\Entity\Mensaje;
use App\Repository\MensajeRepository;
use App\Repository\UsuarioRepository;
use App\Utils\UtilidadesToken;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/mensaje')]
class MensajeController extends AbstractController
{

    /**
     * @throws \Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException
     */
    #[Route('/enviar', name: "enviar_mensaje", methods: ["POST"])]
    public function sendMenssage(EntityManagerInterface $entityManager,
                              Request $request, JWTTokenManagerInterface  $jwtManager,
                              UsuarioRepository $usuarioRepository,
                              UtilidadesToken $utilidadesToken):JsonResponse
    {

        $data = json_decode($request->getContent(), true);

        // Extraer datos del token
        $finaltoken = $utilidadesToken->extraerTokenData($request,$jwtManager)  ;
        $emisor = $usuarioRepository->findOneBy(["username" =>$finaltoken["username"]]);
        $receptor = $usuarioRepository->findOneBy(["id" =>$data["receptor"]]);


        $mensaje = new Mensaje();
        $mensaje->setEmisor($emisor);
        $mensaje->setReceptor($receptor);
        $mensaje->setTexto($data["texto"]);
        $mensaje->setFecha(new \DateTime());

        $entityManager->persist($mensaje);
        $entityManager->flush($mensaje);


        return $this->json(['message' => 'Mensaje enviado'], Response::HTTP_OK);
    }


    #[Route('/receptor', name: "get_by_receptor", methods: ["GET"])]
    public function getByReceptor(MensajeRepository $mensajeRepository,
                                  UsuarioRepository $usuarioRepository,
                                  Request $request,
                                  UtilidadesToken $utilidadesToken,
                                  JWTTokenManagerInterface  $jwtManager):JsonResponse
    {

        // Extraer datos del token
        $finaltoken = $utilidadesToken->extraerTokenData($request,$jwtManager);
        $idReceptor = $request->query->get('receptor');
        $limit = $request->query->get('limit');
        $offset = $request->query->get('offset');

        $emisor = $usuarioRepository->findOneBy(["username" =>$finaltoken["username"]]);
        $receptor = $usuarioRepository->findOneBy(["id" =>$idReceptor]);

        $mensajes = $mensajeRepository-> findByRecetorEmisor($emisor,$receptor, $limit, $offset);

        $dtos = [];


        foreach($mensajes as $m){
            $dto = new MensajeDTO();
            $dto->setId($m->getId());
            $dto->setTexto($m->getTexto());
            $dto->setReceptor($m->getReceptor()->getId());
            $dto->setEmisor($m->getEmisor()->getId());
            $dto->setFecha($m->getFecha());
            $dtos[]= $dto;
        }

        return $this->json($dtos);

    }


}
