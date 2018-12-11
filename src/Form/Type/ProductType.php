<?php
/**
 * Created by PhpStorm.
 * User: Jose
 * Date: 12/10/2018
 * Time: 16:22
 */

namespace App\Form\Type;


use App\Entity\Category;
use App\Entity\Product;
use Doctrine\DBAL\Types\FloatType;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array(
            'required' => true,
            'attr' => array(
                'placeholder' => 'GummyBear'
            ),
            'constraints' => new NotBlank(['message' => 'Este campo es obligatorio'])
        ));

        $builder->add('price', IntegerType::class, array(
            'required' => true,
            'attr' => array(
                'placeholder' => '200€'
            ),
            'constraints' => new NotBlank(['message' => 'Este campo es obligatorio'])
        ));

        $builder->add('description', TextType::class, array(
            'required' => true,
            'attr' => array(
                'placeholder' => 'A giant GummyBear'
            ),
            'constraints' => new NotBlank(['message' => 'Este campo es obligatorio'])

        ));

        $builder->add('visibility', ChoiceType::class, array(
            'required' => true,
            'empty_data' => false,
            'choices' => $this->getVisibilidad(),
            'constraints' => new NotBlank(['message' => 'Este campo es obligatorio'])

        ));

        $builder->add('imageFile', VichImageType::class, array(
            'required' => false,
        ));

        $builder->add('category', EntityType::class, array(
            'required' => true,
            'empty_data' => false,
            'class' => Category::class,
            'constraints' => new NotBlank(['message' => 'Este campo es obligatorio'])
        ));


    }

    private function getVisibilidad(){
        return array(
            "Todos" => Product::VISIBLE_ALL,
            "Logueados" => Product::VISIBLE_LOGGED,
            "Solo yo" => Product::VISIBLE_ME,
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Product::class,
        ));
    }

}