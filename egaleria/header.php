<?php
if(isset($_SESSION['subdir']))
   $path = "../";
else{
   $path = "";
   if(isset($_POST['search']))
      $search = $_POST['search'];
}
?>
<header>
   <a href="<?php echo $path;?>index.php" class="logo" >
      <nav class="logo-nav" id="logo-nav">
         <i class="fa fa-paint-brush"></i>
         <i class="logo-nav-txt">eGaleria</i>
         <i class="fa fa-palette"></i>
      </nav>
   </a>
   <nav class="searchbar" id="searchbar">
      <form method="get" action="<?php echo $path;?>index.php">
         <input type="text" placeholder="Wyszukaj..." name="search"
         <?php if(isset($search)) echo 'value="'.$search.' "' ?>>
         <button type="submit"><i class="fa fa-search"></i></button>
      </form>
   </nav>
   <nav class="menu" id="myLinks">
      <?php
         if(isset($_SESSION['logged_in'])){ 
      ?>
         <a href="<?php echo $path;?>Account/index.php?uname=<?php echo $_SESSION['my_uname'];?>">Konto</a>
         <a href="<?php echo $path;?>Logout/index.php">Wyloguj</a> 
         <a href="<?php echo $path;?>Upload/index.php" title="Prześlij obraz"><i class="fa fa-paint-brush"></i></a>
         <?php if($_SESSION['my_role']>0){ ?>
         <a href="<?php echo $path;?>Moderator/index.php">Moderacja</a>
      <?php  } } ?>
      <?php
         if(!isset($_SESSION['logged_in'])){ 
      ?>
         <a href="<?php echo $path;?>Login/index.php">Login</a> 
         <a href="<?php echo $path;?>Register/index.php">Rejestracja</a> 
      <?php   } ?>
      <a href="javascript:void(0);" title="Tryb ciemny" onclick="switchTheme()" class="menu-dark"><i class="fa-solid fa-moon"></i></a> 
   </nav>
   <a href="javascript:void(0);" title="Menu" class="menu-icon" onclick="menuAction(<?php if(isset($_SESSION['my_role']))
      echo $_SESSION['my_role']; else echo 0; ?>)"><i class="fa-solid fa-bars"></i></a>
</header>