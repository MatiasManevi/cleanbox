<html>
    <head>
        <?= $head ?>
        <script>
            $(document).ready(function(){
                $('#add_conc').hover(function(){
                    $('#tooltipConc').css('display','block');
                },function(){
                    $('#tooltipConc').css('display','none');
                });
                $(document).mousemove(function(event){
                    var mx = event.pageX;
                    var my = event.pageY;
                    $('#tooltipConc').css('left',mx+'px').css('right',my+'px').css('top',-18);
                })  
            });    
        </script>
    </head>
    <body>        
        <?= $header ?>
        <div class="contenedor_centro">
            <?= $content ?>
        </div>
        <?= $footer ?>
    </body>
    <div id="pop_back" onclick="close_pop_up()" >
    </div>
    <div id="pop">
        <img alt="" src="" />
    </div>
</html>
<div id="back_fader1">
    <div id="popup1">
    </div>
</div>
<div id="tooltipConc">
    Presione para agregar un Concepto nuevo
</div>