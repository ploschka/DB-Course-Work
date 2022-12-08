<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WorkerController extends AbstractController
{
    #[Route('/workers', name: 'workers')]
    public function index(): Response
    {
        $navbar = [
            [
                'text' => 'Работники',
                'link' => 'workers',
                'curr' => \true,
            ],
            [
                'text' => 'Спецодежда',
                'link' => '',
                'curr' => \false,
            ],
            [
                'text' => 'Цеха',
                'link' => '',
                'curr' => \false,
            ],
            [
                'text' => 'Получения',
                'link' => '',
                'curr' => \false,
            ]
        ];
        $arr = [
            [1, 2, 3, 4, 5],
            [6, 7, 8, 9, 10],
        ];
        $headers = ['A', 'B', 'C', 'D', 'E'];
        return $this->render('table.html.twig', [
            'title' => 'Работники',
            'items' => $arr,
            'headers' => $headers,
            'navbar' => $navbar,
        ]);
    }
}
