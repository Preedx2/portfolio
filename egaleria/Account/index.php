<?php
   session_start();
   $_SESSION['subdir'] = true;   //varaible used in header and footer
                                 //conveys if the .php file is in subdirectory
   if(isset($_GET['uname']))
      $uname = $_GET['uname'];
   else
      {  //allow only pages with set user name
         header("Location: ../index.php");
         exit();
      }

   try{
      require_once "../connect.php";

      $dbconnection = new mysqli($host, $db_user, $db_password, $db_name);

      if($dbconnection->connect_errno!=0)
         throw new Exception(mysql_connect_errno());

      //extracting avatar filepath from the users table
      if( $qresult = $dbconnection->query(sprintf("SELECT avatar, u_id FROM users WHERE uname='%s'", $uname)))
      {
         $howManyUsers = $qresult->num_rows;
         if($howManyUsers>0)
         {
            $row = $qresult->fetch_assoc();

            $avatar = $row['avatar'];
            $u_id = $row['u_id'];
         }
         else
         {
            throw new Exception("$uname - nie znaleziono takiego użytkownika.");
         }
         $qresult->close();
      }
      else
         throw new Exception($dbconnection->error);

      // ======   User Delete    ==========
      //Deletion rights - user or admin/moderator
      $can_delete = false;
      if((isset($_SESSION['my_uname']) && $_SESSION['my_uname'] == $uname) || (isset($_SESSION['my_role']) && $_SESSION['my_role']>0))
         $can_delete = true;

      //when user confirmed deletion
      if(isset($_POST['delete'])){
         //removing users pictures from database
         if(!$qresult = $dbconnection->query(sprintf("SELECT file_path FROM pictures WHERE u_id='%s'",$u_id)))
            throw new Exception($dbconnection->error);

         //deleting files from the server
         while($row = $qresult->fetch_row())
            if(!unlink("..\Data\\".$row[0]))
               throw new Exception("unlink fail");

         if(!$qresult = $dbconnection->query(sprintf("DELETE FROM pictures WHERE u_id='%s'",$u_id)))
            throw new Exception($dbconnection->error);



         //removing user
         if(!$qresult = $dbconnection->query(sprintf("DELETE FROM users WHERE u_id='%s'",$u_id)))
            throw new Exception($dbconnection->error);

         //removing avatar file from the server if changed
         if($avatar != "default_avatar.png")
            if(!unlink("..\Data\Avatars\\".$avatar))
               throw new Exception("unlink fail");

         unset($_POST['delete']);

         $qresult->close();
         //logout 
         if($_SESSION['my_uname'] == $uname){
            header("Location: ../Logout/index.php");
         }
      }

      
      $dbconnection->close();
   }
   catch(Exception $e){ 
   //error message depends on user permisions
      if(isset($_SESSION['my_role']) && $_SESSION['my_role']==1)
         $_SESSION['e_account_page'] = $e;   //admin message
      else
         $_SESSION['e_account_page'] = "Nie znaleziono podanego użytkownika ".$uname." bądź wystąpił błąd z bazą danych.";
   }

   //TODO avatar upload

?>

<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <script src="https://kit.fontawesome.com/45dd80398a.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="../style.css">
    <script src="../Data/JS/main.js"></script>

	<title>Konto <?php echo $uname;?> - eGaleria</title>
</head>
<body onload="loadTheme()" class=".body-lightgrad">
   <?php include('../header.php'); ?>
   <main>
      <!--  if no error was detected -->
      <?php   if(!isset($_SESSION['e_account_page']))   {?>
      <div class="acc">
         <div class="acc-info">
            <h4 id="acc-info-uname"><?php echo $uname;?></h4>
            <img src="../Data/Avatars/<?php echo $avatar;?>" alt="Zdjęcie profilowe <?php echo $uname;?>" title="Zdjęcie profilowe <?php echo $uname;?>" id="acc-info-upicture">
            <?php    if(isset($_SESSION['my_uname']) && $uname==$_SESSION['my_uname']){?>
            <button class="acc-info-show" id="acc-info-show" onclick="showChange()">Zmień Dane</button>
            <?php } ?>
            <?php if($can_delete) { ?>
               <form method="post" onsubmit='return confirm("Czy na pewno chcesz usunąć użytkownika?\n\nPoza samym użytkownikiem usunięte zostaną też jego obrazy!");'>
                  <input class="acc-info-show" type="submit" name="delete" value="Usuń Użytkownika">
               </form>
            <?php } ?>
            <form method="get" action="../index.php">
               <input type="hidden" name="search" value="uname:<?php echo $uname; ?>">
               <input type="submit" class="acc-info-show" value="Pokaż przesłane obrazy">
            </form>
         </div>

         <form class="acc-change" id="acc-change">
            <label>Adres e-mail:</label>
            <input class="acc-change-input" type="email" placeholder="Wpisz adres email" name="email">
            <label>Data urodzenia:</label>
            <input class="acc-change-input" type="date" name="date">
            <a href="../Change/index.php" title="Zmień Hasło" class="acc-change-pwd">Zmień Hasło</a>
            <input class="acc-change-submit" type="submit" value="Potwierdź">
         </form>
         
      </div>
      <?php   }else{ //if error is dected   ?>
      <div class="acc">
         <div class="error acc-error">
         <?php 
         echo $_SESSION['e_account_page'];
         unset($_SESSION['e_account_page']);
         ?>
         </div>
      </div>
      <?php   }?>
	</main>
	<?php include('../footer.php'); ?>
</body>
<?php unset($_SESSION['subdir']); ?>