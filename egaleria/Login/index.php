<?php
   session_start();
   //subdirectory flag used in footer.php and header.php
   $_SESSION['subdir'] = true;

   if(isset($_POST['uname']))
   {
      require_once "../connect.php";

      try
      {
         $dbconnection = @new mysqli($host, $db_user, $db_password, $db_name);

         if($dbconnection->connect_errno!=0)
            throw new Exception(mysql_connect_errno());

         $uname = $_POST['uname'];
         $pwd = $_POST['pwd'];

         //converting special characters to html entitities
         $uname = htmlentities($uname, ENT_QUOTES, "UTF-8");

         //requesting user information from database, input sanitized
         if( $qresult = $dbconnection->query(sprintf("SELECT * FROM users WHERE uname='%s'", mysqli_real_escape_string($dbconnection, $uname))))
         {  
            $howManyUsers = $qresult->num_rows;
            if($howManyUsers>0)  //if user with given uname exists
            {
               $row = $qresult->fetch_assoc();
               //if typed in password matches hashed stored password
               if(password_verify($pwd, $row['password']))
               {
                  //storing information about logged in user 
                  //and his attributes in session
                  $_SESSION['logged_in']=true;

                  $_SESSION['my_id']=$row['u_id'];
                  $_SESSION['my_uname']=$row['uname'];
                  $_SESSION['my_email']=$row['email'];
                  $_SESSION['my_birth_date']=$row['birth_date'];
                  $_SESSION['my_role']=$row['role'];

                  $qresult->close();
                  //redirect to main site
                  header('Location: ../index.php');
               }
               else
               {
                  $_SESSION['e_pwd'] = "Nieprawidłowa nazwa użytkownika lub hasło.";
               }
            }
            else
            {
               $_SESSION['e_pwd'] = "Nieprawidłowa nazwa użytkownika lub hasło.";
            }
         }
         else{
            throw new Exception($dbconnection->error);
         }

         $dbconnection->close();
      }
      catch(Exception $e)
      {
         echo '<span style="color:red;"><br><br>Błąd serwera. Przepraszamy za niedogodności<br></span>';

         //echo '<br>'.$e; - only for testing
      }
   }

   //TODO remember me checkbox
?>

<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <script src="https://kit.fontawesome.com/45dd80398a.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="../style.css">
    <script src="../Data/JS/main.js"></script>
	<title>Login - eGaleria</title>
</head>
<body onload="loadTheme()" class=".body-lightgrad">
   <?php include('../header.php'); ?>
   <main>
   	<form class="login" method="post">
      	<input class="login-uname" type="text" placeholder="Wpisz nazwę użytkownika" name="uname" required>
      	
      	<input class="login-pwd" type="password" placeholder="Wpisz Hasło" name="pwd" required>

      	<label><input class="login-chkbox" type="checkbox" checked="checked" name="remember">Pamiętaj mnie</label>

         <?php
            if(isset($_SESSION['e_pwd'])){ 
         ?>
            <div class="error login-error">
            <?php
            echo $_SESSION['e_pwd'];
            unset($_SESSION['e_pwd']);
            ?>
            </div>
         <?php
            }
         ?>

      	<input class="login-submit" type="submit" value="Zaloguj">

      	<button class="login-register" type="button" onclick="window.open('../Register/index.php', '_self')">Zarejestruj</button>

      	<div class="login-forgot">
      		<a href="../About/index.php">Zapomniałeś hasła?</a>
      	</div>
   	</form>
   </main>
   <?php include('../footer.php'); ?>
</body>
<?php unset($_SESSION['subdir']); ?>