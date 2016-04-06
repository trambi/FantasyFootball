<?php
namespace FantasyFootball\TournamentCoreBundle\Util\Rdvbb;

class TeamComparator{
  static public function pointsNetTdCasFor($item1,$item2){
    $returnValue = 1;
    
    $points1 = $item1->points;
    $points2 = $item2->points;
    $netTd1 = $item1->netTd;
    $netTd2 = $item2->netTd;
    $cas1 = $item1->casualties;
    $cas2 = $item2->casualties;
    
    if(( $points1 === $points2 )  
        && ($netTd1 == $netTd2) 
        && ($cas1 == $cas2) ){
      $returnValue = 0;
    }else{
      if( $points1 > $points2 ){
        $returnValue = -1;
      }
      if( $points1 < $points2 ){
        $returnValue = 1;
      }
      if( $points1 === $points2 ){
        if($netTd1 > $netTd2){
          $returnValue = -1;
        }
        if($netTd1 < $netTd2){
          $returnValue = 1;
        }
        if($netTd1 === $netTd2){
          if($cas1 > $cas2){
            $returnValue = -1;
          }
          if($cas1 < $cas2){
            $returnValue = 1;
          }
        }
      }
    }
    return $returnValue;  
  }
}