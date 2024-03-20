// Please see documentation at https://docs.microsoft.com/aspnet/core/client-side/bundling-and-minification
// for details on configuring this project to bundle and minify static web assets.

// Write your JavaScript code.

/**
*   Funkcja pokazująca i ukrywająca proste menu z nagłówka
*   Główna logika znajduje się w pliku .css i _Layout.cshtml
*/
function menuAction() {
  var menu = document.getElementById('myLinks');
  if (menu.style.display == 'inline-block') {
    menu.style.display = 'none';
  } else {
    menu.style.display = 'inline-block';  
    }
} 