<?
error_reporting(0);
//date_default_timezone_set('Asia/Yekaterinburg');
date_default_timezone_set('Asia/Novosibirsk');


//print '	<div style="clear:both"></div> ';

//print '			</div>  ';

print '			<div class="blank">
				<div class="blank-left"> ';

print ' 			<div class="graf"> ';
//				типа график2

$sql="SELECT * FROM mercury_devices ";
//$pred=SQLSelect($sql);



//$userdata=SQLSelectOne($sql);

//	$query= mysqli_query($db,$sql);
//    	$rec = mysqli_fetch_assoc($query);

//$rec= mysqli_query($db, $sql, MYSQLI_USE_RESULT); 

$rec = $db->query($sql);

//print_r($rec);
//echo '<table width="100%" cellspacing="0" cellpadding="4" border="1" style="color: #00a648;" >';
echo '<table width="100%" cellspacing="0" cellpadding="4" border="1"  >';
echo "<tr><td>№</td><td>".'ФИО'."</td><td>".'Адрес'."</td><td>".'Реле.'."</td><td>".'Обновлено'."</td><td>Показания кВт</td><td>U1/U2/U3 В</td><td>Ia1/Ia2/Ia3 А</td><td>".'PvT Вт'."</td><tr>";
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
echo "</table>";




print '	   			</div> ';


print ' 								<div class="graf">


';





/////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////
/////////////////////////////////////


print '

<div id="chart1" style="height: 300px"></div>


<script src="https://code.highcharts.com/highcharts.js"></script>


<script type="text/javascript" name="1">
Highcharts.chart("chart1", {

  chart: {
    borderWidth: 0,
    plotBorderWidth: 1,
    spacingTop: 10 ';
//print '    ,width: 850 // 16:9 ratio ';
//print '    ,width: 850 // 16:9 ratio ';
print '
  },

title: {
            text: "График расхода электроэнергии по месяцам:"
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

print ']  },  series: [{    data: [ ';


$sql="SELECT left(ADDED,10) dt, round(AVG(phistory.value),2) value FROM objects, pvalues,phistory where objects.ID=pvalues.OBJECT_ID and pvalues.PROPERTY_NAME='Mercury_".$userdata['ID'].".IaT' and phistory.VALUE_ID=pvalues.ID group by left(ADDED,10)";
///$cmd_rec = SQLSelect($sql);

$cmd_rec = $db->query($sql);


$stroka="";
foreach ($cmd_rec as $cmd_r)
{$stroka.= $cmd_r['value'].",";
}
$stroka=preg_replace("/(.)$/", "", $stroka);
echo $stroka;

print ']  }]});</script> ';


/////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////
/////////////////////////////////////


print '	</div> ';


print ' 			<div class="graf">график</div>	</div>';


/////////////////////////////////////
/////////////////////////////////////
/////////////////////////////////////
/////////////////////////////////////
/////////////////////////////////////


$obsh=$userdata['Total1']+$userdata['Total2'];
print '	  			<div class="blank-right">';


if ($userdata['Total1']=="") $userdata['Total1']="0";
if ($userdata['Total2']=="") $userdata['Total2']="0";

if ($userdata['Uv1']=="") $userdata['Uv1']="0";
if ($userdata['Uv2']=="") $userdata['Uv2']="0";
if ($userdata['Uv3']=="") $userdata['Uv3']="0";

if ($userdata['PvT']=="") $userdata['PvT']="0";






print '				
					<div class="p-left p-sh"><b>Мгновенные значения:</b></div></p><br>				
					<div class="p-left"><b>Показание счетчика:</b></div><div class="p-right">'.round($obsh,2).' кВт</div></p><br>

    				 	<div class="p-left">Тариф 1:</div><div class="p-right">'.round($userdata['Total1'],2).'</div><br>
    				 	<div class="p-left">Тариф 2:</div><div class="p-right">'.round($userdata['Total2'],2).'</div><br>
<br>
   
					<div class="p-left">Напряжение и ток на фазе А:</div><div class="p-right">'.round($userdata['Uv1'],2).' В / '.round($userdata['Ia1'],2).' А</div><br>
					<div class="p-left">Напряжение и ток на фазе B:</div><div class="p-right">'.round($userdata['Uv2'],2).' В / '.round($userdata['Ia2'],2).' А</div><br>
					<div class="p-left">Напряжение и ток на фазе C:</div><div class="p-right">'.round($userdata['Uv3'],2).' В / '.round($userdata['Ia3'],2).' А</div><br>
					<div class="p-left">Общая потребляемая мощность:</div><div class="p-right">'.round($userdata['PvT'],2).' Вт</div><br>
 					<div class="p-left">Последний опрос счетчика:</div><div class="p-right">'.date('d-m-Y H:i:s',$userdata['TS']).'</div>
					<div style="clear:both"></div>
					</div>
';



	print '	<br><br>';

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


echo '<br><div class="p-left"><b>'.$row["TITLE"].'</b></div>';
echo '<div class="p-nright"><small>'.$row["data"].'</small>';
echo '&nbsp;&nbsp;<a href="/modules/mercury/deletenews.php?id='.$row['ID'].'" title="Удалить объявление">x</a> ';
//echo '<a href="#"  onclick="" title="Удалить объявление">x</a> ';
echo '</div><br>';

echo '<div class="p-left">'.$row["message"].'<br><br></div>';
    }

print '<br>

     <form action="/modules/mercury/addnews.php" method="post" enctype="multipart/form-data" name="frmEdit" class="form-horizontal">


  <div class="p-nright">
     <label ><b>Добавить новое объявление:</b></label>
     <div class="col-md-6 input-group">

     <label class="control-label">Тема  объявления</label>
     <input type="text"  class="form-control"   style="width:95%"  name="tema" ><br>

     <label >Текст объявления     </label> ';

//print '     <input type="text"  class="form-control"     style="height:150px; width:95%" name="message"> ';

print '<textarea rows="7" cols="55" name="message"> </textarea> ';

print '     <div class="input-group-btn">
     </div> </div>


';

print ' <button type="submit" name="subm" value="<#LANG_SUBMIT#>" class="btn btn-defaul btn-primary">Опубликовать</button> ';




//print '<input type="hidden" name="message" value="123"> ';
//print '<input type="hidden" name="tema" value="111"> 	   ';
print '<input type="hidden" name="addnews" value="addnews"> ';

print '</fieldset>    </form>';

print '
</div> ';


print '	  								<div style="clear:both"></div> ';

//print '	<div style="clear:both"></div> ';
print '	</body></html>'; 


/////////////////////////
/////////////////////////
/////////////////////////
/////////////////////////
/////////////////////////

/////////////////////////
/////////////////////////
/////////////////////////
/////////////////////////
/////////////////////////
