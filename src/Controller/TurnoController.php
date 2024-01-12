<?php

namespace App\Controller;

use App\Entity\Turno;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/turno')]
class TurnoController extends AbstractController
{
    #[Route('', name: 'api_turno_list', methods: ['GET'])]
    public function list(EntityManagerInterface $entityManager): JsonResponse
    {
        $turnos = $entityManager->getRepository(Turno::class)->findAll();

        return $this->json($turnos);
    }

    #[Route('/{id}', name: 'api_turno_get_by_id', methods: ['GET'])]
    public function show(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $turnos = $entityManager->getRepository(Turno::class)->findBy(["id"=>$id]);
        return $this->json($turnos);
    }

    #[Route('', name: 'api_turno_create', methods: ['POST'])]
    public function create(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Validar que la descripción existe y no está vacía
        if (!empty($data['descripcion'])) {
            // Crear un nuevo objeto Turno
            $turno = new Turno();
            $turno->setDescripcion($data['descripcion']);

            // Guardar el objeto en la base de datos
            $entityManager->persist($turno);
            $entityManager->flush();

            return $this->json(['message' => 'Turno creado'], Response::HTTP_CREATED);
        } else {
            return $this->json(['error' => 'La descripción no puede estar vacía'], Response::HTTP_BAD_REQUEST);
        }

    }

    #[Route('/{id}', name: 'api_turno_update', methods: ['PUT'])]
    public function update(EntityManagerInterface $entityManager, Request $request, Turno $turno): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Validar que el turno existe
        if (!$turno) {
            return $this->json(['error' => 'El turno no existe'], Response::HTTP_NOT_FOUND);
        }

        // Validar que la descripción existe y no está vacía
        if (!empty($data['descripcion'])) {
            // Actualizar los campos del turno
            $turno->setDescripcion($data['descripcion']);

            // Guardar los cambios en la base de datos
            $entityManager->flush();

            return $this->json(['message' => 'Turno actualizado']);
        } else {
            return $this->json(['error' => 'La descripción no puede estar vacía'], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'api_turno_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager,  Turno $turno): JsonResponse
    {
        if(!$turno){
            $entityManager->remove($turno);
            $entityManager->flush();
        }

        return $this->json(['message' => 'Turno eliminado']);
    }
}
