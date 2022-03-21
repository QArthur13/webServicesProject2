<?php

namespace App\Controller;

use App\Entity\Account;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\BrowserKit\Response as BrowserKitResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

class ApiAccount extends AbstractController
{
    /**
     * @Route("/api/accounts", name="api_account_index", methods={"GET"})
     *
     * Permet de récupérer toutes les comptes
     */
    public function index(AccountRepository $accountRepository, Request $request): Response
    {

            $accounts = $accountRepository->findAll();
            $responseType = $this->getType($request->headers->get('Accept', 'application/json'));

            return $this->json($accounts, Response:: HTTP_OK, [

                'Content-Type' => ('json' === $responseType ? 'application/json' : 'application/xml')

            ]);

        }

        private function getType(string $mime){

            return  'application/json' === $mime? 'json': 'xml';

         }

}