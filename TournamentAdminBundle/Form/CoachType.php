<?php
namespace FantasyFootball\TournamentAdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;

use FantasyFootball\TournamentCoreBundle\Entity\RaceRepository;

class CoachType extends AbstractType
{
    public function __construct($editionId)
    {
        $this->editionId = $editionId;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $editionId = $this->editionId;
        $builder->add('teamName', 'text',array('label'=>'Nom de l\'équipe :'));
        $builder->add('name', 'text',array('label'=>'Nom :'));
        $builder->add('race', 'entity',
                array('label'=>'Race :',
                            'class'   => 'FantasyFootballTournamentCoreBundle:Race',
                            'property'  => 'frenchName',
                            'query_builder' => function(RaceRepository $rr) use ($editionId) {
                                return $rr->getQueryBuilderForRaceByEditionOrLesser($editionId);
                            }));
        $builder->add('email', 'email',array('label'=>'Courriel :'));
        $builder->add('nafNumber', 'integer',array('label'=>'Numéro NAF :'));
        $builder->add('ready', 'checkbox',array(
                'label' => 'Coach prêt ?',
		'required' => false))
        ;
        $builder->add('save','submit',array('label'=>'Valider'));
        /*$formModifier = function(FormInterface $form, Edition $edition) {
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
        );*/
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