<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/', name: 'dashboard')]
class DashboardController extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        return $this->render('dashboard/index.html.twig');
    }
}
