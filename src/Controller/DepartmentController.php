<?php

namespace App\Controller;

use App\Repository\DepartmentRepository;
use App\Service\Menu;
use App\Service\MenuCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/department')]
class DepartmentController extends AbstractController
{
    #[Route('/list', name: 'department-list', methods: ['GET'])]
    #[Menu(title: 'Цеха')]
    public function index(DepartmentRepository $departmentRepository): Response
    {
        $departments = $departmentRepository->findAll();
        $table = [];
        foreach ($departments as $department)
        {
            $table[] = [
                $department->getName(),
                $department->getChiefName(),
            ];
        }
        $headers = ['Название', 'ФИО начальника'];
        $m = new MenuCreator;
        return $this->render('table.html.twig', [
            'title' => 'Цеха',
            'table' => $table,
            'headers' => $headers,
            'menu' => $m->getMenu('department-list'),
        ]);
    }

    #[Route('/request', name: 'department-request', methods: ['POST'])]
    public function request(Request $request): JsonResponse
    {
        return $this->json([
            
        ]);
    }
}
