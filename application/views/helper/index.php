<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Helper </h3>
            </div>

            <!--Data Table-->
            <!--===================================================-->
            <div class="panel-body" style="padding-top: 0px;" >
                <br>

               <div class="row">
                   <div class="col-lg-12">
                       <div class="col-lg-4">
                           <h3>Tablas</h3>
                           <div id="divListTablasBD" class="col-lg-12">
                            </div>
                       </div>
                       <div class="col-lg-8">
                           <div id="divHelperTable" class="col-lg-12">

                           </div>
                       </div>
                   </div>
               </div>
              <br><br>
              <!--CUerpot aqui -->
                <h1></h1>
                <table id="tabla_grid" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
                    <thead>
                    <tr><th>#</th>

                        <th>Nombre</th>
                        <th>Fecha Reg.</th>
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
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">

            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="modalTitle">Tareas</h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="divform">


            </div>

        </div>
    </div>
</div>


<?php echo $_css?>
<?php echo $_js?>

<script type="text/template" id="tmpTableHelper">
    <% var row=0;%>
    <p style="font-weight: bold;font-size: 18px;"><%=tabla%></p>
    $dataG=array(<br>
    <% _.each(data,function(ii,kk){ row=(kk+1); %>
       "<%=ii.columna%>"=>$value["<%=ii.columna%>"],<br>
    <% }); %>
    );
    <hr>
    Tipo Post<br>
    <% _.each(data,function(i,k){ %>
    $<%=i.columna%>=$post["<%=i.columna%>"];<br>
    <% }); %>
    <br>
    $dataG=array(<br>
    <% _.each(data,function(i,k){ %>
    "<%=i.columna%>"=>$<%=i.columna%>,<br>
    <% }); %>
    );
    <hr>
    Id- Names<br>
    <% _.each(data,function(i,k){ %>
    id="<%=i.columna%>"  name="<%=i.columna%>" || id='<%=i.columna%>'  name='<%=i.columna%>' <br>
    <% }); %>
    <br>
   <textarea  style="width: 100%;" rows="<%=row%>" >
       <% _.each(data,function(i,k){ %><input type="text" class="form-control" id="<%=i.columna%>" name="<%=i.columna%>" >
       <% }); %>
    </textarea>



</script>
<script type="text/javascript">
var tmpTableHelper=_.template($("#tmpTableHelper").html());

    $(document).on("ready",function () {
        getDBTables();
    });

    function getDBTables(){
        $.post(url_base+"Helper/getTablesBD",function (data) {
            console.log(data);
            var c=data.length;
            var ht="";
            if(c > 0){
                $.each(data,function (k,v) {
                    ht+='<a href="javascript:void(0)"  onclick="generaHelperTabla(\''+v.tabla+'\')">'+v.tabla+'</a><br>';
                });
                $("#divListTablasBD").html(ht);
            }
        },'json');
    }

    function generaHelperTabla(tabla) {
        $.post(url_base+"Helper/getHelperTable",{"tabla":tabla},function (data) {
            console.log(data);
            var c = data.length;
            $("#divHelperTable").html(tmpTableHelper({tabla:tabla,data:data}));
        },'json');
    }
</script>
