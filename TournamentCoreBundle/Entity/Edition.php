<?php

namespace FantasyFootball\TournamentCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="day_1", type="date")
     */
    private $day1;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="day_2", type="date")
     */
    private $day2;

    /**
     * @var integer
     *
     * @ORM\Column(name="round_number", type="smallint")
     */
    private $roundNumber;

    /**
     * @var integer
     *
     * @ORM\Column(name="current_round", type="smallint")
     */
    private $currentRound;

    /**
     * @var integer
     *
     * @ORM\Column(name="use_finale", type="smallint")
     */
    private $useFinale;

    /**
     * @var integer
     *
     * @ORM\Column(name="full_triplette", type="smallint")
     */
    private $fullTriplette;

    /**
     * @var string
     *
     * @ORM\Column(name="ranking_strategy", type="string", length=255)
     */
    private $rankingStrategy;

    /**
     * @var string
     *
     * @ORM\Column(name="pairing_strategy", type="string", length=255)
     */
    private $pairingStrategy;

    /**
     * @var integer
     *
     * @ORM\Column(name="first_day_round", type="smallint")
     */
    private $firstDayRound;


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
     * @param string $rankingStrategy
     * @return Edition
     */
    public function setRankingStrategy($rankingStrategy)
    {
        $this->rankingStrategy = $rankingStrategy;

        return $this;
    }

    /**
     * Get rankingStrategy
     *
     * @return string 
     */
    public function getRankingStrategy()
    {
        return $this->rankingStrategy;
    }

    /**
     * Set pairingStrategy
     *
     * @param string $pairingStrategy
     * @return Edition
     */
    public function setPairingStrategy($pairingStrategy)
    {
        $this->pairingStrategy = $pairingStrategy;

        return $this;
    }

    /**
     * Get pairingStrategy
     *
     * @return string 
     */
    public function getPairingStrategy()
    {
        return $this->pairingStrategy;
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
