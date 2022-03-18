<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\ErrorHandler\DebugClassLoader;
use Symfony\Component\ErrorHandler\ErrorHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Cette classe permet juste d'accéder à la page par défaut de l'API
 */
class DefaultController extends AbstractController
{

    #[Route('/api/default', name: 'app_default', methods: ['GET'])]
    /**
     * Renvoie juste les messages de la page
     * @return Response
     */
    public function index(): Response
    {
        Debug::enable();
        //ErrorHandler::register();
        //DebugClassLoader::enable();

        return $this->json([
            'message' => 'Ce n\'est juste la page par défault!'
        ]);
    }
}
