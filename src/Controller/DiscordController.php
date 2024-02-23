<?php

namespace App\Controller;

use DiscordWebhook\Webhook;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DiscordController extends AbstractController
{
    #[Route('/discord', name: 'app_discord')]
    public function index(): JsonResponse
    {

        $wh = new Webhook("https://discord.com/api/webhooks/1207379532504375346/ktJDB2gHcBJ-RdCHro8cTC6dQTdwfV8CIcbMcZx4MBuegpmJUChIOCSm5n0zDyCuur8T");
        $wh->setMessage("Mensaje Prueba");
        $wh->send();
        return $this->json("Mensaje Enviado");
    }
}
