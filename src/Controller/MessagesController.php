<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class MessagesController extends AbstractController
{
    /**
     * @Route("/contact", name="app_contact")
     */
    public function new(Request $request): Response
    {
       $form = $this->createFormBuilder()
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => 'John Doe'
                ],
                'constraints' => [
                    new NotBlank,
                    new Length(['min' => 1])
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'john.doe@gmail.com'
                ],
                'constraints' => [
                    new NotBlank,
                    new Email,
                ]
            ])
            ->add('message', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Please, enter your message...'
                ],
                'constraints' => [
                    new NotBlank,
                    new Length(['min' => 10])
                ]
            ])
            ->getForm()
       ;

       $form->handleRequest($request);
       if ($form->isSubmitted() && $form->isValid()) {
           $formData = $form->getData();
           dd(sprintf('Incoming new message %s <%s> ...', $formData['name'], $formData['email']));

           $this->addFlash('success', 'Message sent! We\'ll get you back to you very soon!');
           return $this->redirectToRoute('app_home');
       }

        return $this->render('messages/new.html.twig', [
            'formulaire' => $form->createView()
        ]);
    }
}
