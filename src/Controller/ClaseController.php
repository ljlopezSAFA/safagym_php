<?php

namespace App\Controller;

use App\Entity\Clase;
use App\Entity\Monitor;
use App\Repository\ClaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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




}
