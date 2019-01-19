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

        <link rel="stylesheet" href="/3rdparty/bootstrap/css/bootstrap.min.css" type="text/css">
        <link rel="stylesheet" href="/css/admin.css" type="text/css">
        <link rel="stylesheet" type="text/css" href="/css/umbra-css2/style.css" title="theme"/>
        <link rel="stylesheet" href="/css/jquery.autocomplete.css" type="text/css">
        <link rel="stylesheet" href="/css/jquery.betterTooltip.css" type="text/css">
        <link href="/css/devices.css" rel="stylesheet" type="text/css"/>

    <script type="text/javascript" src="/3rdparty/jquery/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="/3rdparty/jquery/jquery-migrate-3.0.0.min.js"></script>

    <script language="javascript" src="/js/scripts.js?v=1"></script>
    
    <script type="text/javascript" src="/3rdparty/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/jquery.cookie.js"></script>
    <script type="text/javascript" src="/js/jquery.autocomplete.js"></script>
    <script type="text/javascript" src="/js/jquery.bgiframe.js"></script>
    <script type="text/javascript" src="/js/jquery.betterTooltip.js"></script>
    <script type="text/javascript" type="text/javascript" src="/js/jWindow.js"></script>

    <link rel="stylesheet" type="text/css" href="/3rdparty/fancybox/jquery.fancybox.min.css?v=3.3.5" media="screen" />
    <script type="text/javascript" src="/3rdparty/fancybox/jquery.fancybox.min.js?v=3.3.5"></script>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
<script type="text/javascript" src="/js/jquery.tiny-pubsub.js"></script>



<!-- #######  YAY, I AM THE SOURCE EDITOR! #########-->
<p>&nbsp;</p>
<table style="margin-left: auto; margin-right: auto; width: 668px; height: 29px;" border="1" cellspacing="20" cellpadding="20">
<tbody>
<tr>
<td style="width: 658px;">
<p style="text-align: right;">Система мониторинга инженерных сетей частного дома</p>
<p style="text-align: right;">ТСН &laquo;Морской Берег&raquo;</p>

<p style="text-align: centr;">Личный кабинет: Пользователь - '.$userdata['FIO']." ".  $userdata['STREET'].'</p>
Логин - '.$userdata['LOGIN'].'

<hr />
<p style="text-align: right;">&nbsp;</p>
<table style="height: 25px; width: 653px;" cellspacing="5" cellpadding="5">
<tbody>
<tr style="height: 277px;">
<td style="width: 118px; height: 277px;">&nbsp;
<table style="height: 404px;" border="2" width="110">
<tbody>
<tr>


<td style="width: 98.75px;">

  <a href="?view_mode=indata_edit&id=[#ID#]&tab=config" class="btn btn-default" title="Edit"><i class="glyphicon glyphicon-pencil">&nbsp;Счетчики &nbsp;энгергии&nbsp;</i></a>
<br>
<br>
<a href="?view_mode=indata_edit&id=[#ID#]&tab=config" class="btn btn-default" title="Edit"><i class="glyphicon glyphicon-pencil">&nbsp;Добавить новое обор.</i></a>
</td>
</tr>
</tbody>
</table>
</td>
<td style="width: 521px; height: 277px;">
<table style="width: 522px; height: 208px;" border="2" cellspacing="5" cellpadding="5">
<tbody>
<tr>
<td style="width: 512px;">
<p>Счетчик электроэнергии: Меркурий 234 РО</p>
<p>Серийный номер: 31568758</p>
<p>Дата установки: 07.10.2017</p>
</td>
</tr>
<tr>
<td style="width: 512px;">
<p>Показание счетчика: 44444 кВт</p>
<p>&nbsp;</p>
<p>Напряжение и ток на фазе А: <strong>220 В / 3 А</strong></p>
<p>Напряжение и ток на фазе B: <strong>220 В / 3 А</strong></p>
<p>Напряжение и ток на фазе C: <strong>220 В / 3 А</strong></p>
<p>&nbsp;</p>
<p>Общая потребляемая мощность: <strong>2500 Вт</strong></p>
<p>&nbsp;Последний опрос счетчика: <strong>21.12.2018 15:00</strong></p>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<p style="text-align: right;">&nbsp;</p>
<p style="text-align: right;">&nbsp;</p>
<p style="text-align: right;">&nbsp;</p>
<p style="text-align: right;">&nbsp;</p>
<p style="text-align: right;">&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</td>
</tr>
</tbody>
</table>
<p style="text-align: right;">&nbsp;</p>
<p style="text-align: right;">&nbsp;</p>
<p>&nbsp;</p>
';










        print "<h1>Доброе время суток! Пользователь:".$userdata['FIO']."(". $userdata['STREET'].")</h1><br><br>";

        print "По данным системы мониторинга, потребление вашего счетчика составляет  ".$userdata['PvT'] .' Вт, напряжение на фазах '.$userdata['U']." В, измеренная сила тока ".$userdata['IaT'].' A.<br><br>';
        print "Регистры счетчика: Тариф 1: ".$userdata['Total1'].", Тариф 2: ".$userdata['Total2'] .'<br>';
$obsh=$userdata['Total1']+$userdata['Total2'];
        print "Общее значение счетчика накопленной энергии: ".$obsh .'<br><br>';
        print "Расходы за день: ".$userdata['DAY_WATT']." Вт /  ".$userdata['DAY_RUB'] .' руб.<br>';
        print "Расходы за неделю: ".$userdata['WEEK_WATT']." Вт /  ".$userdata['WEEK_RUB'] .'руб.<br>';
        print "Расходы за месяц: ".$userdata['MONTH_WATT']." Вт /  ".$userdata['MONTH_RUB'] .'руб.<br>';
        print "Расходы за год: ".$userdata['YEAR_WATT']." Вт /  ".$userdata['YEAR_RUB'] .'руб.<br>';

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

?>

<div id="chart1" style="height: 300px"></div>
<script src="https://code.highcharts.com/highcharts.js"></script>


<script type="text/javascript" name="1">
Highcharts.chart('chart1', {

  chart: {
    borderWidth: 0,
    plotBorderWidth: 1,
    spacingTop: 10
    ,width: 350 // 16:9 ratio

  },

title: {
            text: 'Сила тока:'
        },



  xAxis: {
    categories: [
<? 
$sql="SELECT left(ADDED,10) dt, round(AVG(phistory.value),2) value FROM objects, pvalues,phistory where objects.ID=pvalues.OBJECT_ID and pvalues.PROPERTY_NAME='Mercury_".$userdata['ID'].".IaT' and phistory.VALUE_ID=pvalues.ID group by left(ADDED,10)";
$cmd_rec = SQLSelect($sql);
$stroka="";
foreach ($cmd_rec as $cmd_r)
{$stroka.= '"'.$cmd_r['dt'].'",';
}
$stroka=preg_replace("/(.)$/", "", $stroka);
echo $stroka;

?>


]
  },

  series: [{
    data: [
<? 
$sql="SELECT left(ADDED,10) dt, round(AVG(phistory.value),2) value FROM objects, pvalues,phistory where objects.ID=pvalues.OBJECT_ID and pvalues.PROPERTY_NAME='Mercury_".$userdata['ID'].".IaT' and phistory.VALUE_ID=pvalues.ID group by left(ADDED,10)";
$cmd_rec = SQLSelect($sql);
$stroka="";
foreach ($cmd_rec as $cmd_r)
{$stroka.= $cmd_r['value'].",";
}
$stroka=preg_replace("/(.)$/", "", $stroka);
echo $stroka;

?>
]
  }]

});
</script>


<script src="https://code.highcharts.com/highcharts.js" name="123"></script>
<div id="chart2" style="height: 300px"></div>

		<script type="text/javascript" name="123">
Highcharts.chart('chart2', {

  chart: {
    borderWidth: 0,
    plotBorderWidth: 0,
    spacingTop: 10
    ,width: 350 // 16:9 ratio
  },
title: {
            text: 'Напряжение:'
        },

  xAxis: {
    categories: [
<? 
$sql="SELECT left(ADDED,10) dt, round(AVG(phistory.value),2) value FROM objects, pvalues,phistory where objects.ID=pvalues.OBJECT_ID and pvalues.PROPERTY_NAME='Mercury_".$userdata['ID'].".U' and phistory.VALUE_ID=pvalues.ID group by left(ADDED,10)";
$cmd_rec = SQLSelect($sql);
$stroka="";
foreach ($cmd_rec as $cmd_r)
{$stroka.= '"'.$cmd_r['dt'].'",';
}
$stroka=preg_replace("/(.)$/", "", $stroka);
echo $stroka;

?>


]
  },

  series: [{
    data: [
<? 
$sql="SELECT left(ADDED,10) dt, round(AVG(phistory.value),2) value FROM objects, pvalues,phistory where objects.ID=pvalues.OBJECT_ID and pvalues.PROPERTY_NAME='Mercury_".$userdata['ID'].".U' and phistory.VALUE_ID=pvalues.ID group by left(ADDED,10)";
$cmd_rec = SQLSelect($sql);
$stroka="";
foreach ($cmd_rec as $cmd_r)
{$stroka.= $cmd_r['value'].",";
}
$stroka=preg_replace("/(.)$/", "", $stroka);
echo $stroka;

?>
]
  }]

});
		</script>


<script src="https://code.highcharts.com/highcharts.js" name="123"></script>
<div id="chart3" style="height: 300px"></div>

		<script type="text/javascript" name="123">
Highcharts.chart('chart3', {

  chart: {
    borderWidth: 0,
    plotBorderWidth: 0,
    spacingTop: 10
    ,width: 350

  },
title: {
            text: 'Потребляемая мощность:'
        },

  xAxis: {
    categories: [
<? 
$sql="SELECT left(ADDED,10) dt, round(AVG(phistory.value),2) value FROM objects, pvalues,phistory where objects.ID=pvalues.OBJECT_ID and pvalues.PROPERTY_NAME='Mercury_".$userdata['ID'].".PvT' and phistory.VALUE_ID=pvalues.ID group by left(ADDED,10)";
$cmd_rec = SQLSelect($sql);
$stroka="";
foreach ($cmd_rec as $cmd_r)
{$stroka.= '"'.$cmd_r['dt'].'",';
}
$stroka=preg_replace("/(.)$/", "", $stroka);
echo $stroka;

?>


]
  },

  series: [{
    data: [
<? 
$sql="SELECT left(ADDED,10) dt, round(AVG(phistory.value),2) value FROM objects, pvalues,phistory where objects.ID=pvalues.OBJECT_ID and pvalues.PROPERTY_NAME='Mercury_".$userdata['ID'].".PvT' and phistory.VALUE_ID=pvalues.ID group by left(ADDED,10)";
$cmd_rec = SQLSelect($sql);
$stroka="";
foreach ($cmd_rec as $cmd_r)
{$stroka.= $cmd_r['value'].",";
}
$stroka=preg_replace("/(.)$/", "", $stroka);
echo $stroka;

?>
]
  }]

});
		</script>



