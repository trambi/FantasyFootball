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
use Doctrine\Common\Collections\ArrayCollection;
/**
 * CoachTeam
 *
 * @ORM\Table(name="tournament_coach_team")
  * @ORM\Entity(repositoryClass="FantasyFootball\TournamentCoreBundle\Entity\CoachTeamRepository")
 */
class CoachTeam
{
  /**
   * @ORM\OneToMany(targetEntity="Coach", mappedBy="coachTeam")
   */
  protected $coachs;

  public function __construct()
  {
      $this->coachs = new ArrayCollection();
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
   * @ORM\Column(name="name", type="string", length=255)
   */
  private $name;


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
   * Set name
   *
   * @param string $name
   * @return CoachTeam
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
   * Add coach
   *
   * @param \FantasyFootball\TournamentCoreBundle\Entity\Coach $coach
   * @return CoachTeam
   */
  public function addCoach(\FantasyFootball\TournamentCoreBundle\Entity\Coach $coach)
  {
    $this->coachs[] = $coach;
    $coach->setCoachTeam($this);
    return $this;
  }

  /**
   * Remove coachs
   *
   * @param \FantasyFootball\TournamentCoreBundle\Entity\Coach $coachs
   */
  public function removeCoach(\FantasyFootball\TournamentCoreBundle\Entity\Coach $coach)
  {
    $this->coachs->removeElement($coach);
    $coach->setCoachTeam();
  }

  /**
   * Get coachs
   *
   * @return \Doctrine\Common\Collections\Collection 
   */
  public function getCoachs()
  {
    return $this->coachs;
  }
  
  public function getStatusString()
    {
        $statusStrings = array('success' => 'Ok',
            'warning' => 'Pas tous présents',
            'danger' => 'Tous absents'
            );
        return ($statusStrings[$this->getStatus()]);
    }
    
    public function getStatus()
    {
      $countNok = 0;
      $countOk = 0;
      foreach($this->coachs as $coach){
        if($coach->getReady()){
          $countOk += 1;
        }else{
          $countNok += 1;
        }
      }
      if(0 == $countNok){
        return 'success';
      }else if(0 == $countOk){
        return 'danger';
      }
      else{
        return 'warning';
      }
    }
}
