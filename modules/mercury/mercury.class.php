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

$out['MSG_DEBUG']=file_get_contents($file);

$cmd_rec = SQLSelectOne("SELECT VALUE FROM mercury_config where parametr='CURRENT'");
$out['CURRENT']=$cmd_rec['VALUE'];
$currentid=$cmd_rec['VALUE'];

$cmd_rec = SQLSelectOne("SELECT * FROM mercury_devices where FIO='$currentid'");


$out['MODEL']=$cmd_rec['MODEL'];		


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




 $out['S0']=$cmd_rec['Total'];		
 $out['S1']=$cmd_rec['Total1'];		
 $out['S2']=$cmd_rec['Total2'];	
	


$now=time();

$out['MONTH_WATT']=round(getHistorySum($objectname.'.potrebleno_w', $now-2629743 ,$now));
$out['MONTH_RUB']=round(getHistorySum($objectname.'.potrebleno_w_rub', $now-2629743,$now));

$out['DAY_WATT']=round(getHistorySum($objectname.'.potrebleno_w', $now-86400 ,$now));
$out['DAY_RUB']=round(getHistorySum($objectname.'.potrebleno_w_rub', $now-86400 ,$now));

$out['WEEK_WATT']=round(getHistorySum($objectname.'.potrebleno_w', $now-604800 ,$now));
$out['WEEK_RUB']=round(getHistorySum($objectname.'.potrebleno_w_rub', $now-604800 ,$now));

$out['YEAR_WATT']=round(getHistorySum($objectname.'.potrebleno_w', $now-31556926 ,$now));
$out['YEAR_RUB']=round(getHistorySum($objectname.'.potrebleno_w_rub', $now-31556926 ,$now));





 if ($this->view_mode=='get') {
setGlobal('cycle_mercuryControl','start'); 

$cachedVoiceDir = ROOT . 'cms/cached/';
$file = $cachedVoiceDir . 'mercurydebug.txt';
$debug = file_get_contents($file);

$debug = "Запускаем цикл по счетчикам <br>\n";
file_put_contents($file, $debug);

$cmd_rec = SQLSelect("SELECT ID FROM mercury_devices");
foreach ($cmd_rec as $cmd_r)
{
$myid=$cmd_r['ID'];
$debug .= "Начинаем запрашивать счетчик $myid. <br>\n";
file_put_contents($file, $debug);
$this->getpu($myid);
}

}  

 if (isset($this->data_source) && !$_GET['data_source'] && !$_POST['data_source']) {
  $out['SET_DATASOURCE']=1;
 }

if ($this->view_mode=='get_counters') {
$this->getpu($this->id);
}  

  

 if ($this->view_mode=='indata_edit') {
   $this->editdevices($out, $this->id);
 }

 if ($this->view_mode=='updatecurrent') {
   $this->updatecurrent($out);
 }


 if ($this->view_mode=='config'||$this->view_mode==''||$this->view_mode=='indata_edit') {
   $this->searchdevices($out, $this->id);
   $this->getcurrent($out);

 }


 if ($this->view_mode=='indata_del') {
   $this->delete($this->id);
   $this->redirect("?data_source=$this->data_source&view_mode=node_edit&id=$pid&tab=indata");
 }	

  if ($this->view_mode=='indata_edit') {
   $this->indata_edit($out, $this->id);
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
if (time()-$lastping>300) {
$online=ping(processTitle($ip));
    if ($online) 
{SQLexec("update mercury_devices set ONLINE='1', LASTPING=".time()." where IPADDR='$ip'");} 
else 
{SQLexec("update mercury_devices set ONLINE='0', LASTPING=".time()." where IPADDR='$ip'");}
}}



  require(DIR_MODULES.$this->name.'/search.inc.php');
 }

 function updatecurrent(&$out) {
global $current;
$cmd_rec = SQLSelect("update mercury_config set VALUE='$current' where parametr='CURRENT'");
$out["CURRENT"]= $current;
}


function getcurrent(&$out) {

$cmd_rec = SQLSelectOne("select VALUE from mercury_config where parametr='CURRENT'");
$out["CURRENT"]= $cmd_rec['VALUE'];
//$out["CURRENT"]="123";
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
$debug = file_get_contents($file);

$debug = "Запускаем цикл по счетчикам <br>\n";
file_put_contents($file, $debug);

$cmd_rec = SQLSelect("SELECT ID FROM mercury_devices");
foreach ($cmd_rec as $cmd_r)
{
$myid=$cmd_r['ID'];
$debug .= "Начинаем запрашивать счетчик $myid. <br>\n";
file_put_contents($file, $debug);
$this->getpu($myid);
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
$debug = file_get_contents($file);
// Добавляем нового человека в файл


$debug .= date('d/m/y H:s'). " запущен запрос данных по счетчику $id<br>\n";

/// Создаём сокет TCP/IP. 
$socket252 = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($socket252,SOL_SOCKET, SO_RCVTIMEO, array("sec"=>5, "usec"=>0));
if ($socket252 === false) {
$debug .= "Не удалось выполнить socket_create(): причина: " . socket_strerror(socket_last_error()) . "<br>\n";
} else {
$debug .= "Сокет создан. <br>\n";

}
file_put_contents($file, $debug);

$debug .= "Пытаемся соединиться с '$address252' на порту '$service_port252'...<br>\n";
$result = socket_connect($socket252, $address252, $service_port252);
if ($result === false) {
$debug .= "Не удалось выполнить socket_connect().<br>\nПричина: ($result) " . socket_strerror(socket_last_error($socket)) . "<br>\n";
} else {
$debug .= "Соединение установлено.<br>\n";
}
file_put_contents($file, $debug);

$ncrc=$this->calcCRC($device252,"0101010101010101");
sg('test.ncrc',$ncrc);
 
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

$Ia =$this->merc_gd($socket252,$ncrc, 0.001);
$It = $Ia[0] + $Ia[1] + $Ia[2];

$debug .= "Ia: $Ia[0] - $Ia[1] - $Ia[2] IaT:$It<br>";
file_put_contents($file, $debug);

if ($Ia[0]) {sg($objname.'.Ia1',$Ia[0]); $sql['Ia1']=$Ia[0];}
if ($Ia[1]) {sg($objname.'.Ia2',$Ia[1]); $sql['Ia2']=$Ia[1];}
if ($Ia[2]) {sg($objname.'.Ia3',$Ia[2]); $sql['Ia3']=$Ia[2];}
if ($It) {sg($objname.'.IaT',$It); $sql['IaT']=$It;}




# Мощность по фазам
# =====================================================
$ncrc=$this->calcCRC($device252,"081600");
$Pv =$this->merc_gd($socket252,$ncrc, 0.01);

if ( round($Pv[0], 2) != round($Pv[1] + $Pv[2] + $Pv[3], 2) )
	$error = "error"; else $error = "";
$debug .= "Pv: $Pv[0] - $Pv[1] - $Pv[2] - $Pv[3] $error<br>";
if ($error == "")
{
sg($objname.'.PvT',round($Pv[0],0));
sg($objname.'.Pv1',$Pv[1]);
sg($objname.'.Pv2',$Pv[2]);
sg($objname.'.Pv3',$Pv[3]);

$sql['PvT']=round($Pv[0],0);
$sql['Pv1']=$Pv[1];
$sql['Pv2']=$Pv[2];
$sql['Pv3']=$Pv[3];
}
# Cosf по фазам
# =====================================================
$ncrc=$this->calcCRC($device252,"081630");
$Cos = $this->merc_gd($socket252,$ncrc, 0.001);
$debug .= "Cos: $Cos[0] - $Cos[1] - $Cos[2] - $Cos[3]<br>";


if ($Cos[0]) {sg($objname.'.CosT',$Cos[0]); $sql['CosT']=$Cos[0];}
if ($Cos[0]) {sg($objname.'.Cos1',$Cos[1]); $sql['Cos1']=$Cos[1];}
if ($Cos[0]) {sg($objname.'.Cos2',$Cos[2]); $sql['Cos2']=$Cos[2];}
if ($Cos[0]) {sg($objname.'.Cos3',$Cos[3]); $sql['Cos3']=$Cos[3];}

# Напряжение по фазам
# =====================================================
$Uv = $this->merc_gd($socket252,$this->calcCRC($device252,"081611"), 0.01);
$debug .= "Uv: $Uv[0] - $Uv[1] - $Uv[2]<br>";

if ($Uv[0]) {sg($objname.'.Uv1',round($Uv[0],0));$sql['Uv1']=round($Uv[0],0);}
if ($Uv[1]) {sg($objname.'.Uv2',round($Uv[1],0));$sql['Uv2']=round($Uv[1],0);}
if ($Uv[2]) {sg($objname.'.Uv3',round($Uv[2],0));$sql['Uv3']=round($Uv[2],0);}


$arU=array();

if (round($Uv[0],0)) {$arU[1]=round($Uv[0],0);}
if (round($Uv[1],0)) {$arU[2]=round($Uv[1],0);}
if (round($Uv[2],0)) {$arU[3]=round($Uv[2],0);}

 
sg($objname.'.U',round($this->average($arU)));	
$sql['U']=round($this->average($arU));




# Показания электроэнергии
# =====================================================
$Tot = $this->merc_gd($socket252,$this->calcCRC($device252,"050000"), 0.001, 1);
$debug .= "Total: $Tot[0]<br>";
if ($Tot[0]) {sg($objname.'.Total',round($Tot[0],0)); $sql['Total']=round($Tot[0],0);
$sql['TS']=time();
}


$Tot = $this->merc_gd($socket252,$this->calcCRC($device252,"050001"), 0.001, 1);
$debug .= "Total T1: $Tot[0]<br>";
if ($Tot[0]) {sg($objname.'.Total1',$Tot[0]); $sql['Total1']=$Tot[0];}


$Tot = $this->merc_gd($socket252,$this->calcCRC($device252,"050002"), 0.001, 1);
$debug .= "Total T2: $Tot[0]<br>";
if ($Tot[0]) {sg($objname.'.Total2',$Tot[0]);$sql['Total2']=$Tot[0];}




SQLUpdate('mercury_devices',$sql);

SQLexec("update mercury_config set value=UNIX_TIMESTAMP() where parametr='LASTCYCLE_TS'");		   



$debug .= "Закрываем сокет...";
socket_close($socket252);
$debug .= "OK.<br>\n<br>\n";
// Пишем содержимое обратно в файл
file_put_contents($file, $debug);

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




  $data = <<<EOD
 mercury_devices: ID int(10) unsigned NOT NULL auto_increment
 mercury_devices: TITLE varchar(100) NOT NULL DEFAULT ''
 mercury_devices: IPADDR varchar(100) NOT NULL DEFAULT ''
 mercury_devices: PORT varchar(100) NOT NULL DEFAULT ''
 mercury_devices: HEXADR varchar(100) NOT NULL DEFAULT ''
 mercury_devices: MODEL varchar(100) NOT NULL DEFAULT ''
 mercury_devices: SN varchar(100) NOT NULL DEFAULT ''
 mercury_devices: FIO varchar(100) NOT NULL DEFAULT ''
 mercury_devices: STREET varchar(100) NOT NULL DEFAULT ''
 mercury_devices: PHONE varchar(100) NOT NULL DEFAULT ''
 mercury_devices: LOGIN varchar(100) NOT NULL DEFAULT ''
 mercury_devices: PASSWORD varchar(100) NOT NULL DEFAULT ''
 mercury_devices: LASTPING varchar(100) NOT NULL DEFAULT ''
 mercury_devices: ONLINE varchar(100) NOT NULL DEFAULT ''
 mercury_devices: TS varchar(100) NOT NULL DEFAULT ''
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
EOD;
  parent::dbInstall($data);

  $data = <<<EOD
 mercury_config: parametr varchar(300)
 mercury_config: value varchar(10000)  
EOD;
   parent::dbInstall($data);

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
 return $mess.$crc[2].$crc[3].$crc[0].$crc[1];
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
$cachedVoiceDir = ROOT . 'cms/cached/';
$file = $cachedVoiceDir . 'mercurydebug.txt';
// Открываем файл для получения существующего содержимого
$debug .= file_get_contents($file);
$debug .= "Отправляем запрос ".$hex."<br>\n";
  $in = hex2bin($hex);
$debug .=  " ".$in." ";
  socket_write($socket252, $in, strlen($in));
$debug .=  "OK.<br>\n"; 
// Пишем содержимое обратно в файл
file_put_contents($file, $debug);

   }

//////////////////////////////////////////////
//////////////////////////////////////////////
function merc_gd($socket252, $cmd, $factor = 1, $total = 0)
{
$this->send($socket252, $cmd);
$result =$this->read($socket252);

	$ret = array();
	
	$start_byte = 1;
	
	if ( $total != 1 )
	{
		for ( $i = 0; $i < 4; $i++ )
		{
         	if ( dechex(ord($result[$start_byte + $i * 3])) >= 40 )
			$result[$start_byte + $i * 3] = chr(dechex(ord($result[$start_byte + $i * 3])) - 40);
			if ( strlen($result) > $start_byte + 2 + $i * 3 )
			$ret[$i] = hexdec($this->dd($result[$start_byte + $i * 3]).$this->dd($result[$start_byte + $i * 3 + 2]).$this->dd($result[$start_byte + $i * 3 + 1]))*$factor;
		}
	}
	else
		$ret[0] = hexdec($this->dd($result[$start_byte+1]).$this->dd($result[$start_byte]).$this->dd($result[$start_byte+3]).$this->dd($result[$start_byte+2]))*$factor;
	return $ret;
}
//////////////////////////////////////////////
function read  ($socket252)
{
$cachedVoiceDir = ROOT . 'cms/cached/';
$file = $cachedVoiceDir . 'mercurydebug.txt';
// Открываем файл для получения существующего содержимого
$debug .= file_get_contents($file);

$debug .="Читаем ответ:<br>\n";
$out = socket_read($socket252, 2048);
$debug .= bin2hex($out)."<br>\n";
// Пишем содержимое обратно в файл
file_put_contents($file, $debug);

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
