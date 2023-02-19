<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\UserEntity;
use App\Entity\RequestEntity;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_home');
    }

    #[Route('/user/create/{name}', name: 'app_create_user', requirements: ["name" => "[a-zA-Z0-9]+"])]
    public function create_user(string $name, ManagerRegistry $doctrine): Response
    {
        $user = new UserEntity();
        $user->setName($name);

        $entityManager = $doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        
        return $this->redirectToRoute('app_home');
    }
    
    #[Route('/user/{name}', name: 'app_get_user', requirements: ["name" => "[a-zA-Z0-9]+"])]
    public function get_user(string $name, ManagerRegistry $doctrine, Request $request): Response
    {
        $entityManager = $doctrine->getManager();

        $user = $doctrine->getRepository(UserEntity::class)->findByName($name);
        
        if (!!$user) {
            $form = $this->createFormBuilder()
                ->add('contact', EmailType::class, [
                    'attr' => ['class' => 'input'],
                    'label' => 'Почта:',
                ])
                ->add('message', TextareaType::class, [
                    'attr' => ['class' => 'input'],
                    'label' => 'Сообщение заявки:',
                ])
                ->add('save', SubmitType::class, ['label' => 'Отправить'])
                ->getForm();

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();
                
                $contact = $data["contact"];
                $message = $data["message"];

                $request = new RequestEntity();
                $request->setUser($user);
                $request->setMessage($message);
                $request->setContact($contact);

                $entityManager = $doctrine->getManager();
                $entityManager->persist($request);
                $entityManager->flush();

                return $this->redirectToRoute('app_request_list');
            }

            return $this->render('user/index.html.twig', [
                'controller_name' => 'UserController',
                'user_name' => $user->getName(),
                'form' => $form
            ]);
        } else {
            return $this->render('user/index.html.twig', [
                'name' => $name,
            ]);
        }
    }
}
