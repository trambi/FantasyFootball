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
namespace FantasyFootball\TournamentCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FantasyFootball\TournamentCoreBundle\Util\RankingStrategyFabric;

/**
 * Edition
 *
 * @ORM\Table(name="tournament_edition")
 * @ORM\Entity(repositoryClass="FantasyFootball\TournamentCoreBundle\Entity\EditionRepository")
*/
class Edition
{
  public function __construct()
  {
    $this->currentRound = 0;
    $this->rankingStrategy = null;
    $this->rankings = [];
  }
  /**
   * @var integer
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
  */
  private $id;

  /**
   * @var string
   *
   * @ORM\Column(name="day_1", type="string",length=10)
  */
  protected $day1;

  /**
   * @var string
   *
   * @ORM\Column(name="day_2", type="string",length=10)
  */
  protected $day2;

  /**
   * @var integer
   *
   * @ORM\Column(name="round_number", type="smallint")
  */
  protected $roundNumber;

  /**
   * @var integer
   *
   * @ORM\Column(name="current_round", type="smallint")
  */
  protected $currentRound;

  /**
   * @var integer
   *
   * @ORM\Column(name="use_finale", type="smallint")
  */
  protected $useFinale;

  /**
   * @var integer
   *
   * @ORM\Column(name="full_triplette", type="smallint")
  */
  protected $fullTriplette;

  /**
   * @var string
   *
   * @ORM\Column(name="ranking_strategy", type="string", length=255)
  */
  protected $rankingStrategyName;
  protected $rankingStrategy;
  protected $rankings;

  /**
   * @var integer
   *
   * @ORM\Column(name="first_day_round", type="smallint")
  */
  protected $firstDayRound;

  /**
   * @var string
   *
   * @ORM\Column(name="organiser", type="string", length=65)
  */
  protected $organiser;


  /**
  * Set id
  * @param integer Id
  * @return Edition

  */
  public function setId($id)
  {
    $this->id = $id;
    return $this;
  }

  /**
   * Get id
   *
   * @return integer
   */
  public function getId()
  {
    return $this->id;
  }

  /**
  * Set day1
  *
  * @param string $day1
  * @return Edition
  */
  public function setDay1($day1)
  {
    $this->day1 = $day1;
    return $this;
  }

  /**
  * Get day1
  *
  * @return string
  */
  public function getDay1()
  {
    return $this->day1;
  }

  /**
  * Set day2
  *
  * @param string $day2
  * @return Edition
  */
  public function setDay2($day2)
  {
    $this->day2 = $day2;
    return $this;
  }

  /**
  * Get day2
  *
  * @return string
  */
  public function getDay2()
  {
    return $this->day2;
  }

  /**
  * Set roundNumber
  *
  * @param integer $roundNumber
  * @return Edition
  */
  public function setRoundNumber($roundNumber)
  {
    $this->roundNumber = $roundNumber;
    return $this;
  }

  /**
  * Get roundNumber
  *
  * @return integer
  */
  public function getRoundNumber()
  {
    return $this->roundNumber;
  }

  /**
  * Set currentRound
  *
  * @param integer $currentRound
  * @return Edition
  */
  public function setCurrentRound($currentRound)
  {
    $this->currentRound = $currentRound;
    return $this;
  }

  /**
  * Get currentRound
  *
  * @return integer
  */
  public function getCurrentRound()
  {
    return $this->currentRound;
  }

  /**
  * Set useFinale
  *
  * @param integer $useFinale
  * @return Edition
  */
  public function setUseFinale($useFinale)
  {
    $this->useFinale = $useFinale;
    return $this;
  }

  /**
  * Get useFinale
  *
  * @return integer
  */
  public function getUseFinale()
  {
    return $this->useFinale;
  }

  /**
  * Set fullTriplette
  *
  * @param integer $fullTriplette
  * @return Edition
  */
  public function setFullTriplette($fullTriplette)
  {
    $this->fullTriplette = $fullTriplette;
    return $this;
  }

  /**
  * Get fullTriplette
  *
  * @return integer
  */
  public function getFullTriplette()
  {
    return $this->fullTriplette;
  }

  /**
  * Set rankingStrategy
  *
  * @param string $rankingStrategyName
  * @return Edition
  */
  public function setRankingStrategyName($rankingStrategyName)
  {
    $this->rankingStrategyName = $rankingStrategyName;
    $this->rankingStrategy = RankingStrategyFabric::getByName($rankingStrategyName);
    $this->rankings = $this->rankingStrategy->rankingOptions();
    return $this;
  }

  /**
  * Get rankingStrategyName
  *
  * @return string
  */
  public function getRankingStrategyName()
  {
    return $this->rankingStrategyName;
  }

  /**
  * Get rankingStrategy
  *
  * @return RankingStrategy
  */
  public function getRankingStrategy()
  {
    if ( null == $this->rankingStrategy){
      $this->refresh();
    }
    return $this->rankingStrategy;
  }

  /**
  * Get rankings
  *
  * @return Rankings
  */
  public function getRankings()
  {
    if ( null == $this->rankings){
      $this->refresh();
    }
    return $this->rankings;
  }

  /**
  * Set firstDayRound
  *
  * @param integer $firstDayRound
  * @return Edition
  */
  public function setFirstDayRound($firstDayRound)
  {
    $this->firstDayRound = $firstDayRound;
    return $this;
  }

  /**
  * Get firstDayRound
  *
  * @return integer
  */
  public function getFirstDayRound()
  {
    return $this->firstDayRound;
  }

  public function refresh(){
      $this->rankingStrategy = RankingStrategyFabric::getByName($this->rankingStrategyName);
      $this->rankings = $this->rankingStrategy->rankingOptions();
      return $this;
  }

  /**
  * Set organiser
  *
  * @param string $organiser
  * @return Edition
  */
  public function setOrganiser($organiser)
  {
    $this->organiser = $organiser;
    return $this;
  }

  /**
  * Get organiser
  *
  * @return string
  */
  public function getOrganiser()
  {
    return $this->organiser;
  }


  public function toArray(){
    $returnedArray = array();
    $keys = array_keys(get_class_vars(__class__));
    foreach ($keys as $attribute){
      if ( True == is_a($this->$attribute,"DateTime") ){
        $returnedArray[$attribute] = explode(" ",$this->$attribute->date)[0];
    }else{
        $returnedArray[$attribute] = $this->$attribute;
    }

    }
    return $returnedArray;
  }
}
