<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Plan de produccion</h3>
                <input type="hidden" id="anioViewDetail" value="">
                <input type="hidden" id="tipoproduccionViewDetail" value="">
                <input type="hidden" id="isActiveViewDetailPlan" value="0">
            </div>

            <!--Data Table-->
            <!--===================================================-->
            <div class="panel-body" style="padding-top: 0px;" >
                <br>
                <div class="table-toolbar-left">
                    <!--<button class="btn btn-purple" id="add"><i class="demo-pli-add icon-fw"></i> Agregar</button> -->
                    <button class="btn btn-purple" id="btnAdd"><i class="demo-pli-add icon-fw"></i> Agregar Nuevo</button>
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
                        <th>Descripción</th>

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
<style>
    .modal {
        overflow-y:auto;
    }
</style>


<div class="modal fade"     id="modalEnviarProduccionInv" role="dialog" data-backdrop="false" tabindex="-1" aria-labelledby="demox" aria-hidden="true">
    <div class="modal-dialog  "  style="-webkit-box-shadow: -1px 2px 36px 13px rgba(0,0,0,0.75);
-moz-box-shadow: -1px 2px 36px 13px rgba(0,0,0,0.75);
box-shadow: -1px 2px 36px 13px rgba(0,0,0,0.75);"  >

        <div class="modal-content"   >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" ></h4>
            </div>
            <div class="modal-body" >
                <div class="panel-body">
                    <form id="formEnvioAlmacen">
                        <div class="row">
                            <div class="col-sm-12  ">
                                <label class="control-label">Fecha de envio:</label>
                                <input style="line-height: 10px;"  type="date" class="form-control" id="fechaenvioalmacen" name="fechaenvioalmacen" value="<?= date("Y-m-d")?>" >
                            <br>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">Semilla Acondicionada (t)</label>
                                    <input readonly="readonly" type="text" class="form-control text-right"   id="semillaacondienviox" name="semillaacondienviox">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">Semilla Acondicionada (kg)</label>
                                    <input  type="text" class="form-control text-right" readonly="readonly" id="semillaacondienvioxkg" name="semillaacondienvioxkg">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">Sacos (<b id="htPesoSaco"></b>) </label>
                                    <input readonly="readonly" type="text" class="form-control text-right" id="sacosenviaox" name="sacosenviaox" >
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12  ">
                                <label class="control-label">Medio de verificación:</label>
                                <input  type="file" class="form-control" id="fileVerificacionAlmacen" name="fileVerificacionAlmacen" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12  ">
                                <label class="control-label">Lote:</label>
                                <input  type="text" class="form-control" id="namelote" name="namelote" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12  ">
                                <br>
                                <p><i> *Verifique los datos antes de enviar, ya que no podran ser modificados luego </i></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-9 col-sm-offset-3">
                                <input type="hidden" id="idplanejecEnvioAlmacen" name="idplanejecEnvioAlmacen" >
                                <input type="hidden" id="cultivoejeEnvioAlmacen" name="cultivoejeEnvioAlmacen" >
                                <input type="hidden" id="cultivarejeEnvioAlmacen" name="cultivarejeEnvioAlmacen" >
                                <input type="hidden" id="claseejeEnvioAlmacen" name="claseejeEnvioAlmacen" >
                                <input type="hidden" id="catcultivoejeEnvioAlmacen" name="catcultivoejeEnvioAlmacen" >
                                <input type="hidden" id="loteEjeEnvioAlmacen" name="loteEjeEnvioAlmacen" >
                                <button class="btn btn-mint" type="button" id="confirmaEnvioAlmacen">Confirmar Envio</button>
                                <button class="btn btn-danger" data-dismiss="modal" type="button">Cancelar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade"   id="modalVerPlan" role="dialog" data-backdrop="static" tabindex="-1" aria-labelledby="demo2" aria-hidden="true">
    <div class="modal-dialog modal-lg"  style="width: 96%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="modalTitlex"></h4>
            </div>
            <div class="modal-body" id="divDataTablePlanProduccion">
            </div>
        </div>
    </div>
</div>
<div class="modal fade"   id="modalRegEjecPlan" role="dialog" data-backdrop="static" tabindex="-1" aria-labelledby="demo3" aria-hidden="true">
    <div class="modal-dialog modal-lg"  style="width: 93%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="">Registro de ejecución </h4>
            </div>
            <div class="modal-body" id="divRegEjecPlan">



            </div>
        </div>
    </div>
</div>


<div class="modal fade"   id="modal_id" role="dialog" data-backdrop="static" tabindex="-1" aria-labelledby="4" aria-hidden="true">
    <div class="modal-dialog modal-lg"  style="width: 80%;">
        <div class="modal-content">

            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="modalTitle"></h4>
            </div>
            <!--Modal body-->
            <div class="modal-body">
                <form id="formPlanProduccion">
                    <input type="hidden" id="isEdit" name="isEdit" value="">
                    <input type="hidden" id="idEdit" name="idEdit" value="">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label style="padding-left: 0!important;" class="col-sm-3 control-label" for="demo-hor-inputemail">Producción:</label>
                                    <div class="col-sm-9">
                                        <select id="selTipoProduccion" name="selTipoProduccion" class="form-control">
                                            <?php foreach($tipoproduccion as $i){ ?>
                                                <option value="<?=$i["idtipoproduccion"]?>"><?=$i["nombre"]?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label style="padding-left: 0!important;" class="col-sm-3 control-label" for="demo-hor-inputemail">Año:</label>
                                    <div class="col-sm-9">
                                        <input type="text" id="anio" name="anio" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label style="padding-left: 0!important;" class="col-sm-3 control-label" for="demo-hor-inputemail">Esta. Experimental:</label>
                                    <div class="col-sm-9">
                                        <select id="selEstacionExperimental" name="selEstacionExperimental" class="form-control">
                                            <?php foreach($estacionexperimental as $i){ ?>
                                                <option value="<?=$i["idestacionexperimental"]?>"><?=$i["nombre"]?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label style="padding-left: 0!important;" class="col-sm-3 control-label" for="demo-hor-inputemail">Ubi GPS:</label>
                                    <div class="col-sm-9">
                                        <input type="text" id="ubicagps" name="ubicagps" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">Departamento</label>
                                    <input type="text" id="departamento" name="departamento" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">Provincia</label>
                                    <input type="text" id="provincia" name="provincia" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">Distrito</label>
                                    <input type="text"  id="distrito" name="distrito" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">Anexo</label>
                                    <input type="text" id="anexo" name="anexo" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label style="padding-left: 0!important;" class="col-sm-2 control-label" for="demo-hor-inputemail">Responsable:</label>
                                    <div class="col-sm-9">
                                        <input type="text"  id="responsable" name="responsable" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div id="divFormPlanProd">

                        </div>
                    </div>
                    <div class="panel-footer text-right">
                        <button class="btn btn-success" id="btnSavePlanProduccion" type="button">Guardar</button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>
<script type="text/template" id="tmpIsSemilla" >
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label style="padding-left: 0!important;" class="col-sm-2 control-label" for="demo-hor-inputemail">Cultivo:</label>
                <div class="col-sm-9">
                    <select id="selCultivo" name="selCultivo" class="form-control">
                        <?php foreach($dataCultivo as $i){ ?>
                            <option value="<?=$i["idcultivo"]?>"><?=$i["nombre"]?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Cultivar</label>
                <input type="text" id="cultivar" name="cultivar" class="form-control">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Clase</label>
                <select id="selClase" name="selClase" class="form-control">
                    <?php foreach($dataClaseCultivo as $i){ ?>
                        <option value="<?=$i["idclasecultivo"]?>"><?=$i["nombre"]?></option>
                    <?php }?>
                </select>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Categoria</label>
                <select id="selCategoria" name="selCategoria" class="form-control">
                    <?php foreach($dataCategoriaCultivo as $i){ ?>
                        <option value="<?=$i["idcategoriacultivo"]?>"><?=$i["nombre"]?></option>
                    <?php }?>
                </select>

            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Area a Sembrar (ha.) </label>
                <input type="text" id="areasembrar" name="areasembrar"  class="form-control">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">F. de siembra</label>
                <input style="line-height:28px;padding-top: 0px;padding-bottom: 0px;"
                       type="date" id="fsiembra" name="fsiembra"  class="form-control">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">F. probable de cosecha</label>
                <input style="line-height:28px; padding-top: 0px;padding-bottom: 0px;"
                       type="date" id="fprobablecosecha" name="fprobablecosecha"   class="form-control">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Modalidad de conducción</label>
                <input type="text" id="modalidadconduccion" name="modalidadconduccion" class="form-control">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Semilla / ha a emplear (kg) </label>
                <input type="text" id="semillaaemplear" name="semillaaemplear" class="form-control">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Prod. Estimada en peso total(t)</label>
                <input type="text" id="prodestimadapesototal" name="prodestimadapesototal" class="form-control">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Rendimiento Estimado (kg/ha)</label>
                <input type="text"   id="rendimientoestimado" name="rendimientoestimado"  class="form-control text-right" readonly="readonly">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Semilla acondicionada estimada (t) </label>
                <input type="text" id="semillaacondicioandaestimada" name="semillaacondicioandaestimada" class="form-control">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Costo de producción/ha (S/./ha) </label>
                <input type="text" id="costoproduccionprogramado" name="costoproduccionprogramado"  class="form-control">
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Bajo secano o bajo riego?</label>
                <input type="text" id="riego" name="riego"  class="form-control">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Tipo de riego</label>
                <input type="text"  id="tiporiego" name="tiporiego" class="form-control">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Distanciamiento entre surcos</label>
                <input type="text"  id="distanciasurcos" name="distanciasurcos" class="form-control">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Distanciamiento entre plantas </label>
                <input type="text"  id="distanciaplantas" name="distanciaplantas" class="form-control">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label style="padding-left: 0!important;" class="col-sm-12 control-label" for="demo-hor-inputemail"><b>Presupuesto por Fuente de financiamiento (S/.)</b>
                </label>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label style="padding-left: 0!important;" class="col-sm-2 control-label" for="demo-hor-inputemail">RO 2.3 </label>
                <div class="col-sm-9">
                    <input type="text" id="ro23" name="ro23" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label style="padding-left: 0!important;" class="col-sm-2 control-label" for="demo-hor-inputemail">RDR</label>
                <div class="col-sm-9">
                    <input type="text" id="rdr" name="rdr"  class="form-control">
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label style="padding-left: 0!important;" class="col-sm-3 control-label" for="demo-hor-inputemail">Presupuesto total</label>
                <div class="col-sm-9">
                    <input type="text" id="presupuestototal" name="presupuestototal"  class="form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Nro beneficiarios </label>
                <input type="text" id="nrobeneficiarios" name="nrobeneficiarios"  class="form-control">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Area a atender (Hs)</label>
                <input type="text" id="areaatender" name="areaatender" class="form-control">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Proyección de Ingreso x venta (S/)</label>
                <input type="text" id="proyecciondeingresoporventa" name="proyecciondeingresoporventa" class="form-control">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Margen de utilidad (S/.) </label>
                <input type="text" id="margenutilidad" name="margenutilidad" class="form-control">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label class="control-label">Criterios de estacionalidad de la venta de semillas</label>
                <input type="text" id="criteriosdeestacionalidad" name="criteriosdeestacionalidad"  class="form-control">
            </div>
        </div>
    </div>
    <div class="row">

    </div>

</script>


<script type="text/template" id="tmpIsPlanton" >
    <div class="row">
        <div class="col-sm-8">
            <div class="form-group">
                <label style="padding-left: 0!important;" class="col-sm-3 control-label" for="demo-hor-inputemail"> Cultivo frutícola o forestal</label>
                <div class="col-sm-6">
                    <input type="text" id="cultivoforestal" name="cultivoforestal" class="form-control">
                </div>
            </div>
        </div>
    </div>
<br>
    <div class="row">
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Cultivar</label>
                <input type="text" id="cultivar" name="cultivar" class="form-control">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Semilla No Certificada </label>
                <input type="text" id="semillanocertificada" name="semillanocertificada" class="form-control">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Yema</label>
                <input type="text" id="yema" name="yema"  class="form-control">

            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Patron </label>
                <input type="text" id="patron" name="patron"  class="form-control">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">F. de siembra</label>
                <input style="line-height:28px;padding-top: 0px;padding-bottom: 0px;"
                       type="date" id="fsiembra" name="fsiembra"  class="form-control">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">F. probable de cosecha</label>
                <input style="line-height:28px; padding-top: 0px;padding-bottom: 0px;"
                       type="date" id="fprobablecosecha" name="fprobablecosecha"   class="form-control">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Modalidad de conducción</label>
                <input type="text" id="modalidadconduccion" name="modalidadconduccion" class="form-control">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Semilla / ha a emplear (kg) </label>
                <input type="text" id="semillaaemplear" name="semillaaemplear" class="form-control">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Rendimiento Estimado (kg/ha)</label>
                <input type="text"  id="rendimientoestimado" name="rendimientoestimado"  class="form-control">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Prod. Estimada en peso total(T)</label>
                <input type="text" id="prodestimadapesototal" name="prodestimadapesototal" class="form-control">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Semilla acondicionada estimada (T) </label>
                <input type="text" id="semillaacondicioandaestimada" name="semillaacondicioandaestimada" class="form-control">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Costo de producción/ha (S/./ha) </label>
                <input type="text" id="costoproduccionprogramado" name="costoproduccionprogramado"  class="form-control">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Bajo secano o bajo riego?</label>
                <input type="text" id="riego" name="riego"  class="form-control">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Tipo de riego</label>
                <input type="text"  id="tiporiego" name="tiporiego" class="form-control">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Distanciamiento entre surcos</label>
                <input type="text"  id="distanciasurcos" name="distanciasurcos" class="form-control">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Distanciamiento entre plantas </label>
                <input type="text"  id="distanciaplantas" name="distanciaplantas" class="form-control">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label style="padding-left: 0!important;" class="col-sm-12 control-label" for="demo-hor-inputemail"><b>Presupuesto por Fuente de financiamiento (S/.)</b>
                </label>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label style="padding-left: 0!important;" class="col-sm-2 control-label" for="demo-hor-inputemail">RO 2.3 </label>
                <div class="col-sm-9">
                    <input type="text" id="ro23" name="ro23" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label style="padding-left: 0!important;" class="col-sm-2 control-label" for="demo-hor-inputemail">RDR</label>
                <div class="col-sm-9">
                    <input type="text" id="rdr" name="rdr"  class="form-control">
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label style="padding-left: 0!important;" class="col-sm-3 control-label" for="demo-hor-inputemail">Presupuesto total</label>
                <div class="col-sm-9">
                    <input type="text" id="presupuestototal" name="presupuestototal"  class="form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Nro beneficiarios </label>
                <input type="text" id="nrobeneficiarios" name="nrobeneficiarios"  class="form-control">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Area a atender (Hs)</label>
                <input type="text" id="areaatender" name="areaatender" class="form-control">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Proyección de Ingreso x venta (S/)</label>
                <input type="text" id="proyecciondeingresoporventa" name="proyecciondeingresoporventa" class="form-control">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Margen de utilidad (S/.) </label>
                <input type="text" id="margenutilidad" name="margenutilidad" class="form-control">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label class="control-label">Criterios de estacionalidad de la venta de semillas</label>
                <input type="text" id="criteriosdeestacionalidad" name="criteriosdeestacionalidad"  class="form-control">
            </div>
        </div>
    </div>
    <div class="row">

    </div>

</script>

<?php echo $_js?>
<?php echo $_css?>
<script type="text/javascript">
    var titles={'addtitle':"Nuevo tipo de animal" ,'updatetitle':"Editar tipo de animal"};
    var isEdit=null,idEdit=null;
    var rowSelection;
    $(document).ready(function () {
        datableIni();

    });

    $(document).on("change","#areasembrar,#prodestimadapesototal",function () {
        calRendiAddUpdNew();
    });
    $(document).on("keyup","#areasembrar,#prodestimadapesototal",function () {
        calRendiAddUpdNew();
    });

    function calRendiAddUpdNew(){
        var areasembrar=parseFloat($("#areasembrar").val())||0 ;
        var prodestimadapesototal=parseFloat($("#prodestimadapesototal").val())||0;
        var rendiDD=0;
        if(areasembrar > 0){
            prodestimadapesototal=prodestimadapesototal*1000;
            rendiDD = prodestimadapesototal/areasembrar ;
            rendiDD=rendiDD.toFixed(2)
        }
        console.log(prodestimadapesototal,areasembrar,rendiDD);
        $("#rendimientoestimado").val(rendiDD);
    }

    $(document).on("change","#selTipoProduccion",function () {
        iniSelect();
    });

    function iniSelect() {
        var tmpIsSemilla=_.template($("#tmpIsSemilla").html());
        var tmpIsPlanton=_.template($("#tmpIsPlanton").html());
        //var tmpIsProduccion=_.template($("#tmpIsProduccion").html());
        var selSelect=parseInt($("#selTipoProduccion").val());
        switch (selSelect){
            case 1:$("#divFormPlanProd").html(tmpIsSemilla);
                break;
            case 2:
                $("#divFormPlanProd").html(tmpIsPlanton);
                break;
            case 3:
                break;
            default:break;
        }
    }

    function datableIni(){
       rowSelection = $('#tabla_grid').DataTable({
            "ajax": url_base+'Planproduccion/getDataTable',
            "columns": [
                { "data": null },
                {  "render": function ( data, type, full, meta ) {
                        var name = full.anio;
                        //var lastname = full.apellidos;
                        var html="<a href='javascript:void(0)' onclick='verPlan("+name+","+full.idtipoproduccion+",1)'>"+name+" "+full.nombre+"</a>";
                        return html;
                    }
                },
                {  sortable: false,
                    "render": function ( data, type, full, meta ) {
                        var id = full.anio;
                        var html="";
                        //html='<a href="javascript:void(0)"  onclick="ver('+id+');" class="btn btn-info btn-xs"><i class="fa fa-file-text-o"></i> Ver</a>';
                        //html='<a href="javascript:void(0)"  onclick="vercamp('+id_x +',\''+placeid+'\');" class="btn btn-mint btn-xs"><i class="demo-psi-pen-5 icon-lg"></i></a>';
                        html=html+'&nbsp; <a href="javascript:void(0)" onclick="verPlan('+id+','+full.idtipoproduccion+',1);"  class="btn btn-mint btn-icon btn-xs"><i class="demo-psi-pen-5 icon-xs"></i> Ver</a>';
                        html=html+'&nbsp; <a href="javascript:void(0)" onclick="eliminar('+id+','+full.idtipoproduccion+');"  class="btn btn-danger btn-icon btn-xs"><i class="demo-psi-recycling icon-xs"></i> Eliminar</a>';
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
        $("#formPlanProduccion")[0].reset();

        $('#modalTitle').html("Registrar Plan de Producción");
        open_modal('modal_id');
        iniSelect();
        $("#isActiveViewDetailPlan").val(0);
        calRendiAddUpdNew();
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
    function ver(id){

    }

    function setBtnSave2() {
        var ht='<a href="javascript:void(0)" onclick="btnSave(2)" type="button"  id="btnSave2" class=" btn btn-primary" >';
        ht+='Guardar y agregar otro';
        ht+='</a>';
        return ht;
    }

    function eliminar(anio,tipoprod) {
        if(confirm('¿Esta seguro de eliminar este registro?')){

            $.post(url_base+'planproduccion/deleteDataG','anio='+anio+'&tipoprod='+tipoprod,function (data) {
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

    $(document).on("change","#selCultivoasds",function () {
        var valSelCultivo=parseFloat($("#selCultivo").val());
        var tmpSelVariedad=_.template($("#tmpSelVariedad").html());
        $.post(url_base+"planproduccion/getVariedadCultivo",{"idcultivo":valSelCultivo},function (data) {
            $("#divSelVariedad").html(tmpSelVariedad({data:data}));
        },'json');
    });

    $(document).on("click","#btnSavePlanProduccion",function () {
        var btn=$(this);
        var form=$("#formPlanProduccion").serialize();
        btn.button("loading");
        $.post(url_base+"planproduccion/setForm",form,function (data) {
            if(data.status == "ok"){
                alert_success("Se realizo correctamente");
                close_modal("modal_id");
                if( $("#isActiveViewDetailPlan").val() == 1){
                    var aniox=$("#anioViewDetail").val();
                    var tipoproduccion=$("#tipoproduccionViewDetail").val();
                    verPlan(aniox,tipoproduccion,0);
                }
                refrescar();
            }else{
                alert_error("Ups :(");
            }
            btn.button("reset");
        },'json');
    });

     function verPlan(anio,idtipoproduccion,modal) {
         $("#isActiveViewDetailPlan").val(1);
         if(modal == 1){
             open_modal("modalVerPlan");
         }
         var tmpDetailPlanProd=_.template($("#tmpDetailPlanProd").html());
         $.post(url_base+"planproduccion/getDataPlanProd",{"anio":anio,"idtipoproduccion":idtipoproduccion},function (data) {
             //console.log(data);
               $("#anioViewDetail").val(anio);
              $("#tipoproduccionViewDetail").val(idtipoproduccion);
             $("#divDataTablePlanProduccion").html(tmpDetailPlanProd({data:data,anio:anio,tipoproduccion:idtipoproduccion}));
         },'json');
     }

    function verPlanProdById(jsonx) {
        $("#formPlanProduccion")[0].reset();
        var dt=jsonx;
         console.log(dt);
        open_modal("modal_id");
        $("#selTipoProduccion").val(jsonx.idtipoproduccion);
        $("#anio").val(jsonx.anio);
        $("#selEstacionExperimental").val(jsonx.estacionexperimental);
        $("#ubicagps").val(jsonx.ubigps);

        $("#departamento").val(jsonx.departamento);
        $("#provincia").val(jsonx.provincia);
        $("#distrito").val(jsonx.distrito);
        $("#anexo").val(jsonx.anexo);
        $("#responsable").val(jsonx.responsable);
        iniSelect();
        $("#selCultivo").val(jsonx.idcultivo);
        $("#cultivar").val(jsonx.cultivar);
        $("#selClase").val(jsonx.idclasecultivo);
        $("#selCategoria").val(jsonx.idcategoriacultivo);
        $("#areasembrar").val(jsonx.areasembrar);
        $("#fsiembra").val(jsonx.fechasiembra);
        $("#fprobablecosecha").val(jsonx.fechaestimadacosecha);
        $("#modalidadconduccion").val(jsonx.modalidadconduccion);
        $("#semillaaemplear").val(jsonx.semillaemplear);
        //$("#rendimientoestimado").val(jsonx.rendimientoestimado);
        $("#prodestimadapesototal").val(jsonx.produccionestimado);
        $("#semillaacondicioandaestimada").val(jsonx.semillaacondicionadaestimada);

        $("#costoproduccionprogramado").val(jsonx.costodeproduccion);
        $("#riego").val(jsonx.riego);
        $("#tiporiego").val(jsonx.tiporiego);
        $("#distanciasurcos").val(jsonx.distanciamientosurcos);
        $("#distanciaplantas").val(jsonx.distanciamientoplantas);
        $("#ro23").val(jsonx.ro23);
        $("#rdr").val(jsonx.rdr);
        $("#presupuestototal").val(jsonx.presupuestototal);
        $("#nrobeneficiarios").val(jsonx.nrobeneficiarios);
        $("#areaatender").val(jsonx.areaatender);
        $("#proyecciondeingresoporventa").val(jsonx.proyeccioningresoventa);
        $("#margenutilidad").val(jsonx.margenutilidad);
        $("#criteriosdeestacionalidad").val(jsonx.criteriosdeestacionalidad);
        $("#idEdit").val(jsonx.idplanproduccion);
        $("#isEdit").val(1);
        calRendiAddUpdNew();

    }

     function deletePlanProdById(id,anio,tipoproduccion) {
        if(!confirm("Esta seguro de eliminar este registro?")){ return;}
         $.post(url_base+"planproduccion/deleteDataPlanProdByID",{"id":id},function (data) {
            if(data.status=="ok"){
                alert_success("Se realizo Correctamente");
            }else{
                alert_error("Ups");
            }
             verPlan(anio,tipoproduccion,0);
         },'json');
     }

     function ejectPlanProdById(jsonx) {
        var tmpDivRegEjecucionPlanProd=_.template($("#tmpDivRegEjecucionPlanProd").html());
        $("#divRegEjecPlan").html(tmpDivRegEjecucionPlanProd(jsonx));
        open_modal("modalRegEjecPlan");
     }

     function btnSaveDoc(file) {

     }

    $(document).on("click","#btnSavePlanProdEjec",function () {
        var btn=$(this);
        var formData = new FormData($("#formProdEjec")[0]);
        if(!confirm("Esta seguro de registrar los elementos ? ")){return 0;}

        //console.log(formData);
        saveProdEjecPlan(1,formData,btn);


    });

     function saveProdEjecPlan(isAsync,formData,btn){
         var formaPost=true;
         if(isAsync == 0){
             formaPost=false;
         }
         btn.button("loading");
         $.ajax({
             url: url_base + "Planproduccion/setDataProdEjec",
             type: 'post',
             data: formData,
             async:formaPost,
             processData: false,
             contentType: false,
             dataType: 'json',
             success: function (data) {                          //alert("echo");
                 console.log(data);
                 var objLength = Object.keys(data).length;
                 if(objLength>0){
                     if(data.status == "ok") {
                         if(isAsync != 0){
                             alert_success("Se realizó correctamente!");
                         }

                         btn.button("reset");
                         if((data.dtreturn).length > 0){
                             var tmpDivRegEjecucionPlanProd=_.template($("#tmpDivRegEjecucionPlanProd").html());
                             $("#divRegEjecPlan").html(tmpDivRegEjecucionPlanProd(data.dtreturn[0]));
                             verPlan(data.dtreturn[0].anio,data.dtreturn[0].tipoproduccion,0);
                         }
                         /*if( $("#isActiveViewDetailPlan").val() == 1){
                             var aniox=$("#anioViewDetail").val();
                             var tipoproduccion=$("#tipoproduccionViewDetail").val();
                             verPlan(aniox,tipoproduccion,0);
                         }*/
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
     }

     $(document).on("click","#btnEnviarProduccioSemilla",function(){
         var sm=parseFloat($("#semillaacondieje").val());
         var cultivoXY=($("#cultivoeje").val()).toLowerCase();
        var pesoSacos=0;
         if(cultivoXY == "arroz"){
             pesoSacos=40;
         }
         if(cultivoXY == "maiz"){
             pesoSacos=20;
         }
         var scos=parseFloat((sm*1000)/pesoSacos);
         var psKg=parseFloat((sm*1000));
         $("#htPesoSaco").html( pesoSacos+" kg");
         $("#semillaacondienviox").val(sm);
         $("#semillaacondienvioxkg").val(psKg);
         $("#sacosenviaox").val(scos.toFixed(2));
         $("#idplanejecEnvioAlmacen").val($("#idplanproduccioneje").val());

         $("#cultivoejeEnvioAlmacen").val($("#cultivoeje").val());
         $("#cultivarejeEnvioAlmacen").val($("#cultivareje").val());
         $("#claseejeEnvioAlmacen").val($("#claseeje").val());
         $("#catcultivoejeEnvioAlmacen").val($("#catcultivoeje").val());
         $("#loteEjeEnvioAlmacen").val($("#loteeje").val());
         open_modal("modalEnviarProduccionInv");

     });

     $(document).on("click","#confirmaEnvioAlmacen",function () {
         var btn=$(this);
         var formData2 = new FormData($("#formProdEjec")[0]);

         saveProdEjecPlan(0,formData2,btn);

        // var fileMedioVeraAlmacenX=$("#fileVerificacionAlmacen");
         var inputFileXY = $('input[name=fileVerificacionAlmacen]');
         inputFileXY = inputFileXY[0].files[0];
         if(inputFileXY == undefined){
             alert_error("Ingrese el medio de verificación");
             return 0;
         }
         if(($("#namelote").val()).trim() =="" ){ alert_error("Registre el lote");return 0;}

         //return;
         btn.button("loading");
         var formData = new FormData($("#formEnvioAlmacen")[0]);
         $.ajax({
             url: url_base + "Planproduccion/setEnvioAlmacen",
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
                         close_modal("modalEnviarProduccionInv");
                     } else {
                         alert_error("Ups error");
                     }
                 }
             },
             error: function (e) {
                 alert_error(e+" Ups error");
             },
             progress:function (e){

             }
         });
     });

</script>
<script type="text/template" id="tmpSelVariedad">
    <select class="form-control" id="selVariedad" name="selVariedad" >
        <% _.each(data,function(i,k){ %>
        <option value="<%= i.idvariedadcultivo %>" > <%=i.nombre %></option>
        <% });%>
    </select>
</script>

<script type="text/template" id="tmpDetailPlanProd">


    <div class="row">
        <h3><%=data[0].tipoproduccionx%></h3>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered" style="font-size: 10px;">
            <thead>
            <tr>
                <th>Acción</th>
                <th class="text-center">#</th>
                <th>Estación Experimenral Agraria</th>
                <th>Departamento </th>
                <th>Provincia </th>
                <th>Distrito</th>
                <th>Anexo</th>
                <th>Ubi. GPS</th>
                <th>Responsable</th>
                <th>Cultivo</th>
                <th>Cultivar</th>
                <th>Clase</th>
                <th>Categoria</th>
                <th>Área a sembrar (ha)</th>
                <th>F. siembra</th>
                <th>F. estimado cosecha</th>
                <th>Modalidad de conduccion </th>
                <th>Semilla /ha a emplear (kg) </th>
                <th>Producción estimada  en peso total (t) </th>
                <th>Rdto. estimado (kg/ha)</th>
                <th>Semilla acondicionada estimada  (t) </th>
                <th>Costo de producción/ha (S/./ha) </th>
                <th>Bajo secano o bajo riego?</th>
                <th>Tipo de riego </th>
                <th>Distanciamiento entre surcos </th>
                <th>Distanciamiento entre plantas </th>
                <th>RO 2.3 </th>
                <th>RDR </th>
                <th>Ppto total requerido (S/.) </th>
                <th>N° de beneficiarios </th>
                <th>Área a atender (has) </th>
                <th>Proyección de ingreso a recaudar por venta (S/.) </th>
                <th>Margen de utilidad (S/.)  </th>
                <th>Criterios de estacionalidad de la venta de semillas </th>

            </tr>
            </thead>
            <tbody>
            <%
            var totalAreaSembrar=0,totalSemillaEmplear=0,totalRendimiento=0,totalProduccionEstimada=0 ,totalSemillaEstimada=0 ,totalCostoProduccion=0  ;
            var totalRo23=0,totalRDR=0,totalPresupuestoTotal=0 ,totalBeneficiarios=0,totalAreaAtender=0,totalProyeccionPorVenta=0,totalMargenUtilidad=0 ;

            _.each(data,function(i,k){
            totalAreaSembrar=totalAreaSembrar+parseFloat(i.areasembrar);
            totalSemillaEmplear=totalSemillaEmplear+parseFloat(i.semillaemplear);
            totalRendimiento=totalRendimiento+parseFloat(i.rendimientoestimado);
            totalProduccionEstimada=totalProduccionEstimada+parseFloat(i.produccionestimado);
            totalSemillaEstimada=totalSemillaEstimada+parseFloat(i.semillaacondicionadaestimada);
            totalCostoProduccion=totalCostoProduccion+parseFloat(i.costodeproduccion);
            totalRo23=totalRo23+parseFloat(i.ro23);
            totalRDR=totalRDR+parseFloat(i.rdr);
            totalPresupuestoTotal=totalPresupuestoTotal+parseFloat(i.presupuestototal);
            totalBeneficiarios=totalBeneficiarios+parseFloat(i.nrobeneficiarios);
            totalAreaAtender=totalAreaAtender+parseFloat(i.areaatender);
            totalProyeccionPorVenta=totalProyeccionPorVenta+parseFloat(i.proyeccioningresoventa);
            totalMargenUtilidad=totalMargenUtilidad+parseFloat(i.margenutilidad);

            %>
            <tr>
                <td style="display: inline-block" >
                    <% var jsx=JSON.stringify(i);
                    %>
                    <a href='javascript:void(0)' onclick='ejectPlanProdById(<%=jsx%>)' style='display: inline-block' title='Ejecutar'   class='btn btn-xs btn-dark'><i class='fa fa-play'></i> Ejecución</a>
                    <a href='javascript:void(0)' onclick='verPlanProdById(<%=jsx%>)' style='display: inline-block' title='Editar'   class='btn btn-xs btn-success'><i class='fa fa-pencil'></i></a>
                    <a href='javascript:void(0)' onclick='deletePlanProdById("<%=i.idplanproduccion%>","<%=i.anio%>","<%=i.tipoproduccion%>")'  style='display: inline-block' title='Eliminar'  class='btn btn-xs btn-danger'><i class='fa fa-trash'></i></a>
                </td>
                <td class="text-center"><%=(k+1)%></td>
                <td><%=i.estacionexperimentalx%></td>
                <td><%=i.departamento%> </td>
                <td><%=i.provincia%></td>
                <td><%=i.distrito%> </td>
                <td><%=i.anexo%> </td>
                <td><%=i.ubigps%> </td>
                <td><%=i.responsable%> </td>
                <td><%=i.cultivo%> </td>
                <td><%=i.cultivar%> </td>
                <td><%=i.clasecultivox%> </td>
                <td> <%=i.categoriacultivox%></td>
                <td> <%=i.areasembrar%></td>
                <td> <%=i.fechasiembra%></td>
                <td> <%=i.fechaestimadacosecha%></td>
                <td><%=i.modalidadconduccion%> </td>
                <td style="text-align: right" ><%=i.semillaemplear%> </td>
                <td style="text-align: right" ><%=i.produccionestimado%> </td>
                <td style="text-align: right" >
                    <%  var pppssstx=parseFloat(i.produccionestimado)||0;
                        var pppArea=parseFloat(i.areasembrar)||0;
                        var pppRendix=0
                        if(pppArea > 0){
                               pppssstx=pppssstx*1000;
                                pppRendix=parseFloat(pppssstx/pppArea)||0;
                         }
                    %>
                    <%=pppRendix%> </td>

                <td style="text-align: right" ><%=i.semillaacondicionadaestimada%> </td>
                <td style="text-align: right" > <%=i.costodeproduccion%></td>
                <td><%=i.riego%> </td>
                <td><%=i.tiporiego%> </td>
                <td><%=i.distanciamientosurcos%> </td>
                <td><%=i.distanciamientoplantas%> </td>
                <td><%=i.ro23%> </td>
                <td> <%=i.rdr%></td>
                <td><%=i.presupuestototal%> </td>
                <td><%=i.nrobeneficiarios%> </td>
                <td> <%=i.areaatender%></td>
                <td> <%=i.proyeccioningresoventa%></td>
                <td><%=i.margenutilidad%> </td>
                <td><%=i.criteriosdeestacionalidad%> </td>

            </tr>
            <%  })%>
            <tr style="font-weight: bold;">
                <td class="text-center"> </td>
                <td>TOTAL</td>
                <td>  </td>
                <td> </td>
                <td>  </td>
                <td> </td>
                <td>  </td>
                <td> </td>
                <td>  </td>
                <td> </td>
                <td>  </td>
                <td> </td>
                <td><%=totalAreaSembrar.toFixed(2)%></td>
                <td> </td>
                <td>  </td>
                <td> </td>
                <td><%=totalSemillaEmplear.toFixed(2)%>  </td>
                <td><%=totalRendimiento.toFixed(2)%>  </td>
                <td> <%=totalProduccionEstimada.toFixed(2)%>  </td>
                <td><%=totalSemillaEstimada.toFixed(3)%>  </td>
                <td> <%=totalCostoProduccion.toFixed(2)%> </td>
                <td> </td>
                <td>  </td>
                <td> </td>
                <td> </td>
                <td><%=totalRo23.toFixed(2)%>  </td>
                <td><%=totalRDR.toFixed(2)%> </td>
                <td><%=totalPresupuestoTotal.toFixed(2)%> </td>
                <td><%=totalBeneficiarios.toFixed(2)%>  </td>
                <td><%=totalAreaAtender.toFixed(2)%> </td>
                <td><%=totalProyeccionPorVenta.toFixed(2)%>  </td>
                <td><%=totalMargenUtilidad.toFixed(2)%> </td>
                <td>  </td>
                <td> </td>


            </tr>
            </tbody>
        </table>
    </div>
</script>

<script type="text/template" id="tmpDivRegEjecucionPlanProd">
    <form id="formProdEjec">
    <div class="row">
        <div class="panel-body col-lg-12 form-horizontal " style="padding-top: 0px;"  >
            <div class="form-group center-block" style="text-align: center;margin-bottom: 0px;" >
                <b style="text-align: center" class="col-sm-5 control-label">Programado - <%=anio%> </b>
                <b style="text-align: center" class="col-sm-4 control-label">Ejecutado</b>
                <label class="col-sm-3 control-label" style="margin-bottom: 0px;margin-top: 0px;padding-top: 0px;"  >
                    <p  class="col-sm-12 control-label" style="text-align: center;font-size: 16px;font-weight: bold;margin-bottom: 0px;margin-top: 0px;padding-top: 0px; "><u>Última actualización:</u>
                        <br><%=fechaacteje%>
                    </p>
                </label>
            </div>
            <div class="form-group" style="margin-bottom: 7px;" >
                <label class="col-sm-2 control-label" for="demo-is-inputsmall">Estación Experimental:</label>
                <div class="col-sm-3">
                    <input readonly="readonly" type="text"  class="form-control input-sm" value="<%=estacionexperimentalx%>"  >
                </div>
                <div class="col-sm-4">
                    <input readonly="readonly" id="estaexpeeje" name="estaexpeeje" type="text"  class="form-control input-sm" value="<% if(estacionexperimentaleje == null){ print(estacionexperimentalx) }else{ print(estacionexperimentaleje) } %>"  >
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 7px;" >
                <label class="col-sm-2 control-label" for="demo-is-inputsmall">Departamento:</label>
                <div class="col-sm-3">
                    <input readonly="readonly" type="text"  class="form-control input-sm" value="<%=departamento%>"  >
                </div>
                <div class="col-sm-4">
                    <input readonly="readonly" id="departamentoeje" name="departamentoeje" type="text"  class="form-control input-sm" value="<% if(departamentoeje == null){ print(departamento) }else{ print(departamentoeje) }  %>"  >
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 7px;" >
                <label class="col-sm-2 control-label" for="demo-is-inputsmall">Provincia:</label>
                <div class="col-sm-3">
                    <input readonly="readonly" type="text"  class="form-control input-sm" value="<%=provincia%>"  >
                </div>
                <div class="col-sm-4">
                    <input readonly="readonly" id="provinciaeje" name="provinciaeje" type="text"  class="form-control input-sm" value="<% if(provinciaeje == null){ print(provincia) }else{ print(provinciaeje) }  %>"  >
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 7px;" >
                <label class="col-sm-2 control-label" for="demo-is-inputsmall">Distrito:</label>
                <div class="col-sm-3">
                    <input readonly="readonly" type="text"  class="form-control input-sm" value="<%=distrito%>"  >
                </div>
                <div class="col-sm-4">
                    <input readonly="readonly" id="distritoeje" name="distritoeje" type="text"  class="form-control input-sm" value="<% if(distritoeje == null){ print(distrito) }else{ print(distritoeje) }   %>"  >
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 7px;" >
                <label class="col-sm-2 control-label" for="demo-is-inputsmall">Anexo:</label>
                <div class="col-sm-3">
                    <input readonly="readonly" type="text"  class="form-control input-sm" value="<%=anexo%>"  >
                </div>
                <div class="col-sm-4">
                    <input   id="anexoeje" name="anexoeje" type="text"  class="form-control input-sm" value="<% if(anexoeje == null){ print(anexo) }else{ print(anexoeje) }   %>"  >

                </div>
            </div>
            <div class="form-group" style="margin-bottom: 7px;" >
                <label class="col-sm-2 control-label" for="demo-is-inputsmall">Responsable:</label>
                <div class="col-sm-3">
                    <input readonly="readonly" type="text"  class="form-control input-sm" value="<%=responsable%>"  >
                </div>
                <div class="col-sm-4">
                    <input   id="responsableeje" name="responsableeje" type="text"  class="form-control input-sm" value="<% if(responsableeje == null){ print(responsable) }else{ print(responsableeje) } %>"  >
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 7px;" >
                <label class="col-sm-2 control-label" for="demo-is-inputsmall">Cultivo:</label>
                <div class="col-sm-3">
                    <input readonly="readonly" type="text"  class="form-control input-sm" value="<%=cultivo%>"  >
                </div>
                <div class="col-sm-4">
                    <input readonly="readonly" id="cultivoeje" name="cultivoeje" type="text"  class="form-control input-sm" value="<% if(cultivoeje == null){ print(cultivo) }else{ print(cultivoeje) } %>"  >
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 7px;" >
                <label class="col-sm-2 control-label" for="demo-is-inputsmall">Cultivar:</label>
                <div class="col-sm-3">
                    <input readonly="readonly" type="text"  class="form-control input-sm" value="<%=cultivar%>"  >
                </div>
                <div class="col-sm-4">
                    <input   id="cultivareje" name="cultivareje" type="text"  class="form-control input-sm" value="<% if(cultivareje == null){ print(cultivar) }else{ print(cultivareje) } %>"  >
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 7px;" >
                <label class="col-sm-2 control-label" for="demo-is-inputsmall">Clase:</label>
                <div class="col-sm-3">
                    <input readonly="readonly" type="text"  class="form-control input-sm" value="<%=clasecultivox%>"  >
                </div>
                <div class="col-sm-4">
                    <input id="claseeje" name="claseeje"  type="text"  class="form-control input-sm" value="<% if(clasecultivoeje == null){ print(clasecultivox) }else{ print(clasecultivoeje) } %>"  >
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 7px;" >
                <label class="col-sm-2 control-label" for="demo-is-inputsmall">Categoria:</label>
                <div class="col-sm-3">
                    <input readonly="readonly" type="text"  class="form-control input-sm" value="<%=categoriacultivox%>"  >
                </div>
                <div class="col-sm-4">
                    <input id="catcultivoeje" name="catcultivoeje"  type="text"  class="form-control input-sm" value="<% if(categoriacultivoeje == null){ print(categoriacultivox) }else{ print(categoriacultivoeje) } %>"  >
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 7px;" >
                <label class="col-sm-2 control-label" for="demo-is-inputsmall">Lote:</label>
                <div class="col-sm-3">

                </div>
                <div class="col-sm-4">
                    <input style="text-align: right;"  id="loteeje" name="loteeje" type="text"  class="form-control input-sm" value="<%=loteeje%>"  >
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 7px;" >
                <label class="col-sm-2 control-label" for="demo-is-inputsmall">Area sembrar(ha):</label>
                <div class="col-sm-3">
                    <input readonly="readonly" type="text"  class="form-control input-sm" value="<%=areasembrar%>"  >
                </div>
                <div class="col-sm-4">
                    <input style="text-align: right;"  id="areasembrareje" name="areasembrareje" type="number"  class="form-control input-sm" value="<% if(areasembrareje == null){ print(areasembrar) }else{ print(areasembrareje) } %>"  >
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 7px;">
                <label class="col-sm-2 control-label" for="demo-is-inputsmall">Semilla/ha a emplear (kg)(kg):</label>
                <div class="col-sm-3">
                    <input style="line-height: 10px;" readonly="readonly" type="text"  class="form-control input-sm" value="<%=semillaemplear%>"  >
                </div>
                <div class="col-sm-4">
                    <input style="text-align: right;"  id="semillahaeje" name="semillahaeje" type="number"  class="form-control input-sm" value="<%=semillahaeje%>"  >
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 7px;">
                <label class="col-sm-2 control-label" for="demo-is-inputsmall">F. Siembra:</label>
                <div class="col-sm-3">
                    <input  style="line-height: 10px;" readonly="readonly" type="date"  class="form-control input-sm" value="<%=fechasiembra%>"  >
                </div>
                <div class="col-sm-4">
                    <input  style="line-height: 9px;line-height: 9px;padding-right: 0px;padding-left: 0.5px;text-align: right;"  type="date" id="fsiembraeje" name="fsiembraeje"  class="form-control input-sm" value="<%=fechasiembraeje%>"  >
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 7px;" >
                <label class="col-sm-5 control-label" for="demo-is-inputsmall">Registro Inscripción Organismo Certificador:</label>
                    <div class="col-sm-7" >
                        <% if(urlregistroorgacertificador !=null){ %>
                        <a   class="btn btn-default btn-sm"  href="<?=base_url()?>assets/uploads/archivos/<%=urlregistroorgacertificador%>" target="_blank" style="font-weight: bold;display: inline-block;" >Ver Documento<br> (<%=urlregistroorgacertificador%>) </a>
                        <% }%>
                        <input  style="padding-left: 0px;width: 50%;display: inline-block;" id="fileSiembra" name="fileSiembra" type="file"     class="input-sm form-control" value=""  >
                    </div>
                    <!--<div class="col-sm-4" style="padding-left: 0px; "  >
                        <input  style="padding-left: 0px; " id="fileSiembra" name="fileSiembra" type="file"     class="input-sm" value=""  >
                    </div>-->

            </div>
            <div class="form-group" style="margin-bottom: 7px;" >
                <label class="col-sm-2 control-label" for="demo-is-inputsmall">F. cosecha:</label>
                <div class="col-sm-3">
                    <input style="line-height: 10px;" readonly="readonly" type="date"  class="form-control input-sm" value="<%=fechaestimadacosecha%>"  >
                </div>
                <div class="col-sm-3">
                    <input style="line-height: 9px;line-height: 9px;padding-right: 0px;padding-left: 0.5px;text-align: right;"   type="date" id="fcosechaeje" name="fcosechaeje"   class="form-control input-sm" value="<%=fechacosechaeje%>"  >
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 7px;" >
                <label class="col-sm-5 control-label" for="demo-is-inputsmall">Acta de cosecha:</label>
                <div class="col-sm-7">
                    <% if(urlactacoseha !=null){ %>
                    <a  class="btn btn-default btn-sm" href="<?=base_url()?>assets/uploads/archivos/<%=urlactacoseha%>"  target="_blank" style="font-weight: bold;display: inline-block;"  >Ver Documento<br>(<%=urlactacoseha%>) </a>
                    <% }%>
                    <input style="padding-left: 0px;display: inline-block;"   id="fileCosecha" name="fileCosecha" type="file"  class="input-sm" value=""  >
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 7px;border:1px solid gainsboro " >

                <div class="form-group" style="margin-bottom: 7px;" >
                    <label class="col-sm-5 control-label" for="demo-is-inputsmall" style="font-size: 15px; font-weight: bold" ><u>Procesamiento :</u></label>
                    <label class="col-sm-7 control-label" for="demo-is-inputsmall">_</label>
                </div>
                <div class="form-group" style="margin-bottom: 7px;" >
                    <label class="col-sm-5 control-label" for="demo-is-inputsmall">Informe procesamiento :</label>

                    <div class="col-sm-7">
                        <% if(urlinfoprocesamiento !=null){ %>
                        <a  class="btn btn-default btn-sm" href="<?=base_url()?>assets/uploads/archivos/<%=urlinfoprocesamiento%>"  target="_blank" style="font-weight: bold;display: inline-block;"  >Ver Documento<br>(<%=urlinfoprocesamiento%>)</a>
                        <% }%>
                        <input style="padding-left: 0px;display: inline-block; "  id="fileProcesamiento" name="fileProcesamiento"    type="file"  class="input-sm" value=""  >

                    </div>


                </div>
                <div class="form-group" style="margin-bottom: 7px;" >
                    <label class="col-sm-5 control-label" for="demo-is-inputsmall">Total Ingreso a Planta (kg):</label>
                    <div class="col-sm-3">
                        <input style="text-align: right;"  id="ingresoplantapro" name="ingresoplantaproce" type="number"  class="form-control input-sm" value="<%=totalingresoplantaeje%>"  >
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 7px;" >
                    <label class="col-sm-5 control-label" for="demo-is-inputsmall">Peso Seco (kg):</label>
                    <div class="col-sm-3">
                        <input style="text-align: right;"  id="pesosecoprod" name="pesosecoproce" type="number"  class="form-control input-sm" value="<%=pesosecoproceeje%>"  >
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 7px;" >
                    <label class="col-sm-5 control-label" for="demo-is-inputsmall">Impurezas (kg):</label>
                    <div class="col-sm-3">
                        <input style="text-align: right;"  id="impurezasproce" name="impurezasproce" type="number"  class="form-control input-sm" value="<%=impurezaproceeje%>"  >
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 7px;" >
                    <label class="col-sm-5 control-label" for="demo-is-inputsmall">Descarte (kg):</label>
                    <div class="col-sm-3">
                        <input style="text-align: right;"  id="descarteproce" name="descarteproce" type="number"  class="form-control input-sm" value="<%=descarteproceeje%>"  >
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 7px;" >
                    <label class="col-sm-5 control-label" for="demo-is-inputsmall">Total Semilla Certificada Obtenida (kg):</label>
                    <div class="col-sm-3">
                        <%
                            var v1x=parseFloat(pesosecoproceeje)||0;
                            var v2x=parseFloat(impurezaproceeje)||0;
                           var v3x=parseFloat(descarteproceeje)||0;
                          var rrxs=v1x-v2x-v3x ;
                        %>
                        <input style="text-align: right;"  id="totalsemillaobtenida" name="totalsemillaobtenida" type="number"  readonly="readonly" class="form-control input-sm" value="<%=rrxs%>"  >
                    </div>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 7px;border:1px solid gainsboro " >

                <div class="form-group" style="margin-bottom: 7px;" >
                    <label class="col-sm-5 control-label" for="demo-is-inputsmall" style="font-size: 15px; font-weight: bold"><u>Analisis de calidad :</u></label>
                    <label class="col-sm-7 control-label" for="demo-is-inputsmall">_</label>
                </div>
                <div class="form-group" style="margin-bottom: 7px;" >
                    <label class="col-sm-5 control-label" for="demo-is-inputsmall">Informe de analisis de calidad :</label>

                    <div class="col-sm-7">
                        <% if(urlinfoanalisiscalidadeje !=null){ %>
                        <a  class="btn btn-default btn-sm" href="<?=base_url()?>assets/uploads/archivos/<%=urlinfoanalisiscalidadeje%>"  target="_blank" style="font-weight: bold;display: inline-block;"  >Ver Documento<br>(<%=urlinfoanalisiscalidadeje%>) </a>
                        <% }%>
                        <input style="padding-left: 0px;display: inline-block; "  id="fileAnalisisCalidad" name="fileAnalisisCalidad"    type="file"  class="input-sm" value=""  >

                    </div>


                </div>
                <div class="form-group" style="margin-bottom: 7px;" >
                    <label class="col-sm-5 control-label" for="demo-is-inputsmall">Fecha de análisis:</label>
                    <div class="col-sm-3">
                        <input style="text-align: right;"  id="fechaanalisiscalidad" name="fechaanalisiscalidad" type="date"  class="form-control input-sm" value="<%=fechaanalisiscalidadeje%>"  >
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 7px;" >
                    <label class="col-sm-5 control-label" for="demo-is-inputsmall">Pureza (%):</label>
                    <div class="col-sm-3">
                        <input style="text-align: right;"  id="purezaeje" name="purezaeje" type="number"  class="form-control input-sm" value="<%=purezaanalisiseje%>"  >
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 7px;" >
                    <label class="col-sm-5 control-label" for="demo-is-inputsmall">Material Inerte (%):</label>
                    <div class="col-sm-3">
                        <input style="text-align: right;"  id="materialInerte" name="materialInerte" type="number"  class="form-control input-sm" value="<%=materialinerteeje%>"  >
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 7px;" >
                    <label class="col-sm-5 control-label" for="demo-is-inputsmall">Germinación (%):</label>
                    <div class="col-sm-3">
                        <input style="text-align: right;"  id="germinacioneje" name="germinacioneje" type="number"  class="form-control input-sm" value="<%=germinacionanalisiseje%>"  >
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 7px;" >
                    <label class="col-sm-5 control-label" for="demo-is-inputsmall">Humedad (%):</label>
                    <div class="col-sm-3">
                        <input style="text-align: right;"  id="humedadcalidadeje" name="humedadcalidadeje" type="number"  class="form-control input-sm" value="<%=humedadeje%>"  >
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 7px;" >
                    <label class="col-sm-5 control-label" for="demo-is-inputsmall">Semilla de otras variedades (kg):</label>
                    <div class="col-sm-3">
                        <input style="text-align: right;"  id="semillaotravariedadeje" name="semillaotravariedadeje" type="number"  class="form-control input-sm" value="<%=semillaotravariedadeje%>"  >
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 7px;" >
                    <label class="col-sm-5 control-label" for="demo-is-inputsmall">Semilla de malezas (kg):</label>
                    <div class="col-sm-3">
                        <input style="text-align: right;"  id="semillamalezaeje" name="semillamalezaeje" type="number"  class="form-control input-sm" value="<%=semillamalezaeje%>"  >
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 7px;" >
                    <label class="col-sm-5 control-label" for="demo-is-inputsmall">Descinfectante :</label>
                    <div class="col-sm-3">
                        <input style="text-align: right;"  id="descinfectatntecalidadeje" name="descinfectatntecalidadeje" type="text"     class="form-control input-sm" value="<%=descinfectanteeje%>"  >
                    </div>
                </div>
            </div>
<br><br>



            <div class="form-group" style="margin-bottom: 7px;" >
                <label class="col-sm-3 control-label" for="demo-is-inputsmall">Producción Peso Total(t):</label>
                <div class="col-sm-2">
                    <input style="line-height: 10px;" readonly="readonly" type="text"  class="form-control input-sm text-right"  value="<%=produccionestimado%>"  >
                </div>
                <div class="col-sm-2">
                    <%
                            var pttsemilla=parseFloat(totalingresoplantaeje)||0;
                            var pttsemilla2=parseFloat(totalingresoplantaeje)||0;
                            if(pttsemilla > 0){
                                pttsemilla=totalingresoplantaeje/1000;
                            }
                    %>
                    <input style="text-align: right;" id="producciontotaleje" name="producciontotaleje" type="number" readonly="readonly"  class="form-control input-sm" value="<%=pttsemilla%>"  >
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 7px;" >
                <label class="col-sm-3 control-label" for="demo-is-inputsmall">Semilla acondicionada(t):</label>
                <div class="col-sm-2">
                    <input style="line-height: 10px;" readonly="readonly" type="text"  class="form-control input-sm text-right" value="<%=semillaacondicionadaestimada%>"  >
                </div>
                <div class="col-sm-2">
                    <%  var semillaacondi=0;
                      if(rrxs > 0 ){semillaacondi =  rrxs/1000; }
                    %>
                    <input  style="text-align: right;" id="semillaacondieje" name="semillaacondieje" type="number"  class="form-control input-sm" readonly="readonly" value="<%=semillaacondi%>"  >
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 7px;" >
                <label class="col-sm-3 control-label" for="demo-is-inputsmall">Rendimiento (kg/ha):</label>
                <div class="col-sm-2">
                    <%
                    var rendi1Xxx=0;
                    var ppptotalpro=parseFloat(produccionestimado)||0;
                    var arrexx=parseFloat(areasembrar)||0;
                    if(ppptotalpro > 0 &&  arrexx > 0 ){
                    ppptotalpro=ppptotalpro*1000;
                    rendi1Xxx=parseFloat(ppptotalpro/arrexx);
                    rendi1Xxx=rendi1Xxx.toFixed(2);
                    }

                    %>
                    <input style="line-height: 10px;" readonly="readonly" type="text"  class="form-control input-sm text-right"  value="<%=rendi1Xxx%>"  >
                </div>
                <div class="col-sm-2">
                    <%
                    var rendi1X=0;
                    var arre=parseFloat(areasembrareje)||0;
                    if(pttsemilla2 > 0 &&  arre > 0 ){

                      rendi1X=parseFloat(pttsemilla2/arre);
                    rendi1X=rendi1X.toFixed(2);
                    }

                    %>
                    <input style="text-align: right;" id="rendimientoeje" name="rendimientoeje" type="number"  class="form-control input-sm text-right" readonly="readonly" value="<%=rendi1X%>"  >
                </div>
            </div>
            <% if(isconcluido == 1 ){  %>
            <div class="form-group "  style="margin-bottom: 10px;" >
                <label class="col-sm-5 control-label" for="demo-is-inputsmall" style="font-size: 15px; font-weight: bold">Producción enviada a almacen</label>

            </div>
            <div class="form-group "  style="margin-bottom: 10px;" >
                <label class="col-sm-5 control-label" for="demo-is-inputsmall">Fecha de envio:</label>
                <div class="col-sm-3">
                    <input type="date" readonly="readonly" class="form-control" style="line-height: 10px" value="<%=fechaenvioalmacen%>" >
                </div>
            </div>
            <div class="form-group "  style="margin-bottom: 10px;" >
                <label class="col-sm-5 control-label" for="demo-is-inputsmall">Acta  de envio:</label>
                <div class="col-sm-6">
                    <a  target="_blank"  class="btn btn-default " href="<?=base_url()?>assets/uploads/archivos/<%=urlmedioverificacionenvioalmacen%>"> Ver documento<br> (<%=urlmedioverificacionenvioalmacen%>)</a>
                </div>
            </div>
            <% }else { %>
            <div class="form-group "  style="margin-bottom: 10px;" >
                <label class="col-sm-5 control-label" for="demo-is-inputsmall"> </label>
                <div class="col-sm-6">
                    <button type="button" class="btn btn-dark btn-sm" id="btnEnviarProduccioSemilla">
                        <b>Enviar producción a almacen</b>
                    </button>
                </div>
            </div>
            <% } %>
        </div>
    </div>

    <div class="panel-footer text-center">
        <% if(isconcluido != 1 ){%>
        <input type="hidden" id="idplanproduccioneje" name="idplanproduccioneje" value="<%=idplanproduccion%>">
        <button class="btn btn-success" id="btnSavePlanProdEjec" type="button">Guardar</button>
        <% } %>

    </div>
    </form>
</script>

<script type="text/javascript">

    $(document).on("change","#pesosecoprod,#impurezasproce,#descarteproce",function () {
        calSemillaProcesada();
    });
    $(document).on("keyup","#pesosecoprod,#impurezasproce,#descarteproce",function () {
        calSemillaProcesada();
    });

    function calSemillaProcesada() {
        var ingresoplantapro=parseFloat($("#ingresoplantapro").val())||0 ;
        var pesosecoprod=  parseFloat($("#pesosecoprod").val())||0 ;
        var impurezasproce= parseFloat($("#impurezasproce").val())||0 ;
        var descarteproce=parseFloat($("#descarteproce").val())||0;
        var res=pesosecoprod-impurezasproce-descarteproce;
        $("#totalsemillaobtenida").val(res);
        $("#semillaacondieje").val(res);
        $("#producciontotaleje").val(ingresoplantapro);

        calcRendi();

    }

    function calcRendi() {
        var rendi=0;
        var rendiCert=0;
        var ingresoplantapro=parseFloat($("#ingresoplantapro").val())||0 ;
        var totalsemillaobtenida=parseFloat($("#totalsemillaobtenida").val())||0 ;
        var areasembrareje=parseFloat($("#areasembrareje").val())||0;
        rendi=parseFloat(ingresoplantapro/areasembrareje)||0;
        rendiCert=parseFloat(totalsemillaobtenida/areasembrareje)||0;
        $("#rendimientoeje").val(rendiCert);
        //console.log(rendi,rendiCert);
    }

</script>