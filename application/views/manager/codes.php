<div style="margin-top:5px;position: absolute;top:497px"class="comments">
    <div style="width:100%;margin-bottom: 10px;"class="porcentajes">
        <label style="font-size: 15.2px;">Generador de Códigos</label>
        <input type="text" id="codigo" style="text-align: center;margin-right: 5px;font-size: 16px;width: 152px;" class="form-control ui-autocomplete-input" placeholder="Código Autorizador">
    </div>
    <input id="deb" class="btn btn-primary" style="margin-bottom: 15px; margin-top: 0px;line-height: 18px;font-family: 'futura';text-transform: inherit;
           font-size: 18px;" type="button" value="Generar Código" onclick="generarCodigo();//setTimeout(redirect, 10000);window.print();return false;" />
</div>



<script>
    var redirect = function redirect(){
        var prop = $('#con_prop').val();    
        request_redirect('<?= site_url() . 'redirect_debitos/' ?>'+prop,'','.contenedor_centro');
    }
    function generarCodigo(){
        request_post('<?= site_url() . 'generarCodigo/6' ?>','','#codigo');
    }
</script>    
