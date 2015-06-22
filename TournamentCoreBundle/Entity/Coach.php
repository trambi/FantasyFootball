<?php

namespace FantasyFootball\TournamentCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Coach
 *
 * @ORM\Table(name="tournament_coach")
 * @ORM\Entity(repositoryClass="FantasyFootball\TournamentCoreBundle\Entity\CoachRepository")
 */
class Coach
{
    /**
     * @ORM\ManyToOne(targetEntity="Race", inversedBy="coachs")
     * @ORM\JoinColumn(name="id_race", referencedColumnName="id_race")
     */
    protected $race;
    
    /**
     * @ORM\ManyToOne(targetEntity="CoachTeam", inversedBy="coachs")
     * @ORM\JoinColumn(name="id_coach_team", referencedColumnName="id")
     */
    protected $coachTeam;
    
    public function __construct()
    {
        $this->games = new ArrayCollection();
        $this->points = 0;
        $this->opponentsPoints = 0;
        $this->netTd = 0;
        $this->casualties = 0;    
        $this->edition = 0;    
        $this->nafNumber = 0;    
        $this->name = '';    
        $this->teamName = '';    
        $this->ready = false;
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
     * @ORM\Column(name="team_name", type="string", length=255)
     */
    private $teamName;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var integer
     *
     * @ORM\Column(name="edition", type="smallint")
     */
    private $edition;

    /**
     * @var integer
     *
     * @ORM\Column(name="naf_number", type="integer")
     */
    private $nafNumber;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="points", type="integer")
     */
    private $points;

    /**
     * @var integer
     *
     * @ORM\Column(name="opponents_points", type="integer")
     */
    private $opponentsPoints;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="net_td", type="integer")
     */
    private $netTd;

    /**
     * @var integer
     *
     * @ORM\Column(name="casualties", type="integer")
     */
    private $casualties;    
    /**
     * @var boolean
     *
     * @ORM\Column(name="ready", type="boolean")
     */
    private $ready;


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
     * Set teamName
     *
     * @param string $teamName
     * @return Coach
     */
    public function setTeamName($teamName)
    {
        $this->teamName = $teamName;

        return $this;
    }

    /**
     * Get teamName
     *
     * @return string 
     */
    public function getTeamName()
    {
        return $this->teamName;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Coach
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Coach
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set edition
     *
     * @param integer $edition
     * @return Coach
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
     * Set nafNumber
     *
     * @param integer $nafNumber
     * @return Coach
     */
    public function setNafNumber($nafNumber)
    {
        $this->nafNumber = $nafNumber;

        return $this;
    }

    /**
     * Get nafNumber
     *
     * @return integer 
     */
    public function getNafNumber()
    {
        return $this->nafNumber;
    }

    /**
     * Set ready
     *
     * @param boolean $ready
     * @return Coach
     */
    public function setReady($ready)
    {
        $this->ready = $ready;

        return $this;
    }

    /**
     * Get ready
     *
     * @return boolean 
     */
    public function getReady()
    {
        return $this->ready;
    }

    /**
     * Set race
     *
     * @param \FantasyFootball\TournamentCoreBundle\Entity\Race $race
     * @return Coach
     */
    public function setRace(\FantasyFootball\TournamentCoreBundle\Entity\Race $race = null)
    {
        $this->race = $race;

        return $this;
    }

    /**
     * Get race
     *
     * @return \FantasyFootball\TournamentCoreBundle\Entity\Race 
     */
    public function getRace()
    {
        return $this->race;
    }

    /**
     * Set coachTeam
     *
     * @param \FantasyFootball\TournamentCoreBundle\Entity\CoachTeam $coachTeam
     * @return Coach
     */
    public function setCoachTeam(\FantasyFootball\TournamentCoreBundle\Entity\CoachTeam $coachTeam = null)
    {
        $this->coachTeam = $coachTeam;

        return $this;
    }

    /**
     * Get coachTeam
     *
     * @return \FantasyFootball\TournamentCoreBundle\Entity\CoachTeam 
     */
    public function getCoachTeam()
    {
        return $this->coachTeam;
    }

    /**
     * Has coachTeam
     *
     * @return boolean 
     */
    public function hasCoachTeam()
    {
        return ( null != $this->coachTeam );
    }
    
    public function getStatusString()
    {
        $statusStrings = array('success' => 'Ok',
            'warning' => 'Pas présent',
            'danger' => 'Pas de triplette'
            );
        return ($statusStrings[$this->getStatus()]);
    }
    
    public function getStatus()
    {
        if($this->hasCoachTeam())
        {
            if( $this->getReady() )
            {
                return 'success';
            }
            else{
                return 'warning';
            }
                
        }
        else
            {
            return 'danger';
        }
    }
    
    /**
     * Set points
     *
     * @param integer $points
     * @return Coach
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return integer 
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set opponentsPoints
     *
     * @param integer $opponentsPoints
     * @return Coach
     */
    public function setOpponentsPoints($opponentsPoints)
    {
        $this->opponentsPoints = $opponentsPoints;

        return $this;
    }

    /**
     * Get opponentsPoints
     *
     * @return integer 
     */
    public function getOpponentsPoints()
    {
        return $this->opponentsPoints;
    }

    /**
     * Set netTd
     *
     * @param integer $netTd
     * @return Coach
     */
    public function setNetTd($netTd)
    {
        $this->netTd = $netTd;

        return $this;
    }

    /**
     * Get netTd
     *
     * @return integer 
     */
    public function getNetTd()
    {
        return $this->netTd;
    }

    /**
     * Set casualties
     *
     * @param integer $casualties
     * @return Coach
     */
    public function setCasualties($casualties)
    {
        $this->casualties = $casualties;

        return $this;
    }

    /**
     * Get casualties
     *
     * @return integer 
     */
    public function getCasualties()
    {
        return $this->casualties;
    }
}
