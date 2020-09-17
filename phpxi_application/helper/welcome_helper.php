<?php
if(!defined("PHPXI")){
  die("You cannot access this page.");
}


function utterances(){
  $utterances = array(
    "If you want to shine like the sun, first burn like the sun.",
    "Learn from yesterday, live for today, hope for tomorrow.",
    "Wealth is the slave of wise man, the master of a fool.",
    "One thing only I know, and that is that I know nothing.",
    "Time never comes again."
  );
  $id = rand(0,4);
  return $utterances[$id];
}