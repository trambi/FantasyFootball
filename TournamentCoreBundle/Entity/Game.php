<?php

namespace FantasyFootball\TournamentCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Game
 *
 * @ORM\Table(name="tournament_match")
 * @ORM\Entity(repositoryClass="FantasyFootball\TournamentCoreBundle\Entity\GameRepository")
 */
class Game
{
    static private $allowedStatus = array(self::SCHEDULED,self::RESUMED,self::DETAILED);
    const SCHEDULED = 'programme';
    const RESUMED = 'resume';
    const DETAILED = 'detail';
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Coach")
     * @ORM\JoinColumn(name="id_coach_1", referencedColumnName="id")
     */
    private $coach1;

    /**
     * @ORM\ManyToOne(targetEntity="Coach")
     * @ORM\JoinColumn(name="id_coach_2", referencedColumnName="id")
     */
    private $coach2;
    
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
     * @var integer
     *
     * @ORM\Column(name="completions_1", type="smallint")
     */
    private $completions1;

    /**
     * @var integer
     *
     * @ORM\Column(name="completions_2", type="smallint")
     */
    private $completions2;

    /**
     * @var integer
     *
     * @ORM\Column(name="fouls_1", type="smallint")
     */
    private $fouls1;

    /**
     * @var integer
     *
     * @ORM\Column(name="fouls_2", type="smallint")
     */
    private $fouls2;
    
    /**
     * @var string
     *
     * @ORM\Column(name="special_1", type="string", length=33)
     */
    private $special1;
    
    /**
     * @var string
     *
     * @ORM\Column(name="special_2", type="string", length=33)
     */
    private $special2;
    
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
     * @Assert\Choice(callback = "getAllowedStatus", message = "Choose a valid status.")
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="table_number", type="smallint")
     */
    private $tableNumber;

    public function __construct()
    {
        $this->status = self::SCHEDULED;
        $this->finale = false;
        $this->points1 = 0;
        $this->points2 = 0;
        $this->td1 = 0;
        $this->td2 = 0;
        $this->casualties1 = 0;
        $this->casualties2 = 0;
        $this->completion1 = 0;
        $this->completion2 = 0;
        $this->fouls1 = 0;
        $this->fouls2 = 0;
        $this->specials1 = "";
        $this->specials2 = "";
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
     * Set coach1
     *
     * @param Coach $coach
     * @return Game
     */
    public function setCoach1($coach)
    {
        $this->coach1 = $coach;
        return $this;
    }

    /**
     * Get coach1
     *
     * @return Coach
     */
    public function getCoach1()
    {
        return $this->coach1;
    }

        /**
     * Set coach2
     *
     * @param Coach $coach
     * @return Game
     */
    public function setCoach2($coach)
    {
        $this->coach2 = $coach;
        return $this;
    }

    /**
     * Get coach2
     *
     * @return Coach
     */
    public function getCoach2()
    {
        return $this->coach2;
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
     * Set completions1
     *
     * @param integer $completions
     * @return Game
     */
    public function setCompletions1($completions)
    {
        $this->completions1 = $completions;

        return $this;
    }

    /**
     * Get completions1
     *
     * @return integer 
     */
    public function getCompletions1()
    {
        return $this->completions1;
    }

    /**
     * Set completions2
     *
     * @param integer $completions2
     * @return Game
     */
    public function setCompletions2($completions)
    {
        $this->completions2 = $completions;

        return $this;
    }

    /**
     * Get completions2
     *
     * @return integer 
     */
    public function getCompletions2()
    {
        return $this->completions2;
    }
   
    /**
     * Set fouls1
     *
     * @param integer $fouls
     * @return Game
     */
    public function setFouls1($fouls)
    {
        $this->fouls1 = $fouls;

        return $this;
    }

    /**
     * Get fouls1
     *
     * @return integer 
     */
    public function getFouls1()
    {
        return $this->fouls1;
    }

    /**
     * Set fouls2
     *
     * @param integer $fouls
     * @return Game
     */
    public function setFouls2($fouls)
    {
        $this->fouls2 = $fouls;

        return $this;
    }

    /**
     * Get fouls2
     *
     * @return integer 
     */
    public function getFouls2()
    {
        return $this->fouls2;
    }
    
    /**
     * Set special1
     *
     * @param integer $special
     * @return Game
     */
    public function setSpecial1($special)
    {
        $this->special1 = $special;

        return $this;
    }

    /**
     * Get special1
     *
     * @return integer 
     */
    public function getSpecial1()
    {
        return $this->special1;
    }

    /**
     * Set special2
     *
     * @param integer $special
     * @return Game
     */
    public function setSpecial2($special)
    {
        $this->special2 = $special;

        return $this;
    }

    /**
     * Get special2
     *
     * @return integer 
     */
    public function getSpecial2()
    {
        return $this->special2;
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
        if ( in_array($status,self::$allowedStatus) ){
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
     * Get allowed status
     *
     * @return array of string 
     */
    static public function getAllowedStatus()
    {
        return self::$allowedStatus;
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
