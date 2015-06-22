<?php
namespace FantasyFootball\TournamentAdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FantasyFootball\TournamentCoreBundle\Entity\CoachRepository;

class CoachTeamType extends AbstractType
{
    protected $editionId;
    
    public function __construct($editionId) {
        $this->editionId = $editionId;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $editionId = $this->editionId;
        $builder
            ->add('name', 'text',array('label'=>'Nom :'))
            ->add('coachs', 'collection',array(
                'label' =>  'Membres :',
                'type'   => 'entity',
                'options' =>array('label'=>'Coach :',
                            'class'   => 'FantasyFootballTournamentCoreBundle:Coach',
                            'property'  => 'name',
                            'query_builder' => function(CoachRepository $cr) use ($editionId) {
                                return $cr->getQueryBuilderForCoachsWithoutCoachTeamByEdition($editionId);
                            })))
            ->add('save','submit',array('label'=>'Valider'));
        ;

     }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FantasyFootball\TournamentCoreBundle\Entity\CoachTeam'
        ));
    }

    public function getName()
    {
        return 'coachTeam';
    }
}
?>