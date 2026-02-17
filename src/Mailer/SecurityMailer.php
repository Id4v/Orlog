<?php

namespace App\Mailer;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

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
        $message = new Email();
        $message->from('no-reply@idav.dev');
        $message->to($user->getEmail());
        $message->subject('Login Link');
        $message->text(sprintf("Please find your login link: %s", $loginLink));
        $this->mailer->send($message);
    }
}
