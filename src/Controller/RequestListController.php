<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RequestEntityRepository;

class RequestListController extends AbstractController
{
    #[Route('/request-list/{page}', name: 'app_request_list', requirements: ["page" => "\d+"])]
    public function index(RequestEntityRepository $request_repository, $page = 1): Response
    {
        $show_count = 3;
        $count = $request_repository->count([]);
        $requests = $request_repository->findAllPage($show_count, $page);

        $pages = ceil($count / $show_count);
        
        return $this->render('request_list/index.html.twig', [
            'controller_name' => $count,
            'requests' => $requests,
            'pages' => $pages,
            'current_page' => $page
        ]);
    }
}
