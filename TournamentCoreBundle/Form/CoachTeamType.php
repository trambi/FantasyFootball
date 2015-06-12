<?php

namespace FantasyFootball\TournamentCoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CoachTeamType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FantasyFootball\TournamentCoreBundle\Entity\CoachTeam'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'fantasyfootball_tournamentcorebundle_coachteam';
    }
}
