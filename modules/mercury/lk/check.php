<?
// Скрипт проверки


chdir(dirname(__FILE__) . '/../../..');
 include_once("./config.php");
 include_once("./lib/loader.php");
 include_once(DIR_MODULES."application.class.php");
 $db = new mysql(DB_HOST, '', DB_USER, DB_PASSWORD, DB_NAME); //connecting to database
 include_once("./load_settings.php");

//---------------------------- MySQL

//$db=new mysql(DB_HOST, '', DB_USER, DB_PASSWORD, DB_NAME);

//$link=mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
//$link=new mysql(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
//$db=new mysql(DB_HOST, '', DB_USER, DB_PASSWORD, DB_NAME);
//echo "________________";

if (isset($_COOKIE['login']) and isset($_COOKIE['hash']))
{
$sql="SELECT * FROM mercury_devices WHERE LOGIN = '".$_COOKIE['login']."'";
$userdata=SQLSelectOne($sql);
//    $query = mysqli_query($link, "SELECT * FROM mercury_devices WHERE LOGIN = '".$_COOKIE['login']."' LIMIT 1");
//    $userdata = mysqli_fetch_assoc($query);
//echo $userdata['USERHASH'].'<br>';
//echo $userdata['LOGIN'].'<br>';
//echo $userdata['USERIP'].'<br>';


    if(($userdata['USERHASH']!== $_COOKIE['hash']) or ($userdata['LOGIN'] !== $_COOKIE['login'])
 or (($userdata['USERIP'] !== $_SERVER['REMOTE_ADDR'])  and ($userdata['USERIP'] !== "0")))
    {
        setcookie("id", "", time() - 3600*24*30*12, "/");
        setcookie("hash", "", time() - 3600*24*30*12, "/");
        print "Хм, что-то не получилось";
    }
    else
    {

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
    spacingTop: 10
    ,width: 1024 // 16:9 ratio

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
print '
				<div class="blank-right">
				
					<div class="p-left p-sh">Показание счетчика:</div><div class="p-right">'.$obsh.' кВт/ч</div></p><br>
    				 	<div class="p-left">Тариф 1:</div><div class="p-right">'.$userdata['Total1'].'</div><br>
    				 	<div class="p-left">Тариф 2:</div><div class="p-right">'.$userdata['Total2'].'</div><br>
<br>
   
					<div class="p-left">Напряжение и ток на фазе А:</div><div class="p-right">'.$userdata['Uv1'].' В / '.$userdata['Ia1'].' А</div><br>
					<div class="p-left">Напряжение и ток на фазе B:</div><div class="p-right">'.$userdata['Uv2'].' В / '.$userdata['Ia2'].' А</div><br>
					<div class="p-left">Напряжение и ток на фазе C:</div><div class="p-right">'.$userdata['Uv3'].' В / '.$userdata['Ia3'].' А</div><br>
					<div class="p-left">Общая потребляемая мощность:</div><div class="p-right">'.$userdata['PvT'].' Вт/ч</div><br>
					<div class="p-left">Последний опрос счетчика:</div><div class="p-right">'.date('d-m-Y H:i:s',$userdata['TS']).'</div><br>
					<div style="clear:both"></div>

				</div>
				<div style="clear:both"></div>
			</div>
		</div>
	</body>
</html>';


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


if ($userdata['PREDSED']=='1') {
echo "<br><br><h1>Профиль председателя.</h1><br>";

$sql="SELECT * FROM mercury_devices ";
$pred=SQLSelect($sql);
echo '<table width="100%" cellspacing="0" cellpadding="4" border="1">';
echo "<tr><td>".'ФИО'."</td><td>".'Адрес'."</td><td>".'Сост.'."</td><td>".'ONLINE'."</td><td>Обновлено</td><td>".'IaT'."</td><td>".'PvT'."</td><td>".'U'."</td><td>Показания</td><tr>";
$sump=0;
$sumi=0;
$sumu=0;
foreach ($pred as $rec) 
{
if ($rec['Total1']<>"") $obsh=$rec['Total1']+$rec['Total2'];
if ($rec['PvT']<>"") $sump=$sump+$rec['PvT'];
if ($rec['IaT']<>"") $sumi=$sumi+$rec['IaT'];
if ($rec['TS']<>"") $ts=date('m/d/Y H:i:s',$rec['TS']);		

if ($rec['ONLINE']==1)
$online='<span class="label label-success" >Online</span> ';
else
$online='<span class="label label-warning">Offline</span> ';


echo "<tr><td>".$rec['FIO']."</td><td>".$rec['STREET']."</td><td>".$rec['STATE']."</td><td>".$online."</td><td>".$ts."</td><td>".$rec['IaT']."</td><td>".$rec['PvT']."</td><td>".$rec['U']."</td><td>".$obsh."</td><tr>";
}
echo "<tr><td>Итого</td><td></td><td></td><td></td><td></td><td>".$sumi."</td><td>".$sump."</td><td></td><td></td></tr>";
echo "</table>";



}





    }

}
else
{
    print "Включите куки";
}





