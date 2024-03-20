<?php
   session_start();
   //ser flag for current file being in a subfolder, required for header.php and footer.php
   $_SESSION['subdir'] = true;   

   if(!isset($_SESSION['my_uname']))
   {
      header("Location: ../index.php");
      exit();  //allow only logged in users
   }
   $uname = $_SESSION['my_uname'];

   if(isset($_POST['pwd']))
   {
      $validate_flag=true;

      //Check passwords
      $pwd  = $_POST['pwd'];  //current
      $pwd1 = $_POST['pwd1']; //new 1
      $pwd2 = $_POST['pwd2']; //new 2

      //is password actually being changed
      if($pwd==$pwd1){
         $validate_flag=false;
         $_SESSION['e_pwd'] = "Nowe hasło musi być różne od starego hasła";
      }

      //Password length
      if(strlen($pwd1)<8 || strlen($pwd1)>30){
         $validate_flag=false;
         $_SESSION['e_pwd'] = "Hasło musi posiadać od 8 do 30 znaków.";
      }


      //Password1 == Password2
      if($pwd1 != $pwd2){
         $validate_flag=false;
         $_SESSION['e_pwd'] ="Podane hasła różnią się.";
      }

      if($validate_flag)
      {
         try{
            require_once "../connect.php";

            $dbconnection = new mysqli($host, $db_user, $db_password, $db_name);

            if($dbconnection->connect_errno!=0)
            {
               throw new Exception(mysql_connect_errno());
            }

            //extract informations about user form database
            if( $qresult = $dbconnection->query(sprintf("SELECT * FROM users WHERE uname='%s'", $uname)))
               {
                  $row = $qresult->fetch_assoc();

                  //verify if old password is correct
                  if(password_verify($pwd, $row['password']))
                  {
                     $pwd_hash = password_hash($pwd1, PASSWORD_DEFAULT);

                     //changing password stored in database
                     if($dbconnection->query(sprintf("UPDATE users SET password='%s' WHERE u_id='%s'", $pwd_hash, $row['u_id'])))

                     {  //redirect to account page if succesful
                        header("Location: ../Account/index.php?uname=$uname");
                        //unset error flags if exist
                        if(isset($_SESSION['e_pwd'] ))
                           unset($_SESSION['e_pwd']);
                     }
                     else
                     {
                        throw new Exception($dbconnection->error);
                     }
                  }
                  else
                  {
                     $_SESSION['e_pwd'] = "Nieprawidłowe stare hasło.";
                  }
                  $qresult->close();
               }
               else{
                  throw new Exception($dbconnection->error);
               }

               $dbconnection->close();
         }
         catch(Exception $e)
         {  //error messeges depent on if current user is an admin
         if(isset($_SESSION['my_role']) && $_SESSION['my_role']==1)
            $_SESSION['e_pwd'] = $e;
         else
            $_SESSION['e_pwd'] = "Błąd przy połączeniu z bazą danych. Spróbuj ponownie później.";
         }
      }
   }
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
      <form class="login" method="post">
         <div class="change-txt">Witaj <strong><?php echo $uname;?></strong>. Aby zmienić swoje hasło wypełnij poniższe pola:</div>
         <input class="change-pwd" type="password" placeholder="Stare Hasło" name="pwd" required>

         <input class="change-pwd" type="password" placeholder="Nowe Hasło" name="pwd1" required>

         <input class="change-pwd" type="password" placeholder="Powtórz Nowe Hasło" name="pwd2" required>

         <?php
            if(isset($_SESSION['e_pwd'])){ 
         ?>
            <div class="error change-error">
            <?php
            echo $_SESSION['e_pwd'];
            unset($_SESSION['e_pwd']);
            ?>
            </div>
         <?php
            }
         ?>

         <input class="login-submit" type="submit" value="Zmień Hasło">
      </form>
	</main>
	<?php include('../footer.php'); ?>
</body>
<?php unset($_SESSION['subdir']); ?>