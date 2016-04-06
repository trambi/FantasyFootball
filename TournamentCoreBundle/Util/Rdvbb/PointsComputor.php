<?php
namespace FantasyFootball\TournamentCoreBundle\Util\Rdvbb;

class PointsComputor{
  static public function win5Draw2SmallLoss1Loss0(&$points1,&$points2,$td1,$td2,$cas1,$cas2){
    $win = 5;
    $draw = 2;
    $smallLoss = 1;
    $bigLoss = 0;
    $concedeLoss=-1;

    if( $td1 === $td2 ){
      $points1 = $draw;
      $points2 = $draw;
    }else if( $td1 === $td2+1 ){
      $points1 = $win;
      $points2 = $smallLoss;
    }else if( $td1 > $td2+1 ){
      $points1 = $win;
      $points2 = $bigLoss;
    }else if( $td2 === $td1+1){
      $points1 = $smallLoss;
      $points2 = $win;
    }else if( $td2 > $td1+1 ){
      $points1 = $bigLoss;
      $points2 = $win;
    }else if( -1 === $td1 ){
      $points1 = $concedeLoss;
      $points2 = $win;
    }else if( -1 === $td2 ){
      $points2 = $concedeLoss;
      $points1 = $win;
    }
  }
}

