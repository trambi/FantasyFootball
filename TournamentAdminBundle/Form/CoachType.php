<?php
/*
    FantasyFootball Symfony2 bundles - Symfony2 bundles collection to handle fantasy football tournament 
    Copyright (C) 2017  Bertrand Madet

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
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