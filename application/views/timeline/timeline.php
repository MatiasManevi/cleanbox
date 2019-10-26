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
			<h2 class="timeline_title"><?php echo isset($property) ? 'Timeline de la propiedad ' . $property['prop_dom'] : 'Timeline general' ?></h2>
			<input type="text" id="timeline_filter" class="form-control" placeholder="Busca aqui escribiendo algo sobre el contenido, titulo o fecha del evento que quieras ver ...">
			<div class="events">
				<? foreach ($timeline as $year => $events) { ?>
					<? foreach ($events as $event) { ?>
						<?php echo $this->load->view('timeline/event', ['event' => $event], TRUE); ?>
					<? } ?>
				<? } ?>
			</div>
			<div class="no_records" style="<?php echo !empty($timeline) ? 'display:none' : 'display:block' ?>">
				No se encontraron eventos
			</div>
		</div>
	</section>
</article>

<script type="text/javascript">
	
	$(document).ready(function() {

	    $("#timeline_filter").on("keyup", function() {

	        var value = $(this).val().toLowerCase();

	        $(".timeline__section .event").filter(function() {
              $(this).toggle($(this).find('.data').text().toLowerCase().indexOf(value) > -1)
            });

            if ($('.event:visible').length < 1) {
            	$('.no_records').fadeIn('slow');
            }else{
            	$('.no_records').fadeOut('fast');
            }
	    });

	});

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