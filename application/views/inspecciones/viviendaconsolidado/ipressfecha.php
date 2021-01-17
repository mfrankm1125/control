<style>

</style>
<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <div class="panel-control">
                    <a href="<?=base_url()?>insviviconsolidado" class="btn btn-link">Átras</a>
                </div>
                <h3 class="panel-title">Inspección a viviendas  Realizadas - <?=$ipress?>  <?=$fecha?> </h3>
            </div>

            <!--Data Table-->
            <!--===================================================-->
            <div class="panel-body" style="padding-top: 0px;">
                <br>
                <div class="table-toolbar-left">
                    <!--<button class="btn btn-purple" id="add"><i class="demo-pli-add icon-fw"></i> Agregar</button> -->
                    <button class="btn btn-purple" id="btnAdd"><i class="demo-pli-add icon-fw"></i> Agregar Nuevo</button>
                    <button class="btn btn-purple" id="btnOpenModalAddCSV"><i class="demo-pli-add icon-fw"></i> Importar datos desde CSV</button>
                    <div class="btn-group">
                        <button class="btn btn-default" id="refresh"  ><i class="demo-pli-refresh" ></i>Refrescar</button>

                    </div>
                </div>
                <br><br>
                <!--CUerpot aqui -->
                <h1></h1>
                <table id="tabla_grid" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>IPRESS</th>
                        <th>Total viviendas</th>
                        <th>Total viviendas Insp.</th>
                        <th>Total viviendas positivas</th>
                        <th>Total viviendas tratadas</th>
                        <th>Consumo larvicida (gr)</th>
                        <th title="Índice Aédico">I.A %</th>

                        <th>Fecha Interveción</th>
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


<div class="modal fade"   id="modalSeeDetaill" role="dialog"  data-backdrop="static" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg "  style="width: 95%" >
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id=" "></h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id=" " >
                <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive table-cont"  id='table-cont' style="height: 500px">
                            <table style="border-collapse: separate !important;" class="table table-bordered table-hover table-condensed tx" id="tbx" >
                                <thead style="background-color: white;" >
                                <tr style="font-size: 10px;">
                                    <th  >#</th>
                                    <th  ><span>DISTRITO</span><span style="visibility: hidden">_____</span> </th>
                                    <th  >LOCALIDAD</th>
                                    <th  >ESTABLECIMIENTO</th>
                                    <th style="height: 180px;"  ><p style=" writing-mode: vertical-rl; transform: rotate(180deg)">ZONA O SECTOR</p></th>
                                    <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">Nº MANZANA</p> </th>
                                    <th  style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">CERCO ENTOMOLOGICO Nº DE OVITRAMPA</p>
                                    </th>
                                    <th  > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">TOTAL DE VIVIENDAS</p> </th>
                                    <th  > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">LOTE</p> </th>
                                    <th style="height: 180px;"  > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">VIVIENDA INSPECC. TOTAL</p> </th>
                                    <th  > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">CERRADA </p></th>
                                    <th  > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">DESHABITADA </p></th>
                                    <th  > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">RENUENTE </p></th>
                                    <th style="height: 180px;"  > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">TANQUE ALTO, BAJO, POZOS INSPEC.</p> </th>
                                    <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">TANQUE ALTO, BAJO, POZOS POSITIVOS </p></th>
                                    <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">TANQUE ALTO, BAJO, POZOS  TRATADOS </p></th>
                                    <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">BARRIL, CILINDRO, SANSON  INSPEC. </p></th>
                                    <th  style="height: 180px;"> <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">BARRIL, CILINDRO, SANSON POSITIVO </p></th>
                                    <th style="height: 180px;"> <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">BARRIL, CILINDRO, SANSON TRATADO </p></th>
                                    <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">BALDE, BATEA, TINA  INSPEC.</p> </th>
                                    <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">BALDE, BATEA, TINA POSITIVO </p></th>
                                    <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">BALDE, BATEA, TINA  TRATADO </p></th>
                                    <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">OLLAS, CANTAROS DE BARRO
                                            INSPEC.</p> </th>
                                    <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">OLLAS, CANTAROS DE
                                            BARRO POSITIVOS </p> </th>
                                    <th style="height: 180px;"  > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">OLLAS, CANTAROS DE
                                            BARRO TRATADOS </p> </th>
                                    <th style="height: 180px;"> <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">FLOREROS, MACETAS  INSPEC </p></th>
                                    <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">FLOREROS, MACETAS
                                            POSITIVO </p> </th>
                                    <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">FLOREROS, MACETAS  TRATADO</p> </th>
                                    <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">LLANTAS  INSPEC.</p> </th>
                                    <th style="height: 180px;"  > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">LLANTAS POSITIVAS </p></th>
                                    <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">LLANTAS  TRATADOS</p> </th>
                                    <th  style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">INSERVIBLES QUE SON
                                            CRIADEROS</p>
                                    </th>
                                    <th  > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">INSERVIBLES QUE SON
                                            CRIADEROS POSITIVOS</p>
                                    </th>
                                    <th style="height: 180px;" >
                                        <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">INSERVIBLES QUE SON
                                            CRIADEROS  ELIMINADOS </p>
                                    </th>
                                    <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">OTROS RECIPIENTES  INSPEC.</p> </th>
                                    <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">OTROS RECIPIENTES POSITIVOS </p></th>
                                    <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">OTROS RECIPIENTES  TRATDOS </p> </th>
                                    <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)"> INSPECCIONADOS </p> </th>
                                    <th style="height: 180px;"  > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">TOTAL POSITIVOS </p> </th>
                                    <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">TRATADOS</p> </th>
                                    <th style="height: 180px;"  > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">ELIMINADOS</p> </th>
                                    <th style="height: 180px;"  > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">TOTAL DE VIVIENDAS
                                            TRATADAS</p> </th>
                                    <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">TOTAL DE VIVIENDAS POSITIVAS </p> </th>
                                    <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">CONSUMO DE PYRIPROXYFEN (Gr.) </p> </th>
                                    <th   > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">SEMANA ENTOMOLOGICA</p> </th>
                                    <th  >FECHA DE INTERVENCIÓN</th>
                                    <th  ><span>INSPECTOR</span><span style="visibility: hidden" >__________________________________________</span></th>
                                    <th  ><span>JEFE DE BRIGADA</span><span style="visibility: hidden" >__________________________________________</span></th>
                                </tr>
                                </thead>
                                <tbody id="tTbodyView" style="font-size: 12px;">

                                </tbody>
                            </table>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $_css?>
<?php echo $_js?>

<script type="text/javascript">

    window.onload = function(){
        var tableCont = document.querySelector('#table-cont')
        /**
         * scroll handle
         * @param {event} e -- scroll event
         */
        function scrollHandle (e){
            var scrollTop = this.scrollTop;
            this.querySelector('thead').style.transform = 'translateY(' + scrollTop + 'px)';       }
        tableCont.addEventListener('scroll',scrollHandle)
    }

    var ipress="<?=$ipress?>";
    var aniofecha="<?=$fecha?>";
    $(document).ready(function () {

        var rowSelection = $('#tabla_grid').DataTable({
            "ajax": url_base+'insviviconsolidado/getDataTableAnioIpress/'+ipress+"/"+aniofecha,
            "columns": [
                { "data": null },
                {  "render": function ( data, type, full, meta ) {
                        var name = full.descipress;
                        //var lastname = full.apellidos;
                        var html=""+name;
                        return html;
                    }
                },
                { "data": "sumtotalviviendas" },
                { "data": "sumviviendainsptotal" },
                { "data": "sumtotalviviendaspositivas" },
                { "data": "sumtotalviviendastratadas" },
                { "data": "sumconsumolarvicidagr" },
                {  sortable: true,
                    "render": function ( data, type, full, meta ) {
                    var arrIA=["-","<span style='color:green'><b>Bajo Riesgo</b></span>","<span style='color:orangered'><b>Mediano Riesgo</b></span>","<span style='color:red'><b>Alto Riesgo</b></span>"];
                        var nviviendasposi = Number(full.sumtotalviviendaspositivas);
                        var nviviendasinsp = Number(full.sumviviendainsptotal);
                        var r=0;
                        var ht="-";
                        if(nviviendasinsp > 0){
                            r=Number((nviviendasposi/nviviendasinsp)*100);
                            r=parseFloat(r).toFixed(2);
                            if(r >=0 && r < 1 ){
                                ht=arrIA[1];
                            }else if(r >=1 && r < 2 ){
                                ht=arrIA[2];
                            }else if(r >=3 ){
                                ht=arrIA[3];
                            }
                        }


                        return r+"% - "+ht;
                    }
                },

                {  "render": function ( data, type, full, meta ) {
                        var name = full.fechaintervencion;
                        //var lastname = full.apellidos;
                        var html="<input readonly='readonly' style='border: 0px;' type='date' value='"+name+"'><span style='font-size:1px;visibility: hidden'>"+name+"</span>";
                        return html;
                    }
                },
                {  sortable: false,
                    "render": function ( data, type, full, meta ) {
                        var fecha = full.fechaintervencion;
                        var ipress = full.descipress;
                        html="";
                        //html='<a href="javascript:void(0)"  onclick="ver('+id+');" class="btn btn-info btn-xs"><i class="fa fa-file-text-o"></i> Ver</a>';
                        html='<a href="javascript:void(0)"  onclick="ver(\''+ipress +'\',\''+fecha+'\');" class="btn btn-mint btn-xs"><i class="fa fa-eye"></i>Ver</a>';
                        //html=html+"&nbsp; <a href='javascript:void(0)' onclick='ver(\""+ipress+"\",\""+mesanio+"\");'  class='btn btn-mint btn-icon btn-xs'><i class='fa fa-eye'></i> Ver</a>";
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


    });
    function refrescar () {
        var rowSelection = $('#tabla_grid').DataTable();
        rowSelection.ajax.reload();
    }

    function ver(ipress,fecha) {
        open_modal("modalSeeDetaill");
        var tmpTbodyDetail=_.template($("#tmpTbodyDetail").html());
        $.post(url_base+"insviviconsolidado/getDataDetaillInpsVivienda/",{"ipress":ipress,"fecha":fecha},function (data) {
            $("#tTbodyView").html(tmpTbodyDetail({data:data}));
        },'json');
    }
</script>

<script type="text/template" id="tmpTbodyDetail">
    <% _.each(data,function(i,k){ %>



    <tr>
        <td><%=(k+1)%></td>
        <td><%=i.distrito%></td>
        <td><%=i.localidad%></td>
        <td><%=i.descipress%></td>
        <td><%=i.sector%></td>
        <td><%=i.nmanzana%></td>
        <td><%=i.nroovitrampa%></td>
        <td><%=i.totalviviendas%></td>
        <td><%=i.lote%></td>
        <td><%=i.viviendainsptotal%></td>
        <td><%=i.cerrada%></td>

        <td><%=i.deshabitada%></td>
        <td><%=i.renuente%></td>

        <td><%=i.tanquealto_inspeccionado%></td>
        <td><%=i.tanquealto_positivo%></td>
        <td><%=i.tanquealto_tratados%></td>

        <td><%=i.barril_inspeccionado%></td>
        <td><%=i.barril_positivo%></td>
        <td><%=i.barril_tratado%></td>

        <td><%=i.balde_inspeccionado%></td>
        <td><%=i.balde_positivo%></td>
        <td><%=i.balde_tratado%></td>

        <td><%=i.olla_inspeccionado%></td>
        <td><%=i.olla_positivo%></td>
        <td><%=i.olla_tratado%></td>

        <td><%=i.florero_inspeccionado%></td>
        <td><%=i.florero_positivo%></td>
        <td><%=i.florero_tratado%></td>

        <td><%=i.llantas_inspeccionado%></td>
        <td><%=i.llantas_positivo%></td>
        <td><%=i.llantas_tratado%></td>

        <td><%=i.inservibles_inspeccionado%></td>
        <td><%=i.inservibles_positivo%></td>
        <td><%=i.inservibles_eliminado%></td>

        <td><%=i.otros_inspeccionado%></td>
        <td><%=i.otros_positivo%></td>
        <td><%=i.otros_tratados%></td>

        <td><%=i.tinspeccionados%></td>
        <td><%=i.tpositivos%></td>
        <td><%=i.ttratados%></td>

        <td><%=i.teliminados%></td>
        <td><%=i.totalviviendastratadas%></td>
        <td><%=i.totalviviendaspositivas%></td>

        <td><%=i.consumolarvicidagr%></td>
        <td><%=i.semanaentomologica%></td>
        <td><%=i.fechaintervencion%></td>
        <td><%=i.inspector%></td>
        <td><%=i.jefebrigada%></td>
    </tr>





    <% }); %>

</script>
