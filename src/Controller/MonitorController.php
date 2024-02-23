<?php

namespace App\Controller;

use App\Entity\Clase;
use App\Entity\Mensaje;
use App\Entity\Monitor;
use App\Entity\TipoMonitor;
use App\Repository\ClienteRepository;
use App\Repository\MonitorRepository;
use App\Repository\TipoMonitorRepository;
use App\Repository\TurnoRepository;
use App\Repository\UsuarioRepository;
use App\Utils\UtilidadesToken;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/monitor')]
class MonitorController extends AbstractController
{
    #[Route('', name: 'api_monitores_list', methods: ['GET'])]
    #[IsGranted('ROLE_MONITOR')]
    public function list(MonitorRepository $monitorRepository): JsonResponse
    {
        $monitores = $monitorRepository->findAll();

        return $this->json($monitores);
    }


    #[Route('/tipos', name: 'api_monitores_list_by_tipo', methods: ['GET'])]
    public function listByTipo(MonitorRepository $monitorRepository, Request $request): JsonResponse
    {

        $tipos = json_decode($request->query->get('tipos'), true);

        $monitores = $monitorRepository->findByTipos($tipos);

        return $this->json($monitores);
    }

    #[Route('/get/{id}', name: 'api_monitores_show', methods: ['GET'])]
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
            $turnoPorDefecto = $turnoRepository->findAll();
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
    public function update(EntityManagerInterface $entityManager, Request $request, Monitor $monitor,TurnoRepository $turnoRepository, TipoMonitorRepository $tipoMonitorRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $monitor->setNombre($data['nombre']);
        $monitor->setApellidos($data['apellidos']);
        $monitor->setDni($data['dni']);
        $monitor->setFechaNacimiento($data['fechaNacimiento']); //La fecha viene en formato 'd/m/Y'


        //Establecemos un turno o el turno por defecto
        if(isset($data['id_turno'])){
            $turno = $turnoRepository->find($data['id_turno']);
            $monitor->setTurno($turno);
        }


        //Establecemos sus tipos
        if (isset($data['tipos']) && is_array($data['tipos'])) {
             $tipos = [];
            foreach ($data['tipos'] as $tipoId) {
                $tipoMonitor = $tipoMonitorRepository->find($tipoId);
                if ($tipoMonitor instanceof TipoMonitor) {
                    $tipos[] = ($tipoMonitor);
                }
            }
            $monitor->vaciarTipos();
            $monitor->setTipos($tipos);
        }

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


    /**
     * @throws \Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException
     */
    #[Route('/personal', name: "solicitar_entrenador_personal", methods: ["GET"])]
    public function solicitarEntrenadorPersonal(EntityManagerInterface $entityManager,
                                 Request $request, JWTTokenManagerInterface  $jwtManager,
                                 UsuarioRepository $usuarioRepository,
                                 UtilidadesToken $utilidadesToken, MonitorRepository $monitorRepository,
                                                ClienteRepository $clienteRepository):JsonResponse
    {

        // Extraer datos del token
        $finaltoken = $utilidadesToken->extraerTokenData($request,$jwtManager)  ;
        $usuario = $usuarioRepository->findOneBy(["username" =>$finaltoken["username"]]);


        $monitores= $monitorRepository->findAll();
        $monitorAsignado = $monitores[random_int(0, count($monitores) -1)];
        $cliente = $clienteRepository->findOneBy(["usuario" => $usuario]);


        $cliente->getMonitores()[]=$monitorAsignado;
        $entityManager->flush($cliente);


        return $this->json($monitorAsignado);
    }


    /**
     * @throws \Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException
     */
    #[Route('/personal/get', name: "gei_entrenador_personal", methods: ["GET"])]
    public function getEntrenadorPersonal(Request $request, JWTTokenManagerInterface  $jwtManager,
                                                UsuarioRepository $usuarioRepository,
                                                UtilidadesToken $utilidadesToken,
                                                ClienteRepository $clienteRepository):JsonResponse
    {

        // Extraer datos del token
        $finaltoken = $utilidadesToken->extraerTokenData($request,$jwtManager)  ;
        $usuario = $usuarioRepository->findOneBy(["username" =>$finaltoken["username"]]);
        $cliente = $clienteRepository->findOneBy(["usuario" => $usuario]);
        $personal = null;
        if(count($cliente->getMonitores())>0){
            $personal = $cliente->getMonitores()[0];
        }
        return $this->json($personal);
    }


}
