<?php

namespace App\Controller;

use App\Repository\ReceivingRepository;
use App\Service\Menu;
use App\Service\MenuCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/receiving')]
class ReceivingController extends AbstractController
{
    #[Route('/list', name: 'receiving-list', methods: ['GET'])]
    #[Menu(title: 'Получения')]
    public function index(ReceivingRepository $receivingRepository): Response
    {
        $receivings = $receivingRepository->findAll();
        $table = [];
        foreach ($receivings as $receiving)
        {
            $table[] = [
                $receiving->getWorker()->getName(),
                $receiving->getWorkClothing()->getType(),
                $receiving->getDate()->format('d/m/Y')
            ];
        }
        $headers = ['ФИО работника', 'Вид спецодежды', 'Дата'];
        $m = new MenuCreator;
        return $this->render('table.html.twig', [
            'title' => 'Получения',
            'table' => $table,
            'headers' => $headers,
            'menu' => $m->getMenu('receiving-list'),
        ]);
    }
}
