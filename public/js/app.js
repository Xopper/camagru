const burger = document.getElementById('burger');
const navLinks = document.getElementById('nav__links');

burger.addEventListener('click', function () {
	navLinks.classList.toggle("open");
	burger.classList.toggle("toggle");
});

// Clip path doesn't work in FF-41

