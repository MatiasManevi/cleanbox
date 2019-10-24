<article class="timeline">
	<nav class="timeline__nav">
		<ul>
			<? 
			$years = array_keys($timeline);
			if (count($years) > 1) { ?>
				<? foreach ($years as $year) { ?>
					<li><span><?php echo $year; ?></span></li>
				<? } ?>
			<? } ?>
		</ul>
	</nav>

	<section class="timeline__section">
		<div class="wrapper">
			<? foreach ($timeline as $year => $events) { ?>
				<? foreach ($events as $event) { ?>
					<?php echo $this->load->view('timeline/event', ['event' => $event], TRUE); ?>
				<? } ?>
			<? } ?>
		</div>
	</section>
</article>

<script type="text/javascript">
	$("[data-fancybox]").fancybox({
		padding : 0,
		loop: true
	});
	var owl = $('.gallery');
	owl.owlCarousel({
	    autoplay: false,
	 	items: 1,
	    nav:true,
	    margin:20,
	    animateOut: 'fadeOut',
	    dots: false,
	 	navText: ['<i style="padding:5px;color:white;font-size: 2.3em;margin-right:7px;background: black;opacity: 0.4;" class="glyphicon glyphicon-arrow-left"></i>', '<i style="padding:5px;color:white;font-size: 2.3em;background: black;opacity: 0.4;" class="glyphicon glyphicon-arrow-right"></i>']
	});
</script>