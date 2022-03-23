<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\Token\SwitchUserToken;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;


class SecurityController extends AbstractController {

  
     /**
   * @param UserInterface $user
   * @param JWTTokenManagerInterface $JWTManager
   * @return JsonResponse
   */
  public function getTokenUser(UserInterface $user, JWTTokenManagerInterface $JWTManager)
  {
   return new JsonResponse(['token' => $JWTManager->create($user)]);
  }
   
}