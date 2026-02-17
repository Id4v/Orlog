<?php

declare(strict_types=1);

namespace App\Controller\Front;

use App\Mailer\SecurityMailer;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;

class SecurityController extends AbstractController
{
    public function __construct(private readonly SecurityMailer $securityMailer)
    {
    }

    #[Route('/login', name: 'login')]
    public function index(Request $request, UserRepository $userRepository, LoginLinkHandlerInterface $loginLinkHandler): Response
    {
        if ($request->isMethod('POST')) {
            // load the user in some way (e.g. using the form input)
            $email = $request->getPayload()->get('email');
            $user = $userRepository->findOneBy(['email' => $email]);

            // create a login link for $user this returns an instance
            // of LoginLinkDetails
            $loginLinkDetails = $loginLinkHandler->createLoginLink($user);
            $loginLink = $loginLinkDetails->getUrl();

            $this->securityMailer->sendLoginLink($loginLink, $user);
        }


        return $this->render('front/security/login.html.twig');
    }

    #[Route('/login_check', name: 'login_check')]
    public function check(): never
    {
        throw new \LogicException('This code should never be reached');
    }
}
