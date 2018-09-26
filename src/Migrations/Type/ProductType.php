<?php

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Created by PhpStorm.
 * User: Jose
 * Date: 26/9/18
 * Time: 16:36
 */



class ProductType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', [
            'required' => true,
            'placeholder' => 'A beautiful name please'
        ]);
    }

}