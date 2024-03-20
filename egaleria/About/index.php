<?php
   session_start();
   $_SESSION['subdir'] = true;
?>
<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <script src="https://kit.fontawesome.com/45dd80398a.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="../style.css">
    <script src="../Data/JS/main.js"></script>
	<title>Informacje - eGaleria</title>
</head>
<body onload="loadTheme()" class=".body-lightgrad">
   <?php include('../header.php'); ?>
   <main>
   	<div class="about">
			<h1>Informacje</h1>
			<h3>Kontakt</h3>
			<p>W przypadku <strong>jakichkolwiek</strong> problemów czy pytań <a href="mailto:example@example.com">skontaktuj się z nami</a></p>
			<h3>Wyszukiwanie</h3>
			<p>System wyszukiwania wspiera filtrowanie wyników po tagach, po nazwie użytkownika czy po tytule obrazka.
			<br/>Aby wyszukać po tagu należy wpisac w pole wyszukiwania "tag:PrzykładowyTag"
			<br/>Aby wyszukać po nazwie użytkownika należy wpisac w pole wyszukiwania "uname:PrzykładowyUżytkownik"
			<br/>Wyszukiwanie po tytułach nie wymaga żadnej specjalnej składni. Rodzaje wyszukiwań można dowolnie łączyć.</p>
			<h3>Regulamin</h3>
			<p class="about-agreements">Przykładowy blok tekstu mający na celu zobrazowanie jak strona na niego zareaguje przy użyciu różnych rozdzielczości
				<br><br>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi convallis ipsum ut odio euismod, sit amet eleifend urna vulputate. Vestibulum maximus felis quis sem auctor, ac feugiat orci luctus. Mauris semper orci nunc, quis rhoncus libero placerat ut. Cras quis viverra dolor. Morbi vehicula tincidunt mauris non semper. Curabitur non egestas mi. Nullam et mauris in metus tincidunt pretium.
				<br>Phasellus in velit vel metus posuere suscipit sed ut lorem. Quisque et diam eu tellus porta convallis. Nam quis ipsum arcu. Nullam elementum, augue et facilisis mattis, nibh sapien volutpat magna, malesuada interdum ex libero in justo. Suspendisse potenti. Donec venenatis egestas ex, id venenatis enim tempor quis. Integer sed ultrices ante. Nullam sed consequat nisl. Sed ex ex, lobortis at dui id, accumsan feugiat diam. Praesent at cursus ante. Nam efficitur diam vel pretium sodales. Aliquam nec orci maximus, lacinia enim ut, suscipit tellus. Vivamus vel tellus in lacus vulputate tempus. Morbi varius a orci vel ornare. Proin interdum ligula ac eros aliquet finibus. Proin pellentesque suscipit nisl a scelerisque.
				<br>Praesent nec sollicitudin eros. Maecenas tempor, mi id tristique malesuada, dui libero posuere sapien, cursus sodales sapien nunc sit amet quam. Nam in nunc leo. Mauris interdum condimentum tellus, sit amet rutrum libero placerat eu. Nulla dolor est, aliquam id enim non, tristique eleifend lacus. Sed leo purus, volutpat non ornare eu, malesuada vel enim. Quisque molestie risus vitae dolor dictum semper. Praesent varius justo sit amet ornare iaculis. Morbi malesuada velit tortor, eget iaculis eros tincidunt eget. Donec consequat nisl ac odio porta convallis. Morbi vestibulum enim ut libero hendrerit congue ac nec tortor. Donec sit amet mi et est consequat placerat.
			</p>
			<h3>Wykorzystane Zasoby w części I:</h3>
			<ul>
				<li><a href="https://elearning.po.edu.pl/course/view.php?id=9478" target="_blank">Materiały ze strony kursu</a></li>
				<li><a href="https://www.w3schools.com">Tutoriale ze strony W3schools.com</a></li>
				<li><a href="https://fontawesome.com" target="_blank">Font Awesome v6</a></li>
				<li><a href="https://www.pexels.com/pl-pl/" target="_blank">Przykładowe obrazki z pexels.com</a>
				<li><a href="https://www.lipsum.com/" target="_blank">Generator Lorem Ipsum</a></li>
			</ul>
			<h3>Wykorzystane Zasoby w części II:</h3>
			<ul>
				<li><a href="https://www.w3schools.com">Tutoriale ze strony W3schools.com</a></li>
				<li><a href="https://www.php.net/manual/en/index.php">Podręcznik php</a></li>
				<li><a href="https://regex101.com/">Tester wyrażeń regex</a></li>
				<li><a href="https://miroslawzelent.pl/kurs-php/">Kurs php Mirosława Zelenta</a></li>
			</ul>
		</div>
	</main>
	<?php include('../footer.php'); ?>
</body>
<?php unset($_SESSION['subdir']); ?>