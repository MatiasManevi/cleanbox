<div id="chooseProviderModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" style="width: 95%;">
        <div class="modal-content">
            <div class="modal-header back_menu">
                <h4 class="modal-title text-center _title"></h4>
            </div>
            <div class="modal-body section">
                <!--  Lista de Proveedores  -->
                <div class="filter_container _proveedores_filter">
                    <select class="form-control ui-autocomplete-input filter_input" onchange="general_scripts.filterByValue($(this).val(), 'proveedores', 'area_area', true)">
                        <option value="Area" selected="selected">Filtre por rubro</option>
                        <option value="Plomero">Plomero</option>
                        <option value="Carpintero">Carpintero</option>
                        <option value="Refrigeracion">Refrigeracion</option>
                        <option value="Persianas">Persianas</option>
                        <option value="Vidriero">Vidriero</option>
                        <option value="Ascensores">Ascensores</option>
                        <option value="Pintor">Pintor</option>
                        <option value="Escribano">Escribano</option>
                        <option value="Abogado">Abogado</option>
                        <option value="Agrimensor">Agrimensor</option>
                        <option value="Contador">Contador</option>
                        <option value="Techistas">Techistas</option>
                        <option value="Cerrajeros">Cerrajeros</option>
                        <option value="Electricista">Electricista</option>
                        <option value="Gasista">Gasista</option>
                        <option value="Albañil">Albañil</option>
                        <option value="Aire Acond.">Aire Acond.</option>
                    </select>
                    <input class="form-control ui-autocomplete-input _search_input_choosing filter_input" type="text" aria-haspopup="true" aria-autocomplete="list" role="textbox" autocomplete="off" placeholder="Buscar Proovedor">
                    <a onclick="general_scripts.refreshList('proveedores', 'prov_id', 'prov_name', true)" class="refresh glyphicon glyphicon-refresh" href="javascript:;" title="Recargar lista"></a>
                </div>

                <div class="_list_choose_provider">
                    <table class="table table-hover _table _proveedores_table">
                        <tr>    
                            <th>Nombre</th>
                            <th>Telefono</th>
                            <th>Email</th>    
                            <th>Domicilio</th>
                            <th>Calificación</th>
                            <th>Acciones</th>
                        </tr>
                    </table>
                </div>
            </div>    
            <div class="modal-footer back_menu">
                <a type="button" data-dismiss="modal" class="btn btn-primary">Cancelar</a>
            </div>
        </div>
    </div>
</div> 