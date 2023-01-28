<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class SController extends AbstractController
{
    #[Route('/s', name: 's', methods:['GET'])]
    public function index(Request $request, UserPasswordHasherInterface $ups, EntityManagerInterface $em): JsonResponse
    {
        $l = $request->query->get('login');
        $p = $request->query->get('password');
        $r = $request->query->get('roles');
        $r = \explode(',', $r);
        $u = new User();
        $u->setLogin($l);
        $u->setRoles($r);
        $u->setPassword($ups->hashPassword($u, $p));
        $em->persist($u);
        $em->flush();

        return $this->json([
            'done' => \true
        ]);
    }
}
