<?php

namespace App\Controller;

use App\Service\Menu;
use App\Service\MenuCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReceivingController extends AbstractController
{
    #[Route(path: '/receivings', name: 'receivings')]
    #[Menu(title: 'Получения')]
    public function index(): Response
    {
        $arr = [
            [1, 2, 3, 4, 5],
            [6, 7, 8, 9, 10],
        ];
        $m = new MenuCreator;
        $headers = ['A', 'B', 'C', 'D', 'E'];
        return $this->render('table.html.twig', [
            'title' => 'Получения',
            'items' => $arr,
            'headers' => $headers,
            'menu' => $m->getMenu('receivings'),
        ]);
    }
}
