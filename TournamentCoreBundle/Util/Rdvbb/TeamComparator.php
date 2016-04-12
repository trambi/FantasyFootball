<?php
namespace FantasyFootball\TournamentCoreBundle\Util\Rdvbb;

class TeamComparator{  
  const VAINQUEUR = 2;
  const FINALISTE = 1;
  const NORMAL = 0;

  static public function pointsNetTdCasFor($item1,$item2){
    $returnValue = 1;

    $points1 = $item1->points;
    $points2 = $item2->points;
    $netTd1 = $item1->netTd;
    $netTd2 = $item2->netTd;
    $cas1 = $item1->casualties;
    $cas2 = $item2->casualties;

    if(( $points1 === $points2 )  
        && ($netTd1 === $netTd2) 
        && ($cas1 === $cas2) ){
      $returnValue = 0;
    }else if( $points1 > $points2 ){
      $returnValue = -1;
    }else if( $points1 < $points2 ){
      $returnValue = 1;
    }else if($netTd1 > $netTd2){
      $returnValue = -1;
    }else if($netTd1 < $netTd2){
      $returnValue = 1;
    }else if($cas1 > $cas2){
      $returnValue = -1;
    }else if($cas1 < $cas2){
      $returnValue = 1;
    }
    return $returnValue;  
  }

  static public function pointsOpponentPointsNetTdCasFor($item1,$item2){
    $returnValue = 1;

    $points1 = $item1->points;
    $points2 = $item2->points;
    $opponentPoints1 = $item1->opponentsPoints;
    $opponentPoints2 = $item2->opponentsPoints;
    $netTd1 = $item1->netTd;
    $netTd2 = $item2->netTd;
    $cas1 = $item1->casualties;
    $cas2 = $item2->casualties;
    
    if( ($points1 === $points2) 
    && ($opponentPoints1 === $opponentPoints2) 
    && ($netTd1 === $netTd2) 
    && ($cas1 === $cas2) ){
      $returnValue = 0;
    }else if($points1 > $points2){
      $returnValue = -1;
    }else if($points1 < $points2){
      $returnValue = 1;
    }else if ($opponentPoints1 > $opponentPoints2) {
      $returnValue = -1;
    }else if ($opponentPoints1 < $opponentPoints2) {
      $returnValue = 1;
    }else if($netTd1 > $netTd2){
      $returnValue = -1;
    }else if($netTd1 < $netTd2){
      $returnValue = 1;
    }else if($cas1 > $cas2){
      $returnValue = -1;
    }else if($cas1 < $cas2){
      $returnValue = 1;
    }
    return $returnValue;
  }
  
  static public function pointsOpponentPointsTdForPlusCasFor($item1,$item2){
    $returnValue = 1;

    $points1 = $item1->points;
    $points2 = $item2->points;
    $opponentPoints1 = $item1->opponentsPoints;
    $opponentPoints2 = $item2->opponentsPoints;
    $mixed1 = $item1->tdFor + $item1->casualties;
    $mixed2 = $item2->tdFor + $item2->casualties;
        
    if ( ($points1 === $points2) && ($opponentPoints1 === $opponentPoints2) && ($mixed1 === $mixed2) ) {
      $returnValue = 0;
    }elseif ( $points1 > $points2 ) {
      $returnValue = -1;
    }elseif ( $points1 < $points2 ) {
      $returnValue = 1;
    }elseif ( $opponentPoints1 > $opponentPoints2 ) {
      $returnValue = -1;
    }elseif ( $opponentPoints1 < $opponentPoints2 ) {
      $returnValue = 1;
    }elseif ( $mixed1 > $mixed2 ) {
      $returnValue = -1;
    }elseif ( $mixed1 < $mixed2 ) {
      $returnValue = 1;
    }
    return $returnValue;
  }
  
  static public function finalPointsOpponentPointsNetTdCasFor($item1,$item2){
    $returnValue = 1;
    $special1 = 0;
    $special2 = 0;
    if(isset($item1->special)){
      $special1 = $item->special;
    }
    if(isset($item2->special)){
      $special2 = $item2->special;
    }
    if(self::VAINQUEUR == $special1){
      return -1;
    }else if(self::VAINQUEUR == $special2){
      return 1;
    }else if(self::FINALISTE == $special1){
      return -1;
    }else if(self::FINALISTE == $special2){
      return 1;
    }
    return self::pointsOpponentPointsNetTdCasFor($item1,$item2);
  }
  
  static public function  coachTeamPointsPointsOpponentPointsNetTdCasFor($item1,$item2){
    $points1 = $item1->coachTeamPoints;
    $points2 = $item2->coachTeamPoints;
    if ($points1 > $points2) {
      return -1;
    } else if ($points1 < $points2) {
      return 1;
    } else {
      return self::pointsOpponentPointsNetTdCasFor($item1,$item2);
    }
  }

  static public function coachTeamPointsPointsOpponentPointsTdForPlusCasFor($item1,$item2){
    $points1 = $item1->coachTeamPoints;
    $points2 = $item2->coachTeamPoints;
    if ($points1 > $points2) {
      return -1;
    } else if ($points1 < $points2) {
      return 1;
    } else {
      return self::pointsPointsOpponentPointsTdForPlusCasFor($item1,$item2);
    }
  }  
}