<?php

namespace App\Controller;


use App\Entity\User;
use http\Env\Request;

class UserController
{
    /**
     * Matches /register exactly
     *
     * @Route("/register", name="register_user")
     */
    public function RegisterUser(Request $request){
        $user = new User();

        $form = $this->createForm(User::class, $user);
    }
}