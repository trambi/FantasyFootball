<?php
namespace FantasyFootball\TournamentCoreBundle\Util\Rdvbb;

class PointsComputor{
  static protected function custom($td1,$td2,$cas1,$cas2,$bigWin,$smallWin,$draw,$smallLoss,$bigLoss,$concedeLoss){
    $points1 = -1000;
    $points2 = -1000;
    if( $td1 === $td2 ){
      $points1 = $draw;
      $points2 = $draw;
    }else if( $td1 == $td2+1 ){
      $points1 = $smallWin;
      $points2 = $smallLoss;
    }else if( $td1 > $td2+1 ){
      $points1 = $bigWin;
      $points2 = $bigLoss;
    }else if( $td2 == $td1+1){
      $points1 = $smallLoss;
      $points2 = $smallWin;
    }else if( $td2 > $td1+1 ){
      $points1 = $bigLoss;
      $points2 = $bigWin;
    }else if( -1 === $td1 ){
      $points1 = $concedeLoss;
      $points2 = $bigWin;
    }else if( -1 === $td2 ){
      $points2 = $concedeLoss;
      $points1 = $bigWin;
    }
    if( -1000 === $points1 ){
      throw new \Exception("points1 n'a pas ete affecte ! td1:"+$td1+" td2:"+$td2 + " cas1:" + $cas1 + " cas2:" +$cas2);
    }
    if( -1000 === $points2 ){
      throw new \Exception("points2 n'a pas ete affecte ! td1:"+$td1+" td2:"+$td2 + " cas1:" + $cas1 + " cas2:" +$cas2);
    }
    return array('points1' => $points1,'points2' => $points2);    
  }
  
  static public function win2Draw1Loss0($td1,$td2,$cas1,$cas2){
    return self::custom($td1,$td2,$cas1,$cas2,2,2,1,0,0,0);
  }
  static public function win300Draw100Loss0($td1,$td2,$cas1,$cas2){
    return self::custom($td1,$td2,$cas1,$cas2,300,300,100,0,0,0);
  }
  
  static public function win5Draw2SmallLoss1Loss0($td1,$td2,$cas1,$cas2){
    return self::custom($td1,$td2,$cas1,$cas2,5,5,2,1,0,-1);
  }
  
  static public function win5Draw3SmallLoss1Loss0($td1,$td2,$cas1,$cas2){
    return self::custom($td1,$td2,$cas1,$cas2,5,5,3,1,0,-1);
  }
  
  static public function win10Draw4SmallLoss1Loss0($td1,$td2,$cas1,$cas2){
    return self::custom($td1,$td2,$cas1,$cas2,10,10,4,1,0,-1);
  }
  
  static public function win8Draw4Loss0Bonus1($td1,$td2,$cas1,$cas2){
    return self::custom($td1,$td2,$cas1,$cas2,9,8,4,1,0,0);
  }
  
  static public function win8Draw4Loss0Bonus1ConcedeMinus1($td1,$td2,$cas1,$cas2){
    return self::custom($td1,$td2,$cas1,$cas2,9,8,4,1,0,-1);
  }
  
  static public function teamCustom($td1Array,$td2Array,$cas1Array,$cas2Array,$bigWin,$smallWin,$draw,$smallLoss,$bigLoss,$concedeLoss){
    $points1 = 0;
    $points2 = 0;
    $td1Number = count($td1Array);
    $td2Number = count($td2Array);
    $cas1Number = count($cas1Array);
    $cas2Number = count($cas1Array);
    if( ($td1Number != $td2Number) || ($td1Number != $cas1Number) || ($td1Number != $cas2Number) ){
      throw new \Exception("les élements des tableaux transmis a PointsComputor::teamCustom ne sont pas coherents");
    }
    for ($i = 0; $i < $td1Number ; $i++) {
      $points = self::custom($td1Array[$i],$td2Array[$i],$cas1Array[$i],$cas2Array[$i],$bigWin,$smallWin,$draw,$smallLoss,$bigLoss,$concedeLoss);
      $points1 += $points['points1'];
      $points2 += $points['points2'];
    }
    return array('points1' => $points1,'points2' => $points2);    
  }
  
  static public function teamWithWin5Draw3SmallLoss1Loss0($td1Array,$td2Array,$cas1Array,$cas2Array){
    return self::teamCustom($td1Array,$td2Array,$cas1Array,$cas2Array,5,5,3,1,0,-1);
  }
  
  static public function teamWithWin10Draw4SmallLoss1Loss0($td1Array,$td2Array,$cas1Array,$cas2Array){
    return self::teamCustom($td1Array,$td2Array,$cas1Array,$cas2Array,10,10,4,1,0,-1);
  }
  
  static public function teamWithWin8Draw4Loss0Bonus1($td1Array,$td2Array,$cas1Array,$cas2Array){
    return self::teamCustom($td1Array,$td2Array,$cas1Array,$cas2Array,9,8,4,1,0,0);
  }
  
  static public function teamWin300Draw100Loss0HalfTeamBonus($td1Array,$td2Array,$cas1Array,$cas2Array){
    $sum = self::teamCustom($td1Array,$td2Array,$cas1Array,$cas2Array,300,300,100,0,0,0);
    $sum1 = $sum['points1'];
    $sum2 = $sum['points2'];
    if ($sum1 < $sum2) {
      $points1 = $sum1;
      $points2 = $sum2 + 150;
    } elseif ($sum1 === $sum2) {
      $points1 = $sum1 + 50;
      $points2 = $sum2 + 50;
    } else {
      $points1 = $sum1 + 150;
      $points2 = $sum2;
    }
    return array('points1'=>$points1,'points2'=>$points2);
  }
  
  static public function teamWin2TeamDraw1TeamLoss0($td1Array,$td2Array,$cas1Array,$cas2Array){
    $points1 = 0;
    $points2 = 0;
    $sum = self::teamCustom($td1Array,$td2Array,$cas1Array,$cas2Array,2,2,1,0,0,0);
    $sum1 = $sum['points1'];
    $sum2 = $sum['points2'];
    if ($sum1 < $sum2) {
      $points1 = 0;
      $points2 = 2;
    } elseif ($sum1 === $sum2) {
      $points1 = 1;
      $points2 = 1;
    } else {
      $points1 = 2;
      $points2 = 0;
    }
    return array('points1'=>$points1,'points2'=>$points2);
  }
  
  static public function teamWin2TeamDraw1TeamLoss0win8Draw4Loss0Bonus1ConcedeMinus1($td1Array,$td2Array,$cas1Array,$cas2Array){
    $points1 = 0;
    $points2 = 0;
    $sum = self::teamCustom($td1Array,$td2Array,$cas1Array,$cas2Array,9,8,4,1,0,-1);
    $sum1 = $sum['points1'];
    $sum2 = $sum['points2'];
    if ($sum1 < $sum2) {
      $points1 = 0;
      $points2 = 2;
    } elseif ($sum1 === $sum2) {
      $points1 = 1;
      $points2 = 1;
    } else {
      $points1 = 2;
      $points2 = 0;
    }
    return array('points1'=>$points1,'points2'=>$points2);
  }
}

