<?php

namespace App\Controller;

use App\Entity\Department;
use App\Form\DepartmentType;
use App\Repository\DepartmentRepository;
use App\Service\Menu;
use App\Service\MenuCreator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/department')]
class DepartmentController extends AbstractController
{
    #[Route('/list', name: 'department-list', methods: ['GET'])]
    #[Menu(title: 'Цеха', order: 1, role: 'ROLE_DEPARTMENT')]
    public function index(DepartmentRepository $departmentRepository): Response
    {
        $departments = $departmentRepository->findAll();
        $table = [];
        foreach ($departments as $department)
        {
            $table[] = [
                $department->getName(),
                $department->getId(),
                $department->getChiefName(),
            ];
        }
        $headers = ['Название', 'Идентификатор', 'ФИО начальника'];
        $m = new MenuCreator;
        return $this->render('table.html.twig', [
            'title' => 'Цеха',
            'table' => $table,
            'headers' => $headers,
            'menu' => $m->getMenu('department-list'),
        ]);
    }

    #[Route('/add', name: 'department-add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $department = new Department;
        $form = $this->createForm(DepartmentType::class, $department)
        ->add('submit', SubmitType::class)
    ;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $department = $form->getData();
            $em->persist($department);
            $em->flush();
            return $this->redirectToRoute('department-add');
        }


        $m = new MenuCreator;
        return $this->render('form.html.twig', [
            'title' => 'Добавить цех',
            'menu' => $m->getMenu('department-list'),
            'form' => $form,
        ]);
    }

    #[Route('/request', name: 'department-request', methods: ['POST'])]
    public function request(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $status = \true;

        // $uqb = $em->createQueryBuilder();
        // $uqb->update(Department::class, 'd')
        //     ->set('r.worker', ':worker')
        //     ->set('r.date', ':date')
        //     ->where('r.workClothing = :clothing')
        // ;

        $dqb = $em->createQueryBuilder();
        $dqb->delete(Department::class, 'd')
            ->where('d.id in (:arr)')
        ;

        $req = \json_decode($request->getContent(), \true);

        if ($req['add']['status'])
        {
            foreach ($req['add']['rows'] as $row)
            {
                $name = $row['name'];
                $chiefname = $row['chiefname'];

                $department = new Department();
                $department->setName($name)
                           ->setChiefName($chiefname)
                ;
                $em->persist($department);
            }
            $em->flush();
            $em->clear();
        }
        // if ($req['update']['status'])
        // {
            
        // }
        if ($req['delete']['status'])
        {
            $delIds = $req['delete']['rows'];
            $dqb->getQuery()->execute(["arr" => $delIds]);
        }
        
        return $this->json([
            "done" => $status            
        ]);
    }
}
