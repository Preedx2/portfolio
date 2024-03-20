<?php
   session_start();
   //this file is in the parent directory, so we are making doubley sure that the subdir flag is not raised
   if(isset($_SESSION['subdir']))
      unset($_SESSION['subdir']);

   require_once "connect.php";

   try{
      $dbconnection = @new mysqli($host, $db_user, $db_password, $db_name);

      if($dbconnection->connect_errno!=0)
         throw new Exception(mysql_connect_errno());

      //if we are supposed to be showing the search results
      if(isset($_GET['search']))
      {
         if($_GET['search']==null){
            header('Location: index.php');
            exit();
         }

         //converting special characters in search to html entities
         $search = htmlentities($_GET['search'], ENT_QUOTES, "UTF-8");
         $search_array = explode(" ", $_GET['search']);

         //vars below need to be strings with "true" because 
         //of the foreach loop below, to build valid mysql query
         $tag_search = "true";   //query related to tags
         $tag_modified= false;
         $user_search = "true";  //query related to users
         $title_search= "true";  //query related to picture titles
         foreach ($search_array as $inquiry) {
            if(preg_match('/^tag:/',$inquiry))
            {
               $tag_modified = true;
               $tag_search = $tag_search." AND tag_desc='".substr($inquiry, 4)."'";
            }
            elseif (preg_match('/^uname:/',$inquiry)) 
            {
               $user_search = $user_search." AND uname='".substr($inquiry, 6)."'";
            }
            else
            {
               $title_search = $title_search." AND LOWER(title) LIKE LOWER('%".$inquiry."%')";
            }
         }

         //build query depending on search
         $query_txt = "SELECT DISTINCT p.* FROM pictures p JOIN users u ";
         if($tag_modified)
            $query_txt = $query_txt."JOIN tagged_pics tp JOIN tags t ";

         $query_txt = $query_txt."WHERE p.u_id = u.u_id ";
         if($tag_modified)
             $query_txt = $query_txt."AND tp.p_id = p.p_id AND tp.tag_id = t.tag_id ";

          $query_txt = $query_txt."AND ".$user_search." AND ".$title_search." ";
          if($tag_modified)
             $query_txt = $query_txt."AND ".$tag_search." ";

          $query_txt = $query_txt." ORDER BY post_date ASC";

         //get pictures results for queries above. 
         //queries are treated as if joined by AND statement
         if(!$qresult = $dbconnection->query($query_txt))
            throw new Exception($dbconnection->error);

         //make array of results. 
         //Needs to be the same array name as in else section below
         $pics_array;
         for($i=0 ; $row = $qresult->fetch_assoc() ; $i++) {
            $pics_array[$i]=$row;
         }

         unset($_GET['search']);
         $qresult->close();

      }
      else //if there was no search we simply show all the pictures
      {
         //get all pictures from the db, order by date ASC
         if(!$qresult = $dbconnection->query("SELECT * FROM pictures ORDER BY post_date ASC"))
            throw new Exception($dbconnection->error);

         //remembering the results in an array.
         //Needs to be the same array name as in if section above
         $pics_array;
         for($i=0 ; $row = $qresult->fetch_assoc() ; $i++) {
            $pics_array[$i]=$row;
         }

         $qresult->close();
      }

      $dbconnection->close();
   }
   catch(Exception $e){ //error messege depends on admin permissions
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
   <script src="Data/JS/main.js"></script>
   <link rel="stylesheet" type="text/css" href="style.css">
   <title>eGaleria</title>
</head>
<body onload="loadTheme()" class=".body-lightgrad">
   <?php include('header.php'); ?>
   <main class="gallery">
      <?php if(isset($_SESSION['e_pict_db'])){
         echo $_SESSION['e_pict_db'];
         unset($_SESSION['e_pict_db']);
      }?>
      <div class="row">
      <?php if(isset($pics_array) && count($pics_array)>0) { 
         foreach ($pics_array as $pic) { ?>
         <div class="col-b-3 col-m-4 col-s-6 col-t-12">
            <div class="gallery-frame">
               <a href="Picture/index.php?p_id=<?php echo $pic['p_id']; ?>">
               <img class="gallery-img" src="Data/<?php echo $pic['file_path']; ?>" alt="<?php echo $pic['title']; ?>" title="<?php echo $pic['title']; ?>">
               </a>
            </div>            
         </div>
      <?php } } ?> 
      </div>
   </main>
   <?php include('footer.php'); ?>
</body>
</html>