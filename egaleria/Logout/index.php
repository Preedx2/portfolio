<?php
   //Simple script for clearing users session

   session_start();

   if(isset($_SESSION['logged_in']))
   {  
      session_unset();
   }
   header('Location: ../index.php')
?>