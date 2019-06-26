<?
error_reporting(0);
//date_default_timezone_set('Asia/Yekaterinburg');
date_default_timezone_set('Asia/Novosibirsk');



print '<div class="container-fluid">
<div class="row">
<div class="col-xl-8">';

$sql="SELECT * FROM mercury_devices ";
//$pred=SQLSelect($sql);



//$userdata=SQLSelectOne($sql);

//	$query= mysqli_query($db,$sql);
//    	$rec = mysqli_fetch_assoc($query);

//$rec= mysqli_query($db, $sql, MYSQLI_USE_RESULT); 

$rec = $db->query($sql);

echo '
<div class="container-fluid" style="overflow:auto;">
<table class="table">';
echo '<tr class="thead-dark"><th>№</th><th>ФИО</th><th>Адрес</th><th>Реле</th><th>Обновлено'.'</th><th>Показания кВт</th><th>U1/U2/U3 В</th><th>Ia1/Ia2/Ia3 А</th><th>'.'PvT Вт'.'</th><tr>';
$sump=0;
$sumi=0;
$sumu=0;
//foreach ($pred as $rec) 

//	$total = count($rec); mysqli_num_rows($result)
//	$total =  mysqli_num_rows($rec);
	$total =  $rec->num_rows;

//	echo 'total:'.$total;

$i=1;
$rec->data_seek(0);
while ($row = $rec->fetch_assoc()) {
//    echo " id = " . $row['FIO'] . "\n";
//}

$round=2;
//    for ($i=0;$i<$total;$i++) {
if ($row['Total1']<>"") $obsh=$row['Total1']+$row['Total2'];
if ($row['PvT']<>"") $sump=$sump+$row['PvT'];
if ($row['IaT']<>"") $sumi=$sumi+$row['IaT'];
if ($row['TS']<>"") $ts=date('m/d/Y H:i:s',$row['TS']);		

if ($row['ONLINE']==1)
$online='<span class="label label-success" >Online</span> ';
else
$online='<span class="label label-warning">Offline</span> ';

//{
if ($row['IaT']=="") $row['IaT']="0";
if ($row['PvT']=="") $row['PvT']="0";
if ($row['U']=="") $row['U']="0";
if ($obsh=="") $obsh="0";
//$obsh=$userdata['Total1']+$userdata['Total2'];

if ($row['STATE']=="1") {$state='вкл';}
if ($row['STATE']=="0") {$state='выкл';}
if ($row['STATE']=="") {$state='д.отс';}


//echo "<tr><td>".$row['FIO']."</td><td>".$row['STREET']."</td><td>".$state."</td><td>".$ts."</td><td>".round($row['Ia1'],2)."</td><td>".round($row['Ia2'],2)."</td><td>".round($row['Ia3'],2)."</td><td>".round($row['PvT'],2)."</td><tr>";
echo "<tr>
<td>".$i."</td>
<td>".$row['FIO']."</td><td>".$row['STREET']."</td><td>".$state."</td><td>".$ts."</td>
<td>".round($obsh,2)."</td>
<td>".round($row['Uv1'],$round)."<br>".round($row['Uv2'],$round)."<br>".round($row['Uv3'],$round)."</td>
<td>".round($row['Ia1'],$round)."<br>".round($row['Ia2'],$round)."<br>".round($row['Ia3'],$round)."</td>
<td>".round($row['PvT'],$round)."</td><tr>";
$i=$i+1;
}
//echo "<tr><td>Итого</td><td></td><td></td><td></td><td></td><td>".$sumi."</td><td>".$sump."</td><td></td></tr>";
echo "</table></div>";




print ' 								<div class="graf">


';





/////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////
/////////////////////////////////////


print '

<div id="chart1" style="height: 300px"></div>


<script src="highcharts\highcharts.js"></script>


<script type="text/javascript" name="1">
window.onload = function() {
	Highcharts.chart("chart1", {

  chart: {
    borderWidth: 0,
    plotBorderWidth: 1,
    spacingTop: 10 
,  type: \'column\'
';
//print '    ,width: 850 // 16:9 ratio ';
//print '    ,width: 850 // 16:9 ratio ';
print '
  },

title: {
            text: "График расхода электроэнергии:"
        },



  xAxis: {
    categories: [
';

$sql="SELECT left(ADDED,10) dt, round(AVG(phistory.value),2) value FROM objects, pvalues,phistory where objects.ID=pvalues.OBJECT_ID and pvalues.PROPERTY_NAME='Mercury_".$userdata['ID'].".IaT' and phistory.VALUE_ID=pvalues.ID group by left(ADDED,10)";
//$cmd_rec = SQLSelect($sql);
$cmd_rec = $db->query($sql);
$stroka="";
//foreach ($cmd_rec as $cmd_r)
//{$stroka.= '"'.$cmd_r['dt'].'",';}

$cmd_rec->data_seek(0);
while ($row = $cmd_rec->fetch_assoc()) {
//    echo " id = " . $row['FIO'] . "\n";
$stroka.= '"'.$row['dt'].'",';
}

$stroka=preg_replace("/(.)$/", "", $stroka);
echo $stroka;


print ']  },  series: [{   
  name : "Расход электроэнергии, кВт",
 data: [ ';




$sql="SELECT left(ADDED,10) dt, round(AVG(phistory.value),2) value FROM objects, pvalues,phistory where objects.ID=pvalues.OBJECT_ID and pvalues.PROPERTY_NAME='Mercury_".$userdata['ID'].".IaT' and phistory.VALUE_ID=pvalues.ID group by left(ADDED,10)";
///$cmd_rec = SQLSelect($sql);

$cmd_rec = $db->query($sql);


$stroka="";
foreach ($cmd_rec as $cmd_r)
{$stroka.= $cmd_r['value'].",";
}
$stroka=preg_replace("/(.)$/", "", $stroka);
echo $stroka;

print ']  }]});}</script> </div>';


print '	</div> ';


print '
<div class="col-xl-4">';

require(DIR_MODULES.'mercury/lk/righttbl.php');
require(DIR_MODULES.'mercury/lk/msgbar.php');



/////////////////////////
/////////////////////////
/////////////////////////
/////////////////////////
/////////////////////////



print '
	<div class="text-dark">
    <form action="/modules/mercury/addnews.php" method="post" enctype="multipart/form-data" name="frmEdit" class="form-horizontal">
	<fieldset>
	<h6 class="card-title mr-auto">Добавить объявление</h6>
	<hr>
	Тема
	<input type="text" class="form-control" name="tema">
	Текст
	<textarea class="form-control" rows="7" cols="55" name="message"> </textarea>
	<br>
    <button type="submit" name="subm" value="Опубликовать" class="form-control btn btn-defaul btn-primary">Опубликовать</button>	
	<input type="hidden" name="addnews" value="addnews"> ';

print '
	</fieldset>
	</form>
	</div></div></div>';

print '	</body></html>'; 


/////////////////////////
/////////////////////////
/////////////////////////
/////////////////////////
/////////////////////////

