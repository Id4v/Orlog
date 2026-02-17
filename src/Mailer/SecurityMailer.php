<?php

declare(strict_types=1);

namespace App\Mailer;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

readonly class SecurityMailer
{
    public function __construct(protected MailerInterface $mailer)
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendLoginLink(string $loginLink, User $user): void
    {
        $message = new TemplatedEmail();
        $message
            ->from('no-reply@idav.dev')
            ->to($user->getEmail())
            ->subject('Votre lien de connexion Orlog')
            ->htmlTemplate('email/security/login_link.html.twig')
            ->context([
                'loginLink' => $loginLink,
                'user' => $user,
                'expirationMinutes' => 10,
            ]);

        $this->mailer->send($message);
    }
}
