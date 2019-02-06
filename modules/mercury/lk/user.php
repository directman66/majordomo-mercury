<?
error_reporting(0);
//date_default_timezone_set('Asia/Yekaterinburg');
date_default_timezone_set('Asia/Novosibirsk');





/////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////
/////////////////////////////////////

print '

			<div class="blank">
				<div class="blank-left">
					<div class="graf">
';



print '

<div id="chart1" style="height: 300px"></div>
 ';

$sql="SELECT left(ADDED,10) dt, round(AVG(phistory.value),2) value FROM objects, pvalues,phistory where objects.ID=pvalues.OBJECT_ID and pvalues.PROPERTY_NAME='Mercury_".$userdata['ID'].".IaT' and phistory.VALUE_ID=pvalues.ID group by left(ADDED,10)";
//$cmd_rec = SQLSelect($sql);
//echo $sql;


print '

<script src="https://code.highcharts.com/highcharts.js"></script>


<script type="text/javascript" name="1">
Highcharts.chart("chart1", {

  chart: {
    borderWidth: 0,
    plotBorderWidth: 1,
    spacingTop: 10 ';
//print '    ,width: 1240 // 16:9 ratio ';
//print '    ,width: 1240 // 16:9 ratio ';

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


//print '	<div class="graf">типа график2</div> ';

print '</div>';


/////////////////////////////////////
/////////////////////////////////////
/////////////////////////////////////
/////////////////////////////////////
/////////////////////////////////////


$obsh=$userdata['Total1']+$userdata['Total2'];


if ($userdata['Total1']=="") $userdata['Total1']="0";
if ($userdata['Total2']=="") $userdata['Total2']="0";

if ($userdata['Uv1']=="") $userdata['Uv1']="0";
if ($userdata['Uv2']=="") $userdata['Uv2']="0";
if ($userdata['Uv3']=="") $userdata['Uv3']="0";

if ($userdata['PvT']=="") $userdata['PvT']="0";



print '	  			<div class="blank-right">';

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

echo '<div class="p-left"><b>'.$row["TITLE"].'</b></div>';
echo '<div class="p-nright">'.$row["data"].'</div><br>';

echo '<div class="p-left">'.$row["message"].'</div><br><br>';




    }
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

