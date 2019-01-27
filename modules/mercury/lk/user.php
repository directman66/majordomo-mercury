<?
   print '

<!doctype html>
<html lang="ru">
	<head>
		<meta charset="utf-8" />
		<title>Личный кабинет</title>	
		<link href="css\style.css" rel="stylesheet" type="text/css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

	</head>
	<body>
		<div class="list">
			<div class="header">
				<div class="title text-g">Система мониторинга инженерных сетей частного дома  ТСН «Морской Берег»
				</div>
				<div class="in-header">
					<div class="in-title">
							Личный кабинет
					</div>
					<div class="menu">
						<ul>
							<li><a href="#"><div class="ico img-ico-1"></div><span>Счетчик электроинергии</span></a><div class="line"></div></li>
							<li><a href="#"><div class="ico img-ico-2"></div><span>Счетчик электроинергии</span></a><div class="line"></div></li>
							<li><a href="#"><div class="ico img-ico-3"></div><span>Счетчик электроинергии</span></a><div class="line"></div></li>
							<li><a href="#"><div class="ico img-ico-4"></div><span>Счетчик электроинергии</span></a><div class="line"></div></li>
						</ul>
						<div class="add"><a href="#"><div class="ico img-ico-add"></div><span>Добавить модуль</span></a></div>
					</div>
					<div style="clear:both"></div>
					
				</div>
				<div class="ld">
					Пользователь: <span>

'.$userdata['FIO']."(". $userdata['STREET'].')
</span><br>
					Логин: <span>'.$userdata['LOGIN'].'
</span>				
				</div>
				<div class="sh">
					Счетчик электроэнергии: <span>Меркурий '.$userdata['MODEL'].'</span><br>
					Серийный номер: <span>'.$userdata['SN'].'</span><br>
					Дата производства счетчика: <span>'.$userdata['MADETD'].'</span><br>
				</div>
				<div style="clear:both"></div>
			</div> 
			<div class="blank">
				<div class="blank-left">
					<div class="graf">


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
    spacingTop: 10,
    width: 1240 // 16:9 ratio

  },

title: {
            text: "График расхода электроэнергии по месяцам:"
        },



  xAxis: {
    categories: [
';

$sql="SELECT left(ADDED,10) dt, round(AVG(phistory.value),2) value FROM objects, pvalues,phistory where objects.ID=pvalues.OBJECT_ID and pvalues.PROPERTY_NAME='Mercury_".$userdata['ID'].".IaT' and phistory.VALUE_ID=pvalues.ID group by left(ADDED,10)";
$cmd_rec = SQLSelect($sql);
$stroka="";
foreach ($cmd_rec as $cmd_r)
{$stroka.= '"'.$cmd_r['dt'].'",';
}
$stroka=preg_replace("/(.)$/", "", $stroka);
echo $stroka;

print ']  },  series: [{    data: [ ';


$sql="SELECT left(ADDED,10) dt, round(AVG(phistory.value),2) value FROM objects, pvalues,phistory where objects.ID=pvalues.OBJECT_ID and pvalues.PROPERTY_NAME='Mercury_".$userdata['ID'].".IaT' and phistory.VALUE_ID=pvalues.ID group by left(ADDED,10)";
$cmd_rec = SQLSelect($sql);
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




print '

					</div>
					<div class="graf">
						типа график2
					</div>
				</div>';


/////////////////////////////////////
/////////////////////////////////////
/////////////////////////////////////
/////////////////////////////////////
/////////////////////////////////////


$obsh=$userdata['Total1']+$userdata['Total2'];
print '	  			<div class="blank-right">
				
					<div class="p-left p-sh"><b>Показание счетчика:</b></div><div class="p-right">'.$obsh.' кВт/ч</div></p><br>
    				 	<div class="p-left">Тариф 1:</div><div class="p-right">'.$userdata['Total1'].'</div><br>
    				 	<div class="p-left">Тариф 2:</div><div class="p-right">'.$userdata['Total2'].'</div><br>
<br>
   
					<div class="p-left">Напряжение и ток на фазе А:</div><div class="p-right">'.$userdata['Uv1'].' В / '.$userdata['Ia1'].' А</div><br>
					<div class="p-left">Напряжение и ток на фазе B:</div><div class="p-right">'.$userdata['Uv2'].' В / '.$userdata['Ia2'].' А</div><br>
					<div class="p-left">Напряжение и ток на фазе C:</div><div class="p-right">'.$userdata['Uv3'].' В / '.$userdata['Ia3'].' А</div><br>
					<div class="p-left">Общая потребляемая мощность:</div><div class="p-right">'.$userdata['PvT'].' Вт/ч</div><br>
 					<div class="p-left">Последний опрос счетчика:</div><div class="p-right">'.date('d-m-Y H:i:s',$userdata['TS']).'</div>
					<div style="clear:both"></div>
					</div>
';



	print '	<br><br>';

print '	  			<div class="blank-right">
				
					<div class="p-left p-sh"><b>Объявления:</b></div></p><br>';


//print '<div class="blank-right"> ';


//print '<div class="p-left p-sh"><b>Показание счетчика:</b></div></p><br></div>';


    $res=SQLSelect("SELECT * FROM mercury_news order by ID desc limit 10");
//    $res=SQLSelect("SELECT ID FROM zigbee2mqtt_devices WHERE LINKED_OBJECT='' AND LINKED_PROPERTY=''");
    $total = count($res);
    for ($i=0;$i<$total;$i++) {

echo '<div class="p-left"><b>'.$res[$i]["TITLE"].'</b></div>';
echo '<div class="p-right">'.$res[$i]["data"].'</div><br>';

echo '<div class="p-left">'.$res[$i]["message"].'</div><br><br>';




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

