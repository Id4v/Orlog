<?php

declare(strict_types=1);

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/', name: 'front_home')]
class HomeController extends AbstractController
{
    public function __invoke(Request $request)
    {
        return $this->render('front/home.html.twig');
    }
}
