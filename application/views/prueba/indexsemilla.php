<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <div class="panel-control">
                    <ul class="pager pager-rounded">
                        <li><a href="javascript:void(0)" onclick="refrescar()"  >Actualizar</a></li>
                    </ul>
                    <button class="add-tooltip  btn btn-default btn-lg"  data-toggle="tooltip" data-container="body" data-placement="left" data-original-title="Representa los datos de analisis de suelos"  ><i class="fa fa-question-circle-o"></i></button>

                </div>
                <h3 class="panel-title">Plan de Costos</h3>
            </div>

            <!--Data Table-->
            <!--===================================================-->
            <div class="panel-body" style="padding-top: 0px;" >
                <br>
                <div class="table-toolbar-left">
                    <!--<button class="btn btn-purple" id="add"><i class="demo-pli-add icon-fw"></i> Agregar</button> -->
                    <button class="btn btn-purple" id="btnAdd"><i class="demo-pli-add icon-fw"></i> Agregar Nuevo</button>

                </div>
                <br><br>
                <!--CUerpot aqui -->
                <h1></h1>
                <table id="tabla_grid" class="table table-hover table-bordered table-condensed " cellspacing="0" width="100%">
                    <thead>
                    <tr><th>#</th>
                        <th>Campaña </th>
                        <th>Cultivo </th>
                        <th>Categoría </th>
                        <th>Variedad </th>
                        <th>Localidad</th>
                        <th>Área </th>
                        <th>Coste total </th>
                        <th>Acción</th>

                    </tr>
                    </thead>
                    <tbody id="tabla_body">
                    <tr>
                        <td colspan="5">Sin datos...</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <!--===================================================-->
            <!--End Data Table-->

        </div>
    </div>

</div>

<div class="modal fade"   id="modalProd" role="dialog" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">

            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="modalTitleProd"></h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="divModalProd">

            </div>

        </div>
    </div>
</div>

<div class="modal fade"   id="modalAddNewFactorAdverso" role="dialog" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog " >
        <div class="modal-content">

            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id=" ">Registrar nuevo factor adverso</h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id=" ">
                <form id="formNewFactor">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">Descripción</label>
                                    <input type="text" class="form-control" id="newFactor">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3 control-label"></label>
                        <div class="col-md-9">
                            <a href="javascript:void(0)"  id="btnSaveNewFactor" type="button"   class=" btn btn-success ">
                                Guardar y terminar
                            </a>

                            <button type="button"  data-dismiss="modal" class=" btn btn-danger ">
                                Cancelar
                            </button>

                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>



<div class="modal fade"   id="modalId" role="dialog" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 80%">
        <div class="modal-content">

            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="modalTitle"></h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="divModalBodyForm">



            </div>

        </div>
    </div>
</div>

<?php echo $_js?>
<?php echo $_css?>

<script type="text/template" id="tmpRegFormPlan">

    <form id="formDataPlan" >
        <input type="hidden" name="isEdit" id="isEdit" value="0">
        <input type="hidden" name="idEdit" id="idEdit" value="0">
            <div class="row">
                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="col-md-3 control-label"  style="text-align: right"  >Cultivo</label>
                                <div class="col-md-5">
                                    <select class="form-control" id="selCultivo" name="selCultivo">
                                        <option value="0" >Seleccione</option>
                                        <?php foreach($dataCultivo as $i){ ?>
                                            <option value="<?=$i["idcultivo"]?>" ><?=$i["nombre"]?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 " style="margin-top: 4px;" >
                            <div class="form-group">
                                <label class="col-md-3 control-label"  style="text-align: right"   >Categoria</label>
                                <div class="col-md-5">
                                    <select class="form-control" id="selCategoria" name="selCategoria">
                                        <option value="0" >Seleccione</option>
                                        <?php foreach($dataCategoria as $i){ ?>
                                            <option value="<?=$i["idcategoriacultivo"]?>" ><?=$i["nombre"]?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 " style="margin-top: 4px;" >
                            <div class="form-group">
                                <label class="col-md-3 control-label"  style="text-align: right"   >Variedad</label>
                                <div class="col-md-5" id="divSelVariedad">
                                    <select class="form-control" id="selVariedad" name="selVariedad" disabled>
                                        <option value="0">Seleccione</option>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 " style="margin-top: 4px;" >
                            <div class="form-group">
                                <label class="col-md-3 control-label"  style="text-align: right"  >Área</label>
                                <div class="col-md-5" >
                                    <input type="number" style="display: inline-block;width: 80%;text-align: right;" id="area" name="area"  class="form-control"  /> <span style="display: inline-block">Ha</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 " style="margin-top: 4px;" >
                            <div class="form-group">
                                <label class="col-md-3 control-label"  style="text-align: right"   >Campaña</label>
                                <div class="col-md-5">
                                    <input id="campania" name="campania"  class="form-control"  />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 " style="margin-top: 4px;" >
                            <div class="form-group">
                                <label class="col-md-3 control-label"  style="text-align: right"  >F. siembra </label>
                                <div class="col-md-5">
                                    <input style="line-height:12px;padding-left:5px;" type="date" id="fechasiembra" name="fechasiembra"  class="form-control"  />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 " style="margin-top: 4px;" >
                            <div class="form-group">
                                <label class="col-md-3 control-label"  style="text-align: right"  >F. Cosecha </label>
                                <div class="col-md-5">
                                    <input style="line-height:12px;padding-left:5px;" type="date" id="fechacosecha" name="fechacosecha"  class="form-control"  />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 " style="margin-top: 4px;" >
                            <div class="form-group">
                                <label class="col-md-3 control-label"  style="text-align: right"   >Localidad </label>
                                <div class="col-md-5">
                                    <select class="form-control" id="selLocalidad" name="selLocalidad">

                                        <?php foreach($dataLocalidad as $i){ ?>
                                            <option value="<?=$i["idlocalidad"]?>" ><?=$i["nombre"]?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 " style="margin-top: 4px;" >

                            <hr style="margin-top:4px;margin-bottom: 10px;">
                            <div class="form-group">
                                <label class="col-md-3 control-label" style="text-align: right"  >Plan de costo </label>
                                <div class="col-md-9" id="divFileCosto">
                                    <input  name="file[costo]"   type="file" class="form-control"  />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 " style="margin-top: 4px;" >
                            <div class="form-group">
                                <label class="col-md-3 control-label" style="text-align: right"  >Plan de Fertilización </label>
                                <div class="col-md-9" id="divFileFertilizacion">
                                    <input  name="file[ferti]"   type="file" class="form-control"  />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="row">
                        <table class="table table-bordered table-hover table-condensed">
                            <thead>
                            <tr>
                                <th colspan="3" class="text-center"> ANÁLISIS ECONÓMICO TOTAL </th>

                            </tr>
                            </thead>
                            <tbody>
                            <tr>

                                <td>1. C. Directo (I)</td>
                                <td ><input  style="width: 100px;text-align: right;" class="form-control"  type="text" id="cdirecto" name="cdirecto" ></td>
                            </tr>
                            <tr>
                                <td>2. C. Indirecto  (II)</td>
                                <td><input  style="width: 100px;text-align: right;" class="form-control"  type="text" id="cindirecto" name="cindirecto" ></td>
                            </tr>
                            <tr>
                                <td>3. Costo Total (I) + (II)</td>
                                <td><input style="width: 100px;text-align: right;" class="form-control"  type="text" id="costototal" name="costototal" readonly="readonly" >  </td>
                            </tr>
                            <tr>
                                <td>4. Rendimiento (Kg / Ha) </td>
                                <td><input style="width: 100px;text-align: right;" class="form-control"   type="number" id="rendimiento" name="rendimiento" ></td>
                            </tr>
                            <tr>
                                <td>5. Costo por Kg. (S/.) = CU </td>
                                <td><input style="width: 100px;text-align: right;" class="form-control"   type="number" id="costoxkg" name="costoxkg" readonly="readonly"></td>
                            </tr>
                            <tr>
                                <td>6. Precio mínimo (S/.)</td>
                                <td><input style="width: 100px;text-align: right;" class="form-control"   type="number" id="preciominimo" name="preciominimo" ></td>
                            </tr>
                            <tr>
                                <td>7. Precio  máximo (S/.)</td>
                                <td><input style="width: 100px;text-align: right;"  class="form-control"  type="number"  id="preciomax" name="preciomax" ></td>
                            </tr>
                            <tr>
                                <td>8.Rango de Precio (RP) </td>
                                <td>RP = <b id="rango"> [ - ] </b> </td>
                            </tr>
                            <tr>
                                <td>9. Utilidad mínima (S/.) = Umin </td>
                                <td>
                                    <input style="width: 100px;text-align: right;" class="form-control"  type="text" id="utilidadmin" name="utilidadmin" readonly="readonly" >
                                </td>
                            </tr>
                            <tr>
                                <td>10. Utilidad maxima (S/.) = U max  </td>
                                <td><input style="width: 100px;text-align: right;" class="form-control"  type="text" id="utilidadmax" name="utilidadmax" readonly="readonly" >
                                </td>
                            </tr>
                            <tr>
                                <td>11. Rentabilidad 1 </td>
                                <td><input style="width: 100px;text-align: right;" class="form-control"  type="text" id="renta1" name="renta1" readonly="readonly" >
                                </td>
                            </tr>
                            <tr>
                                <td>12. Rentabilidad 2  </td>
                                <td><input style="width: 100px;text-align: right;" class="form-control"  type="text" id="renta2" name="renta2" readonly="readonly" >
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        <div class="row">
            <label class="col-md-3 control-label"></label>
            <div class="col-md-9">
                <a href="javascript:void(0)"  id="btnSave" type="button"   class=" btn btn-success ">
                    Guardar y terminar
                </a>

                <label id="btn2"   >

                </label>

                <button type="button" id="btnCancel"  data-dismiss="modal" class=" btn btn-danger ">
                    Cancelar
                </button>

            </div>
        </div>

     </form>




</script>

<script type="text/template" id="tmpFormRegProd">

    <form id="formRegProd">

        <input type="hidden" name="id" id="id" value="<%=data.idplancampania%>">
        <div class="col-sm-12 " style="margin-top: 4px;" >
            <div class="form-group">
                <label class="col-md-12 control-label" style="text-align: center;font-weight: bold;font-size: 16px;"  >Producción de <%=data.cultivo%> en la categoría <%=data.categoriacultivo%>-variedad <%=data.variedadcultivo%> (Campaña <%=data.campania%>  )  </label>
                <label class="col-md-6 control-label" style="text-align: center;font-weight: bold;font-size: 14px;"  ><u>Programado</u></label>
                <label class="col-md-6 control-label" style="text-align: center;font-weight: bold;font-size: 14px;"  ><u>Ejecutado</u></label>
                <label class="col-md-2 control-label" style="text-align: right;font-weight: bold;font-size: 14px;"  >Área :</label>
                <label class="col-md-4 control-label" style="text-align: left;font-weight: bold;font-size: 14px;"  ><%=data.area%> ha  </label>
                <label class="col-md-2 control-label" style="text-align: right;font-weight: bold;font-size: 14px;"  >Área :</label>
                <label class="col-md-4 control-label" style="text-align: left;font-weight: bold;font-size: 14px;"  >
                    <input type="text" id="areaejecutada" name="areaejecutada" > ha
                </label>

                <label class="col-md-2 control-label" style="text-align: right;font-weight: bold;font-size: 14px;"  >Fecha Siembra:</label>
                <label class="col-md-4 control-label" style="text-align: left;font-weight: bold;font-size: 14px;"  > <%=formatDateDMY(data.fechasiembra)%></label>

                <label class="col-md-2 control-label" style="text-align: right;font-weight: bold;font-size: 14px;"  >Fecha Siembra:</label>
                <label class="col-md-4 control-label" style="text-align: left;font-weight: bold;font-size: 14px;"  >
                    <input type="date" id="fechaSiembraEjec" name="fechaSiembraEjec" >
                </label>

                <label class="col-md-2 control-label" style="text-align: right;font-weight: bold;font-size: 14px;"  >Fecha Cosecha:</label>
                <label class="col-md-4 control-label" style="text-align: left;font-weight: bold;font-size: 14px;"  > <%=formatDateDMY(data.fechacosecha)%></label>
                <label class="col-md-2 control-label" style="text-align: right;font-weight: bold;font-size: 14px;"  >Fecha Cosecha:</label>
                <label class="col-md-4 control-label" style="text-align: left;font-weight: bold;font-size: 14px;"  >
                    <input type="date" id="fechaCosechaEjec" name="fechaCosechaEjec" >
                </label>

                <label class="col-md-2 control-label" style="text-align: right;font-weight: bold;font-size: 14px;"  >Localidad:</label>
                <label class="col-md-4 control-label" style="text-align: left;font-weight: bold;font-size: 14px;"  >
                    <%=data.localidad%>
                </label>
                <label class="col-md-2 control-label" style="text-align: right;font-weight: bold;font-size: 14px;"  >Localidad:</label>
                <label class="col-md-4 control-label" style="text-align: left;font-weight: bold;font-size: 14px;"  >
                    <select class="form-control" id="selLocalidadEjec" name="selLocalidadEjec">
                        <?php foreach($dataLocalidad as $i){ ?>
                            <option value="<?=$i["idlocalidad"]?>" ><?=$i["nombre"]?></option>
                        <?php }?>
                    </select>
                </label>

                <label class="col-md-2 control-label" style="text-align: right;font-weight: bold;font-size: 14px;"  >Costo total:</label>
                <label class="col-md-4 control-label" style="text-align: left;font-weight: bold;font-size: 14px;"  >
                    <%= parseFloat(data.cdirecto) + parseFloat(data.cindirecto) %>
                </label>
                <label class="col-md-2 control-label" style="text-align: right;font-weight: bold;font-size: 14px;"  >Costo Total:</label>
                <label class="col-md-4 control-label" style="text-align: left;font-weight: bold;font-size: 14px;"  >
                    <input type="number" id="costoTotalEjec" name="costoTotalEjec" style="text-align: right" >
                </label>

                <label class="col-md-2 control-label" style="text-align: right;font-weight: bold;font-size: 14px;"  >Rendimiento:</label>
                <label class="col-md-4 control-label" style="text-align: left;font-weight: bold;font-size: 14px;"  >
                    <%=   parseFloat(data.rendimiento) %>
                </label>
                <label class="col-md-2 control-label" style="text-align: right;font-weight: bold;font-size: 14px;"  >Rendimiento:</label>
                <label class="col-md-4 control-label" style="text-align: left;font-weight: bold;font-size: 14px;"  >
                    <input type="number" id="rendimientoEjec" name="rendimientoEjec" style="text-align: right" >
                </label>

                <label class="col-md-6 control-label" style="text-align: right;font-weight: bold;font-size: 14px;"  > </label>
                <label class="col-md-2 control-label" style="text-align: right;font-weight: bold;font-size: 14px;"  >Peso Bruto</label>
                <label class="col-md-4 control-label" style="text-align: left;font-weight: bold;font-size: 14px;"  >
                    <input type="number" id="pesoBrutoEjec" name="pesoBrutoEjec" class="form-control" style="text-align: right" readonly="readonly" >
                </label>

                <label class="col-md-6 control-label" style="text-align: right;font-weight: bold;font-size: 14px;"  > </label>
                <label class="col-md-2 control-label" style="text-align: right;font-weight: bold;font-size: 14px;"  >Semilla</label>
                <label class="col-md-4 control-label" style="text-align: left;font-weight: bold;font-size: 14px;"  >
                    <input type="number" id="semillaEjec" name="semillaEjec" class="form-control" style="text-align: right"  >
                </label>

                <label class="col-md-6 control-label" style="text-align: right;font-weight: bold;font-size: 14px;"  > </label>
                <label class="col-md-2 control-label" style="text-align: right;font-weight: bold;font-size: 14px;"  >Consumo</label>
                <label class="col-md-4 control-label" style="text-align: left;font-weight: bold;font-size: 14px;"  >
                    <input type="number" id="consumoEjec" name="consumoEjec" class="form-control" style="text-align: right"  >
                </label>

                <label class="col-md-6 control-label" style="text-align: right;font-weight: bold;font-size: 14px;"  > </label>
                <label class="col-md-2 control-label" style="text-align: right;font-weight: bold;font-size: 14px;"  >Descarte</label>
                <label class="col-md-4 control-label" style="text-align: left;font-weight: bold;font-size: 14px;"  >
                    <input type="number" id="descarteEjec" name="descarteEjec" class="form-control" style="text-align: right"  >
                </label>
                <label class="col-md-6 control-label" style="text-align: right;font-weight: bold;font-size: 14px;"  > </label>
                <label class="col-md-2 control-label" style="text-align: right;font-weight: bold;font-size: 14px;"  >Merma</label>
                <label class="col-md-4 control-label" style="text-align: left;font-weight: bold;font-size: 14px;"  >
                    <input type="number" id="mermaEjec" name="mermaEjec" class="form-control" style="text-align: right"  >
                </label>


                <label class="col-md-6 control-label" style="text-align: right;font-weight: bold;font-size: 14px;"  > </label>
                <label class="col-md-6 control-label" style="font-weight: bold;font-size: 14px;"  >Factores Adversos
                    <a href="javascript:void(0)" id="addNewFactorAdverso" class="btn btn-dark btn-xs btn-rounded" title="Agregar Nuevo Factor Adverso">+</a> :
                </label>
                <label class="col-md-6 control-label" style="text-align: right;font-weight: bold;font-size: 14px;"  > </label>
                <div class="col-md-6  " style="text-align: left ;font-size: 14px;" id="divFactoresAdversos" >


                </div>

                <label class="col-md-6 control-label" style="text-align: right;font-weight: bold;font-size: 14px;"  > </label>
                <label class="col-md-2 control-label" style="text-align: right;font-weight: bold;font-size: 14px;"  >Observaciones</label>
                <label class="col-md-4 control-label" style="text-align: left;font-weight: bold;font-size: 14px;"  >
                    <input type="text" id="observacion" name="observacion" class="form-control" style="text-align: right"  >
                </label>

            </div>
        </div>

        <div class="row">
            <label class="col-md-3 control-label"></label>
            <div class="col-md-9">
                <a href="javascript:void(0)"  id="btnSaveFormProd" type="button"   class=" btn btn-success ">
                    Guardar y terminar
                </a>

                <label id="btn2"   >

                </label>

                <button type="button" id="btnCancel"  data-dismiss="modal" class=" btn btn-danger ">
                    Cancelar
                </button>

            </div>
        </div>
    </form>

</script>

<script type="text/template"  id="tmpFactoresAdversos" >
    <%_.each(data,function(i,k){%>
        <div class="checkbox">
            <input id="checkbox<%=i.idfactoresadversos%>" class="magic-checkbox" type="checkbox" name="factor[]" value="<%=i.idfactoresadversos%>" >
            <label for="checkbox<%=i.idfactoresadversos%>"><%=i.nombre%> </label>
        </div>
    <%});%>

</script>

<script type="text/javascript">
    var tableT;
    var tmpRegFormPlan=_.template($("#tmpRegFormPlan").html());
    var tmpFormRegProd=_.template($("#tmpFormRegProd").html());
    var tmpFactoresAdversos=_.template($("#tmpFactoresAdversos").html());

    $(document).on("ready",function () {
        dataTableIni();
    });

    function dataTableIni() {
        tableT = $('#tabla_grid').DataTable({
            "ajax": url_base + 'Semillas/getDataTable',
            "columns": [
                {"data": null},
                {
                    sortable: true,
                    "render": function (data, type, full, meta) {
                        var ht =full.campania

                        return ht;
                    }
                },
                {
                    sortable: true,
                    "render": function (data, type, full, meta) {
                        var ht =full.cultivo;

                        return ht;
                    }
                },
                {
                    sortable: true,
                    "render": function (data, type, full, meta) {
                        var ht =full.categoriacultivo;

                        return ht;
                    }
                },
                {
                    sortable: true,
                    "render": function (data, type, full, meta) {
                        var ht =full.variedadcultivo;
                        return ht;
                    }
                },
                {
                    sortable: true,
                    "render": function (data, type, full, meta) {
                        var ht =full.localidad;
                        return ht;
                    }
                },
                {
                    sortable: true,
                    "render": function (data, type, full, meta) {
                        var ht =full.area;
                        return ht;
                    }
                },
                {
                    sortable: true,
                    "render": function (data, type, full, meta) {
                        var ht =parseFloat((parseFloat(full.cdirecto)+parseFloat(full.cindirecto)));
                        return ht;
                    }
                },
                {
                    sortable: false,
                    "render": function (data, type, full, meta) {
                        var idplancampania =  full.idplancampania;
                        var datax=JSON.stringify(full);
                       // console.log(datax);
                        var html='';
                        //html='<a href="javascript:void(0)"  onclick="vercamp('+id_x +',\''+placeid+'\');" class="btn btn-mint btn-xs"><i class="demo-psi-pen-5 icon-lg"></i></a>';
                        html=html+"&nbsp; <a href='javascript:void(0)' onclick='ver("+datax +");' class='btn btn-default btn-icon btn-xs'><i class='fa fa-search icon-xs'></i>Ver</a>";
                        html=html+"&nbsp; <a href='javascript:void(0)' onclick='registrarProd("+datax +");' class='btn btn-dark btn-icon btn-xs'><i class='fa fa-pencil icon-xs'></i>Producción</a>";

                        html=html+'&nbsp; <a href="javascript:void(0)" onclick="eliminar(\''+idplancampania +'\');"  class="btn btn-danger btn-icon btn-xs"><i class="fa fa-trash icon-xs"></i>Borrar</a>';

                        return html;
                    }
                }

            ],
            "responsive": true,
            "pageLength": 50,
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros",
                "emptyTable": "<b>Ningún dato disponible en esta tabla</b>",
                "zeroRecords": "No se encontraron resultados",
                "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "search": "Buscar:",
                "paginate": {
                    "previous": '<i class="demo-psi-arrow-left"></i>',
                    "next": '<i class="demo-psi-arrow-right"></i>'
                }
            },
            "initComplete": function (settings, json) {
                var info = tableT.page.info();

                // console.log(info.recordsTotal);
                //alert( 'DataTables has finished its initialisation.' );
            }
        });

        $('#tabla_grid').on('click', 'tr', function () {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            }
            else {
                tableT.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        });

        tableT.on('order.dt search.dt', function () {
            tableT.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();

    }

    function refrescar () {
        tableT.ajax.reload();
    }


    $(document).on("click","#btnSave",function () {
        var btn=$(this);
        var formDataPlan=$("#formDataPlan").serialize();

       // var inputFile = $('input[name=file]');
       // var fileUpload = inputFile[0].files[0];
        btn.button("loading");
        var formData = new FormData($("#formDataPlan")[0]);
        //console.log(formData);
        $.ajax({
            url: url_base + "Semillas/setDataForm",
            type: 'post',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (data) {                          //alert("echo");
                console.log(data);
                var objLength = Object.keys(data).length;
                if(objLength>0){
                    if(data.status == "ok") {
                        alert_success("Se realizó correctamente!");
                        btn.button("reset");
                        refrescar();
                    } else {
                        alert_error("Ups error");
                    }
                }
            },
            error: function (e) {
                alert_error(e+" Ups error");

            },
            progress:function (e){
                if(e.lengthComputable) {
                    var pct = (e.loaded / e.total) * 100;
                    $("#divprogressBar").css("display","block");
                    var pgbar=$('#progressBar');
                    pgbar.css('width', pct.toPrecision(3) + '%');
                    pgbar.html(pct.toPrecision(3) + '%');
                    if(pct == 100){

                        $("#divprogressBar").fadeOut(3000);
                    }
                } else {
                    console.log('Content Length not reported!');
                }
            }

        });

       /* $.post(url_base+"Semillas/setDataForm",formDataPlan,function (data) {
            console.log(data);
        },"json");*/
        console.log(formDataPlan);

    });


    $(document).on("click","#btnAdd",function () {
        open_modal("modalId");
        $("#divModalBodyForm").html(tmpRegFormPlan);
        //getHtmlActividadCoste();
    });

    function getHtmlActividadCoste() {
        $.post(url_base+"semillas/getDataActividadCostePlantilla",function (data) {
          //  console.log(data);
            $("#divActividadCoste").html(data);
            calcTotalNivel1();
        });
    }

    function calcTotalNivel1() {
        var input=$("input[name='nivel1[]']") ;
        var total1=0;
        $.each(input,function (k,i) {
            total1=total1+parseFloat($(i).val());
        });

        console.log(total1);
    }

    var delay = (function(){
        var timer = 0;
        return function(callback, ms){
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
          //  console.log(timer);
        };
    })();

    function calCosto(thisx){
        delay(function(){

            var parentTR= $(thisx).parent().parent();

            var cantidad=parseFloat(parentTR.find('input[name="cantidad[]"]').val())||0;
            var costo=parseFloat(parentTR.find('input[name="costounitario[]"]').val())||0;
            var idupd=parseFloat(parentTR.find('input[name="id[]"]').val())||0;

            //  console.log(meses);
            var cant=0;
            // var meses=parentTR.find('input[name="mes[]"]').serializeArray();
            console.log(cantidad,costo,idupd);
            var datasend={"idupd":idupd,"cantidad":cantidad , "costo":costo };
            $.post(url_base+"semillas/setValCantCostoActCosto",datasend,function (data) {
                console.log(data);
                if(data.status == "ok"){
                    getHtmlActividadCoste();
                }else{
                    alert_error("Ups");
                }

            },'json');

        }, 500 );
              //getHtmlActividadCoste();
    }

    //------------------------------





    //--------------------

    $(document).on("keyup","#cdirecto,#cindirecto",function () {
        calcCostototal();
    });
    $(document).on("keyup","#preciominimo,#preciomax",function () {
        calcRangoPreciosRentabilidad();
    });

    function calcRangoPreciosRentabilidad(){
        var preciominimo=$("#preciominimo");
        var preciomax=$("#preciomax");
        var valpreciominimo=parseFloat(((preciominimo.val()).replace(",",".")).replace(",","."))||0;
        var valpreciomax=parseFloat(((preciomax.val()).replace(",",".")).replace(",","."))||0;

        var strRango="<b>["+valpreciominimo+"-"+valpreciomax+"]</b>";
        $("#rango").html(strRango);

        var costototal=parseFloat($("#costototal").val())||0;
        var rendimiento=parseFloat($("#rendimiento").val())||0;
        var utilidadmin=parseFloat(((rendimiento*valpreciominimo)-costototal))||0;
        var utilidadmax=parseFloat(((rendimiento*valpreciomax)-costototal))||0;
        $("#utilidadmin").val(utilidadmin.toFixed(2));
        $("#utilidadmax").val(utilidadmax.toFixed(2));

        var renta1=parseFloat(utilidadmin/costototal)||0;
        var renta2=parseFloat(utilidadmax/costototal)||0;
        renta1=renta1*100;
        renta2=renta2*100;
        $("#renta1").val(renta1.toFixed(2)+" %");
        $("#renta2").val(renta2.toFixed(2)+" %");


    }


    function calcCostototal(){
        var cdirecto=$("#cdirecto");
        var cindirecto=$("#cindirecto");
        var valcindirecto=parseFloat(((cindirecto.val()).replace("","")).replace(",","."))||0;
        var valcdirecto=parseFloat(((cdirecto.val()).replace("","")).replace(",","."))||0;
       // cdirecto.val(valcdirecto);
        var ctotal=parseFloat(valcindirecto+valcdirecto)||0;
       $("#costototal").val(ctotal.toFixed(2));
        calcRangoPreciosRentabilidad();
        calcRendimiento();
    }

    $(document).on("keyup","#rendimiento",function () {
        calcRendimiento();
    });

    function calcRendimiento(){
        var rendimiento=parseFloat($("#rendimiento").val())||0;
        var costototal=parseFloat($("#costototal").val())||0;
        var costoxkg= parseFloat(costototal/rendimiento)||0;
        $("#costoxkg").val(costoxkg.toFixed(2));
        calcRangoPreciosRentabilidad();
    }

    //.--------------------------

    $(document).on("change","#selCultivo",function () {
        var valSelCultivo=parseFloat($("#selCultivo").val())||0;
        var tmpSelVariedad=_.template($("#tmpSelVariedad").html());
        $.post(url_base+"Semillas/getVariedadCultivo",{"idcultivo":valSelCultivo},function (data) {
            $("#divSelVariedad").html(tmpSelVariedad({data:data}));
        },'json');
    });


    $(document).on("click","#verFenologia",function () {
        var fechasiembra=$("#fechasiembra").val();
        if(fechasiembra != ""){
            console.log(fechasiembra);
        }else{
            alert_error("Ingrese Fecha de siembra");
        }

    });
    ////-------------------

    function ver(datax){
        open_modal("modalId");
        $("#divModalBodyForm").html(tmpRegFormPlan);
        $("#selCultivo").val(datax.idcultivo);
        $("#selCategoria").val(datax.idcategoriacultivo);
        //---
        var valSelCultivo=parseFloat($("#selCultivo").val())||0;
        var tmpSelVariedad=_.template($("#tmpSelVariedad").html());
        $.post(url_base+"Semillas/getVariedadCultivo",{"idcultivo":valSelCultivo},function (data) {
            $("#divSelVariedad").html(tmpSelVariedad({data:data}));
            $("#selVariedad").val(datax.idvariedadcultivo);
            console.log( );
        },'json');
      //--

        if(datax.plancostofile != ""){
            var ht='<a href="'+url_base+'assets/uploads/archivos/'+datax.plancostofile+'"  id="linkPlanCosto" target="_blank" class="btn btn-info btn-sm text-bold">Ver Plan</a>'
            ht+='&nbsp; <a href="javascript:void(0)" id="btnCambiarPlanCosto"   class="btn-link">Cambiar</a>'

            $("#divFileCosto").html(ht);
        }
        if(datax.planferti != ""){
            var ht='<a href="'+url_base+'assets/uploads/archivos/'+datax.planferti+'" id="linkPlanFerti" target="_blank" class="btn btn-warning btn-sm text-bold">Ver Plan</a>'
            ht+='&nbsp; <a href="javascript:void(0)" id="btnCambiarPlanFerti"   class="btn-link">Cambiar</a>'

            $("#divFileFertilizacion").html(ht);
        }


        $("#isEdit").val(1);
        $("#idEdit").val(datax.idplancampania);
        $("#area").val(datax.area);
        $("#campania").val(datax.campania);
        $("#fechasiembra").val(datax.fechasiembra);
        $("#fechacosecha").val(datax.fechacosecha);
        $("#selLocalidad").val(datax.idlocalidad);

        $("#cdirecto").val(datax.cdirecto);
        $("#cindirecto").val(datax.cindirecto);

        $("#rendimiento").val(datax.rendimiento);
        $("#preciominimo").val(datax.preciomin);
        $("#preciomax").val(datax.preciomax);

        calcCostototal();
        calcRendimiento();
        calcRangoPreciosRentabilidad();


    }

    function eliminar(id) {
        if(confirm("¿Esta Seguro de eliminar este registro?")){
            $.post(url_base+"semillas/deleteData",{"id":id},function (data) {
                if(data.status =="ok"){
                    refrescar();
                    alert_success("Se elimino correctamente el registro");
                }else{
                    alert_error("Ups... Ocurrio un error contacte con soporte");
                }
            },"json");
        }else{

        }

    }

    //----------------
    var DivLinkPlanCosto=null,DivLinkPlanFerti=null;
    $(document).on("click","#btnCambiarPlanCosto",function () {
        DivLinkPlanCosto=$("#divFileCosto").html();
        console.log(DivLinkPlanCosto);
        var ht='<input  name="file[costo]"   type="file" class="form-control" /><a href="javascript:void(0)" id="cancelarCambioPlanCosto" class="btn-link">Cancelar</a>   ';
        $("#divFileCosto").html(ht);
    });
    $(document).on("click","#btnCambiarPlanFerti",function () {
        DivLinkPlanFerti=$("#divFileFertilizacion");
        var ht='<input  name="file[ferti]"   type="file" class="form-control"  /><a href="javascript:void(0)" id="cancelarCambioPlanFerti" class="btn-link">Cancelar</a>  ';
        $("#divFileFertilizacion").html(ht);
    });
    $(document).on("click","#cancelarCambioPlanCosto",function () {
        console.log(DivLinkPlanCosto);
        $("#divFileCosto").html(DivLinkPlanCosto);
        DivLinkPlanCosto=null;
    });
    $(document).on("click","#btnCambiarPlanFerti",function () {
         $("#divFileFertilizacion").html(DivLinkPlanFerti);
         DivLinkPlanFerti=null;
    });


    ///____________

    function registrarProd(datax) {
        open_modal("modalProd");
        $("#divModalProd").html(tmpFormRegProd({data:datax}));
        getFactoresAdversos();
    }

    function getFactoresAdversos() {
        $.post(url_base+"Semillas/getFactoresAdversos",function (data) {
            $("#divFactoresAdversos").html(tmpFactoresAdversos({data:data}))
        },'json')
    }


    //------

    $(document).on("click","#btnSaveFormProd",function (e) {
        var formProd=$("#formRegProd").serialize();
        $.post(url_base+"Semillas/setProduccionSemilla",formProd,function (data) {
            console.log(data);
        },'json');
        console.log(formProd);
    });


    /// Factor
    $(document).on("click","#addNewFactorAdverso",function (e) {
        open_modal("modalAddNewFactorAdverso");
        $("#newFactor").val("");

    });
    $(document).on("click","#btnSaveNewFactor",function (e) {
        var newFactor=$("#newFactor");
        var bol=true;
        bol=bol && newFactor.required()
        if(bol){

            $.post(url_base+"semillas/setNewFactorAdversoProduccion",{"factorAdverso":newFactor.val()},function (data) {
                if(data.status=="ok"){
                    alert_success("Se realizo correctamente");
                    newFactor.val("");
                    close_modal("modalAddNewFactorAdverso");
                    getFactoresAdversos();
                }else{
                    alert_error("Fallo :(");
                }
            },'json');
        }else{

        }

    });



</script>

<script type="text/template" id="tmpSelVariedad">
    <select class="form-control" id="selVariedad" name="selVariedad" >

        <% _.each(data,function(i,k){ %>
            <option value="<%= i.idvariedadcultivo %>" > <%=i.nombre %></option>
        <% });%>

    </select>
</script>
