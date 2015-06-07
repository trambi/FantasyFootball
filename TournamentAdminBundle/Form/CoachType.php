<?php
namespace FantasyFootball\TournamentAdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;

class CoachType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('teamName', 'text',array('label'=>'Nom de l\'équipe :'))
            ->add('name', 'text',array('label'=>'Nom :'))
            ->add('race', 'choice',array(
                'label' =>  'Race :',
                'choices'   => array(),
		'required'  => true))
            ->add('emailAddress', 'email',array('label'=>'Courriel :'))
            ->add('nafNumber', 'integer',array('label'=>'Numéro NAF :'))
            ->add('ready', 'checkbox',array(
                'label' => 'Coach prêt ?',
		'required' => false))
        ;
        $formModifier = function(FormInterface $form, Edition $edition) {
            $races = $edition->getAvailableRaces();

            $form->add('race', 'entity', array('choices' => $races));
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function(FormEvent $event) use ($formModifier) {
                // ce sera votre entité, c-a-d Coach
                $data = $event->getData();

                $formModifier($event->getForm(), $data->getEdition());
            }
        );

        $builder->get('edition')->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event) use ($formModifier) {
                // Il est important de récupérer ici $event->getForm()->getData(),
                // car $event->getData() vous renverra la données initiale (vide)
                $edition = $event->getForm()->getData();

                // puisque nous avons ajouté l'écouteur à l'enfant, il faudra passer
                // le parent aux fonctions de callback!
                $formModifier($event->getForm()->getParent(), $edition);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FantasyFootball\TournamentCoreBundle\Entity\Coach'
        ));
    }

    public function getName()
    {
        return 'coach';
    }
}
?>