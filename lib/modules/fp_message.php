<?php

  // 
  // Module to show a fun random message on the front page,
  // depending on time of the year
  //


class fp_message {

  public function run() {
    $month = date('m');
    $day = date('d');  
 
    $christmas_has_arrived=array();$waiting_for_christmas=array();

    // Christmas day messages
    $christmas_has_arrived[] = 'CHRISTMAS HAS ARRIVED 🎄 🎄 🎄';
    $christmas_has_arrived[] = 'IT IS CHRISTMAS 🎄 🎄 🎄';
    
    // December messages
    $waiting_for_christmas[] = 'WAITING FOR CHRISTMAS 🎄';
    $waiting_for_christmas[] = (24-$day) . ' DAYS TO CHRISTMAS 🎄';
 
    $message = 'EPICNESS HAS ARRIVED'; // Message for the rest of the year
 
   if ($month == 12) {
     if($day != 24) {
       $max = count($waiting_for_christmas)-1;$random = rand(0, $max);
       $message = $waiting_for_christmas["$random"];
     } else {
       $max = count($christmas_has_arrived)-1;$random = rand(0, $max);
       $message = $christmas_has_arrived["$random"];
     }
   } else if ($month == 1) {
       $message = 'HAPPY NEW YEAR'; // Message for January
   }
   return $message;
  }
    
}