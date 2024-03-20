/**
 * Function for loading theme from localStorage.
 * Works on chromium browser, doesn't work on firefox
 * On firefox each subdomain seems to have their own 
 * localStorage
 */
function loadTheme() {
  var theme = localStorage.getItem('theme');
  if(localStorage.length == 0){
    localStorage.setItem('theme', 'default')
  }

  if(theme == 'default') {
    lightMode();
  } else if(theme == 'dark') {
    darkMode();
  }
}

 /**
  * Function for changing values of color variables
  * to switch between default theme and dark theme.
  * Current theme is remembered in localStorage.
  * Works on chromium browser, doesn't work on firefox
  * On firefox each subdomain seems to have their own 
  * localStorage
  */
function switchTheme() {
  var theme = localStorage.getItem('theme');
  if (theme == 'default') {
    darkMode();    
  } else {
    lightMode();
  }
}

/**
 * Changes css variables to desired values and switches
 * background gradient to desired one
 */
function darkMode() {
  document.documentElement.style.setProperty('--plain-bckgrnd', '#1F1F1F');
  document.documentElement.style.setProperty('--box-bckgrnd','#5F5F5F')
  document.documentElement.style.setProperty('--bttn-bckgrnd','#393939')
  document.documentElement.style.setProperty('--bttn-dark-bckgrnd','#AAA')
  document.documentElement.style.setProperty('--bttn-bckgrnd-hvr','#CCC')

  document.documentElement.style.setProperty('--txt','#EEE')
  document.documentElement.style.setProperty('--box-txt','#EEE')
  document.documentElement.style.setProperty('--bttn','#EEE')
  document.documentElement.style.setProperty('--bttn-dark','#EEE')
  document.documentElement.style.setProperty('--bttn-hvr','#1F1F1F')

  document.documentElement.style.setProperty('--error','#FF0000')

  document.documentElement.style.setProperty('--link','#00AAFF')
  document.documentElement.style.setProperty('--visited','#7788CC')

  document.documentElement.style.setProperty('--bttn-brdr','#EEE') 

  document.body.classList.add('body-darkgrad');
  document.body.classList.remove('body-lightgrad');

  localStorage.setItem('theme','dark');
}

/**
 * Changes css variables to desired values and switches
 * background gradient to desired one
 */
function lightMode() { 
  document.documentElement.style.setProperty('--plain-bckgrnd', '#FFF');
  document.documentElement.style.setProperty('--box-bckgrnd','#EEE')
  document.documentElement.style.setProperty('--bttn-bckgrnd','#00AAFF')
  document.documentElement.style.setProperty('--bttn-dark-bckgrnd','#00AAFF')
  document.documentElement.style.setProperty('--bttn-bckgrnd-hvr','#22CCFF')

  document.documentElement.style.setProperty('--txt','#000')
  document.documentElement.style.setProperty('--box-txt','#000')
  document.documentElement.style.setProperty('--bttn','#FFF')
  document.documentElement.style.setProperty('--bttn-dark','#FFF')
  document.documentElement.style.setProperty('--bttn-hvr','#FFF')

  document.documentElement.style.setProperty('--error','#FF0000')
  
  document.documentElement.style.setProperty('--link','#0000EE')
  document.documentElement.style.setProperty('--visited','#551A8B')
  
  document.documentElement.style.setProperty('--bttn-brdr','#EEE')

  document.body.classList.add('body-lightgrad');
  document.body.classList.remove('body-darkgrad');

  localStorage.setItem('theme','default');
}

/**
 * Function for dynamically showing and hiding
 * menu in the header with the press of a button.
 * If screen is wide then it shrinks searchbar to make space
 * otherwise it hides searchbar altogether.
 * 
 * If User is an admin/moderator it makes more room.
 * NOTE - even regular user can see moderator buttons
 * if they modify the site via "inspect element",
 * proper validation of user role is executed after they
 * click the button
 */
function menuAction(role) {
  var menu = document.getElementById('myLinks');
  var sbar = document.getElementById('searchbar');
  var logo = document.getElementById('logo-nav');

  if (menu.style.display == 'block') {
    menu.style.display = 'none';

    if(getWidth() < 870 || ((role>0) && (getWidth() < 1000))){
      sbar.style.display = 'block';
      sbar.style.marginRight = '70px';
      logo.style.display = 'block';
    } else {
      sbar.style.marginRight = '150px';
    }

  } else {
    menu.style.display = 'block';  

    if(getWidth() < 870 || ((role>0) && (getWidth() < 1000))){
      sbar.style.display = 'none';
      logo.style.display = 'none';
    } else {
      if(role>0)
        sbar.style.marginRight = '440px';
      else
        sbar.style.marginRight = '365px';;
    }
  }
} 

/**
 * Function for checking screen width, should work
 * on all modern browsers
 */
function getWidth() {
  return Math.max(
    document.body.scrollWidth,
    document.documentElement.scrollWidth,
    document.body.offsetWidth,
    document.documentElement.offsetWidth,
    document.documentElement.clientWidth
  );
}

/**
 * Function for dynamically showing and hiding
 * form in subpage Account/index.html
 */
function showChange() {
  var form = document.getElementById("acc-change");
  var bttn = document.getElementById("acc-info-show");
  if(form.style.display == 'inline-block') {
    form.style.display = 'none';
    if(getWidth() < 870) {
      form.style.marginBottom = '10px';
    }
  } else {
    form.style.display = 'inline-block';
    if(getWidth() < 870) {
      form.style.marginBottom = '50px';
    }
  }
}
