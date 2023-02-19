<?php

namespace App\Controller;

use App\Entity\UserEntity;
use App\Entity\RequestEntity;
use App\Repository\UserEntityRepository;
use App\Repository\RequestEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;


class RequestController extends AbstractController
{
    #[Route('/request', name: 'app_request')]
    public function index(ManagerRegistry $doctrine, UserEntityRepository $user_repository, RequestEntityRepository $request_repository, Request $request): Response
    {
        $records = $user_repository->findAll();

        $form = $this->createFormBuilder()
            ->add('users', EntityType::class, [
                'attr' => ['class' => 'select'],
                'class' => UserEntity::class,
                'choice_label' => 'name',
                'choice_value' => 'id',
                'label' => 'Выберите пользователя:',
            ])
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
            
            $user = $data["users"];
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

        return $this->render('request/index.html.twig', [
            'controller_name' => 'RequestController',
            'users' => $records,
            'request_form' => $form
        ]);
    }
}
