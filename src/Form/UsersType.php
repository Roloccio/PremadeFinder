<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class UsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('users', FileType::class, [
            'label' => 'CSV Usuarios',

            // unmapped means that this field is not associated to any entity property
            'mapped' => false,

            // make it optional so you don't have to re-upload the PDF file
            // every time you edit the Product details
            'required' => true,

            // unmapped fields can't define their validation using annotations
            // in the associated entity, so you can use the PHP constraint classes
            'constraints' => [
                new File([
                    'maxSize' => '10024k',
                    'mimeTypes' => [
                        'text/plain', 
                        'application/vnd.ms-excel',
                        'application/vnd.msexcel',
                        'text/csv',
                        'application/csv', 
                        'text/comma-separated-values',          
                        'application/octet-stream', 
                        'text/tab-separated-values',
                        'text/tsv',
                        'application/x-csv'
                    ],
                    'mimeTypesMessage' => 'Por favor, sube un archivo csv con usuarios',
                ])
            ],
                
        ])
    ;
        
        
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'allow_extra_fields' => true,  
        ]);
    }
}
