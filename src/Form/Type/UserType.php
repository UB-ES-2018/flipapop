<?php

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Created by PhpStorm.
 * User: Jose
 * Date: 03/10/2018
 * Time: 17:20
 */

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array(
            'required' => true,
            'empty_data' => 'Ej: Pablo',
            'constraints' => new NotBlank(['message' => 'Este campo es obligatorio'])
        ));

        $builder->add('surname', TextType::class, array(
            'required' => true,
            'empty_data' => 'Ej: Palotes',
            'constraints' => new NotBlank(['message' => 'Este campo es obligatorio'])
        ));

        $builder->add('email', TextType::class, array(
            'required' => true,
            'empty_data' => 'Ej: pablo.palotes@somemail.com',
            'constraints' => new Email(['message' => 'Este campo debe ser un correo electronico'])

        ));

        $builder->add('plainPassword', RepeatedType::class, array(
            'type' => PasswordType::class,
            'required' => true,
            'first_options'  => array('label' => 'Password'),
            'second_options' => array('label' => 'Repeat Password'),
        ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}