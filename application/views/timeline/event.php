<div <?php echo isset($event['year']) ? 'class="year" id="year_'.$event['year'].'"' : '' ?>>
	<div class='container event'>
		<? if (count($event['pictures']) > 0) { ?>
			<div style="margin-left: -18px; margin-right: 15px;" class="col-lg-6">
				<div class="gallery owl-carousel">
					<? foreach ($event['pictures'] as $picture) { ?>
						<a href="<?php echo $picture['url'] ?>" data-fancybox="gallery_<?= $event['id'] ?>">
							<img class="picture" src="<?php echo $picture['url'] ?>" alt="" />
						</a>
					<? } ?>
				</div>
			</div>
			<div class="col-lg-6">
				<div class='content' style="margin-left: -45px">
					<h4 class="title"><?php echo $event['name'] ?></h4>
					<span class="date"><?php echo $event['date'] ?></span>	
					<p class="description"><?php echo $event['description'] ?></p>
				</div>
			</div>
		<? } else {?>
			<div class="col-lg-12">
				<div class='content'>
					<h4 class="title"><?php echo $event['name'] ?></h4>
					<span class="date"><?php echo $event['date'] ?></span>	
					<p class="description"><?php echo $event['description'] ?></p>
				</div>
			</div>
		<? } ?>
	</div>
</div>