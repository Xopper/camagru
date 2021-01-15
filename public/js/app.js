window.addEventListener("load", function() {
	var el = document.querySelector("body");
	clearTimeout(resizeTimer);
	var resizeTimer = setTimeout(() => {
	  el.classList.remove("preload");
	}, 400);
	// el.classList.remove("preload");
});

window.addEventListener("resize", () => {
  document.body.classList.add("resize-animation-stopper");
  clearTimeout(resizeTimer);
  var resizeTimer = setTimeout(() => {
    document.body.classList.remove("resize-animation-stopper");
  }, 400);
});

const burger = document.getElementById('burger');
const navLinks = document.getElementById('nav__links');

burger.addEventListener('click', function () {
	navLinks.classList.toggle("open");
	burger.classList.toggle("toggle");
});

// Clip path doesn't work in FF-41

