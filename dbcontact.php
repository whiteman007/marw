<?php

$dbn='mysql:host=gqd.rzb.mybluehost.me;dbname=gqdrzbmy_WPJHG';
$user='gqdrzbmy_WPJHG';
$pass='381988Az@';
$option= array(
        PDO::MYSQL_ATTR_INIT_COMMAND =>'set NAMES utf8',
    );
    try {
      $con= new PDO($dbn,$user,$pass,$option);
      $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      // HERE SOMTHING TO PRINT
    } catch (PDOException $e) {
      echo "failed to connect" . $e->getmessage() . "</br>";

    }

 ?>
