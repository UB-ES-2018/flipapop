<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\Type\UserType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use function var_dump;


class UserController extends AbstractController
{
    /**
     * Matches /register exactly
     *
     * @Route("/register", name="register_user", options={"expose"=true})
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder){

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $em = $this->getDoctrine()->getManager();
        // como es la misma funcion la que genera el formulario y la que valida los datos hay que hacer aqui el handleRequest()
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $session = $this->get('session');
            $session->set('user', $user);

            return $this->redirectToRoute('landing_page');
        }


        return $this->render('modals/register.html.twig', array('form' => $form->createView()));


    }

    /**
     * @param Request $request
     * @Route("/logout", name="logout_user")
     */
    public function logout(Request $request){
        $this->get('session')->remove('user');
        return $this->redirectToRoute('landing_page');
    }





}

