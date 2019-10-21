$(() => {
	let stickyTop = 0,
	scrollTarget = false;
	
	let nav = $('.timeline__nav'),
	years = $('li', nav),
	milestones = $('.timeline__section .year'),
	offsetTop = parseInt(nav.css('top'));

	const TIMELINE_VALUES = {
		start: 200,
		step: 10
	};

	$(window).resize(function () {
		nav.removeClass('fixed')

		stickyTop = nav.offset().top - offsetTop

		$(window).trigger('scroll')
	}).trigger('resize');

	$(window).scroll(function () {
		if ($(window).scrollTop() > stickyTop) {
			nav.addClass('fixed')
		} else {
			nav.removeClass('fixed')
		}
	}).trigger('scroll');

	years.find('span').click(function () {
		let nav_button = $(this).parent(),
		index = nav_button.index(),
		year = $(this).html(),
		milestone = $('#year_'+year);

		if (!nav_button.hasClass('active') && milestone.length) {
			scrollTarget = index

			let scrollTargetTop = milestone.offset().top - 80
			
			$('html, body').animate({ scrollTop: scrollTargetTop }, {
				duration: 400,
				complete: function complete() {
					scrollTarget = false
				}
			})
		}
	});

	$(window).scroll(function () {
		let viewLine = $(window).scrollTop() + $(window).height() / 3,
		active = -1

		if (scrollTarget === false) {
			milestones.each(function () {
				if ($(this).offset().top - viewLine > 0) {
					return false
				}
				
				active++
			})
		} else {
			active = scrollTarget
		}

		nav.css('top', -1 * active * TIMELINE_VALUES.step + TIMELINE_VALUES.start + 'px')
		nav.css('left', '100px')

		years.filter('.active').removeClass('active')
		years.eq(active != -1 ? active : 0).addClass('active')

	}).trigger('scroll');
});