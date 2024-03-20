<?php
   namespace Register;
   
   require "RegisterValidator.php";

   session_start();
   //rising flag for file being in a subdirectory, used in footer.php and header.php
   $_SESSION['subdir'] = true;

   if(isset($_POST['uname']))
   {
      $valid_flag = true;

      $uname = $_POST['uname'];

      //Check name length
      if(!RegisterValidator::username_length($uname))
      {
         $valid_flag = false;
         $_SESSION['e_error'] = true; //common error flag, changes display
         $_SESSION['e_uname'] = "Nazwa użytkownika musi posiadać od 4 do 30 znaków.";
      }

      //Check alphanumeric, important! - before html entities
      if(!RegisterValidator::username_regex($uname))
      {
         $valid_flag = false;
         $_SESSION['e_error'] = true;
         $_SESSION['e_uname'] = "Nazwa użytkownika może składać się z liter, cyfr, oraz znaku _ (bez polskich liter)";
      }

      //converting characters to html entities
      $uname = htmlentities($uname, ENT_QUOTES, "UTF-8");

      //Check and sanitize email
      $email = $_POST['email'];
      $emailb = filter_var($email, FILTER_SANITIZE_EMAIL);

      if(!filter_var($emailb, FILTER_VALIDATE_EMAIL) || $emailb!=$email)
      {
         $valid_flag = false;
         $_SESSION['e_error'] = true;
         $_SESSION['e_email'] = "Podaj poprawny adres E-mail.";
      }

      //Check passwords
      $pwd1 = $_POST['pwd1'];
      $pwd2 = $_POST['pwd2'];

      //Password length
      if(!RegisterValidator::password_length($pwd1))
      {
         $valid_flag = false;
         $_SESSION['e_error'] = true;
         $_SESSION['e_pwd'] = "Hasło musi posiadać od 8 do 30 znaków.";
      }

      //Password1 must == Password2
      if(!RegisterValidator::password_compare($pwd1, $pwd2))
      {
         $valid_flag = false;
         $_SESSION['e_error'] = true;
         $_SESSION['e_pwd'] ="Podane hasła różnią się.";
      }

      $pwd_hash = password_hash($pwd1, PASSWORD_DEFAULT);

      //Check birthdate
      $date = $_POST['date'];
      $dob = new \DateTime($date);

      //Birthdate must be in the past
      if(!RegisterValidator::date_check($dob))
      {
         $valid_flag = false;
         $_SESSION['e_error'] = true;
         $_SESSION['e_date'] ="Błędna data urodzin.";
      }

      //remember user input in case of an error
      $_SESSION['fr_uname']=$uname;
      $_SESSION['fr_email']=$email;
      $_SESSION['fr_pwd1'] =$pwd1;
      $_SESSION['fr_pwd2'] =$pwd2;
      $_SESSION['fr_date'] =$date;
      if(isset($_POST['agreement']))
         $_SESSION['fr_agreement']=true;

      require_once "../connect.php";

      try
      {
         $dbconnection = new \mysqli($host, $db_user, $db_password, $db_name);

         if($dbconnection->connect_errno!=0)
         {
            throw new Exception(mysql_connect_errno());
         }
         else
         {  //try to find users with given email
            $qresult = $dbconnection->query("SELECT u_id FROM users WHERE email='$email'");

            if(!$qresult)
               throw new Exception($dbconnection->error);

            $howManyEmails = $qresult->num_rows;
            if($howManyEmails>0) 
            {  //if email is already used, rise an error
               $valid_flag = false;
               $_SESSION['e_error'] = true;
               $_SESSION['e_email'] ="Do podanego adresu email jest już przypisane istniejące konto.";
            }
            //sanitize uname
            $uname = mysqli_real_escape_string($dbconnection, $uname);
            $qresult = $dbconnection->query("SELECT u_id FROM users WHERE uname='$uname'"); 
            //try to find users with given name

            if(!$qresult)
               throw new \Exception($dbconnection->error);

            $howManyNames = $qresult->num_rows;
            if($howManyNames>0)
            {  //if name already used, rise an error
               $valid_flag = false;
               $_SESSION['e_error'] = true;
               $_SESSION['e_uname'] ="Wybrana nazwa użytkownika jest już zajęta";
            }

            if($valid_flag) //if everything ok, insert user into db
            {  
               if($dbconnection->query("INSERT INTO users VALUES(NULL, '$uname','$email', '$pwd_hash', '$date', '0', 'default_avatar.png', DEFAULT) "))
               {
                     //$_SESSION['reg_ok']=true;
                     header('Location: ../Login');

                     //unset all the local variables stored in session
                     if(isset($_SESSION['fr_uname']))
                        unset($_SESSION['fr_uname']);
                     if(isset($_SESSION['fr_email']))
                        unset($_SESSION['fr_email']);
                     if(isset($_SESSION['fr_pwd1'] ))
                        unset($_SESSION['fr_pwd1']);
                     if(isset($_SESSION['fr_pwd2'] ))
                        unset($_SESSION['fr_pwd2']);
                     if(isset($_SESSION['fr_date'] ))
                        unset($_SESSION['fr_date']);
                     if(isset($_POST['fr_agreement']))
                        unset($_POST['fr_agreement']);

                     if(isset($_SESSION['e_uname']))
                        unset($_SESSION['e_uname']);
                     if(isset($_SESSION['e_email']))
                        unset($_SESSION['e_email']);
                     if(isset($_SESSION['e_pwd'] ))
                        unset($_SESSION['e_pwd']);
                     if(isset($_SESSION['e_date']))
                        unset($_SESSION['e_date']);
               }
               else
               {
                  throw new \Exception($dbconnection->error);
               }
            }

            $qresult -> close();
            $dbconnection->close();
         }
      }
      catch(\Exception $e)
      {
         echo '<span style="color:red;"><br><br>Błąd serwera. Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie<br></span>';

         //echo '<br><br>'.$e; - only for testing
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
	<title>Rejestracja - eGaleria</title>
</head>
<body onload="loadTheme()" class=".body-lightgrad">
   <?php include('../header.php'); ?>
   <main>
   	<form class="reg" method="post" <?php    
      if(isset($_SESSION['e_error'])){    //error changes display
         echo 'style="height: 600px;"';
         unset($_SESSION['e_error']);    }?>>
   		<div class="reg-uname">
   			<label>Nazwa użytkownika:</label>
      		<input type="text" name="uname" required value="<?php
            if(isset($_SESSION['fr_uname']))
            {
               echo $_SESSION['fr_uname'];
               unset( $_SESSION['fr_uname']);
            }
            else
            {
               echo '" placeholder="Wpisz nazwę użytkownika';
            }
            ?>" >
            <?php
               if(isset($_SESSION['e_uname'])){ 
               ?>
               <div class="error">
               <?php
               echo $_SESSION['e_uname'];
               unset($_SESSION['e_uname']);
               ?>
               </div>
               <?php
               }
            ?>
      	</div>

      	<div class="reg-email">
   			<label>Adres e-mail:</label>
      		<input type="email" name="email" required value="<?php
            if(isset($_SESSION['fr_email']))
            {
               echo $_SESSION['fr_email'];
               unset( $_SESSION['fr_email']);
            }
            else
            {
               echo '" placeholder="Wpisz adres email';
            }
            ?>" >
            <?php 
            if(isset($_SESSION['e_email']))
            {
               echo '<div class="error">'.$_SESSION['e_email'].' </div>';
               unset($_SESSION['e_email']);
            }
            ?>
      	</div>

      	<div class="reg-pwd">
   			<label>Hasło:</label>
      		<input type="password" name="pwd1" required value="<?php
            if(isset($_SESSION['fr_pwd1']))
            {
               echo $_SESSION['fr_pwd1'];
               unset( $_SESSION['fr_pwd1']);
            }
            else
            {
               echo '" placeholder="Wpisz hasło';
            }
            ?>" >
            <?php 
            if(isset($_SESSION['e_pwd']))
            {
               echo '<div class="error">'.$_SESSION['e_pwd'].' </div>';
               unset($_SESSION['e_pwd']);
            }
            ?>
      	</div>

      	<div class="reg-pwd2">
   			<label>Powtórz hasło:</label>
      		<input type="password" name="pwd2" required value="<?php
               if(isset($_SESSION['fr_pwd2']))
               {
                  echo $_SESSION['fr_pwd2'];
                  unset( $_SESSION['fr_pwd2']);
               }
               else
               {
                  echo '" placeholder="Wpisz hasło ponownie';
               }
            ?>" >
      	</div>

      	<div class="reg-date">
   			<label>Data urodzenia:</label>
      		<input type="date" name="date" required value="<?php
            if(isset($_SESSION['fr_date']))
            {
               echo $_SESSION['fr_date'];
               unset( $_SESSION['fr_date']);
            }
            ?>" >
            <?php 
               if(isset($_SESSION['e_date']))
               {
                  echo '<div class="error">'.$_SESSION['e_date'].' </div>';
                  unset($_SESSION['e_date']);
               }
            ?>
      	</div>

      	<div class="reg-agree">
      		<label><input type="checkbox" name="agreement" required 
            <?php
            if(isset($_SESSION['fr_agreement']))
            {
               echo "checked";
               unset( $_SESSION['fr_agreement']);
            }
            ?>>Zapoznałem się z <a href="../About/index.php">regulaminem</a></label>
            <?php 
               if(isset($_SESSION['e_agreement']))
               {
                  echo '<div class="error">'.$_SESSION['e_agreement'].' </div>';
                  unset($_SESSION['e_agreement']);
               }
            ?>
      	</div>

      	<input class="reg-submit" type="submit" value="Potwierdź"></button>
   	</form>
   </main>
   <?php include('../footer.php'); ?>
</body>
<?php unset($_SESSION['subdir']); ?>