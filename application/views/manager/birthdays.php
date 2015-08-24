<div id="cumplidores" class="alert alert-info">
    El dia de hoy, <?= date('d-m-Y') ?>, cumplen años... 
    <?
    for ($i = 0; $i < count($cumplen); $i++) {
        ?>
        <div class="cumpl"><?= $cumplen[$i] ?></div>
        <?
    }
    ?>

</div>