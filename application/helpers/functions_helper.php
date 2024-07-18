<?php
function getActive($menu) {

   $menuArray = array(
       "Dashboard" => '',
       "Users" => '',
       "Orders" => '',
       "Config" => ''
       );
   
   foreach ($menuArray as $key => $value) {
       if ($key == $menu) $menuArray[$key] = 'active';
   }
   return $menuArray;
}

function timeSince($time)
{
   $time = time() - $time; // to get the time since that moment
   $time = ($time<1)? 1 : $time;
   $tokens = array (
      31536000 => 'año',
      2592000 => 'mes',
      604800 => 'semana',
      86400 => 'dia',
      3600 => 'hora',
      60 => 'minuto',
      1 => 'segundo'
   );

   foreach ($tokens as $unit => $text) {
      if ($time < $unit) continue;
      $numberOfUnits = floor($time / $unit);
      return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
   }
}
function formatYmd($date){
   setlocale(LC_TIME, 'es_ES.utf8','esp');
   $timestamp = strtotime($date);
   $dateFortmat = strftime('%e, de %b %Y', $timestamp);
   return $dateFortmat;
}
?>