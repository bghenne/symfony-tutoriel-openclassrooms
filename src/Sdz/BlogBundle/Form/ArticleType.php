<?php

namespace Sdz\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', 'date')
            ->add('titre', 'text')
            ->add('auteur', 'text')
            ->add('publication', 'checkbox', array('required' => false))
            ->add('contenu', 'ckeditor')
            ->add('image', new ImageType())
            ->add('categories', 'entity', array(
                'class' => 'SdzBlogBundle:Categorie',
                'property' => 'nom',
                'multiple' => true,
                'expanded' => false
            ))
        ;

        $factory = $builder->getFormFactory();

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($factory) {
           $article = $event->getData();

            if (is_null($article)) {
                return;
            }

            if (false === $article->getPublication()) {
                $event->getForm()->add(
                    $factory->createNamed('publication', 'checkbox', null, array(
                        'required' => false
                    ))
                );
            } else {
                $event->getForm()->remove('publication');
            }
        });
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sdz\BlogBundle\Entity\Article'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sdz_blogbundle_article';
    }
}
