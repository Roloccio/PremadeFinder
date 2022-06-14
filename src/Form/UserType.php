<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Curso;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\CallbackTransformer;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('username')
        ->add('discord')
        // ->add('DNI')
        ->add('Password', PasswordType::class, [
            // instead of being set onto the object directly,
            // this is read and encoded in the controller
            'mapped' => false,
            'attr' => ['autocomplete' => 'new-password'],
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter a password',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Tu contraseña debe ser de al menos {{ limit }} caracteres',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ]),
            ],
        ])
        ->add('email', EmailType::class)
        // ->add('roles', ChoiceType::class, [
        //     'choices'  => [
        //         'Alumno' => 'ALUMNO',
        //         'Docente' => 'DOCENTE',
        //     ],
        // ])
        // ->add('curso', EntityType::class, array(
        //     'class' => 'App\Entity\Curso',
        //     'choice_label' => 'nombre'
        // ));
    ;
        
        
    ;


    // $builder->get('roles')
    // ->addModelTransformer(new CallbackTransformer(
    //     function ($rolesArray) {
    //          // transform the array to a string
    //          return count($rolesArray)? $rolesArray[0]: null;
    //     },
    //     function ($rolesString) {
    //          // transform the string back to an array
    //          return [$rolesString];
    //     }
    // ));


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'allow_extra_fields' => true,  
        ]);
    }
}


// ->add('plainPassword', PasswordType::class, [
//     // instead of being set onto the object directly,
//     // this is read and encoded in the controller
//     'mapped' => false,
//     'attr' => ['autocomplete' => 'new-password'],
//     'constraints' => [
//         new NotBlank([
//             'message' => 'Please enter a password',
//         ]),
//         new Length([
//             'min' => 6,
//             'minMessage' => 'Your password should be at least {{ limit }} characters',
//             // max length allowed by Symfony for security reasons
//             'max' => 4096,
//         ]),
//     ],
// ])