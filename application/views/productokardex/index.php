<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Productos</h3>
            </div>

            <!--Data Table-->
            <!--===================================================-->
            <div class="panel-body" style="padding-top: 0px;" >

                <div class="table-toolbar-left">
                    <!--<button class="btn btn-purple" id="add"><i class="demo-pli-add icon-fw"></i> Agregar</button>
                    <button class="btn btn-purple" id="btnAdd"><i class="demo-pli-add icon-fw"></i> Agregar Nuevo</button>-->
                    <!--<div class="btn-group">
                      <button class="btn btn-default" id="refresh"  ><i class="demo-pli-refresh" ></i>Refrescar</button>

                  </div>-->
              </div>
              <br><br>
              <!--CUerpot aqui -->
                <h1></h1>
                <table id="tabla_grid" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
                    <thead>
                    <tr><th>#</th>
                        <th>Lote</th>
                        <th>Detalle</th>
                        <th>Stock</th>
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



<div class="modal fade"   id="modal_id" role="dialog" data-backdrop="static" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 80%;" >
        <div class="modal-content">

            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="btn btn-default btn-sm" style="text-align: right" onclick="Imprime('pdf')" ><i class="fa fa-print"></i> Imprimir PDFs </button>
                <button type="button" class="btn btn-default btn-sm" style="text-align: right" onclick="Imprime('exel')" ><i class="fa fa-print"></i> Imprimir Excel </button>

                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="modalTitle"></h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="divModalBody">

            </div>
        </div>
    </div>
</div>

<?php echo $_js?>
<?php echo $_css?>
<script type="text/javascript">
    var titles={'addtitle':"Nuevo tipo de animal" ,'updatetitle':"Editar tipo de animal"};
    var isEdit=null,idEdit=null;
    var rowSelection=null;

    $(document).ready(function () {
        iniDatatable();
    });

    function iniDatatable() {
        var rowSelection = $('#tabla_grid').DataTable({
            "ajax": url_base+'productokardex/getDataTable',
            "columns": [
                { "data": null },
                {  "render": function ( data, type, full, meta ) {
                        var name = full.nroloteproduccion;
                        //var lastname = full.apellidos;
                        var html=""+name;
                        return html;
                    }
                },
                {  "render": function ( data, type, full, meta ) {
                        var name = full.cultivo+" "+full.clasecultivo+" "+full.catcultivo+" | "+full.cultivar;
                        //var lastname = full.apellidos;
                        var html=""+name;
                        return html;
                    }
                },
                {  "render": function ( data, type, full, meta ) {
                        var name = full.stock;
                        //var lastname = full.apellidos;
                        var html=""+name;
                        return html;
                    }
                },
                {  sortable: false,
                    "render": function ( data, type, full, meta ) {
                        var id = full.idproductokardex;
                        html="";
                        //html='<a href="javascript:void(0)"  onclick="ver('+id+');" class="btn btn-info btn-xs"><i class="fa fa-file-text-o"></i> Ver</a>';
                        //html='<a href="javascript:void(0)"  onclick="vercamp('+id_x +',\''+placeid+'\');" class="btn btn-mint btn-xs"><i class="demo-psi-pen-5 icon-lg"></i></a>';
                        html=html+"&nbsp; <a href='javascript:void(0)' onclick='ver("+id +","+JSON.stringify(full)+");'  class='btn btn-mint btn-icon btn-xs'><i class='fa fa-file'></i> Ver</a>";
                        //html=html+'&nbsp; <a href="javascript:void(0)" onclick="eliminar('+id +');"  class="btn btn-danger btn-icon btn-xs"><i class="demo-psi-recycling icon-xs"></i> Eliminar</a>';
                        return html;
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
            }

        });

        $('#tabla_grid').on( 'click', 'tr', function () {
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
            }
            else {
                rowSelection.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        } );

        rowSelection.on( 'order.dt search.dt', function () {
            rowSelection.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();

    }

    function refrescar () {
        var rowSelection = $('#tabla_grid').DataTable();
        rowSelection.ajax.reload();
    }

    $(document).on('click','#btnAdd',function () {
        $("#isEdit").val(0);
        $("#idEdit").val(0);
        $("#formActop")[0].reset();
        $('#modalTitle').html(titles['addtitle']);
        open_modal('modal_id');


        //alert(isActividad);
        //console.log();
    });

    function validateForm() {
        var bol=true;
        bol=bol&& $('#name').required();
        return bol;
    }
    function btnSave(id){
        var idd=parseInt(id);
        var bol=validateForm();
        var status=null;
        console.log($('#formActop').serialize());
        //bol=false;
        if(bol){
            var dataForm=$('#formActop').serialize();
            $.post(url_base+'tipoanimal/setForm',dataForm,function (data) {
                console.log(data);
                if(data.status === "ok"){
                    alert_success("Se ejecuto correctamente ...");
                    close_modal('modal_id');
                    refrescar();
                }else{
                    alert_error("Error");
                }
            },'json');
        }else{

        }
    }

    function  editar(id){
        $('#btnSave2').remove();
        isEdit=1;

        var idd=parseInt(id);
        $.post(url_base+'tipoanimal/getData','id='+idd,function (data) {
            console.log(data);
            setForm(data[0]);
            $('#modalTitle').html(titles['updatetitle']);
            open_modal('modal_id');
        },'json')
            .always(function () {
            });

    }

    function setForm(data){
        var valData=Object.keys(data).length;
        if(valData > 0){
            $("#name").val(data.nombre);
            $("#isEdit").val(1);
            $("#idEdit").val(data.idtipoanimal);
        }else{
            alert_error("Error");
        }
    }
    function ver(id,proddt) {
        open_modal("modal_id");
        var tmpModalBody=_.template($("#tmpModalBody").html());
        $.post(url_base + "productokardex/getProductKardex", {"id": id}, function (data) {
            $("#divModalBody").html(tmpModalBody({proddt:proddt,data:data}));
        },'json');
    }

    function setBtnSave2() {
        var ht='<a href="javascript:void(0)" onclick="btnSave(2)" type="button"  id="btnSave2" class=" btn btn-primary" >';
        ht+='Guardar y agregar otro';
        ht+='</a>';
        return ht;
    }

    function eliminar(id) {
        if(confirm('¿Esta seguro de eliminar este registro?')){
            var idd=parseInt(id);
            $.post(url_base+'tipoanimal/deleteData','id='+idd,function (data) {
                if(data.status == 'ok'){
                    alert_success('Se realizo correctamente');
                    refrescar();
                }else{
                    alert_error('Error');
                }
                console.log(data);
            },'json');
        }
    }

    function printDiv(nombreDiv) {
        var contenido= document.getElementById(nombreDiv).innerHTML;
        var contenidoOriginal= document.body.innerHTML;

        document.body.innerHTML = contenido;

        window.print();

        document.body.innerHTML = contenidoOriginal;
    }
    function Imprime(op){
        if(op=="pdf"){
            printDiv("divModalBody");
        }else{
            window.open(url_base+"Reportes/imprimirProductoKardex/"+$("#idprod").val(),"", "width=400,height=300");
        }

    }
</script>
<script type="text/template" id="tmpModalBody">
    <% console.log(proddt); %>
    <div class="row">
        <input type="hidden" id="idprod" value="<%=proddt.idproductokardex%>">
        <div class="col-lg-12" style="text-align: center">
            <h4>
            Kardex - Semilla - <%=proddt.cultivo%><br>
            <%=proddt.cultivar%> <br>
            <%=proddt.clasecultivo%> - <%=proddt.catcultivo%>
            </h4><br>
        </div>
        <div class="form-horizontal">
            <div class="panel-body">
                <div class="form-group" style="margin-bottom: 0px;" >
                    <label class="col-sm-2 control-label"  >Campaña:</label>
                    <label class="col-sm-3 control-label" style="text-align: left;"  ><%=proddt.anio%></label>
                </div>
                <div class="form-group" style="margin-bottom: 0px;" >
                    <label class="col-sm-2 control-label" for="demo-hor-inputemail">Fecha Ingreso:</label>
                    <label class="col-sm-3 control-label" style="text-align: left;" ><%=proddt.fechaenvioalmacen%></label>
                </div>
                <div class="form-group" style="margin-bottom: 0px;" >
                    <label class="col-sm-2 control-label" for="demo-hor-inputemail">Cantidad Semilla:</label>
                    <label class="col-sm-3 control-label" style="text-align: left;" ><%=proddt.semillavendible%></label>
                </div>
                <div class="form-group" style="margin-bottom: 0px;" >
                    <label class="col-sm-2 control-label" for="demo-hor-inputemail">Precio (kg):</label>
                    <label class="col-sm-3 control-label" style="text-align: left;" ><input type="text" class="form-control" id="precio" name="precio">  </label>
                </div>
                <div class="form-group" style="margin-bottom: 0px;" >
                    <label class="col-sm-2 control-label" for="demo-hor-inputemail">Lote:</label>
                    <label class="col-sm-3 control-label" style="text-align: left;" ><%=proddt.nroloteproduccion%></label>
                </div>
                <div class="form-group" style="margin-bottom: 0px;" >
                    <label class="col-sm-2 control-label" for="demo-hor-inputemail">Sacos (<%=proddt.kgsaco%>kg):</label>
                    <label class="col-sm-3 control-label"  style="text-align: left;" ><%= (proddt.semillavendible/proddt.kgsaco).toFixed(2)%></label>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-condensed">
                    <thead>

                    <tr>
                        <th class="text-center" rowspan="2" >Fecha</th>
                        <th  rowspan="2">Documento</th>
                        <th rowspan="2" >Cliente</th>
                        <th colspan="2" > Ingreso de Semilla </th>
                        <th colspan="2" > Salida de Semilla </th>
                        <th colspan="2" > Saldo de Semilla </th>
                    </tr>
                    <tr>
                        <th>Kg</th>
                        <th>Saco</th>
                        <th>Kg</th>
                        <th>Saco</th>
                        <th>Kg</th>
                        <th>Saco</th>
                    </tr>
                    </thead>
                    <tbody>
                    <%  var histoSaldo=Number(proddt.semillavendible);
                      console.log(histoSaldo);
                       _.each(data,function(i,k){
                        var montosalidaproducto=0;
                        var montoingresoproducto=0
                        if(i.idtipoflujo == 1){
                            montosalidaproducto=i.cantidad;
                            histoSaldo=histoSaldo-montosalidaproducto;
                          }else{
                           montoingresoproducto=i.cantidad;
                           histoSaldo=histoSaldo+montoingresoproducto;
                         }

                    %>
                    <tr>
                        <td><input type="date" style="border: 0"   readonly="readonly" value="<%= i.fechareg %>"></td>
                        <td><%= i.nrodoc %></td>
                        <td><%= i.nombre %></td>
                        <td><%= montoingresoproducto %></td>
                        <td><%= (montoingresoproducto/proddt.kgsaco).toFixed(2) %></td>
                        <td><%= montosalidaproducto %></td>
                        <td><%= (montosalidaproducto/proddt.kgsaco).toFixed(2) %></td>
                        <td><%= histoSaldo %></td>
                        <td><%= (histoSaldo/proddt.kgsaco).toFixed(2) %></td>
                    </tr>
                    <% });%>
                    </tbody>
                </table>
            </div>


        </div>
    </div>
</script>
