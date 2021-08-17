<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class AnnonceType extends AbstractType
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre',TextType::class,[
                'required' => true,
            ])
            ->add('categorie',EntityType::class,[
                'class'=>Categorie::class,
                'required' => true,
            ])
            ->add('contenu',TextType::class,[
                'required' => true,
            ]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $annonce = $event->getData();
            $categorie_id = $annonce['categorie']??null;
            $categorie = $this->entityManager->getRepository(Categorie::class)->find($categorie_id);
            if(!$categorie && !$categorie instanceof Categorie)
                return;
            $form = $event->getForm();
            if ($categorie->getName() === Categorie::IMMOBILIER) {
                $form->add('surface', NumberType::class,[
                    'required' => true,
                    'constraints'=>[new NotBlank(null,null,true),new Positive()],
                ]);
                $form->add('prix', MoneyType::class,[
                    'required' => true,
                    'constraints'=>[new NotBlank(null,null,true),new Positive()],
                ]);
            }
            if ($categorie->getName() === Categorie::AUTOMOBILE) {
                $form->add('carburant', TextType::class,[
                    'required' => true,
                    'constraints'=>[new NotBlank(null,null,true)],
                ]);
                $form->add('marque', TextType::class,[
                    'required' => true,
                    'constraints'=>[new NotBlank()],
                ]);
                $form->add('modele', TextType::class,[
                    'required' => true,
                    'constraints'=>[new NotBlank()],
                ]);
                $form->add('prix', MoneyType::class,[
                    'required' => true,
                    'constraints'=>[new NotBlank(null,null,true),new Positive()],
                ]);
            }
            if ($categorie->getName() === Categorie::EMPLOI) {
                $form->add('contrat', TextType::class,[
                    'required' => true,
                    'constraints'=>[new NotBlank(null,null,true)],
                ]);
                $form->add('salaire', MoneyType::class,[
                    'required' => true,
                    'constraints'=>[new NotBlank(null,null,true),new Positive()],
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
            'csrf_protection'=>false
        ]);
    }
}
