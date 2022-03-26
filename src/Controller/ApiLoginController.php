<?php

namespace App\Controller;

use App\Entity\User;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Permet de se connecter avec l'api
 */
class ApiLoginController extends AbstractController
{
    #[Route('/api/login', name: 'app_api_login', methods: ["POST"])]
    public function index(#[CurrentUser] ?User $user, Request $request, SerializerInterface $serializer): Response
    {
        if (null === $user) {

            return new Response($serializer->serialize(

                ["message" => "informations d'identification manquantes"], 'json'),
                Response::HTTP_UNAUTHORIZED,
                ['Content-Type' => 'application/json']

            );

        }

        /*$key = InMemory::base64Encoded(base64_encode('test'));
        $token = $key;
        dump($token->passphrase());
        dd();*/
        $token = 'test-45';

        return new Response($serializer->serialize(

            ['user' => $user->getUserIdentifier(), 'token' => $token], 'json'),
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']

        );
    }

    #[Route('api/logout', name: 'app_api_logout', methods: ["GET"])]
    /**
     * @return void
     * @throws \Exception
     */
    public function logout(): void
    {
        throw new \Exception('Test!');
    }
}
