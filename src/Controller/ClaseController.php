<?php

namespace App\Controller;

use App\Entity\Clase;
use App\Entity\Monitor;
use App\Repository\ClaseRepository;
use App\Repository\ClienteRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/clase')]
class ClaseController extends AbstractController
{

    #[Route('', name: "clase_list", methods: ["GET"])]
    public function list(ClaseRepository $claseRepository):JsonResponse
    {
        $list = $claseRepository->findAll();

        return $this->json($list);
    }

    #[Route('/{id}', name: "clase_by_id", methods: ["GET"])]
    public function getById(Clase $clase):JsonResponse
    {
        return $this->json($clase);

    }


    #[Route('/fecha', name: "clase_by_id", methods: ["GET"])]
    #[IsGranted('ROLE_CLIENTE')]
    public function getByDay(ClaseRepository $claseRepository, Request $request):JsonResponse
    {
        $fecha = $request->query->get('fecha');
        $clases = $claseRepository->findByFecha($fecha);

        foreach ($clases as $c) {
            $c->setAsistentes($c->getClientes()->count());
        }

        return $this->json($clases);

    }

    #[Route('', name: "crear_clase", methods: ["POST"])]
    public function crear(EntityManagerInterface $entityManager, Request $request):JsonResponse
    {
        $json = json_decode($request-> getContent(), true);

        $nuevaClase = new Clase();
        $nuevaClase->setNombre($json["nombre"]);
        $nuevaClase->setAforo($json["aforo"]);
        $nuevaClase->setDuracion($json["duracion"]);
        $nuevaClase->setFecha($json["fecha"]);

        $monitor = $entityManager->getRepository(Monitor::class)->findBy(["id"=> $json["id_monitor"]]);
        $nuevaClase->setMonitor($monitor[0]);


        $entityManager->persist($nuevaClase);
        $entityManager->flush();

        return $this->json(['message' => 'Clase creada'], Response::HTTP_CREATED);


    }


    #[Route('/{id}', name: "editar_clase", methods: ["PUT"])]
    public function editar(EntityManagerInterface $entityManager, Request $request, Clase $clase):JsonResponse
    {
        $json = json_decode($request-> getContent(), true);

        $clase->setNombre($json["nombre"]);
        $clase->setAforo($json["aforo"]);
        $clase->setDuracion($json["duracion"]);
        $clase->setFecha($json["fecha"]);

        $monitor = $entityManager->getRepository(Monitor::class)->findBy(["id"=> $json["id_monitor"]]);
        $clase->setMonitor($monitor[0]);

        $entityManager->flush();

        return $this->json(['message' => 'Clase modificada'], Response::HTTP_OK);


    }

    #[Route('/{id}', name: "delete_by_id", methods: ["DELETE"])]
    public function deleteById(EntityManagerInterface $entityManager, Clase $clase):JsonResponse
    {

        $entityManager->remove($clase);
        $entityManager->flush();

        return $this->json(['message' => 'Clase eliminada'], Response::HTTP_OK);

    }

    /**
     * @throws \Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException
     */
    #[Route('/join/{id}', name: "inscribirse_a_clase", methods: ["POST"])]
    public function joinClase(EntityManagerInterface $entityManager, Clase $clase,
                              Request $request, JWTTokenManagerInterface  $jwtManager,
                              UsuarioRepository $usuarioRepository, ClienteRepository $clienteRepository):JsonResponse
    {

        // Obtener el token JWT de la cabecera
        $token = $request->headers->get('authorization');
        $formatToken = str_replace('Bearer ', '', $token);

        // Extraer datos del token
        $finaltoken = $jwtManager->parse($formatToken);


         $usuario = $usuarioRepository->findOneBy(["username" =>$finaltoken["username"]]);
         $cliente = $clienteRepository->findOneBy(["usuario" => $usuario]);

         $clase->addCliente($cliente);


        $entityManager->persist($clase);
        $entityManager->flush($clase);


        return $this->json(['message' => 'Incripcioón a clase completada'], Response::HTTP_OK);

    }

    /**
     * @throws \Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException
     */
    #[Route('/abandon/{id}', name: "borrarse_de_clase", methods: ["POST"])]
    #[IsGranted('ROLE_CLIENTE')]
    public function abandonClase(EntityManagerInterface $entityManager, Clase $clase,
                              Request $request, JWTTokenManagerInterface  $jwtManager,
                              UsuarioRepository $usuarioRepository, ClienteRepository $clienteRepository):JsonResponse
    {

        // Obtener el token JWT de la cabecera
        $token = $request->headers->get('authorization');
        $formatToken = str_replace('Bearer ', '', $token);

        // Extraer datos del token
        $finaltoken = $jwtManager->parse($formatToken);


        $usuario = $usuarioRepository->findOneBy(["username" =>$finaltoken["username"]]);
        $cliente = $clienteRepository->findOneBy(["usuario" => $usuario]);

        $clase->removeCliente($cliente);


        $entityManager->persist($clase);
        $entityManager->flush($clase);


        return $this->json(['message' => 'Cancelación a clase completada'], Response::HTTP_OK);
    }




}
