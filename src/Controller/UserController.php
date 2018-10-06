<?php

namespace App\Controller;


use App\Entity\User;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * Matches /register exactly
     *
     * @Route("/register", name="register_user")
     */
    public function RegisterUser(Request $request, UserPasswordEncoderInterface $passwordEncoder){
        $user = new User();
        $form = $this->createForm(User::class, $user);
        $em = $this->getDoctrine()->getManager();
        // como es la misma funcion la que genera el formulario y la que valida los datos hay que hacer aqui el handleRequest()
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();


            // Descomentar cuando tengamos pantalla principal a donde redirigir
            //return $this->redirectToRoute('replace_with_some_route');
        }

        // Descomentar una vez hecho el merge con la story del frontend
        /*return $this->render(
            'registration/register.html.twig',
            array('form' => $form->createView())
        );
        */



    }
}