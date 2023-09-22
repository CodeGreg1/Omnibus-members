(function () {

	"use strict";

	var autoplaySpeed = 5000;

	//====== ajax setup
	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});


	//====== sticky
	window.onscroll = function () {
		var header_navbar = document.querySelector(".navbar-area");
		var sticky = header_navbar.offsetTop;

		if (window.pageYOffset > sticky) {
			header_navbar.classList.add("sticky");
		} else {
			header_navbar.classList.remove("sticky");
		}
	};

	//===== navbar-toggler
	let navbarToggler = document.querySelector(".navbar-toggler");
	navbarToggler.addEventListener('click', function () {
		navbarToggler.classList.toggle("active");
	});


	//WOW Scroll Spy
	var wow = new WOW({
		//disabled for mobile
		mobile: false
	});
	wow.init();

	//====== counter up 
	var cu = new counterUp({
		start: 0,
		duration: 2000,
		intvalues: true,
		interval: 100,
	});
	cu.start();


	//======= portfolio-btn active
	var elements = document.getElementsByClassName("portfolio-btn");
	for (var i = 0; i < elements.length; i++) {
		elements[i].onclick = function () {

			// remove class from sibling

			var el = elements[0];
			while (el) {
				if (el.tagName === "BUTTON") {
					//remove class
					el.classList.remove("active");

				}
				// pass to the new sibling
				el = el.nextSibling;
			}

			this.classList.add("active");
		};
	}

	/*----------------------------/
	/* CAROUSEL
	/*---------------------------*/
	if($('.slick-carousel').length > 0) {
		$('.recent-works-section.slick-carousel .portfolio-container').slick({
			dots: true,
			autoplay: true,
			autoplaySpeed: autoplaySpeed,
			slidesToShow: 3,
			cssEase: 'ease-in',
			prevArrow: '<button type="button" data-role="none" class="btn slick-prev">Previous</button>',
			nextArrow: '<button type="button" data-role="none" class="btn slick-next">Next</button>',
			responsive: [
				{
					breakpoint: 993,
					settings: {
						slidesToShow: 2
					}
				},
				{
					breakpoint: 481,
					settings: {
						slidesToShow: 1
					}
				}
			]
		});

		$('.testimonial-section.slick-carousel .testimonial-container').slick({
			dots: false,
			autoplay: true,
			prevArrow: false,
    		nextArrow: false,
			autoplaySpeed: autoplaySpeed,
			slidesToShow: 2,
			cssEase: 'ease-in',
			responsive: [
				{
					breakpoint: 993,
					settings: {
						slidesToShow: 1
					}
				},
				{
					breakpoint: 481,
					settings: {
						slidesToShow: 1
					}
				}
			]
		});
	}

})();

// ====== scroll top js
function scrollTo(element, to = 0, duration= 1000) {

	const start = element.scrollTop;
	const change = to - start;
	const increment = 20;
	let currentTime = 0;

	const animateScroll = (() => {

		currentTime += increment;

		const val = Math.easeInOutQuad(currentTime, start, change, duration);

		element.scrollTop = val;

		if (currentTime < duration) {
			setTimeout(animateScroll, increment);
		}
	});

	animateScroll();
};

Math.easeInOutQuad = function (t, b, c, d) {

	t /= d/2;
	if (t < 1) return c/2*t*t + b;
	t--;
	return -c/2 * (t*(t-2) - 1) + b;
};

document.querySelector('.scroll-top').onclick = function () {
    e.preventDefault();

    let destination = $(this.hash);

    let scrollPosition
        = destination.offset().top - 50;
        
    let animationDuration = 500;

    $('html, body').animate({
        scrollTop: scrollPosition
    }, animationDuration);
}

