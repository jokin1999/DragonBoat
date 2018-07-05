<?php
// +----------------------------------------------------------------------
// | Constructed by Jokin [ Think & Do & To Be Better ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2018 Jokin All rights reserved.
// +----------------------------------------------------------------------
// | Author: Jokin <Jokin@twocola.com>
// +----------------------------------------------------------------------
/**
 * 龙舟文字图生成
 * @version: 1.0.0
**/
// Global Options
define('IS_DEBUG', false);
// Get info
if( IS_DEBUG === false){
  header("content-type: image/png");
}
storage::$src_h = 172; // 单高度
storage::$interval = 5; // 间隔宽度
storage::$var = "plztxt"; // 接收量
storage::$text = $_POST[storage::$var];
getPicSize($width, $height);
// 创建主画布
$img = imagecreatetruecolor($width, $height);
storage::$image_bg_color = imagecolorallocate($img,255,255,255);
storage::$image_text_color = imagecolorallocate($img,24,14,0);
imagefill($img,0,0,storage::$image_bg_color);
$text = explode(PHP_EOL, storage::$text);
for($i = 0; $i < storage::$row; $i++){
  $row = $i + 1;
  $t = $text[$i];
  $l = mb_strlen($t);
  for ($p=0; $p < $l+2; $p++) {
    $col = $p + 1;
    $position = getPosition($row, $col);
    // 判断特殊位置
    if($col === 1){
      $src = storage::$lt;
      $_img = imagecreatefrompng($src);
      $d = 0;
      $w = storage::$lt_w;
      $h = storage::$lt_h;
    }else if($col === $l+2){
      $src = storage::$lw;
      $_img = imagecreatefrompng($src);
      $d = 59;
      $w = storage::$lw_w;
      $h = storage::$lw_h;
    }else{
      $src = storage::$ls;
      $_img = imagecreatefrompng($src);
      $_t = mb_substr($t, $col-2, 1);
      imageTTFtext($_img, 33, 0, 15, 48, storage::$image_text_color, storage::$font, $_t);
      $d = 42;
      $w = storage::$ls_w;
      $h = storage::$ls_h;
    }
    imagecopy($img, $_img, $position['left'], $position['top']+$d ,0 ,0 ,$w, $h);
  }
}
// 设置背景透明
imagecolortransparent($img, imagecolorAllocateAlpha($img,255,255,255,127));
if( IS_DEBUG === true ){
  imagepng($img, "./img.png");
}else{
  imagepng($img);
}
// 函数区
/**
 * 计算图片位置
 * @param  int $row
 * @param  int $col
 * @return array
 */
function getPosition($row, $col){
  $top = ($row-1) * storage::$interval + ($row-1) * storage::$src_h;
  $d = ($col !== 1) ? 62 : 0;
  $left = ($col-1) * storage::$ls_w + $d;
  $r['top'] = $top;
  $r['left'] = $left;
  return $r;
}
/**
 * 获取图片大小
 * @param  int $width
 * @param  int $height
 * @return void
 */
function getPicSize(&$width, &$height){
  $text = storage::$text;
  $text = explode(PHP_EOL, $text);
  // 计算行数
  storage::$row = count($text);
  // 计算列数
  storage::$col = getMaxCol($text);
  // 计算宽度
  storage::$width = storage::$ls_w * storage::$col + storage::$lt_w + storage::$lw_w;
  // 计算高度
  storage::$height = storage::$src_h * storage::$row + storage::$interval * (storage::$row - 1);
  $width = storage::$width;
  $height = storage::$height;
  $r['w'] = $width;
  $r['h'] = $height;
}
/**
 * 获取最大列
 * @param  array $array
 * @return void
 */
function getMaxCol($array){
  $max = 0;
  foreach ($array as $value) {
    $len = mb_strlen($value);
    $max = ( $len > $max ) ? $len : $max;
  }
  return $max;
}
// 存储区
class storage {
  public static $src_h = 0; // 行高
  public static $interval = 0;
  public static $text = 0;
  public static $var = "text";
  public static $col = 0;
  public static $row = 0;
  public static $width = 0;
  public static $height = 0;
  public static $lt = "./static/mod_img/lt.png";
  public static $lt_w = 131;
  public static $lt_h = 172;
  public static $lw = "./static/mod_img/lw.png";
  public static $lw_w = 122;
  public static $lw_h = 113;
  public static $ls = "./static/mod_img/ls.png";
  public static $ls_w = 69;
  public static $ls_h = 130;
  public static $font = "C:\WINDOWS\Fonts\SIMHEI.TTF";
  public static $image_bg_color = false;
  public static $image_text_color = false;
}
?>
