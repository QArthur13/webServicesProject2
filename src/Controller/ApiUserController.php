<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
//use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\AcceptHeader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
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
        //On vérifie si la personne est authentifier et possède le droit admin
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Seuls les personnes avec le role "admin" peuvent accéder à cette information!');

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

        return new Response($serializer->serialize($users, $mime, [DateTimeNormalizer::FORMAT_KEY => 'H:i:s d/m/Y']), Response::HTTP_OK, [

            'Content-Type' => ('xml' === $mime ? 'application/xml' : 'application/json')

        ]);
    }

    #[Route('api/user', name: 'app_api_create_user', methods: ['POST'])]
    /**
     * Permet de créer un utilisateur
     * @param Request $request
     * @param ManagerRegistry $managerRegistry
     * @param UserPasswordHasherInterface $passwordHasher
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function create(Request $request, ManagerRegistry $managerRegistry, UserPasswordHasherInterface $passwordHasher, SerializerInterface $serializer): Response
    {
        //On vérifie si la personne est authentifier et possède le droit admin
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Seuls les personnes avec le role "admin" peuvent accéder à cette information!');

        //On fait un appel à la fonction getFormats pour savoir qu'est-ce que le client attends en retour
        $mime = $this->getFormats($request->headers->get('Accept', 'application/json'));
        $entityManager = $managerRegistry->getManager();
        //On désérialize les données qu'on à reçu, puis on créer un nouveau utilisateur
        $requestData = $serializer->deserialize($request->getContent(), User::class, $mime);

        $entityManager->persist($requestData);
        $entityManager->flush();

        return new Response($serializer->serialize($requestData, $mime), Response::HTTP_CREATED, [

            'Content-Type' => ('xml' === $mime ? 'application/xml' : 'application/json')

        ]);
    }

    #[Route('api/user/{id}', name: 'api_one_user', methods: ["GET"])]
    /**
     * Permet de récupérer un utilisateur
     * @param Request $request
     * @param UserRepository $userRepository
     * @param int $id
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function show(Request $request, UserRepository $userRepository, int $id, SerializerInterface $serializer): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $mime = $this->getFormats($request->headers->get('Accept', 'application/json'));
        $user = $userRepository->find($id);

        //dump(["id requete" => $id, "user actuel" => $this->getUser()->getId(), "user repo" => $user->getId(), "Test" => !($user->getId() === $this->getUser()->getId() ? "Meme Id" : "Id pas pareil")]);

        //On test pour savoir s'il s'agit du même Id pour afficher
        if (!($user->getId() === $this->getUser()->getId())) {

            //L'admin peut lui modifier ce qu'il veut, par contre
            if ($this->isGranted('ROLE_ADMIN')) {

                return new Response(

                    $serializer->serialize($user, $mime),
                    Response::HTTP_OK,
                    ["Content-Type" => ("xml" === $mime ? "application/xml" : "application/json")]

                );

            }

        } else {

            //S'il s'agit bien du même ID, alors on affiche

            return new Response(

                $serializer->serialize($user, $mime),
                Response::HTTP_OK,
                ["Content-Type" => ("xml" === $mime ? "application/xml" : "application/json")]

            );

        }


        return new Response(

                $serializer->serialize(["message" => "Vous n'êtes pas autoriser à faire ça!"], $mime),
                Response::HTTP_FORBIDDEN,
                ["Content-Type" => ("xml" === $mime ? "application/xml" : "application/json")]

            );

    }

    #[Route('api/user/{id}', name: 'api_update_user', methods: ["PUT"])]
    /**
     * Mets à jour un utilisteur
     * @param Request $request
     * @param ManagerRegistry $managerRegistry
     * @param UserRepository $userRepository
     * @param int $id
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function update(Request $request, ManagerRegistry $managerRegistry, UserRepository $userRepository, int $id, SerializerInterface $serializer): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $mime = $this->getFormats($request->headers->get('Accept', 'application/json'));
        $user = $userRepository->find($id);
        $requestData = $serializer->deserialize($request->getContent(), User::class, $mime, [AbstractNormalizer::OBJECT_TO_POPULATE => $user]);
        $entityManager = $managerRegistry->getManager();

        //On test pour savoir s'il s'agit du même Id pour afficher
        if (!($user->getId() === $this->getUser()->getId())) {

            //L'admin peut lui modifier ce qu'il veut, par contre
            if ($this->isGranted('ROLE_ADMIN')) {

                $entityManager->flush();

                return new Response(

                    $serializer->serialize($user, $mime),
                    Response::HTTP_OK,
                    ["Content-Type" => ("xml" === $mime ? "application/xml" : "application/json")]

                );

            }

        } else {

            $entityManager->flush();

            return new Response(

                $serializer->serialize($user, $mime),
                Response::HTTP_OK,
                ["Content-Type" => ("xml" === $mime ? "application/xml" : "application/json")]

            );

        }

        return new Response(

            $serializer->serialize(["message" => "Vous n'êtes pas autoriser à faire ça!"], $mime),
            Response::HTTP_FORBIDDEN,
            ["Content-Type" => ("xml" === $mime ? "application/xml" : "application/json")]

        );

    }

}
