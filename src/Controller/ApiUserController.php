<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\AcceptHeader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Contient toutes fonctions en rapport avec l'entité User
 */
class ApiUserController extends AbstractController
{
    /**
     * Permet de savoir quel formats à été envoyé
     *
     * @param string $mime
     * @return string
     */
    private function getFormats(string $mime): string
    {
        return 'application/xml' === $mime ? 'xml' : 'json';
    }

    #[Route('/api/user', name: 'app_api_user', methods: ['GET'])]
    /**
     * Renvoie toutes les informations des utilisateurs
     *
     * @param Request $request
     * @param UserRepository $userRepository
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function index(Request $request, UserRepository $userRepository, SerializerInterface $serializer): Response
    {
        /**
         * @var User $users
         * Une liste d'utilisateurs
         */
        $users = $userRepository->findAll();
        /**
         * @var $mime
         * On récupère le le type de formats récupérés
         */
        $mime = $this->getFormats($request->headers->get('Accept', 'application/json'));

        return new Response($serializer->serialize($users, $mime), Response::HTTP_OK, [

            'Content-Type' => ('xml' === $mime ? 'application/xml' : 'application/json')

        ]);
    }
}
