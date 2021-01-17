<?php 
$mes=["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
 ?>
<h1><?=$anio?></h1>
<table border="1">	
	<tr>
		<td>#</td>
		<td>Mes</td>
		<td>Prod Mañana</td>
		<td>Prod Tarde</td>
		<td>Total</td>
	</tr>

	<?php 
		$total=0;
		foreach($data as $key=>$value){ 
		$total=$total+$value["total"];		
	?>
		<tr>
			<td><?=($key+1)?></td>
			<td><?=$mes[$value["mes"]]?></td>
			<td><?=$value["totalmaniana"]?></td>
			<td><?=$value["totaltarde"]?></td>
			<td><?=$value["total"]?></td>
		</tr>
	<?php }?>
		<tr>
			<td colspan="4">Total</td>			 
			<td><?=$total?></td>
		</tr>	

</table>