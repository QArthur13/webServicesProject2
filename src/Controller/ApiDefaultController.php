<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiDefaultController extends AbstractController
{
    #[Route('/api/default', name: 'app_api_default', methods: ["GET"])]
    /**
     * Pour faire des tests !
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function index(Request $request, SerializerInterface $serializer): Response
    {
        if ('application/xml' === $request->headers->get('Accept')) {

            $formats = 'xml';
            $contentType = 'application/xml';

        } else {

            $formats = 'json';
            $contentType = 'application/json';

        }

        return new Response(

            $serializer->serialize(['message' => 'pour effectuer des test!'], $formats),
            Response::HTTP_OK,
            ['Content-Type' => $contentType]

        );
    }
}
