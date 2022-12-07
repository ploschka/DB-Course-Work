<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WorkerController extends AbstractController
{
    #[Route('/worker', name: 'app_worker')]
    public function index(): Response
    {
        $arr = [
            [1, 2, 3, 4, 5],
            [6, 7, 8, 9, 10],
        ];
        return $this->render('table.html.twig', [
            'title' => 'Работники',
            'text' => 'ЖОПА',
            'array' => $arr,
        ]);
    }
}
