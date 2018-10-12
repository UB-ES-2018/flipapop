<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\Type\UserType;
use App\Security\LoginFormAuthenticator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use function var_dump;


class UserController extends AbstractController
{
    /**
     * Matches /register exactly
     *
     * @Route("/register", name="register_user", options={"expose"=true})
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $authenticatorHandler, LoginFormAuthenticator $formAuthenticator){

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

            return $authenticatorHandler->authenticateUserAndHandleSuccess($user, $request, $formAuthenticator,'main' );

        }


        return $this->render('security/register.html.twig', array('form' => $form->createView()));


    }

    /**
     * @param Request $request
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @param Request $request
     * @Route("/logout", name="logout")
     */
    public function logout(Request $request){
        $this->get('session')->remove('user');
        return $this->redirectToRoute('landing_page');
    }

    /**
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @Route("/profile", name="user_profile")
     */
    public function profile(Request $request){

        return $this->render('profile.html.twig', array('user' => $this->getUser()));
    }




}

