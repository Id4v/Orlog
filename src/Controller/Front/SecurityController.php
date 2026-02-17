<?php

declare(strict_types=1);

namespace App\Controller\Front;

use App\Mailer\SecurityMailer;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\RateLimiter\RateLimiterFactoryInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;

class SecurityController extends AbstractController
{
    public function __construct(
        private readonly SecurityMailer $securityMailer,
        private readonly RateLimiterFactoryInterface $loginResendLimiter
    ) {
    }

    #[Route('/login', name: 'login')]
    public function login(
        Request $request,
        UserRepository $userRepository,
        LoginLinkHandlerInterface $loginLinkHandler
    ): Response {
        if ($request->isMethod('POST')) {
            $email = $request->getPayload()->get('email');
            $clientIp = $request->getClientIp() ?? 'unknown';
            $limiterKey = hash('sha256', $clientIp . '|' . $email);

            // Check rate limit
            $limiter = $this->loginResendLimiter->create($limiterKey);
            if (!$limiter->consume()->isAccepted()) {
                $this->addFlash('error', 'Trop de tentatives. Veuillez patienter.');

                return $this->redirectToRoute('app_login');
            }

            $user = $userRepository->findOneBy(['email' => $email]);
            $this->addFlash('success', 'Si cette adresse est associée à un compte, un lien de connexion a été envoyé.');

            // Always show generic message for security
            if (null !== $user) {
                try {
                    $loginLinkDetails = $loginLinkHandler->createLoginLink($user);
                    $loginLink = $loginLinkDetails->getUrl();
                    $this->securityMailer->sendLoginLink($loginLink, $user);
                    $request->getSession()->set('login_email', $email);

                    return $this->redirectToRoute('app_check_email');
                } catch (\Exception $e) {
                    // Log error but don't reveal to user
                    error_log('Failed to send login link: ' . $e->getMessage());
                }
            }
        }

        return $this->render('front/security/login.html.twig');
    }

    #[Route('/check-email', name: 'check_email')]
    public function checkEmail(Request $request): Response
    {
        $email = $request->getSession()->get('login_email');

        if (null === $email) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('front/security/check_email.html.twig', [
            'email' => $email,
        ]);
    }

    #[Route('/resend-email', name: 'resend_email', methods: ['POST'])]
    public function resendEmail(
        Request $request,
        UserRepository $userRepository,
        LoginLinkHandlerInterface $loginLinkHandler,
    ): Response {
        $email = $request->getSession()->get('login_email');

        if (null === $email) {
            return $this->redirectToRoute('app_login');
        }

        $clientIp = $request->getClientIp() ?? 'unknown';
        $limiterKey = hash('sha256', $clientIp . '|' . $email);
        $limiter = $this->loginResendLimiter->create($limiterKey);

        if (!$limiter->consume()->isAccepted()) {
            $this->addFlash('error', 'Trop de tentatives. Veuillez patienter 10 minutes.');

            return $this->redirectToRoute('app_login');
        }

        $user = $userRepository->findOneBy(['email' => $email]);

        if (null !== $user) {
            try {
                $loginLinkDetails = $loginLinkHandler->createLoginLink($user);
                $loginLink = $loginLinkDetails->getUrl();
                $this->securityMailer->sendLoginLink($loginLink, $user);
                $this->addFlash('success', 'Un nouveau lien a été envoyé.');

                return $this->redirectToRoute('app_check_email');
            } catch (\Exception $e) {
                error_log('Failed to resend login link: ' . $e->getMessage());
                $this->addFlash('error', 'Une erreur est survenue. Veuillez réessayer.');
            }
        } else {
            $this->addFlash('success', 'Si cette adresse est associée à un compte, un lien de connexion a été envoyé.');
        }

        return $this->redirectToRoute('app_check_email');
    }

    #[Route('/login_check', name: 'login_check')]
    public function check(): never
    {
        throw new \LogicException('This code should never be reached');
    }
}
