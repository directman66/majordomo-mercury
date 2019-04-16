<?php
/**
* mercury 
* @package project
* @author Wizard <sergejey@gmail.com>
* @copyright http://majordomo.smartliving.ru/ (c)
* @version 0.1 (wizard, 10:01:31 [Jan 03, 2018])
*/
//
//
//ini_set('max_execution_time', '600');
ini_set ('display_errors', 'off');
class mercury extends module {
/**
*
* Module class constructor
*
* @access private
*/
function mercury() {
  $this->name="mercury";
  $this->title="Cчетчики Меркурий";
  $this->module_category="<#LANG_SECTION_DEVICES#>";
  $this->checkInstalled();
}
/**
* saveParams
*
* Saving module parameters
*
* @access public
*/
function saveParams($data=0) {
 $p=array();
 if (IsSet($this->id)) {
  $p["id"]=$this->id;
 }
 if (IsSet($this->view_mode)) {
  $p["view_mode"]=$this->view_mode;
 }
 if (IsSet($this->edit_mode)) {
  $p["edit_mode"]=$this->edit_mode;
 }
 if (IsSet($this->tab)) {
  $p["tab"]=$this->tab;
 }
 return parent::saveParams($p);
}
/**
* getParams
*
* Getting module parameters from query string
*
* @access public
*/
function getParams() {
  global $id;
  global $mode;
  global $view_mode;
  global $edit_mode;
  global $tab;
//	 global $title;
//	 global $port;
//	 global $hexadr;
//	 global $ipaddr;
//	 global $model;
//	 global $fio;
//	 global $phone;
//	 global $street;


  if (isset($id)) {
   $this->id=$id;
  }
  if (isset($mode)) {
   $this->mode=$mode;
  }
  if (isset($view_mode)) {
   $this->view_mode=$view_mode;
  }
  if (isset($edit_mode)) {
   $this->edit_mode=$edit_mode;
  }
  if (isset($tab)) {
   $this->tab=$tab;
  }
  $this->checkSettings();
}
/**
* Run
*
* Description
*
* @access public
*/
function run() {
 global $session;
  $out=array();
  if ($this->action=='admin') {
   $this->admin($out);
  } else {
   $this->usual($out);
  }
  if (IsSet($this->owner->action)) {
   $out['PARENT_ACTION']=$this->owner->action;
  }
  if (IsSet($this->owner->name)) {
   $out['PARENT_NAME']=$this->owner->name;
  }
  $out['VIEW_MODE']=$this->view_mode;
  $out['EDIT_MODE']=$this->edit_mode;
  $out['MODE']=$this->mode;
  $out['ACTION']=$this->action;
  $out['TAB']=$this->tab;
  $this->data=$out;

if (gg('cycle_mercuryRun')) {
        if ((time() - gg('cycle_mercuryRun')) < 360*2 ) {
			$out['CYCLERUN'] = 1;
		} else {
			$out['CYCLERUN'] = 0;
		}
}

  $p=new parser(DIR_TEMPLATES.$this->name."/".$this->name.".html", $this->data, $this);
  $this->result=$p->result;
}
/**
* BackEnd
*
* Module backend
*
* @access public
*/
function admin(&$out) {

if (gg('cycle_mercuryRun')) {
        if ((time() - gg('cycle_mercuryRun')) < 360*2 ) {
			$out['CYCLERUN'] = 1;
		} else {
			$out['CYCLERUN'] = 0;
		}
}
	


//$cmd_rec = SQLSelectOne("SELECT VALUE FROM mercury_config where parametr='DEBUG'");
$cachedVoiceDir = ROOT . 'cms/cached/';
$file = $cachedVoiceDir . 'mercurydebug.txt';

//$out['MSG_DEBUG']=file_get_contents($file);

$cmd_rec = SQLSelectOne("SELECT VALUE FROM mercury_config where parametr='CURRENT'");
$out['CURRENT']=$cmd_rec['VALUE'];
$currentid=$cmd_rec['VALUE'];

$cmd_rec = SQLSelectOne("SELECT * FROM mercury_devices where FIO='$currentid'");


$out['MODEL']=$cmd_rec['MODEL'];		


$out['FIO']=$cmd_rec['FIO'];		


 $out['TS2']=date('m/d/Y H:i:s',$cmd_rec['TS']);		
 $out['COUNTTS']=date('m/d/Y H:i:s',$cmd_rec['TS']);		

// $out['P']=$cmd_rec['Pv1']+$cmd_rec['Pv2']+$cmd_rec['Pv3'];		
 $out['P']=$cmd_rec['PvT'];		
 $out['P1']=$cmd_rec['Pv1'];		
 $out['P2']=$cmd_rec['Pv2'];		
 $out['P3']=$cmd_rec['Pv3'];		


 $out['I']=$cmd_rec['IaT'];		
 $out['I1']=$cmd_rec['Ia1'];		
 $out['I2']=$cmd_rec['Ia2'];		
 $out['I3']=$cmd_rec['Ia3'];		


 $out['U1']=$cmd_rec['Uv1'];		
 $out['U2']=$cmd_rec['Uv2'];		
 $out['U3']=$cmd_rec['Uv3'];		
 $out['U']=$cmd_rec['U'];		

$objectname='Mercury_'.$cmd_rec['ID'];		
$out['OBJECTNAME']=$objectname;

if (gg($objectname.'.FIO')!=$cmd_rec['FIO'])  sg($objectname.'.FIO',$cmd_rec['FIO']);		



 $out['S0']=$cmd_rec['Total'];		
 $out['S1']=$cmd_rec['Total1'];		
 $out['S2']=$cmd_rec['Total2'];	
	


$now=time();

$out['MONTH_WATT']=round(getHistorySum($objectname.'.rashodt1', $now-2629743 ,$now))+round(getHistorySum($objectname.'.rashodt2', $now-2629743 ,$now));
$out['MONTH_RUB']=(round(getHistorySum($objectname.'.rashodt1', $now-2629743 ,$now)*SETTINGS_APPMERCURY_T1))+(round(getHistorySum($objectname.'.rashodt2', $now-2629743 ,$now)*SETTINGS_APPMERCURY_T2));

$out['DAY_WATT']=round(getHistorySum($objectname.'.rashodt1', $now-86400 ,$now))+round(getHistorySum($objectname.'.rashodt2', $now-86400 ,$now));
$out['DAY_RUB']=(round(getHistorySum($objectname.'.rashodt1', $now-86400 ,$now)*SETTINGS_APPMERCURY_T1))+(round(getHistorySum($objectname.'.rashodt2', $now-86400 ,$now)*SETTINGS_APPMERCURY_T2));

$out['WEEK_WATT']=round(getHistorySum($objectname.'.rashodt1', $now-604800 ,$now))+round(getHistorySum($objectname.'.rashodt2', $now-604800 ,$now));
$out['WEEK_RUB']=(round(getHistorySum($objectname.'.rashodt1', $now-604800 ,$now)*SETTINGS_APPMERCURY_T1))+(round(getHistorySum($objectname.'.rashodt2', $now-604800 ,$now)*SETTINGS_APPMERCURY_T2));

$out['YEAR_WATT']=round(getHistorySum($objectname.'.rashodt1', $now-31556926 ,$now))+round(getHistorySum($objectname.'.rashodt2', $now-31556926 ,$now));
$out['YEAR_RUB']=(round(getHistorySum($objectname.'.rashodt1', $now-31556926 ,$now)*SETTINGS_APPMERCURY_T1))+(round(getHistorySum($objectname.'.rashodt2', $now-31556926 ,$now)*SETTINGS_APPMERCURY_T2));





 if ($this->view_mode=='get') {
setGlobal('cycle_mercuryControl','start'); 

$cachedVoiceDir = ROOT . 'cms/cached/';
$file = $cachedVoiceDir . 'mercurydebug.txt';
//$debug = file_get_contents($file);

//$debug = "Запускаем цикл по счетчикам <br>\n";
//debmes("Запускаем цикл по счетчикам",'mercury');
//file_put_contents($file, $debug);

$cmd_rec = SQLSelect("SELECT ID FROM mercury_devices");
foreach ($cmd_rec as $cmd_r)
{
$myid=$cmd_r['ID'];
//$debug .= "Начинаем запрашивать счетчик $myid. <br>\n";
//debmes("Начинаем запрашивать счетчик $myid ",'mercury');

//file_put_contents($file, $debug);
$this->getpu($myid);
$this->updatecosts($myid);


}

}  

 if (isset($this->data_source) && !$_GET['data_source'] && !$_POST['data_source']) {
  $out['SET_DATASOURCE']=1;
 }

if ($this->view_mode=='get_counters') {
$this->getpu($this->id);
$this->getrates($this->id);
}  



if ($this->view_mode=='turnon') {
$this->turnon($this->id);
}  

if ($this->view_mode=='turnoff') {
$this->turnoff($this->id);
}  


if ($this->view_mode=='getinfo') {
$this->getinfo($this->id);
}  

  

 if ($this->view_mode=='indata_edit') {
   $this->editdevices($out, $this->id);
 }

 if ($this->view_mode=='updatecurrent') {
   $this->updatecurrent($out);
   $this->redirect("?");
 }


 if ($this->view_mode=='addnews') {

$news = SQLSelect("SELECT * FROM mercury_news");
{
$news['data']=date('Y-m-d H:i:s');
$news['message']=$this->message;
$news['TITLE']=$this->tema;
SQLInsert('mercury_news', $news);	
 }
}



   $this->searchdevices($out, $this->id);
 if ($this->view_mode=='config'||$this->view_mode==''||$this->view_mode=='indata_edit') {
//   $this->searchdevices($out, $this->id);
   $this->getcurrent($out);

 }


 if ($this->view_mode=='indata_del') {
   $this->delete($this->id);
   $this->redirect("?data_source=$this->data_source&view_mode=node_edit&id=$pid&tab=indata");
 }	

  if ($this->view_mode=='indata_edit') {
   $this->indata_edit($out, $this->id);
  }


  if ($this->view_mode=='getrates') {
   $this->getrates($this->id);
   $this->updatecosts($this->id);
  }

  if ($this->view_mode=='updatecosts') {
   $this->updatecosts($this->id);
  }


}

  



function getrates($id) {

if (!$id){

$all_rec = SQLSelect("SELECT * FROM mercury_devices");
foreach ($all_rec as $rc) {
$this->updaterates($rc['ID']);
//$this->updatecosts($id);
}
} else {

$this->updaterates($id);
}
}

function updaterates($id) {

//pmesg($objectname);
$objectname='Mercury_'.$id;		
$cmd_rec = SQLSelectOne("SELECT * FROM mercury_devices where ID='$id'");
$now=time();
$cmd_rec['MONTH_WATT']=round(getHistorySum($objectname.'.rashodt1', $now-2629743 ,$now))+round(getHistorySum($objectname.'.rashodt2', $now-2629743 ,$now));
$cmd_rec['MONTH_RUB']=(round(getHistorySum($objectname.'.rashodt1', $now-2629743 ,$now)*SETTINGS_APPMERCURY_T1))+(round(getHistorySum($objectname.'.rashodt2', $now-2629743 ,$now)*SETTINGS_APPMERCURY_T2));

$cmd_rec['DAY_WATT']=round(getHistorySum($objectname.'.rashodt1', $now-86400 ,$now))+round(getHistorySum($objectname.'.rashodt2', $now-86400 ,$now));
$cmd_rec['DAY_RUB']=(round(getHistorySum($objectname.'.rashodt1', $now-86400 ,$now)*SETTINGS_APPMERCURY_T1))+(round(getHistorySum($objectname.'.rashodt2', $now-86400 ,$now)*SETTINGS_APPMERCURY_T2));

$cmd_rec['WEEK_WATT']=round(getHistorySum($objectname.'.rashodt1', $now-604800 ,$now))+round(getHistorySum($objectname.'.rashodt2', $now-604800 ,$now));
$cmd_rec['WEEK_RUB']=(round(getHistorySum($objectname.'.rashodt1', $now-604800 ,$now)*SETTINGS_APPMERCURY_T1))+(round(getHistorySum($objectname.'.rashodt2', $now-604800 ,$now)*SETTINGS_APPMERCURY_T2));

$cmd_rec['YEAR_WATT']=round(getHistorySum($objectname.'.rashodt1', $now-31556926 ,$now))+round(getHistorySum($objectname.'.rashodt2', $now-31556926 ,$now));
$cmd_rec['YEAR_RUB']=(round(getHistorySum($objectname.'.rashodt1', $now-31556926 ,$now)*SETTINGS_APPMERCURY_T1))+(round(getHistorySum($objectname.'.rashodt2', $now-31556926 ,$now)*SETTINGS_APPMERCURY_T2));
SQLUpdate('mercury_devices',$cmd_rec);


}

function updatecosts($id) {

debmes('запущен updatecosts id='.$id,'mercury');

if ($id<>""){

$year=date('Y',time());		
$month=date('m',time());		

$lastvalue = SQLSelectOne("SELECT IFNULL(max(LASTVALUE),0) LASTVALUE FROM mercury_costs where IDDEV='$id' ")['LASTVALUE'];


//first record
if ($lastvalue==0) {$first=1;}
$value = SQLSelectOne("SELECT IFNULL(TOTAL,0) VALUE FROM mercury_devices where ID='$id' ")['VALUE'];


$sql="SELECT * FROM mercury_costs where IDDEV='$id' and YEAR='$year' and MONTH='$month'";

$cmd_rec = SQLSelectOne($sql);

debmes($sql,'mercury');

$cmd_rec['TITLE']='Расход в квт/ч за месяц';
$cmd_rec['PARAMETR']='potreblenie';
$cmd_rec['YEAR']=$year;
$cmd_rec['IDDEV']=$id;
$cmd_rec['VALUE']=$value;
$cmd_rec['LASTVALUE']=$value;
$cmd_rec['MONTH']=$month;
$cmd_rec['UPDATED']=date('Y-m-d H:i:s');

if ($cmd_rec['ID']) {
//update
if ($cmd_rec['SUM']) {$cmd_rec['SUM']=$cmd_rec['SUM']+($value-$lastvalue);}else  {$cmd_rec['SUM']=$value;}

debmes('update mercury_costs','mercury');
SQLUpdate('mercury_costs',$cmd_rec);
}
else 
{
//$cmd_rec['SUM']=$value-$lastvalue;

if ($first=="1") {$cmd_rec['SUM']='0';} else {$cmd_rec['SUM']=$value-$lastvalue;}
debmes('insert mercury_costs','mercury');
SQLInsert('mercury_costs',$cmd_rec);
}
}}

function processSubscription($event_name, $details='') {
  if ($event_name=='HOURLY') {
		$this->getrates();
  }
 }	
 

function checkSettings() {
  $settings=array(
    array(
    'NAME'=>'APPMERCURY_T1', 
    'TITLE'=>'Стоимость Тариф 1, RUB',
    'TYPE'=>'text',
    'DEFAULT'=>'2.99' )
 ,  array(    'NAME'=>'APPMERCURY_T2', 
    'TITLE'=>'Стоимость Тариф 2, RUB',
    'TYPE'=>'text',  
    'DEFAULT'=>'1.42'    )
,array('NAME'=>'APPMERCURY_INTERVAL', 
    'TITLE'=>'Период опроса (min)', 
    'TYPE'=>'text',
    'DEFAULT'=>'5'    )
,   array(    'NAME'=>'APPMERCURY_ENABLE', 
    'TITLE'=>'Включен цикл',
    'TYPE'=>'yesno',
    'DEFAULT'=>'1'    )
,   array(    'NAME'=>'APPMERCURY_ENABLEDEBUG', 
    'TITLE'=>'Включена отладка',
    'TYPE'=>'yesno',
    'DEFAULT'=>'2'    )   );
   foreach($settings as $k=>$v) {
    $rec=SQLSelectOne("SELECT ID FROM settings WHERE NAME='".$v['NAME']."'");
    if (!$rec['ID']) {
     $rec['NAME']=$v['NAME'];
     $rec['VALUE']=$v['DEFAULT'];
     $rec['DEFAULTVALUE']=$v['DEFAULT'];
     $rec['TITLE']=$v['TITLE'];
     $rec['TYPE']=$v['TYPE'];
     $rec['DATA']=$v['DATA'];
     $rec['ID']=SQLInsert('settings', $rec);
     Define('SETTINGS_'.$rec['NAME'], $v['DEFAULT']);
    }
   }}


 	
 
	
	


 function indata_edit(&$out, $id) {
  require(DIR_MODULES.$this->name.'/indata_edit.inc.php');
 }
 
 function searchdevices(&$out) {


$mhdevices=SQLSelect("SELECT * FROM mercury_devices");
$total = count($mhdevices);
for ($i = 0; $i < $total; $i++)
{ 
$ip=$mhdevices[$i]['IPADDR'];
$lastping=$mhdevices[$i]['LASTPING'];
//echo time()-$lastping;



if ((!$lastping)||(time()-$lastping>300))
{


$cmd='
$online=ping(processTitle("'.$ip.'"));
if ($online) 
{SQLexec("update mercury_devices set ONLINE=1, LASTPING='.time().' where IPADDR=\''.$ip.'\'");} 
else 
{SQLexec("update mercury_devices set ONLINE=0, LASTPING='.time().' where IPADDR=\''.$ip.'\'");}

';
 SetTimeOut('mercury_devices_ping'.$ip,$cmd, '1'); 




/*
    if ($online) 
{SQLexec("update mercury_devices set ONLINE='1', LASTPING=".time()." where IPADDR='$ip'");} 
else 
{SQLexec("update mercury_devices set ONLINE='0', LASTPING=".time()." where IPADDR='$ip'");}

*/
}}



  require(DIR_MODULES.$this->name.'/search.inc.php');
 }

// function updatecurrent(&$out) {
 function updatecurrent($out) {
global $current;
$cmd_rec = SQLSelect("update mercury_config set VALUE='$current' where parametr='CURRENT'");
$out["CURRENT"]= $current;
}


function getcurrent(&$out) {

//$cmd_rec = SQLSelectOne("select VALUE from mercury_config where parametr='CURRENT'");
//$out["CURRENT"]= $cmd_rec['VALUE'];
//$out["CURRENT"]="123";
global $current;
$out["CURRENT"]=$current;
}


  
 
/**
* FrontEnd
*
* Module frontend
*
* @access public
*/
function usual(&$out) {
 $this->admin($out);
}



/**

*
* @access public
*/



 
 function processCycle() {
//   $every=$this->config['EVERY'];



$cmd_rec = SQLSelectOne("SELECT VALUE FROM mercury_config where parametr='ENABLE'");
$enable=$cmd_rec['VALUE'];

$enable=1;

$cmd_rec = SQLSelectOne("SELECT VALUE FROM mercury_config where parametr='EVERY'");
$every=$cmd_rec['VALUE'];


$cmd_rec = SQLSelectOne("SELECT VALUE FROM mercury_config where parametr='LASTCYCLE_TS'");
$latest=$cmd_rec['VALUE'];

   $tdev = time()-$latest;
   $has = $tdev>$every*60;
   if ($tdev < 0) {$has = true;}
   
   if ($has) {  

if ($enable==1) {


$cachedVoiceDir = ROOT . 'cms/cached/';
$file = $cachedVoiceDir . 'mercurydebug.txt';
//$debug = file_get_contents($file);

//$debug = "Запускаем цикл по счетчикам <br>\n";
file_put_contents($file, $debug);

$cmd_rec = SQLSelect("SELECT ID FROM mercury_devices");
foreach ($cmd_rec as $cmd_r)
{
$myid=$cmd_r['ID'];
//$debug .= "Начинаем запрашивать счетчик $myid. <br>\n";
file_put_contents($file, $debug);
$this->getpu($myid);
$this->getrates($myid);
$this->updatecosts($myid);
}



}
  } 
  }


 function delete($id) {
  $rec=SQLSelectOne("SELECT * FROM mercury_devices WHERE ID='$id'");
  // some action for related tables
  SQLExec("DELETE FROM mercury_devices WHERE ID='".$rec['ID']."'");
 }
/**
* InData edit/add
*
* @access public
*/
 function editdevices(&$out, $id) {	
  require(DIR_MODULES.$this->name.'/indata_edit.inc.php');
 } 


//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////


 function getinfo($id) {

$rec=SQLSelectOne("SELECT * FROM mercury_devices WHERE ID='$id'");

$address=$rec['IPADDR'];
$service_port=$rec['PORT'];
$device=$rec['HEXADR'];


$cachedVoiceDir = ROOT . 'cms/cached/';
$file = $cachedVoiceDir . 'mercurydebug.txt';


// Открываем файл для получения существующего содержимого
//$debug = file_get_contents($file);


//$debug = date('d/m/y H:s'). " запущен запрос состояния счетчика $id<br>\n";
//file_put_contents($file, $debug);

// Создаём сокет TCP/IP. 
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($socket,SOL_SOCKET, SO_RCVTIMEO, array("sec"=>5, "usec"=>0));
//if ($socket === false) {
//$debug .=  "Не удалось выполнить socket_create(): причина: " . socket_strerror(socket_last_error()) . "<br>";
//file_put_contents($file, $debug);
//} 
//else {
//$debug .= "OK.<br>";
//file_put_contents($file, $debug);
//}

//$debug .=  "Пытаемся соединиться с '$address' на порту '$service_port'...";
//file_put_contents($file, $debug);
$result = socket_connect($socket, $address, $service_port);
//if ($result === false) {
//$debug .= "Не удалось выполнить socket_connect().\nПричина: ($result) " . socket_strerror(socket_last_error($socket)) . "<br>";
//file_put_contents($file, $debug);
//} else {
//$debug .=  "OK.<br>";
//file_put_contents($file, $debug);
///}

//открытие канала связи
$ncrc=$this->calcCRC($device252,"0101010101010101");
//$ncrc=$this->send($socket, $this->calcCRC($device,"0102020202020202"));

//sg('test.ncrc',$ncrc);
 
$this->send($socket252, $ncrc);
$this->read($socket252);



//создаем устройство

$classname='Mercury';
$objname=$classname.'_'.$id;

addClassObject($classname,$objname);
$sql=SQLSelectOne("SELECT * FROM mercury_devices WHERE ID=".$id);


//$ncrc=$this->calcCRC($device252,"081621");

//$res =$this->merc_gd($socket252,$ncrc, 0.001);
//$res = $this->merc_gd($socket,$this->calcCRC($device,"0800"),1);

//sg('test.sn',$res[0].$res[1].$res[2].$res[3].$res[4].$res[5].$res[6]);

//$this->send($socket, $this->calcCRC($device,"0101010101010101"));
//read($socket);


//При работе с трехфазными счетчиками Меркурий (Меркурий 230, Меркурий 233, Меркурий 236 и Меркурий 234)
// к команде отправляемой счетчику необходимо добавлять в начало посылки байт - "01" . Для однофазных счетчиков этого не требуется.

//Опросить счётчики, находящиеся в сети и получить их сетевые адреса (групповой запрос)
$this->send($socket, $this->calcCRC($device,"0805"));
$res = $this->read($socket);

debmes( "request: 0805 result: ".$res, 'mercury');
$sn = hexdec($this->dd($res[1])).hexdec($this->dd($res[2])).hexdec($this->dd($res[3])).hexdec($this->dd($res[4]));

$made = hexdec($this->dd($res[5])).".".hexdec($this->dd($res[6])).".".hexdec($this->dd($res[7]));

debmes( "sn: ".$sn, 'mercury');
debmes( "made: ".$made, 'mercury');



//запрос серийного номера и даты производства
$this->send($socket, $this->calcCRC($device,"0800"));
$res = $this->read($socket);

debmes( "request: 0800 result: ".$res, 'mercury');


//$sn = hexdec($this->dd($res[1])).hexdec($this->dd($res[2])).hexdec($this->dd($res[3])).hexdec($this->dd($res[4]));
$sn = str_pad(hexdec($this->dd($res[1])),2,"0",STR_PAD_LEFT).str_pad(hexdec($this->dd($res[2])),2,"0",STR_PAD_LEFT).str_pad(hexdec($this->dd($res[3])),2,"0",STR_PAD_LEFT).str_pad(hexdec($this->dd($res[4])),2,"0",STR_PAD_LEFT);

$made = hexdec($this->dd($res[5])).".".hexdec($this->dd($res[6])).".".hexdec($this->dd($res[7]));

debmes( "sn: ".$sn, 'mercury');
debmes( "made: ".$made, 'mercury');


str_pad($input, 10, "-=", STR_PAD_LEFT);
$sql['SN']=$sn;
$sql['MADEDT']=$made;

//sg('test.sn',print_r($res));
//sg('test.sn',$sn);

//версия ПО
$this->send($socket, $this->calcCRC($device,"0803"));
$res = $this->read($socket);

debmes( "request: 0803 result: ".$res, 'mercury');
$po= hexdec($this->dd($res[1])).".".hexdec($this->dd($res[2])).".".hexdec($this->dd($res[3]));
$sql['PO']=$po;
debmes( "PO: ".$po, 'mercury');

//коэффициент трансформации
$this->send($socket, $this->calcCRC($device,"0802"));

$res = $this->read($socket);
debmes( "request: 0802 result: ".$res, 'mercury');

$kn= hexdec($this->dd($res[1])).".".hexdec($this->dd($res[2]));
$kt= hexdec($this->dd($res[3])).".".hexdec($this->dd($res[4]));


debmes( "kn: ".$kn, 'mercury');
debmes( "kt: ".$kt, 'mercury');


$sql['KN']=$kn;
$sql['KT']=$kt;


//логинимся под админом
$this->send($socket, $this->calcCRC($device,"0102020202020202"));
$res = $this->read($socket);

debmes( "request: 0102020202020202 result: ".$res, 'mercury');

//чтение слова состояние нагрузки
$this->send($socket, $this->calcCRC($device,"0818"));
$res = $this->read($socket);

debmes( "request: 0818 result: ".$res, 'mercury');


$flimithex=$this->dd($res[1]).$this->dd($res[2]);
debmes( "flimithex: ".$flimithex, 'mercury');
$flimit = hexdec($this->dd($res[1]).$this->dd($res[2]));
debmes( "flimit: ".$flimit, 'mercury');
//echo strtoupper(substr($flimithex,0,4));
//echo "<br>";
//echo hex2bin($flimithex);

$state='';

if ((strtoupper(substr($flimithex,0,4))=='0008') or (strtoupper(substr($flimithex,0,4))=='F000') )
{
//echo 'включено';
$state='1';
} 


if (strtoupper(substr($flimithex,0,4))=='084A' )
{$state='0';
//echo 'выключено';
} 
$sql['STATE']=$state;
$sql['STATEWORD']=$flimithex.':'.$flimit;


//$debug .=  "Закрываем сокет...";
//file_put_contents($file, $debug);

socket_close($socket);
//$debug .=  "OK.\n\n";
//file_put_contents($file, $debug);





//$debug .= "Закрываем сокет...";
//file_put_contents($file, $debug);
socket_close($socket);
//$debug .= "OK.\n\n";
file_put_contents($file, $debug);

SQLUpdate('mercury_devices',$sql);
}

//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////

 function turnoff($id) {
$rec=SQLSelectOne("SELECT * FROM mercury_devices WHERE ID='$id'");
$address=$rec['IPADDR'];
$service_port=$rec['PORT'];
$device=$rec['HEXADR'];

$cachedVoiceDir = ROOT . 'cms/cached/';
$file = $cachedVoiceDir . 'mercurydebug.txt';


// Открываем файл для получения существующего содержимого
//$debug = file_get_contents($file);

$classname='Mercury';
$objname=$classname.'_'.$id;


//$debug .= date('d/m/y H:s'). " запущен запрос на включение счетчика $id<br>\n";
//file_put_contents($file, $debug);


/* Создаём сокет TCP/IP. */
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($socket,SOL_SOCKET, SO_RCVTIMEO, array("sec"=>5, "usec"=>0));
//if ($socket === false) {
//$debug .=  "Не удалось выполнить socket_create(): причина: " . socket_strerror(socket_last_error()) . "<br>";
//} else {
//$debug .=  "OK.<br>";
//}

//$debug .=  "Пытаемся соединиться с '$address' на порту '$service_port'...";
//file_put_contents($file, $debug);
$result = socket_connect($socket, $address, $service_port);
//if ($result === false) {
//$debug .=  "Не удалось выполнить socket_connect().\nПричина: ($result) " . socket_strerror(socket_last_error($socket)) . "<br>";
//file_put_contents($file, $debug);
//} else {
//$debug .=  "OK.<br>";
//file_put_contents($file, $debug);
//}

//$this->send($socket, $this->calcCRC($device,"0101010101010101"));
$this->send($socket, $this->calcCRC($device,"0102020202020202"));
$this->read($socket);
//$res = $this->merc_gd($socket,$this->calcCRC($device,"0819"),0.01);
$this->send($socket, $this->calcCRC($device,"033101"));
$res = $this->read($socket);
 sleep(2);
$this->send($socket, $this->calcCRC($device,"0101010101010101"));
$res = $this->read($socket);
//чтение слова состояние нагрузки
$this->send($socket, $this->calcCRC($device,"0818"));
$res = $this->read($socket);
$flimithex=$this->dd($res[1]).$this->dd($res[2]);
$flimit = hexdec($this->dd($res[1]).$this->dd($res[2]));
//echo strtoupper(substr($flimithex,0,4));
//echo "<br>";
//echo hex2bin($flimithex);

$state='';

if ((strtoupper(substr($flimithex,0,4))=='0008') or (strtoupper(substr($flimithex,0,4))=='F000') )
{
//echo 'включено';
$state='1';
} 


if (strtoupper(substr($flimithex,0,4))=='084A' )
{$state='0';
//echo 'выключено';
} 
$sql=SQLSelectOne("SELECT * FROM mercury_devices WHERE ID=".$id);
$sql['STATE']=$state;
$sql['STATEWORD']=$flimithex.':'.$flimit;
SQLUpdate('mercury_devices',$sql);




//$debug .=  "Закрываем сокет...";
//file_put_contents($file, $debug);

socket_close($socket);
//$debug .=  "OK.\n\n";
//file_put_contents($file, $debug);

}

//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////

 function turnon($id) {
$rec=SQLSelectOne("SELECT * FROM mercury_devices WHERE ID='$id'");
$address=$rec['IPADDR'];
$service_port=$rec['PORT'];
$device=$rec['HEXADR'];

$cachedVoiceDir = ROOT . 'cms/cached/';
$file = $cachedVoiceDir . 'mercurydebug.txt';


// Открываем файл для получения существующего содержимого
//$debug = file_get_contents($file);

$classname='Mercury';
$objname=$classname.'_'.$id;


//$debug .= date('d/m/y H:s'). " запущен запрос на включение счетчика $id<br>\n";
//file_put_contents($file, $debug);


/* Создаём сокет TCP/IP. */
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($socket,SOL_SOCKET, SO_RCVTIMEO, array("sec"=>5, "usec"=>0));
//if ($socket === false) {
//$debug .=  "Не удалось выполнить socket_create(): причина: " . socket_strerror(socket_last_error()) . "<br>";
//} else {
//$debug .=  "OK.<br>";
//}

//$debug .=  "Пытаемся соединиться с '$address' на порту '$service_port'...";
//file_put_contents($file, $debug);
$result = socket_connect($socket, $address, $service_port);
//if ($result === false) {
//$debug .=  "Не удалось выполнить socket_connect().\nПричина: ($result) " . socket_strerror(socket_last_error($socket)) . "<br>";
//file_put_contents($file, $debug);
//} else {
//$debug .=  "OK.<br>";
//file_put_contents($file, $debug);
//}

//$this->send($socket, $this->calcCRC($device,"0101010101010101"));
$this->send($socket, $this->calcCRC($device,"0102020202020202"));
$this->read($socket);
//$res = $this->merc_gd($socket,$this->calcCRC($device,"0819"),0.01);
$this->send($socket, $this->calcCRC($device,"033100"));
$res = $this->read($socket);

//debmes( "request: 033100 result: ".$res, 'mercury');

  sleep(2);
$this->send($socket, $this->calcCRC($device,"0101010101010101"));
$res = $this->read($socket);
//debmes( "request: 0101010101010101 result: ".$res, 'mercury');

//чтение слова состояние нагрузки
$this->send($socket, $this->calcCRC($device,"0818"));
$res = $this->read($socket);

//debmes( "request: 0818 result: ".$res, 'mercury');

$flimithex=$this->dd($res[1]).$this->dd($res[2]);
$flimit = hexdec($this->dd($res[1]).$this->dd($res[2]));

//debmes( "flimit: ".$flimit, 'mercury');


//echo strtoupper(substr($flimithex,0,4));
//echo "<br>";
//echo hex2bin($flimithex);

$state='';

if ((strtoupper(substr($flimithex,0,4))=='0008') or (strtoupper(substr($flimithex,0,4))=='F000') )
{
//echo 'включено';
$state='1';
} 


if (strtoupper(substr($flimithex,0,4))=='084A' )
{$state='0';
//echo 'выключено';
} 
$sql=SQLSelectOne("SELECT * FROM mercury_devices WHERE ID=".$id);
$sql['STATE']=$state;
$sql['STATEWORD']=$flimithex.':'.$flimit;
SQLUpdate('mercury_devices',$sql);




//$debug .=  "Закрываем сокет...";
//file_put_contents($file, $debug);

socket_close($socket);
//$debug .=  "OK.\n\n";
//file_put_contents($file, $debug);
}
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
 function getpu($id) {

$rec=SQLSelectOne("SELECT * FROM mercury_devices WHERE ID='$id'");

$address252=$rec['IPADDR'];
$service_port252=$rec['PORT'];
$device252=$rec['HEXADR'];

//sg('test.merc_ip',$address252);
//sg('test.merc_port',$service_port252);
//sg('test.merc_hex',$device252);

//$ot = $this->object_title;
//setTimeOut($ot.'_updateValue','callMethod("'.$ot.'.GetValues");',10);

$cachedVoiceDir = ROOT . 'cms/cached/';
$file = $cachedVoiceDir . 'mercurydebug.txt';


// Открываем файл для получения существующего содержимого
//$debug = file_get_contents($file);
// Добавляем нового человека в файл


//$debug .= date('d/m/y H:s'). " запущен запрос данных по счетчику $id<br>\n";

/// Создаём сокет TCP/IP. 
$socket252 = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($socket252,SOL_SOCKET, SO_RCVTIMEO, array("sec"=>5, "usec"=>0));
//if ($socket252 === false) {
//$debug .= "Не удалось выполнить socket_create(): причина: " . socket_strerror(socket_last_error()) . "<br>\n";
//} else {
//$debug .= "Сокет создан. <br>\n";

//}
//file_put_contents($file, $debug);

//$debug .= "Пытаемся соединиться с '$address252' на порту '$service_port252'...<br>\n";
$result = socket_connect($socket252, $address252, $service_port252);
//if ($result === false) {
//$debug .= "Не удалось выполнить socket_connect().<br>\nПричина: ($result) " . socket_strerror(socket_last_error($socket)) . "<br>\n";
//} else {
//$debug .= "Соединение установлено.<br>\n";
//}
//file_put_contents($file, $debug);

$ncrc=$this->calcCRC($device252,"0101010101010101");
//sg('test.ncrc',$ncrc);
 
$this->send($socket252, $ncrc);
$this->read($socket252);





//создаем устройство

$classname='Mercury';
$objname=$classname.'_'.$id;

addClassObject($classname,$objname);
$sql=SQLSelectOne("SELECT * FROM mercury_devices WHERE ID=".$id);

# Сила тока по фазам
# =====================================================

$ncrc=$this->calcCRC($device252,"081621");

//debmes( "request: 081621 result: ".$ncrc, 'mercury');

$Ia =$this->merc_gd($socket252,$ncrc, 0.001);
$It = $Ia[0] + $Ia[1] + $Ia[2];

//debmes( "Ia: ".$Ia, 'mercury');

//debmes( "It: ".$It, 'mercury');



$debug .= "Ia: $Ia[0] - $Ia[1] - $Ia[2] IaT:$It<br>";

//debmes( "summ: Ia: $Ia[0] - $Ia[1] - $Ia[2] IaT:$It", 'mercury');
file_put_contents($file, $debug);

if (($Ia[0])&&($Ia[0]<1000)) {sg($objname.'.Ia1',$Ia[0]); $sql['Ia1']=$Ia[0];}
if (($Ia[1])&&($Ia[1]<1000)) {sg($objname.'.Ia2',$Ia[1]); $sql['Ia2']=$Ia[1];}
if (($Ia[2])&&($Ia[2]<1000)) {sg($objname.'.Ia3',$Ia[2]); $sql['Ia3']=$Ia[2];}
if (($It)&&($It<1000)) {sg($objname.'.IaT',$It); $sql['IaT']=$It;}




# Мощность по фазам
# =====================================================
$ncrc=$this->calcCRC($device252,"081600");

//debmes( "request: 081600 result: ".$ncrc, 'mercury');

$Pv =$this->merc_gd($socket252,$ncrc, 0.01);

//debmes( "Pv: ".$Pv, 'mercury');

if ( round($Pv[0], 2) != round($Pv[1] + $Pv[2] + $Pv[3], 2) )
	$error = "error"; else $error = "";
//$debug .= "Pv: $Pv[0] - $Pv[1] - $Pv[2] - $Pv[3] $error<br>";

//debmes( $debug, 'mercury');

if ($error == "")
{
//if (round($Pv[0],0)) sg($objname.'.PvT',round($Pv[0],0));
if ($Pv[0]) sg($objname.'.PvT',round($Pv[0],0));
if ($Pv[1]) sg($objname.'.Pv1',$Pv[1]);
if ($Pv[2]) sg($objname.'.Pv2',$Pv[2]);
if ($Pv[3]) sg($objname.'.Pv3',$Pv[3]);

if ($Pv[0]) $sql['PvT']=round($Pv[0],0);
if ($Pv[1]) $sql['Pv1']=$Pv[1];
if ($Pv[2]) $sql['Pv2']=$Pv[2];
if ($Pv[3]) $sql['Pv3']=$Pv[3];
}
# Cosf по фазам
# =====================================================
$ncrc=$this->calcCRC($device252,"081630");
$Cos = $this->merc_gd($socket252,$ncrc, 0.001);
//$debug .= "Cos: $Cos[0] - $Cos[1] - $Cos[2] - $Cos[3]<br>";


if ($Cos[0]) {sg($objname.'.CosT',$Cos[0]); $sql['CosT']=$Cos[0];}
if ($Cos[0]) {sg($objname.'.Cos1',$Cos[1]); $sql['Cos1']=$Cos[1];}
if ($Cos[0]) {sg($objname.'.Cos2',$Cos[2]); $sql['Cos2']=$Cos[2];}
if ($Cos[0]) {sg($objname.'.Cos3',$Cos[3]); $sql['Cos3']=$Cos[3];}

# Напряжение по фазам
# =====================================================
$Uv = $this->merc_gd($socket252,$this->calcCRC($device252,"081611"), 0.01);
//debmes( "request: 081611 result: ".$Uv, 'mercury');
//$debug .= "Uv: $Uv[0] - $Uv[1] - $Uv[2]<br>";
//debmes( $debug, 'mercury');

if (($Uv[0])&&($Uv[0]<1000)) {sg($objname.'.Uv1',round($Uv[0],0));$sql['Uv1']=round($Uv[0],0);}
if (($Uv[1])&&($Uv[1]<1000)) {sg($objname.'.Uv2',round($Uv[1],0));$sql['Uv2']=round($Uv[1],0);}
if (($Uv[2])&&($Uv[2]<1000)) {sg($objname.'.Uv3',round($Uv[2],0));$sql['Uv3']=round($Uv[2],0);}


$arU=array();

if ((round($Uv[0],0))&&(round($Uv[0],0)<1000)) {$arU[1]=round($Uv[0],0);}
if ((round($Uv[1],0))&&(round($Uv[1],0)<1000)) {$arU[2]=round($Uv[1],0);}
if ((round($Uv[2],0))&&(round($Uv[2],0)<1000)) {$arU[3]=round($Uv[2],0);}

//echo $this->average($arU);
if ( (round($Uv[0],0))&&(round($Uv[1],0))&&(round($Uv[2],0))
&&(round($Uv[0],0)<1000)
&&(round($Uv[1],0)<1000)
&&(round($Uv[2],0)<1000)){


$temp=round($this->average($arU));
if  ($temp) {
sg($objname.'.U',$temp);	
$sql['U']=$temp;
}
}



# Показания электроэнергии
# =====================================================
$Tot = $this->merc_gd($socket252,$this->calcCRC($device252,"050000"), 0.001, 1);
//debmes( "Показания электроэнергии общей: ", 'mercury');
//debmes( "request: 050000 result: ".$Tot, 'mercury');

//$debug .= "Total: $Tot[0]<br>";
if ($Tot[0]) {sg($objname.'.Total',round($Tot[0],0)); $sql['Total']=round($Tot[0],0);
$sql['TS']=time();
$sql['TS_TEXT']=date('d-m-Y H:i:s',time());
}


$Tot = $this->merc_gd($socket252,$this->calcCRC($device252,"050001"), 0.001, 1);

//debmes( "Показания электроэнергии T1: ", 'mercury');
//debmes( "request: 050001 result: ".$Tot, 'mercury');
//debmes( "Total1: ".$Tot[0], 'mercury');

//$debug .= "Total T1: $Tot[0]<br>";
if ($Tot[0]) {sg($objname.'.Total1',$Tot[0]); $sql['Total1']=$Tot[0];}


$Tot = $this->merc_gd($socket252,$this->calcCRC($device252,"050002"), 0.001, 1);
$debug .= "Total T2: $Tot[0]<br>";
if ($Tot[0]) {sg($objname.'.Total2',$Tot[0]);$sql['Total2']=$Tot[0];}

//debmes( "Показания электроэнергии T2: ", 'mercury');
//debmes( "request: 050002 result: ".$Tot, 'mercury');
//debmes( "Total2: ".$Tot[0], 'mercury');


SQLUpdate('mercury_devices',$sql);

SQLexec("update mercury_config set value=UNIX_TIMESTAMP() where parametr='LASTCYCLE_TS'");		   



$debug .= "Закрываем сокет...";
socket_close($socket252);
//$debug .= "OK.<br>\n<br>\n";
// Пишем содержимое обратно в файл
//file_put_contents($file, $debug);

 }
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////


//////////////////////////////////////////////
//////////////////////////////////////////////

/**

*
* @access public
*/
 
/**

*
* @access public
*/
 
/**
* Install
*
* Module installation routine
*
* @access private
*/
 function install($data='') {

  parent::install();
 }
/**
* Uninstall
*
* Module uninstall routine
*
* @access public
*/
 function uninstall() {

SQLExec('DROP TABLE IF EXISTS mercury_devices');
SQLExec('DROP TABLE IF EXISTS mercury_config');
SQLExec('delete from settings where NAME like "%APPMERCURY%"');
SQLExec("delete from pvalues where property_id in (select id FROM properties where object_id in (select id from objects where class_id = (select id from classes where title = 'Mercury')))");
SQLExec("delete from properties where object_id in (select id from objects where class_id = (select id from classes where title = 'Mercury'))");
SQLExec("delete from objects where class_id = (select id from classes where title = 'Mercury')");
SQLExec("delete from classes where title = 'Mercury'");	 

  parent::uninstall();

 }
/**
* dbInstall
*
* Database installation routine
*
* @access private
*/
 function dbInstall($data = '') {

setGlobal('cycle_mercuryAutoRestart','1');	 	 
$classname='Mercury';
addClass($classname); 

$ChangeT1='
$objn=$this->object_title;
$currentcount=$this->getProperty("Total1");
$lasttotal=gg($objn.".lasttotal1");

SQLUpdate("objects", array("ID"=>$this->id, "DESCRIPTION"=>gg($objn.".FIO")." P:".gg($objn.".PvT")." U:".gg($objn.".U")." ".gg("sysdate")."  ".gg("timenow"))); 
if (IsSet($lasttotal) and ($lasttotal<>0) )
{
$rashod=$currentcount-$lasttotal;
sg($objn.".rashodt1",$rashod);}


sg($objn.".lasttimestamp", time());
sg($objn.".lasttotal1", $currentcount);';
$ChangeT2='
$objn=$this->object_title;
$currentcount=$this->getProperty("Total2");
$lasttotal=gg($objn.".lasttotal2");

SQLUpdate("objects", array("ID"=>$this->id, "DESCRIPTION"=>gg($objn.".FIO")." P:".gg($objn.".PvT")." U:".gg($objn.".U")." ".gg("sysdate")."  ".gg("timenow"))); 
if (IsSet($lasttotal) and ($lasttotal<>0) )
{
$rashod=$currentcount-$lasttotal;
sg($objn.".rashodt2",$rashod);}


sg($objn.".lasttimestamp", time());
sg($objn.".lasttotal2", $currentcount);
';

addClassMethod($classname,'ChangeT1',$ChangeT1);	 
addClassMethod($classname,'ChangeT2',$ChangeT2);	 



$prop_id=addClassProperty($classname, 'Adress', 0);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Адрес счетчика'; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'ControlLimit', 0);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Лимит'; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'Cos1', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']=''; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'Cos2', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']=''; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'Cos3', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']=''; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'CosT', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']=''; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'Ia1', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Сила тока по фазе 1'; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'Ia2', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Сила тока по фазе 2'; //   <-----------
SQLUpdate('properties',$property); }


$prop_id=addClassProperty($classname, 'Ia3', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Сила тока по фазе 3'; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'IaT', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Сила тока общая'; //   <-----------
SQLUpdate('properties',$property); }


$prop_id=addClassProperty($classname, 'LimitValue', 0);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Лимит'; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'Pv1', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Мощность по фазе 1'; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'Pv2', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Мощность по фазе 2'; //   <-----------
SQLUpdate('properties',$property); }



$prop_id=addClassProperty($classname, 'Pv3', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Мощность по фазе 3'; //   <-----------
SQLUpdate('properties',$property); }



$prop_id=addClassProperty($classname, 'PvT', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Мощность суммарная'; //   <-----------
SQLUpdate('properties',$property); }



$prop_id=addClassProperty($classname, 'Total', 0);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']=''; //   <-----------
SQLUpdate('properties',$property); }



$prop_id=addClassProperty($classname, 'Uv1', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Напряжение по фазе 1'; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'Uv2', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Напряжение по фазе 2'; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'Uv3', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Напряжение по фазе 3'; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'U', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Напряжение, среднее значение по 3 фазам'; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'rashodt1', 365);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Израсходовано по тарифу 1'; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'rashodt2', 365);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Израсходовано по тарифу 1'; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'Total1', 0);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Текущее значение счетчика по тарифу 1'; //   <-----------
$property['ONCHANGE']="ChangeT1"; //	   	       
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'Total2', 0);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Текущее значение счетчика по тарифу 2'; //   <-----------
$property['ONCHANGE']="ChangeT2"; //	   	       
SQLUpdate('properties',$property); }









  $data = <<<EOD
 mercury_devices: ID int(10) unsigned NOT NULL auto_increment
 mercury_devices: TITLE varchar(100) NOT NULL DEFAULT ''
 mercury_devices: IPADDR varchar(100) NOT NULL DEFAULT ''
 mercury_devices: PORT varchar(100) NOT NULL DEFAULT ''
 mercury_devices: HEXADR varchar(100) NOT NULL DEFAULT ''
 mercury_devices: MODEL varchar(100) NOT NULL DEFAULT ''
 mercury_devices: SN varchar(100) NOT NULL DEFAULT ''
 mercury_devices: MADEDT varchar(100) NOT NULL DEFAULT ''
 mercury_devices: FIO varchar(100) NOT NULL DEFAULT ''
 mercury_devices: STREET varchar(100) NOT NULL DEFAULT ''
 mercury_devices: PHONE varchar(100) NOT NULL DEFAULT ''
 mercury_devices: LOGIN varchar(100) NOT NULL DEFAULT ''
 mercury_devices: PASSWORD varchar(100) NOT NULL DEFAULT ''
 mercury_devices: LASTPING varchar(100) NOT NULL DEFAULT ''
 mercury_devices: ONLINE varchar(100) NOT NULL DEFAULT ''
 mercury_devices: STATE varchar(100) NOT NULL DEFAULT ''
 mercury_devices: STATEWORD varchar(100) NOT NULL DEFAULT ''
 mercury_devices: TS varchar(100) NOT NULL DEFAULT ''
 mercury_devices: TS_TEXT varchar(100) NOT NULL DEFAULT ''
 mercury_devices: USERIP varchar(100) NOT NULL DEFAULT ''
 mercury_devices: USERHASH varchar(100) NOT NULL DEFAULT ''
 mercury_devices: Ia1 varchar(100) NOT NULL DEFAULT ''
 mercury_devices: Ia2 varchar(100) NOT NULL DEFAULT ''
 mercury_devices: Ia3 varchar(100) NOT NULL DEFAULT ''
 mercury_devices: CosT varchar(100) NOT NULL DEFAULT ''
 mercury_devices: Pv1 varchar(100) NOT NULL DEFAULT ''
 mercury_devices: Pv2 varchar(100) NOT NULL DEFAULT ''
 mercury_devices: Pv3 varchar(100) NOT NULL DEFAULT ''
 mercury_devices: Cos1 varchar(100) NOT NULL DEFAULT ''
 mercury_devices: Cos2 varchar(100) NOT NULL DEFAULT ''
 mercury_devices: Cos3 varchar(100) NOT NULL DEFAULT ''
 mercury_devices: Uv1 varchar(100) NOT NULL DEFAULT ''
 mercury_devices: Uv2 varchar(100) NOT NULL DEFAULT ''
 mercury_devices: Uv3 varchar(100) NOT NULL DEFAULT ''
 mercury_devices: U varchar(100) NOT NULL DEFAULT ''
 mercury_devices: IaT varchar(100) NOT NULL DEFAULT ''
 mercury_devices: PvT varchar(100) NOT NULL DEFAULT ''
 mercury_devices: Total varchar(100) NOT NULL DEFAULT ''
 mercury_devices: Total1 varchar(100) NOT NULL DEFAULT ''
 mercury_devices: Total2 varchar(100) NOT NULL DEFAULT ''
 mercury_devices: Temp1 varchar(100) NOT NULL DEFAULT ''
 mercury_devices: Temp2 varchar(100) NOT NULL DEFAULT ''
 mercury_devices: Temp3 varchar(100) NOT NULL DEFAULT ''
 mercury_devices: Temp4 varchar(100) NOT NULL DEFAULT ''
 mercury_devices: Temp5 varchar(100) NOT NULL DEFAULT ''
 mercury_devices: Hum1 varchar(100) NOT NULL DEFAULT ''
 mercury_devices: Hum2 varchar(100) NOT NULL DEFAULT ''
 mercury_devices: Hum3 varchar(100) NOT NULL DEFAULT ''
 mercury_devices: Hum4 varchar(100) NOT NULL DEFAULT ''
 mercury_devices: Hum5 varchar(100) NOT NULL DEFAULT ''
 mercury_devices: Leak1 varchar(100) NOT NULL DEFAULT ''
 mercury_devices: Leak2 varchar(100) NOT NULL DEFAULT ''
 mercury_devices: Leak3 varchar(100) NOT NULL DEFAULT ''
 mercury_devices: Leak4 varchar(100) NOT NULL DEFAULT ''
 mercury_devices: Leak5 varchar(100) NOT NULL DEFAULT ''
 mercury_devices: Leak6 varchar(100) NOT NULL DEFAULT ''
 mercury_devices: MONTH_WATT varchar(100) NOT NULL DEFAULT ''
 mercury_devices: MONTH_RUB varchar(100) NOT NULL DEFAULT ''
 mercury_devices: DAY_WATT varchar(100) NOT NULL DEFAULT ''
 mercury_devices: DAY_RUB varchar(100) NOT NULL DEFAULT ''
 mercury_devices: WEEK_WATT varchar(100) NOT NULL DEFAULT ''
 mercury_devices: WEEK_RUB varchar(100) NOT NULL DEFAULT ''
 mercury_devices: YEAR_WATT varchar(100) NOT NULL DEFAULT ''
 mercury_devices: YEAR_RUB varchar(100) NOT NULL DEFAULT ''
 mercury_devices: PREDSED int(10) 
 mercury_devices: PO varchar(100) NOT NULL DEFAULT ''
 mercury_devices: KN varchar(100) NOT NULL DEFAULT ''
 mercury_devices: KT varchar(100) NOT NULL DEFAULT ''

 mercury_costs: ID int(10) unsigned NOT NULL auto_increment
 mercury_costs: IDDEV varchar(100) NOT NULL DEFAULT ''
 mercury_costs: TITLE varchar(100) NOT NULL DEFAULT ''
 mercury_costs: YEAR int(4) 
 mercury_costs: MONTH int(2) 
 mercury_costs: PARAMETR varchar(100) NOT NULL DEFAULT ''
 mercury_costs: VALUE varchar(100) NOT NULL DEFAULT ''
 mercury_costs: LASTVALUE varchar(100) NOT NULL DEFAULT ''
 mercury_costs: SUM varchar(100) NOT NULL DEFAULT ''
 mercury_costs: UPDATED datetime

 mercury_news: ID int(10) unsigned NOT NULL auto_increment
 mercury_news: TITLE varchar(100) NOT NULL DEFAULT ''
 mercury_news: data datetime
 mercury_news: message  varchar(2000) NOT NULL DEFAULT ''





EOD;
  parent::dbInstall($data);

  $data = <<<EOD
 mercury_config: parametr varchar(300)
 mercury_config: value varchar(10000)  
EOD;
   parent::dbInstall($data);

$news = SQLSelect("SELECT * FROM mercury_news");
if (!$news[0]['ID']) 
{
$news['data']=date('Y-m-d H:i:s');;
$news['message']='Уважаемые жители. Прочитайте важное объявление';
$news['TITLE']='Важное объявление!!!';
SQLInsert('mercury_news', $news);	

$news['data']=date('Y-m-d H:i:s');;
$news['message']='Уважаемые жители. Прочитайте важное объявление2';
$news['TITLE']='Важное объявление 2!!!';
SQLInsert('mercury_news', $news);	

}


$cmd_rec = SQLSelect("SELECT * FROM mercury_devices");
if ($cmd_rec[0]['ID']) {null;}
else {

$dev['TITLE']='Устройство №1';
$dev['IPADDR']='192.168.1.252';
$dev['PORT']='20252';
$dev['HEXADR']='0E';
$dev['MODEL']='230';
$dev['SN']='';
$dev['FIO']='Амелин Е.';
$dev['STREET']='Участок 58';
$dev['PHONE']='';
$dev['LOGIN']='amelin';
$dev['PASSWORD']='amelin';

SQLInsert('mercury_devices', $dev);	

$dev['TITLE']='Устройство №2';
$dev['IPADDR']='192.168.1.254';
$dev['PORT']='20254';
$dev['HEXADR']='3A';
$dev['MODEL']='254';
$dev['SN']='';
$dev['FIO']='Муратов М.Э.';
$dev['STREET']='Участок 74';
$dev['PHONE']='';
$dev['LOGIN']='muratov';
$dev['PASSWORD']='muratov';
SQLInsert('mercury_devices', $dev);	

$dev['TITLE']='Устройство №3';
$dev['IPADDR']='192.168.1.56';
$dev['PORT']='20256';
$dev['HEXADR']='1B';
$dev['MODEL']='56';
$dev['SN']='';
$dev['FIO']='Дронов Р.В.';
$dev['STREET']='Участок 59';
$dev['PHONE']='';
$dev['LOGIN']='dronov';
$dev['PASSWORD']='dronov';

SQLInsert('mercury_devices', $dev);	

$dev['TITLE']='Устройство №4';
$dev['IPADDR']='192.168.1.57';
$dev['PORT']='20257';
$dev['HEXADR']='46';
$dev['MODEL']='234';
$dev['SN']='';
$dev['FIO']='Долженков';
$dev['STREET']='Участок в 3 очереди';
$dev['PHONE']='';
$dev['LOGIN']='doljenkov';
$dev['PASSWORD']='doljenkov';
SQLInsert('mercury_devices', $dev);	


$dev['TITLE']='Устройство №5';
$dev['IPADDR']='192.168.1.57';
$dev['PORT']='20257';
$dev['HEXADR']='1E';
$dev['MODEL']='234';
$dev['SN']='';
$dev['FIO']='Есин';
$dev['LOGIN']='esin';
$dev['PASSWORD']='esin';
$dev['STREET']='Участок в 3 очереди';
$dev['PHONE']='';
SQLInsert('mercury_devices', $dev);	

}


$cmd_rec = SQLSelect("SELECT * FROM mercury_config");
if ($cmd_rec[0]['EVERY']) {
null;
} else {

$par['parametr'] = 'EVERY';
$par['value'] = 30;		 
SQLInsert('mercury_config', $par);				
	
$par['parametr'] = 'LASTCYCLE_TS';
$par['value'] = "0";		 
SQLInsert('mercury_config', $par);						

$par['parametr'] = 'CURRENT';
$par['value'] = "";		 
SQLInsert('mercury_config', $par);						
		
$par['parametr'] = 'LASTCYCLE_TXT';
$par['value'] = "0";		 
SQLInsert('mercury_config', $par);						
$par['parametr'] = 'DEBUG';
$par['value'] = "";		 
SQLInsert('mercury_config', $par);	
}
}


//////////////////////////////////////////////
function calcCRC($device252,$msg)
{
 $mess = $device252.$msg;
 $crc = $this->crc16_modbus($mess);
// return $mess.$crc[2].$crc[3].$crc[0].$crc[1];
 return $mess.$crc[2].$crc[3].$crc[0].$crc[1];
// return $device252.$mess.$crc[2].$crc[3].$crc[0].$crc[1];
}
//////////////////////////////////////////////
//////////////////////////////////////////////

    function dd  ($data = "")
{
     $result = "";
	$data2 = "";
	for ( $j = 0; $j < count($data); $j++ )
	{
		$data2 = dechex(ord($data[0]));
		if ( strlen($data2) == 1  )
		$result = "0".$data2;
		else
		$result .= $data2;

	}
	return $result;
}

//////////////////////////////////////////////
//////////////////////////////////////////////
function send  ($socket252, $hex = "") {
//$file = ROOT . 'cms/cached/mercurydebug.txt';
//$file = ROOT . 'cms/cached/mercurysend.txt';
//$debug .= file_get_contents($file);

//$debug .= "Отправляем запрос ".$hex."<br>\n";
//file_put_contents($file, $debug);
  $in = hex2bin($hex);
//$debug .=  " ".$hex." ";
//echo SETTINGS_APPMERCURY_ENABLEDEBUG;
if (SETTINGS_APPMERCURY_ENABLEDEBUG=="1")  echo "send:".$hex."<br>";
  socket_write($socket252, $in, strlen($in));
//$debug .=  "OK.<br>\n"; 
// Пишем содержимое обратно в файл
//file_put_contents($file, $debug);

   }
   
//////////////////////////////////////////////
//////////////////////////////////////////////
// Проверка CRC
function checkCRC($input) {
	// Проверяем ответ
	$answ='';
	for ($i=0; $i < strlen($input); $i++) $answ.=$this->dd($input[$i]);
	$answ=strtoupper($answ); 	// Переводим в верхний регистр
	$crcA=substr($answ, -4); 	// Смотрим полученный CRC
	$data=substr($answ, 0, -4);	// Информация для рассчета CRC
	$crc=$this->calcCRC('',$data);	// Рассчитываем свой CRC
	$crc=substr($crc, -4);
	$result=false;
	if ($crcA==$crc) $result=true;
	//debmes("$cmd - $answ | CRC: $crcA - $crc",'mercury');
	return $result;
}


//////////////////////////////////////////////
//////////////////////////////////////////////
function merc_gd($socket252, $cmd, $factor = 1, $total = 0)
{

// Запрашиваем до трех попыток пока не сойдется CRC, если неудачно - прерываем.
$i=0;
do {
	$this->send($socket252, $cmd);
	$result =$this->read($socket252);
	$i++;
} while ((($this->checkCRC($result))==false)&&($i<3));

// Если три попытки неудачны и CRC неверен - прерываем функцию.
if ($i==3) {
	if (($this->checkCRC($result))==false) return;
}

	$ret = array();
	
	$start_byte = 1;
		
	if ( $total != 1 )
	{	
		for ( $i = 0; $i < 4; $i++ )
		{
         	//if ( dechex(ord($result[$start_byte + $i * 3])) >= 40 )
			//$result[$start_byte + $i * 3] = chr(dechex(ord($result[$start_byte + $i * 3])) - 40);
			if ( strlen($result) > $start_byte + 2 + $i * 3 )
				// Для всех запросов кроме мощности используем стандартный рассчет
				if (substr($cmd,2,6)!='081600') {
					$ret[$i] = hexdec($this->dd($result[$start_byte + $i * 3]).$this->dd($result[$start_byte + $i * 3 + 2]).$this->dd($result[$start_byte + $i * 3 + 1]))*$factor;
				}
				// При запросе мощности нужно маскировать два старших разряда старшего бита
				else {
					$hex = $this->dd($result[$start_byte + $i * 3]).$this->dd($result[$start_byte + $i * 3 + 2]).$this->dd($result[$start_byte + $i * 3 + 1]);
					$bin=base_convert($hex, 16, 2);
					// Обрезаем строку до 22 бит
					while (strlen($bin)>22) $bin=substr($bin,1);
					$ret[$i] = bindec($bin)*$factor;
				}
		}
		
	}
	else
		$ret[0] = hexdec($this->dd($result[$start_byte+1]).$this->dd($result[$start_byte]).$this->dd($result[$start_byte+3]).$this->dd($result[$start_byte+2]))*$factor;
	return $ret;
}
//////////////////////////////////////////////
function read($socket252)
{

//$file = ROOT . 'cms/cached/mercurydebug.txt';

//$debug .= file_get_contents($file);

//$debug .="Читаем ответ:<br>\n";
$out = socket_read($socket252, 2048);
//$debug .= bin2hex($out)."<br>\n";
if (SETTINGS_APPMERCURY_ENABLEDEBUG=="1") echo  "answ:".bin2hex($out).'<br>';
//file_put_contents($file, $debug);

return $out;
}

//////////////////////////////////////////////
function crc16_modbus($msg)
{
    $data = pack('H*',$msg);
    $crc = 0xFFFF;
    for ($i = 0; $i < strlen($data); $i++)
    {
        $crc ^=ord($data[$i]);

        for ($j = 8; $j !=0; $j--)
        {
            if (($crc & 0x0001) !=0)
            {
                $crc >>= 1;
                $crc ^= 0xA001;
            }
            else $crc >>= 1;
        }
    }   
    return sprintf('%04X', $crc);
}

//////////////////////////////////////////////
	
function average($arr)
{
   if (!is_array($arr)) return false;

   return array_sum($arr)/count($arr);
}





}
// --------------------------------------------------------------------
	
/*
*
* TW9kdWxlIGNyZWF0ZWQgSmFuIDAzLCAyMDE4IHVzaW5nIFNlcmdlIEouIHdpemFyZCAoQWN0aXZlVW5pdCBJbmMgd3d3LmFjdGl2ZXVuaXQuY29tKQ==
*
*/


//////////////////////////////////////////////
//////////////////////////////////////////////
