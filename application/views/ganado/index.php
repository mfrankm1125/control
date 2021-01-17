<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
               
                <div class="panel-control">
                     <em class="text-muted"><small>Última Actualización - <b>x</b> &nbsp; </small></em>
                    <ul class="pager pager-rounded">
                         <li><a href="javascript:void(0)" id="openModalReportProdLeche" >Generar Reporte</a></li>
                    </ul>
                </div>                
                <h3 class="panel-title">Registro Diario de Producción de leche</h3>
            </div>

            <!--Data Table-->
            <!--===================================================-->
            <div class="panel-body" style="padding-top: 5px;" >

                <div class="row">
                        <div class="col-sm-8">
                              
                        </div>    
                        <div class="col-sm-4">
                             <div class="form-group">
                                <label class="control-label " style="font-size: 18px;">Stock Actual : </label>
                                <b style=" " id="bStockActualProdLeche" class="text-2x mar-no text-semibold">...</b>
                                <b>Litros</b>
                            </div>
                        </div>    
                  </div>
                  <div class="row">
                      <a class="btn-link" href="javascript:void(0)" onclick="openModalRegGanado()" >Registrar Ganado</a>
                       <a class="btn-link" href="javascript:void(0)" onclick="openModalRegSalida()" >Registrar Salida</a>  
                  </div>

                <br>
                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label class="control-label">Fecha</label>
                            <input type="date" id="fechareg" class=""  value="<?=date("Y-m-j")?>" >
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label class="control-label">Cod</label>
                            <input type="text" id="codanimal" class="form-control">
                        </div>
                    </div>
                     <div class="col-sm-2">
                        <div class="form-group">
                            <label class="control-label">Mañana</label>
                            <input type="text" id="cantmaniana"   style="text-align: right;" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label class="control-label">Tarde</label>
                            <input type="text" id="canttarde" style="text-align: right;"  class="form-control">
                        </div>
                    </div>
                     <div class="col-sm-2">
                        <div class="form-group">
                            <label class="control-label">Total</label>
                            <input type="text" class="form-control" id="total" readonly="readonly">
                        </div>
                    </div>
                     <div class="col-sm-2">
                        <div class="form-group">
                            <label class="control-label"></label><br>
                            <button type="button" class="btn btn-success btn-mint btn-sm" id="btnSaveProdLeche" >Agregar</button>
                        </div>
                    </div>
                </div>
               <hr>
               <table id="tableProdLeche" class="table table-hover table-bordered table-condensed " cellspacing="0" width="100%">
                    <thead>
                    <tr><th>#</th>
                        <th>Fecha </th>
                        <th>Cod</th>
                        <th>Mañana</th>
                        <th>Tarde</th>
                        <th style="text-align: center;">Total</th>
                        <th>Acción</th>
                    </tr>
                    </thead>
                    <tbody id="tbodyProdLeche">
                      
                    </tbody>
                </table>
                
                
            </div>
            <!--===================================================-->
            <!--End Data Table-->

        </div>
    </div>

</div>

<script type="text/template" id="tmpTbodyFormatoInforme">
    <tr>
        <td> <a href="<?=base_url()?>assets/uploads/<%=data.url%>" class="btn-link" target="_blank">Click para ver documento</a> </td>
        <td><%=data.fechaupdate%></td>
        <td> <input  style="display: inline-block" id="inFormatoInforme"  type="file" class="">
            <button style="display: inline-block" id="btnActFormatoInforme" type="button" class="btn btn-mint btn-sm" >
                Actualizar
            </button>
            <div class="form-group"  id="divprogressBar1" style="display: none;" >
                <label class="col-sm-2 control-label" style="text-align: right"></label>
                <div class="col-sm-5">
                    <div class="progress" >
                        <div id="progressBar"  style="width: 0%;font-size:12px" class="progress-bar progress-bar-info"></div>
                    </div>
                </div>
            </div>
        </td>
    </tr>
</script>


<script type="text/template" id="tmpTbodyFormatoInformeJustify">
    <tr>
        <td> <a href="<?=base_url()?>assets/uploads/<%=data.url%>" class="btn-link" target="_blank">Click para ver documento</a> </td>
        <td><%=data.fechaupdate%></td>
        <td> <input  style="display: inline-block" id="inFormatoInformeJustify"  type="file" class="">
            <button style="display: inline-block" id="btnActFormatoInformeJustify" type="button" class="btn btn-mint btn-sm" >
                Actualizar
            </button>
            <div class="form-group"  id="divprogressBar1" style="display: none;" >
                <label class="col-sm-2 control-label" style="text-align: right"></label>
                <div class="col-sm-5">
                    <div class="progress" >
                        <div id="progressBar"  style="width: 0%;font-size:12px" class="progress-bar progress-bar-info"></div>
                    </div>
                </div>
            </div>
        </td>
    </tr>
</script>

<script type="text/template" id="tmpTrProdLeche">
    <tr>
        <td></td>
        <td> 
            <input  id=""  type="date" class="" value="<?=date("Y-m-j")?>">   
        </td>
        <td>
            <input  id=""  type="text" class="">         
        </td>
         <td>
            <input  id=""  type="text" class="">         
        </td>
         <td>
            <input  id=""  type="text" class="">         
        </td>
         <td>
            <b></b>        
        </td>
        <td>
            <button type="button" class="btn btn-success btn-xs" id="" >Guardar</button>    
        </td>
    </tr>
</script>


<div class="modal fade" id="modalReporteProdLeche"  role="dialog" tabindex="-1"  aria-hidden="true" data-backdrop="false" >
    <div class="modal-dialog modal-lg" style="" >
        <div class="panel  panel-bordered-mint  " style="-webkit-box-shadow: -12px 15px 31px -8px rgba(0,0,0,0.75);
-moz-box-shadow: -12px 15px 31px -8px rgba(0,0,0,0.75);
box-shadow: -12px 15px 31px -8px rgba(0,0,0,0.75);"  >
            <div class="panel-heading">
                <div class="panel-control">
                    <button class="btn btn-default" data-dismiss="modal"> <i class="demo-pli-cross"></i></button>

                </div>
                <h3  class="panel-title" id="titleModalReportArea"></h3>
            </div>
            <div class="panel-body">
                <div class="row" >
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label">Año</label>
                           <div class="form-group" id="divSelAnioProdLeche">                               
                                  <select class="form-control" id="selAnioProdLecheR1" >
                                      
                                  </select>     
                           </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label">Generar Reporte en:</label>
                             <span class="input-group-btn">
                              <button style="border: 1px solid blue;margin-right: 2px;" class="btn btn-default" type="button" onclick="loadReport('anio','word')" title="Reporte Semestre Word"><i style="color:blue" class="fa fa-file-word-o"></i> Word</button>
                            </span>
                        </div>
                    </div>
              </div>
              <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label">Año</label>
                           <div class="form-group">                               
                                    <select class="form-control" id="selAnioProdLecheR2" >
                                        
                                    </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label">Mes</label>
                               <select class="form-control" id="selMesIniRep" >
                                        <option value="1">Enero</option>
                                        <option value="2">Febrero</option>
                                        <option value="3">Marzo</option>
                                        <option value="4">Abril</option>
                                        <option value="5">Mayo</option>
                                        <option value="6">Junio</option>
                                        <option value="7">Julio</option>
                                        <option value="8">Agosto</option>
                                        <option value="9">Septiembre</option>
                                        <option value="10">Octubre</option>
                                        <option value="11">Noviembre</option>
                                        <option value="12">Diciembre</option>
                               </select>
                        </div>
                    </div>
                     <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label">Mes</label>
                             <span class="input-group-btn">
                              <button style="border: 1px solid blue;margin-right: 2px;" class="btn btn-default" type="button" onclick="loadReport('aniomes','word')" title="Reporte Semestre Word"><i style="color:blue" class="fa fa-file-word-o"></i> Word</button>
                            </span>
                            <span class="input-group-btn">
                              <button style="border: 1px solid green;margin-right: 2px;" class="btn btn-default" type="button"  onclick="loadReport('aniomes','excel')" title="Reporte Semestre Excel"> <i style="color:green" class="fa fa-file-excel-o"></i> Excel</button>
                            </span>
                            <span class="input-group-btn">
                              <button style="border: 1px solid darkviolet;margin-right: 2px;" class="btn btn-default" type="button" onclick="loadReport('aniomes','digital')" > <i style="" class="fa fa-bar-chart"></i> Digital</button>
                            </span>
                        </div>
                    </div>
              </div>      
                
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalFormRegGanado"  role="dialog" tabindex="-1"  aria-hidden="true"   >
    <div class="modal-dialog modal-lg" style="width: 80%" >
        <div class="panel  panel-bordered-mint  " style=" "  >
            <div class="panel-heading">
                <div class="panel-control">
                    <button class="btn btn-default" data-dismiss="modal"> <i class="demo-pli-cross"></i></button>

                </div>
                <h3  class="panel-title" id="titleModalReportArea">Registro Ganado</h3>
            </div>
            <div class="panel-body" id="divModalFormRegGanado">
                      
                
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalFormRegSalida"  role="dialog" tabindex="-1"  aria-hidden="true"   >
    <div class="modal-dialog modal-lg" style="width: 80%" >
        <div class="panel  panel-bordered-mint  " style=" "  >
            <div class="panel-heading">
                <div class="panel-control">
                    <button class="btn btn-default" data-dismiss="modal"> <i class="demo-pli-cross"></i></button>

                </div>
                <h3  class="panel-title" id="titleModalReportArea">Registro Salida Prod. leche</h3>
            </div>
            <div class="panel-body" id="divModalFormRegSalida">
                      
                
            </div>
        </div>
    </div>
</div>

<script type="text/template" id="tmpSelAnioProdLeche">
  
        <%_.each(data,function(i,k){ %>
            <option value="<%=i.anio%>"><%=i.anio%></option>  
        <% });%>                                              
  
</script>


<script type="text/template" id="tmpBodyModalFormRegGanado">
    <form>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="demo-is-inputsmall">Tipo Animal</label>
            <div class="col-sm-6">
                <select class="form-control">
                    <option></option>
                </select> 
            </div>
        </div>
        <br><br><br>
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label" title="Codigo de identificación">Código de Ident. </label>
                    <input type="text" class="form-control">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label" title="Codigo de identificación">Padre </label>
                    <input type="text" class="form-control" value="No definido" >
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label">Madre</label>
                    <input type="text" class="form-control"  value="" >
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label">Fecha Nacimiento</label><br>
                    <input type="date" class="" style="width:100%;">
                </div>
            </div>
           
        </div>
        <div class="row">
             <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label">Sexo</label>
                    <input type="text" class="form-control">
                </div>
            </div>
             <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label">Raza</label>
                    <input type="text" class="form-control">
                </div>
            </div>
             <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label">Pureza</label>
                    <input type="text" class="form-control">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label">Edad</label>
                    <input type="text" readonly="readonly" class="form-control">
                </div>
            </div>
        </div>
        <div class="row">
             <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label">Propósito</label>
                    <input type="text" class="form-control">
                </div>
            </div>
              
        </div>
        <h5>De la Entrada/Salida</h5>
        <hr>
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label" title="Codigo de identificación">Tipo de Entrada </label>
                    <input type="text" class="form-control" value="" style="display: inline-block; width: 90%;" >
                    <button type="button" class="btn btn-xs btn-success" style="display: inline-block;">+</button>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label">Vendido a:</label>
                    <input type="number" class="form-control"  value="No definido" >
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label class="control-label">Precio S/.</label>
                    <input type="number" class="form-control"  value="" >
                </div>
            </div>
            
        </div>
        <div class="row">
             
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label" title="Codigo de identificación"> Tipo de Salida</label>
                    <input type="text" class="form-control" value="" style="display: inline-block; width: 90%;" >
                    <button type="button" class="btn btn-xs btn-success" style="display: inline-block;" >+</button>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label">Comprado a:</label>
                    <input type="number" class="form-control"  value="No definido" >
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label">Precio S/.</label>
                    <input type="number" class="form-control"  value="No definido" >
                </div>
            </div>
        </div>
    
    
    </form>       
</script>



<script type="text/template" id="tmpFormRegSalida">
    <form>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="demo-is-inputsmall">Tipo Salida</label>
            <div class="col-sm-6">
                <select class="form-control" id="selTipoSalida">
                    <?php foreach($tiposalida as $key=>$value){ 
                            if( strtolower($value["nombre"]) != "muerte"){    ?>
                                <option value="<?=$value["idtiposalidaanimal"]?>"><?=$value["nombre"]?></option>
                         <?php }?>                       
                    <?php }?>                  
                </select> 
            </div>
        </div>
        <br><br>
        <div id="divTipoSalida" >
            

        </div>
    </form>    
</script>

<script type="text/template" id="tmpFormRegSalidaDivIsVenta">
    <div class="form-group">
            <label class="col-sm-2 control-label" for="demo-is-inputsmall">Cliente</label>
            <div class="col-sm-6">
                <select class="form-control" id="selCliente">
                                
                </select> 
            </div>
    </div>
     <br><br><br>
    <div class="row">   
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Stock</label>
                <input type="number" class="form-control"  value="" readonly="readonly" >
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group">
                <label class="control-label">Monto A vender</label>
                <input type="number" class="form-control"  value="" >
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group">
                <label class="control-label">Precio S/.</label>
                <input type="number" class="form-control"  value=""  >
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Total</label><br>               
                <input type="number" class="form-control"  value="No definido" readonly="readonly" style="display: inline-block; width: 70%;" >
                <button type="button" class="btn btn-xs btn-success" style="display: inline-block;" >Agregar</button>
            </div>
        </div>
  </div>
   <div class="row">
    <table class="table table-condensed table-hover table-bordered">        
        <tr>
            <th>#</th>
            <th>Cliente</th>
            <th>Litros</th>
            <th>Precio</th>
            <th>Total</th>
            <th>Acción</th>
        </tr>
        <tbody>
            <tr>

            </tr>    
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align: right;">Total</td>
                <td>...</td>
            </tr>   
        </tfoot>
    </table>

   </div> 
  <div class="row">
    <div class="col-sm-12" style="text-align: center;">
        <button class="btn btn-mint" type="button">Guardar</button>
        <button class="btn btn-warning" type="button">Cancelar</button>
    </div>
  </div>
</script>

<?php echo $_css?>
<?php echo $_js?>

<script type="text/javascript">
var tmpTrProdLeche=_.template($("#tmpTrProdLeche").html());
var tmpSelAnioProdLeche=_.template($("#tmpSelAnioProdLeche").html());

$(document).on("ready",function(){
    dataTableProdLecheDiaria();
    getStockActualProdLeche();
});

$(document).on("click","#btnAddTrProdLeche",function(){
 var tr="";   
 $('#tableProdLeche >tbody > tr:first').before(tmpTrProdLeche);
 //$("#tbodyProdLeche").appendTo(tmpTrProdLeche);
});  

function getDataProdLecheDiaria(){
    $.post(url_base+"Ganado/getDataProdLecheDiaria",function(data){
       console.log(data);     
    },"json");
}

 var tableProdLecheDiario;
 
function dataTableProdLecheDiaria() {
        tableProdLecheDiario = $('#tableProdLeche').DataTable({
            "ajax": url_base+"Ganado/getDataTable",
            "columns": [
                { "data": null },
                {  sortable: true,
                    "render": function ( data, type, full, meta ) {
                        
                        var fechaprodleche =  full.fechaprodleche;
                        var input='<input  type="date" value="'+fechaprodleche+'" name="tfechaprodleche" onchange="updateRegProdLeche(\'fecha\',this)" onkeyup="updateRegProdLeche(\'fecha\',this)"  >';                          
                        return input;
                    }
                },
                {  sortable: true,
                    "render": function ( data, type, full, meta ) {
                        var idanimal =full.idanimal ;
                        var input='<input class="form-control" style="text-align:right;" type="text" value="'+idanimal+'"  name="tidanimal"  onkeyup="updateRegProdLeche(\'idanimal\',this)" >';
                        input=input+'<input  type="hidden" value="'+full.idprodleche +'"  name="tidprodlechehide">';    
                        return input;
                    }
                },
                {  sortable: true,
                    "render": function ( data, type, full, meta ) {
                        var cantmaniana =full.cantmaniana ;
                        var input='<input class="form-control" style="text-align:right;" type="text" value="'+cantmaniana+'" name="tcantmaniana"  onkeyup="calcTotal(this)" >';                          
                        return input;
                         
                    }
                },
                {  sortable: true,
                    "render": function ( data, type, full, meta ) {
                        
                        var canttarde =full.canttarde ;
                        var input='<input class="form-control"  style="text-align:right;" type="text" value="'+canttarde+'" name="tcanttarde"   onkeyup="calcTotal(this)" >';                          
                        return input;
                    }
                },
                {  sortable: true,
                    "render": function ( data, type, full, meta ) {
                        var total =parseFloat(full.cantmaniana) + parseFloat(full.canttarde);     
                        var ht="<b id='btotal'>"+total+"</b><input type='hidden'  name='ttotalini' value='"+total+"'>"                 
                        return ht ; 
                    }
                } ,
                {  sortable: false,
                    "render": function ( data, type, full, meta ) {
                        var idprodleche =full.idprodleche ;
                        var btnSave='<button type="button" class="btn btn-success btn-xs" onclick="saveUpdateProdLeche(this)" >Guardar</button> &nbsp;';
                        var btnDelete='<button type="button" class="btn btn-danger btn-xs" onclick="deleteProdLeche('+idprodleche+')">Borrar</button>';
                        return btnSave+btnDelete;
                    }
                } 

            ],
            "responsive": true,
            "pageLength": 50,
            "language": {
                "lengthMenu":     "Mostrar _MENU_ registros",
                "emptyTable":     "<b>Ningún dato disponible en esta tabla</b>",
                "zeroRecords":    "No se encontraron resultados",
                "info":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "search":       "Buscar:",
                "paginate": {
                    "previous": '<i class="demo-psi-arrow-left"></i>',
                    "next": '<i class="demo-psi-arrow-right"></i>'
                }
            },
            "initComplete": function(settings, json) {
                var info = tableProdLecheDiario.page.info();
                //console.log(info.recordsTotal);
                $("#labelObservados").html(info.recordsTotal);
                //alert( 'DataTables has finished its initialisation.' );
            }

        });

        $('#tabla_gridObservados').on( 'click', 'tr', function () {
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
            }
            else {
                tableProdLecheDiario.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        } );

        tableProdLecheDiario.on( 'order.dt search.dt', function () {
            tableProdLecheDiario.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();

    }
    
    function actualizarGrid (alert) {
        tableProdLecheDiario.ajax.reload(function (json) {
            //console.log(json);
            if(alert=="onAlert"){
                 alert_success("Se actualizo correctamente"); 
            }
          
        });
    }

    function updateRegProdLeche(input,thisx){
        /*var tipoInput=input;
        var ctx=$(thisx);
        var valInput=ctx.val();
        var idprodleche=ctx.parent().parent().find("input[name='tidprodlechehide']").val();
        var dataSend={"op":tipoInput,"idprodleche":idprodleche,"valor":valInput};
        //console.log(idanimalupdate);
             $.post(url_base+"Ganado/updateDataProdLeche",dataSend,function(data){
                console.log(data);
                if(data.status=="ok"){
                   // alert_success("Se realizo correctamente");
                }else{
                    alert_error("ocurrio un problema");
                }
                //actualizarGrid("");
            },'json');*/
    }

    function calcTotal(thisx){
        var ctx=$(thisx);   
        
        var canttarde=ctx.parent().parent().find("input[name='tcanttarde']").val();
        var cantmaniana=ctx.parent().parent().find("input[name='tcantmaniana']").val();
        var bTotal=ctx.parent().parent().find("#btotal");
        canttarde=parseFloat(canttarde) || 0;
        cantmaniana=parseFloat(cantmaniana)||0;
        var total=canttarde+cantmaniana;
        bTotal.html(redondea(total));

    }

    $(document).on("keyup","#canttarde,#cantmaniana",function(){
       // console.log("sss");
        var canttarde=parseFloat($("#canttarde").val()) || 0;
        var cantmaniana=parseFloat($("#cantmaniana").val()) || 0;
        var total=cantmaniana+canttarde;
        $("#total").val(total);    
    });

    $(document).on("click","#btnSaveProdLeche",function(){
        saveData();
    });

    function saveData(){
       // if(confirm("Esta seguro de guardar")){
            var fechareg=$("#fechareg").val();
            var codanimal=$("#codanimal").val();
            var canttarde=parseFloat($("#canttarde").val()) || 0;
            var cantmaniana=parseFloat($("#cantmaniana").val()) || 0;
            var dataSend={"fechareg":fechareg,"codanimal":codanimal,"cantmaniana":cantmaniana,"canttarde":canttarde};
            //console.log(dataSend);
            $.post(url_base+"Ganado/saveProdLeche",dataSend,function(data){
                 if(data.status=="ok"){
                   alert_success("Se realizo correctamente");
                }else{
                    alert_error("ocurrio un problema");
                }
                 getStockActualProdLeche();
                 $("#codanimal").val("");
                 $("#canttarde").val("");
                 $("#cantmaniana").val("") ;
                     
                actualizarGrid("");
            },'json');
        //}
    }

    $(document).keypress(function(e){
    if(e.which == 13) {
        alert('You pressed enter!');
    }
  
    });

      function saveUpdateProdLeche(thisx){
        var ctx=$(thisx);
        var idprodleche=ctx.parent().parent().find("input[name='tidprodlechehide']").val();
        var fechareg=ctx.parent().parent().find("input[name='tfechaprodleche']").val();
        var codanimal=ctx.parent().parent().find("input[name='tidanimal']").val();
        var canttarde=ctx.parent().parent().find("input[name='tcanttarde']").val();
        var cantmaniana=ctx.parent().parent().find("input[name='tcantmaniana']").val();
        var totalini=ctx.parent().parent().find("input[name='ttotalini']").val();
        var dataSend={"idprodleche":idprodleche,"fechareg":fechareg,"codanimal":codanimal,"cantmaniana":cantmaniana,"canttarde":canttarde,"totalini":totalini};
         $.post(url_base+"Ganado/updateDataProdLeche",dataSend,function(data){
                console.log(data);
                if(data.status=="ok"){
                   alert_success("Se realizo correctamente");

                }else{
                    alert_error("ocurrio un problema");
                }
                actualizarGrid("");
                getStockActualProdLeche();
            },'json');
      //  console.log(dataSend);
    }

    $(document).on("click","#openModalReportProdLeche",function(){
         getDataSelAnioProdLeche();
        open_modal("modalReporteProdLeche");
    });

    function loadReport(tipotiempo,tipodoc){
       var anioReport=0;
       var mesIniReport=0;
        var mesEndReport=0;
       if(tipotiempo =="anio"){
        anioReport=$("#selAnioProdLecheR1").val();
       }else if(tipotiempo == "aniomes"){
         anioReport=$("#selAnioProdLecheR2").val();
         mesIniReport=$("#selMesIniRep").val();
         mesEndReport=mesIniReport;
       }
       var urlReport=""+tipotiempo+"/"+tipodoc+"/"+anioReport+"/"+mesIniReport+"/"+mesEndReport; 
       //console.log(urlReport);
       window.open(url_base+"Reportes/reportProdLeche/"+urlReport,"", "width=700,height=1000");

    }

    function getDataSelAnioProdLeche(){
        $.post(url_base+"Ganado/getYearsProdLeche",function(data){
            console.log(data);
            if(data.length > 0){
               $("#selAnioProdLecheR1").html(tmpSelAnioProdLeche({data:data}));
               $("#selAnioProdLecheR2").html(tmpSelAnioProdLeche({data:data})) ;    
            }else{

            }
        },"json");
    }

    var tmpBodyModalFormRegGanado=_.template($("#tmpBodyModalFormRegGanado").html());
    function openModalRegGanado(){
        open_modal("modalFormRegGanado");
        $("#divModalFormRegGanado").html(tmpBodyModalFormRegGanado);
    }

    function deleteProdLeche(id){
         $.post(url_base+"Ganado/delete",{"id":id},function(data){
            if(data.status =="ok"){
                   alert_success("Se Realizo correctamente");
                   actualizarGrid("");
            }else{

            }
            console.log(data);
        },"json");
    }

    function getStockActualProdLeche(){
        $.post(url_base+"Ganado/getStockActualProdLeche",function(data){
            $("#bStockActualProdLeche").html(data.stockactual+ "");
        },'json');
    }



//------------------Reg salida
var tmpFormRegSalida=_.template($("#tmpFormRegSalida").html());
var tmpFormRegSalidaDivIsVenta=_.template($("#tmpFormRegSalidaDivIsVenta").html());
var tmpFormRegSalidaDivIsDescarte;
function openModalRegSalida(){
    open_modal("modalFormRegSalida");
    $("#divModalFormRegSalida").html(tmpFormRegSalida);
    $("#divTipoSalida").html(tmpFormRegSalidaDivIsVenta);
}

$(document).on("change","#selTipoSalida",function(){
    var valSel=$(this).val();

console.log(valSel);
});
    
</script>


