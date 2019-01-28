<?
error_reporting(0);
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

'.$userdata['FIO'].'(председатель) ';
print '
</span><br>
					Логин: <span>'.$userdata['LOGIN'].'&nbsp;&nbsp;<a href="index.php" title="Выход">Выход</a> 
</span>				
				</div>
				<div class="sh">
					Счетчик электроэнергии: <span>Меркурий '.$userdata['MODEL'].'</span><br>
					Серийный номер: <span>'.$userdata['SN'].'</span><br> ';
//if ($userdata['MADETD']) {
print ' 					Дата производства счетчика: <span>'.$userdata['MADETD'].'</span><br> ';
//}
print '				</div>
				<div style="clear:both"></div>
			</div> 
			<div class="blank">
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
echo "<tr><td>".'ФИО'."</td><td>".'Адрес'."</td><td>".'Сост.'."</td><td>".'ONLINE'."</td><td>Обновлено</td><td>".'IaT'."</td><td>".'PvT'."</td><td>".'U'."</td><td>Показания</td><tr>";
$sump=0;
$sumi=0;
$sumu=0;
//foreach ($pred as $rec) 

//	$total = count($rec); mysqli_num_rows($result)
//	$total =  mysqli_num_rows($rec);
	$total =  $rec->num_rows;

//	echo 'total:'.$total;


$rec->data_seek(0);
while ($row = $rec->fetch_assoc()) {
//    echo " id = " . $row['FIO'] . "\n";
//}


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
echo "<tr><td>".$row['FIO']."</td><td>".$row['STREET']."</td><td>".$row['STATE']."</td><td>".$online."</td><td>".$ts."</td><td>".$row['IaT']."</td><td>".$row['PvT']."</td><td>".$row['U']."</td><td>".$obsh."</td><tr>";
}
echo "<tr><td>Итого</td><td></td><td></td><td></td><td></td><td>".$sumi."</td><td>".$sump."</td><td></td><td></td></tr>";
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


print ' 			<div class="graf">
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

$sql="SELECT * FROM mercury_news order by ID desc limit 10";
//    $res=SQLSelect("SELECT * FROM mercury_news order by ID desc limit 10");
//    $res=SQLSelect("SELECT ID FROM zigbee2mqtt_devices WHERE LINKED_OBJECT='' AND LINKED_PROPERTY=''");
//    $total = count($res);
//    for ($i=0;$i<$total;$i++) {


$cmd_rec = $db->query($sql);
$stroka="";

$cmd_rec->data_seek(0);
while ($row = $cmd_rec->fetch_assoc()) {


echo '<div class="p-left"><b>'.$row["TITLE"].'</b>
</div>';
echo '<div class="p-nright">'.$row["data"];
echo '&nbsp;&nbsp;<a href="/modules/mercury/deletenews.php?id='.$row['ID'].'" title="Удалить объявление">x</a> ';
//echo '<a href="#"  onclick="" title="Удалить объявление">x</a> ';
echo '</div><br>';

echo '<div class="p-left">'.$row["message"].'</div><br><br>';
    }

print '

     <form action="/modules/mercury/addnews.php" method="post" enctype="multipart/form-data" name="frmEdit" class="form-horizontal">

<!--     <fieldset> -->
     <label ><b>Добавить новое объявление</b></label>
     <div class="col-md-6 input-group">

     <label class="control-label">Тема  объявления</label>
     <input type="text"  class="form-control"   style="width:95%"  name="tema" ><br>

     <label >Текст объявления     </label>
     <input type="text"  class="form-control"     style="height:150px; width:95%" name="message">
     <div class="input-group-btn">
     </div> </div>';

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
