<?php

namespace App\Controller;

use App\Entity\Department;
use App\Form\DepartmentType;
use App\Repository\DepartmentRepository;
use App\Service\Menu;
use App\Service\MenuCreator;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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
                [$department->getName(), ['data-tag' => 'name']],
                [$department->getId(), ['data-tag' => 'id']],
                [$department->getChiefName(), ['data-tag' => 'chief_name']],
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
            ->add('submit', SubmitType::class, ['label' => 'Отправить']);

        $err = null;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em->beginTransaction();
            try
            {
                $department = $form->getData();
                $em->persist($department);
                $em->flush();
                $em->commit();
                return $this->redirectToRoute('department-add');
            }
            catch (Exception $e)
            {
                $em->rollback();
                $err = $e->getMessage();
            }
        }

        $m = new MenuCreator;
        return $this->render('form.html.twig', [
            'title' => 'Добавить цех',
            'menu' => $m->getMenu('department-list'),
            'form' => $form,
            'error' => $err,
        ]);
    }

    #[Route('/update', name: 'department-update')]
    public function update(Request $request, EntityManagerInterface $em): Response
    {
        $options = [
            'id_field' => true,
            'id_value' => $request->query->get('id'),
            'name_value' => $request->query->get('name'),
            'chief_name_value' => $request->query->get('chief_name'),
        ];
        $form = $this->createForm(DepartmentType::class, options: $options)
            ->add('submit', SubmitType::class, ['label' => 'Отправить']);

        $err = null;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em->beginTransaction();
            try
            {
                $department = $em->find(Department::class, $form->get('id')->getData());
                if (\is_null($department))
                {
                    $em->rollback();
                    $err = 'Цеха с таким идентификатором не существует';
                }
                else
                {
                    $department->setName($form->get('name')->getData());
                    $department->setChiefName($form->get('chief_name')->getData());
                    $em->persist($department);
                    $em->flush();
                    $em->commit();
                    return $this->redirectToRoute('department-list');
                }
            }
            catch (Exception $e)
            {
                $em->rollback();
                $err = $e->getMessage();
            }
        }

        $m = new MenuCreator;
        return $this->render('form.html.twig', [
            'title' => 'Изменить цех',
            'menu' => $m->getMenu('department-list'),
            'form' => $form,
            'error' => $err,
        ]);
    }

    #[Route('/delete', name: 'department-delete', methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $dqb = $em->createQueryBuilder();
        $dqb->delete(Department::class, 'd')
            ->where('d.id in (:arr)');

        $req = \json_decode($request->getContent(), \true);
        $status = \true;
        $error = null;

        $em->beginTransaction();
        try
        {
            $delIds = $req;
            $dqb->getQuery()->execute(["arr" => $delIds]);
            $em->commit();
        }
        catch (Exception $e)
        {
            $error = $e->getCode();
            $status = \false;
            $em->rollback();
        }

        return $this->json([
            'done' => $status,
            'error' => $error,
        ]);
    }
}
