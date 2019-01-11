<?php

namespace App\Form;

use App\Entity\CuentasCorrientes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CuentasCorrientesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre',null,array('attr' => array('class'=>'form-control')))
            ->add('apellido',null,array('attr' => array('class'=>'form-control')))
            ->add('dni',null,array('attr' => array('class'=>'form-control')))
            ->add('telefono',null,array('attr' => array('class'=>'form-control')))
            ->add('estado',null,array('attr' => array('class'=>'form-control')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CuentasCorrientes::class,
              'attr' => ['class' => 'form-group'],
        ]);
    }
}
