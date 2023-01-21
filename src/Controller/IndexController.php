<?php

namespace App\Controller;

use App\Service\Menu;
use App\Service\MenuCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'index')]
    #[Menu(title: 'Главная', order: 0, role:"PUBLIC_ACCESS")]
    public function index(): Response
    {
        $m = new MenuCreator;
        return $this->render('index.html.twig', [
            'controller_name' => 'IndexController',
            'menu' => $m->getMenu('index'),
        ]);
    }
}
