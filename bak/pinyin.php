<?php
function trans($string,$t=1){
  $keyword=array(
  "ā" => "a",
  "á" => "a",
  "ǎ" => "a",
  "à" => "a",
  "Ā" => "a",
  "Á" => "a",
  "Ǎ" => "a",
  "À" => "a",
  "ō" => "o",
  "ó" => "o",
  "ǒ" => "o",
  "ò" => "o",
  "Ō" => "o",
  "Ó" => "o",
  "Ǒ" => "o",
  "Ò" => "o",
  "ē" => "e",
  "é" => "e",
  "ě" => "e",
  "è" => "e",
  "Ē" => "e",
  "É" => "e",
  "Ě" => "e",
  "È" => "e",
  "ī" => "i",
  "í" => "i",
  "ǐ" => "i",
  "ì" => "i",
  "ū" => "u",
  "ú" => "u",
  "ǔ" => "u",
  "ù" => "u",
  "ü" => "v",
  "ǖ" => "v",
  "ǘ" => "v",
  "ǚ" => "v",
  "ǜ" => "v",
  "'" => "",
  " " => "",
  "“" => "",
  "”" => "",
  "·" => "",
  "《" => "",
  "》" => "",
);
  $keyword;
  if($t==1){
    $string = strtr($string, $keyword);
  }elseif($t==2){
    $string = strtr($string, array_flip($keyword));   
  }
  return $string;
}
function pinyin($cn)
{
  $url='http://translate.google.cn/translate_a/single?client=webapp&sl=zh-CN&dt=rm&ie=UTF-8&q='.trans($cn);
  $data = file_get_contents($url);
  preg_match('/,,,"(.*?)"]],,/',$data,$n);
return trim(strtolower(trans($n[1])));
}
?>