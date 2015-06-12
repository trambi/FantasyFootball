<?php

namespace FantasyFootball\TournamentCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Game
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FantasyFootball\TournamentCoreBundle\Entity\GameRepository")
 */
class Game
{
    const ALLOWED_STATUS = array('programme','resume','detail');
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="td_1", type="smallint")
     */
    private $td1;

    /**
     * @var integer
     *
     * @ORM\Column(name="td_2", type="smallint")
     */
    private $td2;

    /**
     * @var integer
     *
     * @ORM\Column(name="round", type="smallint")
     */
    private $round;

    /**
     * @var integer
     *
     * @ORM\Column(name="points_1", type="smallint")
     */
    private $points1;

    /**
     * @var integer
     *
     * @ORM\Column(name="points_2", type="smallint")
     */
    private $points2;

    /**
     * @var integer
     *
     * @ORM\Column(name="casualties_1", type="smallint")
     */
    private $casualties1;

    /**
     * @var integer
     *
     * @ORM\Column(name="casualties_2", type="smallint")
     */
    private $casualties2;

    /**
     * @var boolean
     *
     * @ORM\Column(name="finale", type="boolean")
     */
    private $finale;

    /**
     * @var integer
     *
     * @ORM\Column(name="edition", type="smallint")
     */
    private $edition;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=33)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="table_number", type="smallint")
     */
    private $tableNumber;


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
     * Set td1
     *
     * @param integer $td1
     * @return Game
     */
    public function setTd1($td1)
    {
        $this->td1 = $td1;

        return $this;
    }

    /**
     * Get td1
     *
     * @return integer 
     */
    public function getTd1()
    {
        return $this->td1;
    }

    /**
     * Set td2
     *
     * @param integer $td2
     * @return Game
     */
    public function setTd2($td2)
    {
        $this->td2 = $td2;

        return $this;
    }

    /**
     * Get td2
     *
     * @return integer 
     */
    public function getTd2()
    {
        return $this->td2;
    }

    /**
     * Set round
     *
     * @param integer $round
     * @return Game
     */
    public function setRound($round)
    {
        $this->round = $round;

        return $this;
    }

    /**
     * Get round
     *
     * @return integer 
     */
    public function getRound()
    {
        return $this->round;
    }

    /**
     * Set points1
     *
     * @param integer $points1
     * @return Game
     */
    public function setPoints1($points1)
    {
        $this->points1 = $points1;

        return $this;
    }

    /**
     * Get points1
     *
     * @return integer 
     */
    public function getPoints1()
    {
        return $this->points1;
    }

    /**
     * Set points2
     *
     * @param integer $points2
     * @return Game
     */
    public function setPoints2($points2)
    {
        $this->points2 = $points2;

        return $this;
    }

    /**
     * Get points2
     *
     * @return integer 
     */
    public function getPoints2()
    {
        return $this->points2;
    }

    /**
     * Set casualties1
     *
     * @param integer $casualties1
     * @return Game
     */
    public function setCasualties1($casualties1)
    {
        $this->casualties1 = $casualties1;

        return $this;
    }

    /**
     * Get casualties1
     *
     * @return integer 
     */
    public function getCasualties1()
    {
        return $this->casualties1;
    }

    /**
     * Set casualties2
     *
     * @param integer $casualties2
     * @return Game
     */
    public function setCasualties2($casualties2)
    {
        $this->casualties2 = $casualties2;

        return $this;
    }

    /**
     * Get casualties2
     *
     * @return integer 
     */
    public function getCasualties2()
    {
        return $this->casualties2;
    }

    /**
     * Set finale
     *
     * @param boolean $finale
     * @return Game
     */
    public function setFinale($finale)
    {
        $this->finale = $finale;

        return $this;
    }

    /**
     * Get finale
     *
     * @return boolean 
     */
    public function getFinale()
    {
        return $this->finale;
    }

    /**
     * Set edition
     *
     * @param integer $edition
     * @return Game
     */
    public function setEdition($edition)
    {
        $this->edition = $edition;

        return $this;
    }

    /**
     * Get edition
     *
     * @return integer 
     */
    public function getEdition()
    {
        return $this->edition;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Game
     */
    public function setStatus($status)
    {
        if ( in_array($status,ALLOWED_STATUS) ){
            $this->status = $status;    
        }else{
            //@TODO FIXME
        }
        
        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set tableNumber
     *
     * @param integer $tableNumber
     * @return Game
     */
    public function setTableNumber($tableNumber)
    {
        $this->tableNumber = $tableNumber;

        return $this;
    }

    /**
     * Get tableNumber
     *
     * @return integer 
     */
    public function getTableNumber()
    {
        return $this->tableNumber;
    }
}
