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
use FantasyFootball\TournamentCoreBundle\Entity\CoachRepository;

class CoachTeamType extends AbstractType{
  protected $editionId;
  protected $coachTeamId;
    
  public function __construct($editionId,$coachTeamId=0) {
    $this->editionId = $editionId;
    $this->coachTeamId = $coachTeamId;
  }

  public function buildForm(FormBuilderInterface $builder, array $options){
    $editionId = $this->editionId;
    $coachTeamId = $this->coachTeamId;
    $builder->add('name', 'text',['label'=>'Nom :'])
            ->add('coachs', 'collection',
              ['label' =>  'Membres :','type'   => 'entity',
              'options' =>['label'=>'Coach :',
                'class'   => 'FantasyFootballTournamentCoreBundle:Coach',
                'property'  => 'name',
                'query_builder' => function(CoachRepository $cr) use ($editionId,$coachTeamId) {
                  return $cr->getQueryBuilderFreeCoachsByEditionAndCoachTeam($editionId,$coachTeamId);
                }]])
            ->add('save','submit',array('label'=>'Valider'));
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