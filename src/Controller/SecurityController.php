<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

use Symfony\Component\Security\Core\Authentication\Token\SwitchUserToken;
use Symfony\Component\Security\Core\Security;


class SecurityController extends AbstractController {

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

   
    #[Route('/api/login_check', name: 'api_login')]
     public function api_login(): JsonResponse
     {
        $user = $this->getUser('email', 'password');
        $token = $this->security->getToken();
        if ($token instanceof SwitchUserToken) {
            $user = $token->getOriginalToken()->getUser();
        }
        return new JsonResponse(
           [
           'email'=> $user->getUserIdentifier(),
           'roles'=> $user->getRoles(),
       ]);
        //  $user = $this->getUser();
        //  return new JsonResponse(
        //      [
        //      'email'=> $user->getUserIdentifier(),
        //      'roles'=> $user->getRoles(),
        //  ]);
       // return new JsonResponse();
     }
    }