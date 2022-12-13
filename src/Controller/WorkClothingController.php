<?php

namespace App\Controller;

use App\Service\Menu;
use App\Service\MenuCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WorkClothingController extends AbstractController
{
    #[Route(path: '/clothing', name: 'clothing')]
    #[Menu(title: 'Спецодежда')]
    public function index(): Response
    {
        $arr = [
            [1, 2, 3, 4, 5],
            [6, 7, 8, 9, 10],
        ];
        $m = new MenuCreator;
        $headers = ['A', 'B', 'C', 'D', 'E'];
        return $this->render('table.html.twig', [
            'title' => 'Спецодежда',
            'items' => $arr,
            'headers' => $headers,
            'menu' => $m->getMenu('clothing'),
        ]);
    }
}