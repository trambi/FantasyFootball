<?php

namespace FantasyFootball\TournamentCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FantasyFootball\TournamentCoreBundle\Util\RankingStrategyFabric;

/**
 * Edition
 *
 * @ORM\Table(name="tournament_edition")
 * @ORM\Entity
 */
class Edition
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="day_1", type="date")
     */
    public $day1;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="day_2", type="date")
     */
    public $day2;

    /**
     * @var integer
     *
     * @ORM\Column(name="round_number", type="smallint")
     */
    public $roundNumber;

    /**
     * @var integer
     *
     * @ORM\Column(name="current_round", type="smallint")
     */
    public $currentRound;

    /**
     * @var integer
     *
     * @ORM\Column(name="use_finale", type="smallint")
     */
    public $useFinale;

    /**
     * @var integer
     *
     * @ORM\Column(name="full_triplette", type="smallint")
     */
    public $fullTriplette;

    /**
     * @var string
     *
     * @ORM\Column(name="ranking_strategy", type="string", length=255)
     */
    public $rankingStrategyName;

    /**
     * @var integer
     *
     * @ORM\Column(name="first_day_round", type="smallint")
     */
    public $firstDayRound;
    
    public $rankingStrategy;
    public $rankings;

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
     * @param \DateTime $day1
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
     * @return \DateTime 
     */
    public function getDay1()
    {
        return $this->day1;
    }

    /**
     * Set day2
     *
     * @param \DateTime $day2
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
     * @return \DateTime 
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
        $this->rankingStrategy = RankingStrategyFabric::getByName($this->rankingStrategyName);
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
        if ( null == $this->rankingStrategy){
          $this->rankingStrategy = RankingStrategyFabric::getByName($this->rankingStrategyName);
        }
        $this->rankings = $this->rankingStrategy->rankingOptions();
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
}
