<?php
   session_start();
   //subdirectory flag required by header and footer .php
   $_SESSION['subdir'] = true;

   if(!isset($_SESSION['my_role']) || ($_SESSION['my_role']<1))
   {	//redirect users that are not moderators
   	header("Location: ../index.php");
   	exit();
   }

   require_once "../connect.php";

   try{
   	$dbconnection = @new mysqli($host, $db_user, $db_password, $db_name);

      if($dbconnection->connect_errno!=0)
         throw new Exception(mysql_connect_errno());

      //get all the tags from the database, and their number of occurences in pictures
      if($qresult = $dbconnection->query(sprintf("SELECT t.*, COUNT(tp.p_id) AS tagged_nmbr FROM tags t LEFT JOIN tagged_pics tp ON t.tag_id = tp.tag_id GROUP BY t.tag_id,t.tag_desc ORDER BY tagged_nmbr DESC;")))
      {
      	$tags_array;

      	//store tags in an array
      	for($i=0 ; $row = $qresult->fetch_row() ; $i++) {
      		$tags_array[$i]=$row;	
      	}

      	$qresult->close();
      }
      else
         throw new Exception($dbconnection->error);

      //get all the user names from database
      if($qresult = $dbconnection->query(sprintf("SELECT uname FROM users")))
      {
      	$users_array;

      	//store tags in an array
      	for($i=0 ; $row = $qresult->fetch_row() ; $i++) {
      		$users_array[$i]=$row;	
      	}

      	$qresult->close();
      }
      else
         throw new Exception($dbconnection->error);


      //Deletion of tags
      if(isset($_POST['tag_delete'])){

      	//handle tags that user checked-in
         $tags_ids = array();
         foreach ($tags_array as $tag) {
				if(isset($_POST['tag_'.$tag[0]]))
					array_push($tags_ids, $tag[0]);
			}

			//for each selected tag
			foreach($tags_ids as $tag_id){
				//delete associations with pictures
				if(!$dbconnection->query(sprintf("DELETE FROM tagged_pics WHERE tag_id='%s'",$tag_id)))
						throw new Exception($dbconnection->error);

				//delete tag from the table
				if(!$dbconnection->query(sprintf("DELETE FROM tags WHERE tag_id='%s'",$tag_id)))
						throw new Exception($dbconnection->error);
			}

      	unset($_POST['tag_delete']);
      	header("Location: index.php");
      }

      //Addition of tag
      if(isset($_POST['tag_add'])){
      	$valid_flag = true;
      	$tag_desc = htmlentities($_POST['tag_desc'], ENT_QUOTES, "UTF-8");

      	//check tag length
      	if(strlen($tag_desc)<3 || strlen($tag_desc)>20)
      	{
      		$valid_flag = false;
      		$_SESSION['e_tag_add'] = "Tag może zawierać od 3 do 20 znaków.";
      	}

      	//check if tag with given name already exists
      	foreach($tags_array as $tag){
      		if($tag[1]==$tag_desc){
      			$valid_flag = false;
      			$_SESSION['e_tag_add'] = "Tag o nazwie ".$tag_desc." znajduje się już w bazie danych.";
      		}
      	}

      	//sanitize
      	$tag_desc = mysqli_real_escape_string($dbconnection, $tag_desc);

      	//if everything is ok
      	if($valid_flag){
	      	if(!$dbconnection->query(sprintf("INSERT INTO tags VALUES(NULL, '%s')",$tag_desc)))
	      		throw new Exception($dbconnection->error);

	      	header("Location: index.php");
      	}
      	
      	unset($tag_desc);
      	unset($_POST['tag_add']);
      	unset($_POST['tag_desc']);
      }

      $dbconnection->close();
   }
   catch(Exception $e){
   	//error messege depends on user being an admin
   	if($_SESSION['my_role']==1)
   		$_SESSION['e_upload_db'] = $e;
   	else
   		$_SESSION['e_upload_db'] = "Błąd z połączeniem z bazą danych. Prosimy zgłosić problem i spróbować ponownie później.";
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
	<title>Moderacja - eGaleria</title>
</head>
<body onload="loadTheme()" class=".body-lightgrad">
   <?php include('../header.php'); ?>
   <main>
   	<div class="mod">
   		<?php 
   		if(isset($_SESSION['e_upload_db'])) { 
   			echo '<div class="error upld-cont-error">'.$_SESSION['e_upload_db'].'</div>';
   			unset($_SESSION['e_upload_db']);
   		} ?>
   		<form class="mod-form-td" method="post">
   			<h3>Lista tagów</h3>Lista zawiera wszystkie zawarte w bazie tagi, razem z liczbą wystąpień w obrazach.<br>Jeśli chcesz usunąć tagi - zaznacz je i potwierdź.<br/><br/>
   			<div class="mod-tags">
					<?php
					if(isset($tags_array) && count($tags_array)>0){
						foreach ($tags_array as $tag) {
							echo '<label class="tag-inline"><input type="checkbox" name="tag_'.$tag[0].'">'.$tag[1].' #'.$tag[2].'</label>';
						}
					}
					else
					{
						echo '<label class="error">Brak tagów do wyświetlenia!</label>';
					}	?>
				</div>
				<input type="submit" class="acc-info-show" name="tag_delete" value="Usuń wybrane tagi">
			</form>
			<form class="mod-form-add" method="post">
				Jeśli chcesz dodać nowy tag nadaj mu nazwę i użyj przycisku poniżej<br><br>
				<input type="text" name="tag_desc" placeholder="Tytuł taga" required>
				<input type="submit" class="acc-info-show" name="tag_add" value="Dodaj nowy tag">
				<?php if(isset($_SESSION['e_tag_add'])) { ?>
					<div class="error"><?php echo $_SESSION['e_tag_add']; ?></div>
				<?php unset($_SESSION['e_tag_add']); } ?>
			</form>

			<div class="mod-userlist">
				<h3> Lista Użytkowników:</h3>
				<?php
					if(isset($users_array) && count($users_array)>0){
						foreach ($users_array as $user) {
							echo '<a class="mod-user" href="../Account/index.php?uname='.$user[0].'">'.$user[0].'  </a>';
						}
					}
					else
					{
						echo '<label class="error">Brak użytkowników do wyświetlenia!</label>';
					}	?>
			</div>
		</div>
	</main>
	<?php include('../footer.php'); ?>
</body>
<?php unset($_SESSION['subdir']); ?>