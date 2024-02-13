<?php

namespace App\Controller;

use App\Entity\Cliente;
use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[Route('/api/registro')]
class RegistroController extends AbstractController
{

    #[Route('', name: "registrar_usuario", methods: ["POST"])]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher,EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);


        $user = new Usuario();
        $user->setUsername($data['username']);
        $user->setPassword($passwordHasher->hashPassword($user, $data['password']));
        $user->setRol($data['rol']);


        $entityManager->persist($user);
        $entityManager->flush();


        return new JsonResponse(['message' => 'Usuario registrado con éxito'], 201);
    }

    #[Route('/cliente', name: "registrar_cliente", methods: ["POST"])]
    public function registroCliente(Request $request, UserPasswordHasherInterface $passwordHasher,EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);


        $user = new Usuario();
        $user->setUsername($data['username']);
        $user->setPassword($passwordHasher->hashPassword($user, $data['password']));
        $user->setRol('CLIENTE');

        $cliente = new Cliente();
        $cliente->setNombre($data['nombre']);
        $cliente->setApellidos($data['apellidos']);
        $cliente->setDni($data['dni']);
        $cliente->setFechaNacimiento($data['fecha']);

        $cliente->setUsuario($user);


        $entityManager->persist($cliente);
        $entityManager->flush();


        return new JsonResponse(['message' => 'Cliente registrado con éxito'], 201);
    }

}
