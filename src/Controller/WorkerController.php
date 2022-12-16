<?php

namespace App\Controller;

use App\Repository\WorkerRepository;
use App\Service\Menu;
use App\Service\MenuCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WorkerController extends AbstractController
{
    #[Route('/workers', name: 'workers')]
    #[Menu(title: 'Работники')]
    public function table(WorkerRepository $workerRepository): Response
    {
        $workers = $workerRepository->findAll();
        $table = [];
        foreach ($workers as $worker)
        {
            $table[] = [
                $worker->getName(),
                $worker->getDepartment()->getName(),
                $worker->getPost()->getName(),
            ];
        }
        $headers = ['ФИО', 'Цех', 'Должность'];
        $m = new MenuCreator;
        return $this->render('table.html.twig', [
            'title' => 'Работники',
            'table' => $table,
            'headers' => $headers,
            'menu' => $m->getMenu('workers'),
        ]);
    }
}
