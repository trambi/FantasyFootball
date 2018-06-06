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
namespace FantasyFootball\TournamentCoreBundle\Util\Rdvbb;

class PointsComputor{
  static protected function custom($game,$bigWin,$smallWin,$draw,$smallLoss,$bigLoss,$concedeLoss){
    $points1 = -1000;
    $points2 = -1000;
    $td1 = $game->getTd1();
    $td2 = $game->getTd2();
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
      throw new \Exception("points1 n'a pas ete affecte ! td1:"+$td1+" td2:"+$td2);
    }
    if( -1000 === $points2 ){
      throw new \Exception("points2 n'a pas ete affecte ! td1:"+$td1+" td2:"+$td2);
    }
    return [$points1,$points2];    
  }
  
  static public function win2Draw1Loss0($game){
    return self::custom($game,2,2,1,0,0,0);
  }
  static public function win300Draw100Loss0($game){
    return self::custom($game,300,300,100,0,0,0);
  }
  
  static public function win5Draw2SmallLoss1Loss0($game){
    return self::custom($game,5,5,2,1,0,-1);
  }
  
  static public function win5Draw3SmallLoss1Loss0($game){
    return self::custom($game,5,5,3,1,0,-1);
  }
  
  static public function win10Draw4SmallLoss1Loss0($game){
    return self::custom($game,10,10,4,1,0,-1);
  }
  
  static public function win8Draw4Loss0Bonus1($game){
    return self::custom($game,9,8,4,1,0,0);
  }
  
  static public function win8Draw4Loss0Bonus1ConcedeMinus1($game){
    return self::custom($game,9,8,4,1,0,-1);
  }
  
  static public function teamCustom($games,$bigWin,$smallWin,$draw,$smallLoss,$bigLoss,$concedeLoss){
    $points1 = 0;
    $points2 = 0;
    $gameNumber = count($games);
    for ($i = 0; $i < $gameNumber ; $i++) {
      $points = self::custom($games[$i],$bigWin,$smallWin,$draw,$smallLoss,$bigLoss,$concedeLoss);
      $points1 += $points[0];
      $points2 += $points[1];
    }
    return [$points1,$points2];    
  }
  
  static public function teamWithWin5Draw3SmallLoss1Loss0($games){
    return self::teamCustom($games,5,5,3,1,0,-1);
  }
  
  static public function teamWithWin10Draw4SmallLoss1Loss0($games){
    return self::teamCustom($games,10,10,4,1,0,-1);
  }
  
  static public function teamWithWin8Draw4Loss0Bonus1($games){
    return self::teamCustom($games,9,8,4,1,0,0);
  }
  
  static public function teamWin300Draw100Loss0HalfTeamBonus($games){
    $sum = self::teamCustom($games,300,300,100,0,0,0);
    $sum1 = $sum[0];
    $sum2 = $sum[1];
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
    return [$points1,$points2];
  }
  
  static public function teamWin2Draw1Loss0FullTeamBonus($games){
    $sum = self::teamCustom($games,2,2,1,0,0,0);
    $sum1 = $sum[0];
    $sum2 = $sum[1];
    if ($sum1 < $sum2) {
      $points1 = $sum1;
      $points2 = $sum2 + 2;
    } elseif ($sum1 === $sum2) {
      $points1 = $sum1 + 1;
      $points2 = $sum2 + 1;
    } else {
      $points1 = $sum1 + 2;
      $points2 = $sum2;
    }
    return [$points1,$points2];
  }

  static public function teamWin2TeamDraw1TeamLoss0($games){
    $points1 = 0;
    $points2 = 0;
    $sum = self::teamCustom($games,2,2,1,0,0,0);
    $sum1 = $sum[0];
    $sum2 = $sum[1];
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
    return [$points1,$points2];
  }
  
  static public function teamWin2TeamDraw1TeamLoss0win8Draw4Loss0Bonus1ConcedeMinus1($games){
    $points1 = 0;
    $points2 = 0;
    $sum = self::teamCustom($games,9,8,4,1,0,-1);
    $sum1 = $sum[0];
    $sum2 = $sum[1];
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
    return [$points1,$points2];
  }
}