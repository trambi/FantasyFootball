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
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use FantasyFootball\TournamentCoreBundle\Util\RankingStrategyFabric;

class EditionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $editionId = $this->editionId;
        $builder->add('day1', DateType::class, array('label'=>'Day 1:','widget' => 'single_text'));
        $builder->add('day2', DateType::class, array('label'=>'Day 2:','widget' => 'single_text'));
        $builder->add('roundNumber', 'integer',array('label'=>'Round number:'));
        $builder->add('firstDayRound', 'integer',array('label'=>'Round number in first day:'));
        $builder->add('useFinale', 'checkbox',array(
                'label' => 'Use finale:',
		        'required' => false));
        $builder->add('fullTriplette', 'checkbox',array(
                'label' => 'Is CoachTeam tournament:',
		        'required' => false));
        $strategyChoices = [];
        foreach (RankingStrategyFabric::getNames() as $strategyName){
            $strategyChoices[$strategyName] = $strategyName;
        }
        $builder->add('rankingStrategyName', ChoiceType::class, array(
            'choices'  => $strategyChoices,
            'label' => 'Ranking strategy:',
            'choices_as_values' => true)
        );

        $builder->add('save','submit',array('label'=>'Valider'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FantasyFootball\TournamentCoreBundle\Entity\Edition'
        ));
    }

    public function getName()
    {
        return 'edition';
    }
}
?>
