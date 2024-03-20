<?php
   session_start();
   $_SESSION['subdir'] = true;

   if(!isset($_GET['p_id'])){
   	header("Location: ../index.php");
   	exit();
   }

   $p_id = $_GET['p_id'];

   require_once("../connect.php");

   try{
   	$dbconnection = @new mysqli($host, $db_user, $db_password, $db_name);

      if($dbconnection->connect_errno!=0)
         throw new Exception(mysql_connect_errno());

      if(!$qresult = $dbconnection->query(sprintf("SELECT * FROM pictures WHERE p_id='%s'",$p_id)))
      	throw new Exception($dbconnection->error);

      $row = $qresult->fetch_assoc();

      $title = $row['title'];
      $file_path = $row['file_path'];
      $post_date = $row['post_date'];
      $desc = $row['description'];
      $u_id = $row['u_id'];

      if(!$qresult = $dbconnection->query(sprintf("SELECT uname, avatar FROM users WHERE u_id='%s'",$u_id)))
      	throw new Exception($dbconnection->error);

      $row = $qresult->fetch_assoc();

      $uname = $row['uname'];
      $avatar = $row['avatar'];

      if(!$qresult = $dbconnection->query(sprintf("SELECT t.tag_id, t.tag_desc FROM tags t JOIN tagged_pics p WHERE t.tag_id=p.tag_id AND p.p_id='%s'",$p_id)))
      	throw new Exception($dbconnection->error);

      $tags_array;
      for($i=0 ; $row = $qresult->fetch_row() ; $i++) {
      	$tags_array[$i]=$row;
      }

      // ======	Picture Delete		==========
      //Deletion rights - uploader or admin/moderator
	   $can_delete = false;
	   if((isset($_SESSION['my_uname']) && $_SESSION['my_uname'] == $uname) || (isset($_SESSION['my_role']) && $_SESSION['my_role']>0))
	   	$can_delete = true;

	   //when user confirmed deletion
      if(isset($_POST['delete'])){
      	//removing tag associations
      	if(!$qresult = $dbconnection->query(sprintf("DELETE FROM tagged_pics WHERE p_id='%s'",$p_id)))
      	throw new Exception($dbconnection->error);

      	//removing picture from database
      	if(!$qresult = $dbconnection->query(sprintf("DELETE FROM pictures WHERE p_id='%s'",$p_id)))
      		throw new Exception($dbconnection->error);

      	//removing file from the server
      	if(!unlink("..\Data\\".$file_path))
      		throw new Exception("unlink fail");

      	unset($_POST['delete']);

      	header("Location: ../index.php");
      }

      $qresult->close();
      $dbconnection->close();
   }
   catch(Exception $e){
   	if(isset($_SESSION['my_role']) && $_SESSION['my_role']==1)
	   	$_SESSION['e_pict_db'] = $e;
	   else
	   	$_SESSION['e_pict_db'] = "Błąd z połączeniem z bazą danych. Prosimy zgłosić problem i spróbować ponownie później.";
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

    <!--change title to dynamic picture name after implementing Database-->
	<title>Obraz - eGaleria</title>
</head>
<body onload="loadTheme()" class=".body-lightgrad">
   <?php include('../header.php'); ?>
   <main>
   	<div class="pict-cont">
   		<?php 
   		if(isset($_SESSION['e_pict_db'])) { 
   			echo '<div class="error pict-cont-error">'.$_SESSION['e_pict_db'].'</div>';
   			unset($_SESSION['e_pict_db']);
   		} ?>
	      <img class="pict-cont-img" src="..\Data\<?php echo $file_path; ?>" alt="<?php echo $title; ?>" title="<?php echo $title; ?>">
	      <div class="pict-cont-info">
	      	<a class="pict-hidden-link " href="../Account/index.php?uname=<?php echo $uname; ?>">
			   	<div class="pict-cont-meta">
						<img class="pict-cont-avatar" src="..\Data\Avatars\<?php echo $avatar; ?>">
						<p class="pict-cont-uploader"><strong><?php echo $uname; ?></strong></p>
					</div>
				</a>
				<div class="pict-cont-box">
					<div class="pict-cont-desc">
						<h3 class="pict-cont-title"><?php echo $title; ?></h3>
						<h4 class="pict-cont-date">Zamieszczono: <?php echo $post_date; ?></h4>
						<p class="pict-cont-desc-text"><?php echo $desc; ?><br><br></p>
					</div>
					<?php if($can_delete) { ?>
						<form method="post" onsubmit='return confirm("Czy na pewno chcesz usunąć ten obraz?");'>
							<input class="pict-cont-delete" type="submit" name="delete" value="Usuń Obraz">
						</form>
					<?php } ?>
					<div class="pict-cont-tags">
						Tagi:
						<?php if(isset($tags_array)&& count($tags_array)>0)
						{
						foreach ($tags_array as $tag) {
							echo '<a class="pict-hidden-link" href="..\index.php?search=tag%3A'.$tag[1].'"><div class="pict-cont-tag">'.$tag[1].'</div></a>';
							}
						}
						else
						{
							echo "Obraz nieotagowany.";
						} ?>
					</div>
				</div>
			</div>
		</div>
	</main>
	<?php include('../footer.php'); ?>
</body>
<?php unset($_SESSION['subdir']); ?>