<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class InzidentziacategoryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categories', EntityType::class, array(
                'class' => 'AppBundle:Category',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('b');
                },
                'multiple' => true,
                'required' => true,
                'placeholder' => 'Aukeratu Kategoria'
            ))
//            ->add('categories', EntityType::class, array(
//                'class' => 'AppBundle:Category',
//                'query_builder' => function (EntityRepository $er) {
//                    return $er->createQueryBuilder('b')
//                        ->where('b.parent is NULL');
//                },
//                'multiple' => true,
//                'required' => true,
//                'placeholder' => 'Aukeratu Kategoria'
//            ))

            ->add('userid')            
            ->add('teknikoa')
            ->add('azalpena',CKEditorType::class)
            ->add('konpondua')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Inzidentzia'
        ));
    }
}
