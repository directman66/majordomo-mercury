<?
print '	  			<div class="blank-right">
				
					<div class="p-left p-sh"><b>Объявления:</b></div></p><br>';


//print '<div class="blank-right"> ';


//print '<div class="p-left p-sh"><b>Показание счетчика:</b></div></p><br></div>';


$sql="SELECT * FROM mercury_news order by ID desc limit 10";
//    $res=SQLSelect("SELECT * FROM mercury_news order by ID desc limit 10");
//    $res=SQLSelect("SELECT ID FROM zigbee2mqtt_devices WHERE LINKED_OBJECT='' AND LINKED_PROPERTY=''");
//    $total = count($res);
//    for ($i=0;$i<$total;$i++) {


$cmd_rec = $db->query($sql);
$stroka="";

$cmd_rec->data_seek(0);
while ($row = $cmd_rec->fetch_assoc()) {

echo '<div class="p-left"><b>'.$row["TITLE"].'</b></div>';
echo '<div class="p-nright">'.$row["data"];

if ($userdata['PREDSED']=='1') {


echo '&nbsp;&nbsp;<a href="/modules/mercury/deletenews.php?id='.$row['ID'].'" title="Удалить объявление">x</a> ';



}

echo '</div><br>';

echo '<div class="p-left">'.$row["message"].'</div><br><br>';





    }
print '	  								<div style="clear:both"></div> ';
