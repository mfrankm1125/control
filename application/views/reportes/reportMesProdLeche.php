<?php 
$mes=["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
 ?>

<?php foreach ($data as $key => $value) { ?>
	 <h2><?=$mes[$value["mes"]]?>-<?=$anio?> </h2>

	 <table border="1">	
	 	<tr>
			<td>#</td>
			<td>Día</td>
			<td>Prod Mañana</td>
			<td>Prod Tarde</td>
			<td>Total</td>
		</tr>
	 <?php 
	 	$total=0;
	 	foreach($value["dataMes"] as $k => $v ){
		$total=$total+$v["total"];		
	 ?>
		<tr>
			<td><?=($k+1)?></td>
			<td><?=$v["fechaprodleche"]?></td>
			<td><?=$v["totalmaniana"]?></td>
			<td><?=$v["totaltarde"]?></td>
			<td><?=$v["total"]?></td>
		</tr>

	 <?php }?>
		 <tr>
			<td colspan="4">Total</td>			 
			<td><?=$total?></td>
		</tr>	
	 </table>		

<?php }  ?>