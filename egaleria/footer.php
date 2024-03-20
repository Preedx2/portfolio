<?php
if(isset($_SESSION['subdir']))
   $path = "../";
else
   $path = "";?>
<footer>
   <nav class="social">
      <button title="twitter" onclick="window.open('https://twitter.com' , '_blank')"><i class="fa fa-twitter"></i></button>
      <button title="facebook" onclick="window.open('https://facebook.com', '_blank')"><i class="fa fa-facebook"></i></button>
      <button title="instagram" onclick="window.open('https://instagram.com', '_blank')"><i class="fa fa-instagram"></i></button>
      <button title="gitHub" onclick="window.open('https://github.com',  '_blank')"><i class="fa fa-github"></i></button>     
   </nav>
   <button class="footer-about" title="Kontakt" onclick="window.open('<?php echo $path;?>About/index.php', '_self')"><i class="fa-solid fa-message"></i></button>
</footer>