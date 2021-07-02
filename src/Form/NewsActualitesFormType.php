<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;



class NewsActualitesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                // Label du champ
                'label' => false,
            
                // Liste des contraintes du champ
                'constraints' => [
            
                    // Ne doit pas être vide
                    new NotBlank([
                        'message' => 'Merci de renseigner un titre' // Message d'erreur si cette contrainte n'est pas respectée
                    ]),
            
                    // Doit avoir une certaine taille
                    new Length([
                        'min' => 2, // Taille minimum autorisée
                        'minMessage' => 'Le titre doit contenir au moins {{ limit }} caractères',   // Message d'erreur si plus petit
                        'max' => 100,   // Taille maximum autorisée
                        'maxMessage' => 'Le titre doit contenir au maximum {{ limit }} caractères'  // Message d'erreur si plus grand
                    ]),
                ]
            ])
            
            ->add('contentShort', TextareaType::class, [
                // Label du champ
                'label' => false,
                
                // Liste des contraintes du champ
                'constraints' => [

                    // Ne doit pas être vide
                    new NotBlank([
                        'message' => 'Merci de faire un court résumé de l\'article' // Message d'erreur si cette contrainte n'est pas respectée
                        ]),

                        // Doit avoir une certaine taille
                        new Length([
                        'min' => 10, // Taille minimum autorisée
                        'minMessage' => 'Le résumé court doit contenir au moins {{ limit }} caractères',   // Message d'erreur si plus petit
                        'max' => 255,   // Taille maximum autorisée
                        'maxMessage' => 'Le résumé court doit contenir au maximum {{ limit }} caractères'  // Message d'erreur si plus grand
                    ]),
                ]
            ])
            ->add('contentLong', TextareaType::class, [
                // Label du champ
                'label' => false,
                
                // Liste des contraintes du champ
                'constraints' => [

                    // Ne doit pas être vide
                    new NotBlank([
                        'message' => 'Merci de faire un résumé de l\'article' // Message d'erreur si cette contrainte n'est pas respectée
                        ]),

                        // Doit avoir une certaine taille
                        new Length([
                        'min' => 10, // Taille minimum autorisée
                        'minMessage' => 'Le résumé  doit contenir au moins {{ limit }} caractères',   // Message d'erreur si plus petit
                        'max' => 10000,   // Taille maximum autorisée
                        'maxMessage' => 'Le résumé court doit contenir au maximum {{ limit }} caractères'  // Message d'erreur si plus grand
                    ]),
                ]
            ])
            ->add('image', FileType::class, [
                'label' => 'Sélectionnez une image ',
                'constraints' => [
                    new File([
                        // Taille maximum de 2 Mo comme dans php.ini
                        'maxSize' => '2M',

                        // jpg et png uniquement
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],

                        // Message d'erreur en cas de fichier au type non autorisé
                        'mimeTypesMessage' => 'L\'image doit être de type jpg ou png',

                        // Message en cas de fichier trop gros
                        'maxSizeMessage' => 'Fichier trop volumineux ({{ size }} {{ suffix }}). La taille maximum autorisée est {{ limit }}{{ suffix }}',
                    ]),
                    new NotBlank([
                        // Message en cas de formulaire envoyé sans fichier
                        'message' => 'Vous devez sélectionner un fichier',
                    ])
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-outline-primary col-12'
                ]
            ])
        
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
