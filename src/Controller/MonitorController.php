<?php

namespace App\Controller;

use App\Entity\Monitor;
use App\Entity\TipoMonitor;
use App\Repository\MonitorRepository;
use App\Repository\TipoMonitorRepository;
use App\Repository\TurnoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/monitor')]
class MonitorController extends AbstractController
{
    #[Route('', name: 'api_monitores_list', methods: ['GET'])]
    public function list(MonitorRepository $monitorRepository): JsonResponse
    {
        $monitores = $monitorRepository->findAll();

        return $this->json($monitores);
    }

    #[Route('/{id}', name: 'api_monitores_show', methods: ['GET'])]
    public function show(Monitor $monitor): JsonResponse
    {
        return $this->json($monitor);
    }

    #[Route('', name: 'api_monitores_create', methods: ['POST'])]
    public function create(EntityManagerInterface $entityManager, Request $request,TurnoRepository $turnoRepository, TipoMonitorRepository $tipoMonitorRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $monitor = new Monitor();
        $monitor->setNombre($data['nombre']);
        $monitor->setApellidos($data['apellidos']);
        $monitor->setDni($data['dni']);
        $monitor->setFechaNacimiento($data['fechaNacimiento']); //la fecha viene en formato 'd/m/Y'

        //Establecemos un turno o el turno por defecto
        if(isset($data['id_turno'])){
            $turno = $tipoMonitorRepository->find($data['id_turno']);
            $monitor->setTurno($turno);
        }else{
            $turnoPorDefecto = $turnoRepository->findBy(["descripcion" => "Mixto"]);
            $monitor->setTurno($turnoPorDefecto[0]);
        }

        //Establecemos sus tipos
        if (isset($data['tipos']) && is_array($data['tipos'])) {
            foreach ($data['tipos'] as $tipoId) {
                $tipoMonitor = $tipoMonitorRepository->find($tipoId);
                if ($tipoMonitor instanceof TipoMonitor) {
                    $monitor->addTipo($tipoMonitor);
                }
            }
        }

        $entityManager->persist($monitor);
        $entityManager->flush();

        return $this->json(['message' => 'Monitor creado'], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_monitores_update', methods: ['PUT'])]
    public function update(EntityManagerInterface $entityManager, Request $request, Monitor $monitor): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $monitor->setNombre($data['nombre']);
        $monitor->setApellidos($data['apellidos']);
        $monitor->setDni($data['dni']);
        $monitor->setFechaNacimiento($data['fechaNacimiento']); //La fecha viene en formato 'd/m/Y'

        $entityManager->flush();

        return $this->json(['message' => 'Monitor actualizado']);
    }

    #[Route('/{id}', name: 'api_monitores_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, Monitor $monitor): JsonResponse
    {
        $entityManager->remove($monitor);
        $entityManager->flush();

        return $this->json(['message' => 'Monitor eliminado']);
    }
}