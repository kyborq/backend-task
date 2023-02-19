<?php

namespace App\Controller;

use App\Entity\UserEntity;
use App\Repository\UserEntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(UserEntityRepository $user_repository): Response
    {
        $records = $user_repository->findAll();
        
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'users' => $records
        ]);
    }
}
