var leftToggle = document.getElementById('left-toggle');
var leftSlide = document.getElementById('left-slide');

var rightToggle = document.getElementById('right-toggle');
var rightSlide = document.getElementById('right-slide');

leftToggle.addEventListener('click', () => {
    leftSlide.classList.toggle('left-slide-open');
    leftToggle.classList.toggle('left-toggle-active');
});

rightToggle.addEventListener('click', () => {
    rightSlide.classList.toggle('right-slide-open');
    rightToggle.classList.toggle('right-toggle-active');
});


