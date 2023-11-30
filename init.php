<?php
  ini_set('display_errors','on');
  error_reporting(E_ALL);

  include 'dbcontact.php';

  // routs
  $tpl='includes/templets/';
  $func='includes/functions/';

  


  // include the important file
  include $tpl . 'header.php';
  include $func . 'functions.php';


?>