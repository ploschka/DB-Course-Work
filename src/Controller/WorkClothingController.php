<?php

namespace App\Controller;

use App\Repository\WorkClothingRepository;
use App\Service\Menu;
use App\Service\MenuCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/clothing')]
class WorkClothingController extends AbstractController
{
    #[Route('/list', name: 'clothing-list', methods: ['GET'])]
    #[Menu(title: 'Спецодежда')]
    public function index(WorkClothingRepository $workClothingRepository): Response
    {
        $clothing = $workClothingRepository->findAll();
        $table = [];
        foreach ($clothing as $item)
        {
            $table[] = [
                $item->getId(),
                $item->getType(),
                $item->getPrice(),
                $item->getWearTime()
            ];
        }
        $headers = ['Идентификатор', 'Вид', 'Цена', 'Время носки'];
        $m = new MenuCreator;
        return $this->render('table.html.twig', [
            'title' => 'Спецодежда',
            'table' => $table,
            'headers' => $headers,
            'menu' => $m->getMenu('clothing-list'),
        ]);
    }

    #[Route('/request', name: 'clothing-request', methods: ['POST'])]
    public function request(Request $request): JsonResponse
    {
        return $this->json([
            
        ]);
    }
}
