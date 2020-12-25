const navSlide = () => {
	const burger = document.querySelector(".burger");
	const nav = document.querySelector(".nav__links");
	const navLinks = document.querySelectorAll(".nav__links li");

	burger.addEventListener("click", () => {
		// toggle Nav
		nav.classList.toggle("nav__active");

		// Animate Links
		navLinks.forEach((link, index) => {
			if (link.style.animation) {
				link.style.animation = "";
			} else {
				link.style.animation = `navLinkFade 0.5s ease forwards ${
					index / 7 + 0.5
				}s`;
			}
		});

		// animate burger
		burger.classList.toggle("toggle");
	});
};

navSlide();
