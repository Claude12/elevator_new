
window.addEventListener('resize', toggleNav);

function toggleNav() {
  var width = window.innerWidth;
  var dropdownLink = document.getElementsByClassName("dropdown nav-link");
  if (width < 1199) {

    for(var e = 0; e < dropdownLink.length; e++) { // For each element
      var elt = dropdownLink[e];
      elt.removeAttribute("data-bs-hover","dropdown");
      elt.setAttribute("data-bs-toggle", "dropdown");
    }

  } else {


    for(var e = 0; e < dropdownLink.length; e++) { // For each element
      var elt = dropdownLink[e];
      elt.removeAttribute("data-bs-toggle","dropdown");
      elt.setAttribute("data-bs-hover", "dropdown");
    }
  }

}


/*jQuery(document).ready(function($) {

  $(window).resize(function() {
    if ($(window).width() < 768) {
      $(".dropdown.nav-link").children().click(function(){
        $(".dropdown.nav-link").removeAttr('data-toggle dropdown');
        var href = $(this).parent().attr('href');
        location.href = href;
      });
      $(".dropdown.nav-link").attr('data-toggle', 'dropdown');
      $(".dropdown.nav-link").removeAttr('data-hover dropdown');
    } else {

      $(".dropdown.nav-link").removeAttr('data-toggle dropdown');
      $(".dropdown.nav-link").attr('data-hover', 'dropdown');
    }
  }).resize();
});*/
