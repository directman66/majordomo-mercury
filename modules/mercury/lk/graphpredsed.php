<?

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

