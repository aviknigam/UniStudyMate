// Hide Header on on scroll down
var didScroll;
var lastScrollTop = 0;
var delta = 5;
var navbarHeight = document.getElementById("jsNavbar").offsetHeight;

window.onscroll = function(event){
    didScroll = true;
};

setInterval(function() {
    if (didScroll) {
        hasScrolled();
        didScroll = false;
    }
}, 250);

function hasScrolled() {
    var navbar = document.getElementById("jsNavbar");
    var st = this.scrollY;
    console.log(st);
    
    // Make sure they scroll more than delta
    if(Math.abs(lastScrollTop - st) <= delta)
    return;
    
    // If they scrolled down and are past the navbar, add class .nav-up.
    // This is necessary so you never see what is "behind" the navbar.
    if (st > lastScrollTop && st > navbarHeight){
        navbar.classList.remove('navbar-visible');
        navbar.classList.add('navbar-hidden');
        // Scroll Down
    } else {
        // Scroll Up
        if(st < lastScrollTop) {
            navbar.classList.remove('navbar-hidden');
            navbar.classList.add('navbar-visible');
        }
    }
    
    lastScrollTop = st;
}