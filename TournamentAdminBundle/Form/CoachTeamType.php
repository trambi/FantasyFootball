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

use FantasyFootball\TournamentCoreBundle\Entity\CoachRepository;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CoachTeamType extends AbstractType{

  public function buildForm(FormBuilderInterface $builder, array $options){
    $coachs = $options['data']->getCoachs();
    $editionId = -1;
    if (null !== $coachs && 0 < count($coachs) ){
      $editionId = $coachs[0]->getEdition();

    }
    $coachTeamId = $options['data']->getId();
    if(null === $coachTeamId){
      $coachTeamId = 0;
    }
    $builder->add('name', TextType::class, ['label'=>'Nom :'])
            ->add('coachs', CollectionType::class,
              ['label' =>  'Membres :','entry_type'   => EntityType::class,
              'entry_options' =>['label'=>'Coach :',
                'class'   => 'FantasyFootballTournamentCoreBundle:Coach',
                'choice_label' => function ($coach) {
                  return $coach->getName();
                },
                'query_builder' => function(CoachRepository $cr) use ($editionId, $coachTeamId) {
                  return $cr->getQueryBuilderFreeCoachsByEditionAndCoachTeam($editionId, $coachTeamId);
                }]])
            ->add('save', SubmitType::class, array('label'=>'Valider'));
  }

  public function configureOptions(OptionsResolver $resolver){
    $resolver->setDefaults(array(
        'data_class' => 'FantasyFootball\TournamentCoreBundle\Entity\CoachTeam'
    ));
  }

  public function getName(){
    return 'coachTeam';
  }
}
?>