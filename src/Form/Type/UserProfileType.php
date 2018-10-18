<?php
/**
 * Created by PhpStorm.
 * User: carlesmagallon
 * Date: 18/10/2018
 * Time: 23:08
 */

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
 * User: cmtea
 * Date: 17/10/2018
 * Time: 19:35
 */


class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('imageFile', VichImageType::class, array(
            'required' => false,
        ));


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }

}