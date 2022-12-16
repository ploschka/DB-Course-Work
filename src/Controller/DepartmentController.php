<?php

namespace App\Controller;

use App\Repository\DepartmentRepository;
use App\Service\Menu;
use App\Service\MenuCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DepartmentController extends AbstractController
{
    #[Route('/department', name: 'department')]
    #[Menu(title: 'Цеха')]
    public function index(DepartmentRepository $departmentRepository): Response
    {
        $departments = $departmentRepository->findAll();
        $table = [];
        foreach ($departments as $department)
        {
            $table[] = [
                $department->getName(),
                $department->getChief()->getName(),
            ];
        }
        $headers = ['Название', 'ФИО начальника'];
        $m = new MenuCreator;
        return $this->render('table.html.twig', [
            'title' => 'Цеха',
            'table' => $table,
            'headers' => $headers,
            'menu' => $m->getMenu('department'),
        ]);
    }
}
