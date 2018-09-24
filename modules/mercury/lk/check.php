<?
// Скрипт проверки


 chdir('../../');
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
        print "Доброе время суток! Пользователь:".$userdata['FIO']."(". $userdata['STREET'].")<br><br>";

        print "По данным системы мониторинга, потребление вашего счетчика составляет  ".$userdata['PvT'] .' Вт, напряжение на фазах '.$userdata['U']." В, измеренная сила тока ".$userdata['IaT'].' A.<br><br>';
        print "Регистры счетчика: Тариф 1: ".$userdata['Total1'].", Тариф 2: ".$userdata['Total2'] .'<br><br>';
        print "Расходы за день: ".$userdata['DAY_WATT']." Вт /  ".$userdata['DAY_RUB'] .' руб.<br>';
        print "Расходы за неделю: ".$userdata['WEEK_WATT']." Вт /  ".$userdata['WEEK_RUB'] .'руб.<br>';
        print "Расходы за месяц: ".$userdata['MONTH_WATT']." Вт /  ".$userdata['MONTH_RUB'] .'руб.<br>';
        print "Расходы за год: ".$userdata['YEAR_WATT']." Вт /  ".$userdata['YEAR_RUB'] .'руб.<br>';




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
    borderWidth: 1,
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



