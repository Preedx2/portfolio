<?php
   session_start();
   //subdirectory flag required by header and footer .php
   $_SESSION['subdir'] = true;

   if(!isset($_SESSION['logged_in']))
   {	//redirect users that are not logged in
   	header("Location: ../index.php");
   	exit();
   }

   $u_id = $_SESSION['my_id'];

   require_once "../connect.php";

   try{
   	$dbconnection = @new mysqli($host, $db_user, $db_password, $db_name);

      if($dbconnection->connect_errno!=0)
         throw new Exception(mysql_connect_errno());

      //get all the tags from the database
      if($qresult = $dbconnection->query(sprintf("SELECT * FROM tags")))
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

      $dbconnection->close();
   }
   catch(Exception $e){
   	//error messege depends on user being an admin
   	if($_SESSION['my_role']==1)
   		$_SESSION['e_upload_db'] = $e;
   	else
   		$_SESSION['e_upload_db'] = "Błąd z połączeniem z bazą danych. Prosimy zgłosić problem i spróbować ponownie później.";
   }

   //if user pressed the submit button and file succesfully uploaded
   if(isset($_POST['upld_title']) && isset($_FILES['upld_file']))
   {
   	$valid_flag=true;

   	$title = $_POST['upld_title'];
   	if(isset($_POST['upld_desc']))
   		$desc = $_POST['upld_desc'];
   	else
   		$desc = null; //handling empty descriptions

   	//sanitization
   	$title = htmlentities($title, ENT_QUOTES, "UTF-8");

   	//convert endline chars into <br>
   	$desc  = nl2br(htmlentities($desc,  ENT_QUOTES, "UTF-8"));

   	//Enforcing title length constrains
   	if(strlen($title)<3 || strlen($title)>100){
   		$valid_flag=false;
   		$_SESSION['e_title'] = "Tytuł może zawierać od 3 do 100 znaków.";
   	}

   	//Enforcing description length constrains
   	if(isset($_POST['upld_desc']) && strlen($desc)>60000){
   		$valid_flag=false;
   		$_SESSION['e_desc'] = "Opis musi zawierać mniej niż 60 000 znaków.";
   	}

   	//checking directory in which the image will be saved
   	$target_dir = "..\Data\\".date('Y-m')."\\";

   	//if it doesnt exist then create it
   	if(!file_exists($target_dir))
   		mkdir($target_dir, 0777, true);

  		$file = $_FILES['upld_file']['name'];
  		$allowed_ext = array('jpeg', 'jpg', 'png', 'gif', 'bmp');
  		$file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

  		//checking if file is of allowed type
   	if(!in_array($file_ext,$allowed_ext) ) {
			$valid_flag=false;
   		$_SESSION['e_file'] = "Plik z niedozowolonym rozszerzeniem. Przyjmujemy tylko pliki jpeg, png, gif i bmp.";
		}

		//getting rid of whitespace in filename
		$title_nospace = preg_replace('/\s+/', '', $title);

		//building filepath in directory ..\Data\
		$target_fname = date('Y-m')."\\".time().'_'.$title_nospace.'.'.$file_ext;
		$target_path = "..\Data\\".$target_fname;

		//move file to target detination
	   if(!move_uploaded_file($_FILES['upld_file']['tmp_name'], $target_path)){
	   	$valid_flag=false; //if unsuccesfull
   		$_SESSION['e_file'] = "Problem przy przenoszeniu pliku.";
	   }

	   //if everything is ok than make db operations
		if($valid_flag){
	   	try{
	   		$dbconnection = @new mysqli($host, $db_user, $db_password, $db_name);

	   		if($dbconnection->connect_errno!=0)
	         	throw new Exception(mysql_connect_errno());

	         //sanitize filename
	         $target_fname = mysqli_real_escape_string($dbconnection, htmlentities($target_fname, ENT_QUOTES, "UTF-8"));
	         if($dbconnection->query("INSERT INTO pictures VALUES(NULL, '$title','$target_fname', DEFAULT, '$desc', '$u_id') "))
            {	
                  //remove errors after success if necessary
                  if(isset($_SESSION['e_title']))
                     unset($_SESSION['e_title']);
                  if(isset($_SESSION['e_desc']))
                     unset($_SESSION['e_desc']);
                  if(isset($_SESSION['e_file']))
                     unset($_SESSION['e_file']);
            }
            else
            {
               throw new Exception($dbconnection->error);
            }

            //handle tags that user checked-in
            $tags_ids = array();
            foreach ($tags_array as $tag) {
					if(isset($_POST['upld-tag-'.$tag[0]]))
						array_push($tags_ids, $tag[0]);
				}

				//get id of freshly uploaded picture
				if( $qresult = $dbconnection->query("SELECT p_id FROM pictures WHERE file_path='$target_fname' "))
				{
					$p_id = $qresult->fetch_assoc()['p_id'];
				}
				else
					throw new Exception($dbconnection->error);

				//for each selected tag create a new tag-pic association
				foreach($tags_ids as $tag_id){
					if(!$dbconnection->query("INSERT INTO tagged_pics VALUES('$tag_id','$p_id') "))
						throw new Exception($dbconnection->error);
				}
				$dbconnection->close();

				//after success redirect to page of uploaded picture
				header('Location: ../Picture/index.php?p_id='.$p_id);
	   	}
	   	catch(Exception $e){
	   		if($_SESSION['my_role']==1)
	   			$_SESSION['e_upload_db'] = $e;
	   		else
	   			$_SESSION['e_upload_db'] = "Błąd z połączeniem z bazą danych. Prosimy zgłosić problem i spróbować ponownie później.";
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
	<title>Nowy Obraz - eGaleria</title>
</head>
<body onload="loadTheme()" class=".body-lightgrad">
   <?php include('../header.php'); ?>
   <main>
   	<div class="upld-cont">
   		<?php 
   		if(isset($_SESSION['e_upload_db'])) { 
   			echo '<div class="error upld-cont-error">'.$_SESSION['e_upload_db'].'</div>';
   			unset($_SESSION['e_upload_db']);
   		} ?>
	      <img class="upld-cont-img" id="upld-img-preview" src="#" alt="Podgląd przesłanego pliku" title="Podgląd">
	      <form class="upld-cont-form" enctype="multipart/form-data" method="post">
	      	<label class="upld-cont-form-file">
	      		<input type="hidden" name="MAX_FILE_SIZE" value="30000000" />
					<input class="upld-hidden-file" name="upld_file" type="file" accept="image/*" required />
					Prześlij plik
				</label>
				<?php if(isset($_SESSION['e_file'])){ 
					echo '<div class="error">'.$_SESSION['e_file'].'</div>';
					unset($_SESSION['e_file']);
				} ?>
				<input class="upld-cont-form-title" name="upld_title" type="text" placeholder="Tytuł" required />
				<?php if(isset($_SESSION['e_title'])){ 
					echo '<div class="error">'.$_SESSION['e_title'].'</div>';
					unset($_SESSION['e_title']);
				} ?>
				<textarea class="upld-cont-form-desc" name="upld_desc" rows="5" cols="100" placeholder="Opis"></textarea>
				<?php if(isset($_SESSION['e_desc'])){ 
					echo '<div class="error">'.$_SESSION['e_desc'].'</div>';
					unset($_SESSION['e_desc']);
				} ?>
				Wybierz tagi:
				<div class="upld-cont-form-tags">
				<?php
				if(isset($tags_array) && count($tags_array)>0){
					foreach ($tags_array as $tag) {
						echo '<label class="upld-cont-form-tag"><input type="checkbox" name="upld-tag-'.$tag[0].'">'.$tag[1].'</label>';
					}
				}
				else
				{
					echo '<label class="error">Brak tagów do wyświetlenia!</label>';
				}	?>
				</div>
				<input class="upld-cont-form-submit" type="submit" value="Potwierdź" />
			</form>
		</div>
	</main>
	<?php include('../footer.php'); ?>
</body>
<?php unset($_SESSION['subdir']); ?>