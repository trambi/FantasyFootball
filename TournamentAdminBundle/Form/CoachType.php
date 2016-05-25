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
        $builder->add('teamName', 'text',array('label'=>'Nom de l\'équipe :','required' => false));
        $builder->add('name', 'text',array('label'=>'Nom :'));
        $builder->add('race', 'entity',
                array('label'=>'Race :',
                            'class'   => 'FantasyFootballTournamentCoreBundle:Race',
                            'property'  => 'frenchName',
                            'query_builder' => function(RaceRepository $rr) use ($editionId) {
                                return $rr->getQueryBuilderForRaceByEditionOrLesser($editionId);
                            }));
        $builder->add('email', 'email',array('label'=>'Courriel :','required' => false));
        $builder->add('nafNumber', 'integer',array('label'=>'Numéro NAF :'));
        $builder->add('ready', 'checkbox',array(
                'label' => 'Coach prêt ?',
		'required' => false))
        ;
        $builder->add('save','submit',array('label'=>'Valider'));
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