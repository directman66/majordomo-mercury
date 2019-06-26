<?
print '
	  			<div class="card border-dark mb-3"> 
					<div class="card-header">Объявления</div>
						<div class="card-body text-dark">';

$sql="SELECT * FROM mercury_news order by ID desc limit 10";
//    $res=SQLSelect("SELECT * FROM mercury_news order by ID desc limit 10");
//    $res=SQLSelect("SELECT ID FROM zigbee2mqtt_devices WHERE LINKED_OBJECT='' AND LINKED_PROPERTY=''");
//    $total = count($res);
//    for ($i=0;$i<$total;$i++) {


$cmd_rec = $db->query($sql);
$stroka="";

$cmd_rec->data_seek(0);
while ($row = $cmd_rec->fetch_assoc()) {
	echo '<div class="row">
			<h6 class="card-title mr-auto">'.$row["TITLE"].'</h6>';
	echo '<p class="card-title">'.$row["data"].'</p>';

	
	
	if ($userdata['PREDSED']=='1') {
		echo ' <a href="/modules/mercury/deletenews.php?id='.$row['ID'].'" title="Удалить объявление">
		<button type="button" class="close" aria-label="Удалить объявление" style="margin-top: -3px;">
		  <span aria-hidden="true">&times;</span>
		</button></a>'
		;		
	}
	echo '</div><hr>';
	echo $row["message"].'<br><br>';
}

