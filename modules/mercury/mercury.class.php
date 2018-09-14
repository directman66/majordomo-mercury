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

        if ((time() - gg('cycle_mercuryRun')) < 360*2 ) {
			$out['CYCLERUN'] = 1;
		} else {
			$out['CYCLERUN'] = 0;
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
        if ((time() - gg('cycle_mercuryRun')) < 360*2 ) {
			$out['CYCLERUN'] = 1;
		} else {
			$out['CYCLERUN'] = 0;
		}
	
$now=date();

$cmd_rec = SQLSelectOne("SELECT VALUE FROM mercury_config where parametr='DEBUG'");
$out['MSG_DEBUG']=$cmd_rec['VALUE'];

$cmd_rec = SQLSelectOne("SELECT VALUE FROM milur_config where parametr='CURRENT'");
$out['CURRENT']=$cmd_rec['VALUE'];


 if ($this->view_mode=='get') {
setGlobal('cycle_mercuryControl','start'); 
$this->getdata();
//echo "start"; 
}  

 if (isset($this->data_source) && !$_GET['data_source'] && !$_POST['data_source']) {
  $out['SET_DATASOURCE']=1;
 }

if ($this->view_mode=='getcounters') {
$this->getcounters();
}  
if ($this->view_mode=='getinfo') {
$this->getinfo2();
}  
if ($this->view_mode=='getpu') {
$this->getpu();
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
* milur_devices search
*
* @access public
*/



 
 function processCycle() {
//   $every=$this->config['EVERY'];
 $every=SETTINGS_APPMILUR_EVERY;
$cmd_rec = SQLSelectOne("SELECT VALUE FROM mercury_config where parametr='LASTCYCLE_TS'");
$latest=$cmd_rec['VALUE'];
   $tdev = time()-$latest;
   $has = $tdev>$every*60;
   if ($tdev < 0) {$has = true;}
   
   if ($has) {  

if ($enable==1) {$this->getpu();   }
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
 function getdata() {
$this->getinfo2();
$this->getpu();
$this->getcounters();
}
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
 function getpu($address252, $service_port252,$device252) {
//$ot = $this->object_title;
//setTimeOut($ot.'_updateValue','callMethod("'.$ot.'.GetValues");',10);


/* Создаём сокет TCP/IP. */
$socket252 = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($socket252,SOL_SOCKET, SO_RCVTIMEO, array("sec"=>5, "usec"=>0));
if ($socket252 === false) {
    echo "Не удалось выполнить socket_create(): причина: " . socket_strerror(socket_last_error()) . "<br>";
} else {
    echo "OK.<br>";
}

echo "Пытаемся соединиться с '$address252' на порту '$service_port252'...";
$result = socket_connect($socket252, $address252, $service_port252);
if ($result === false) {
    echo "Не удалось выполнить socket_connect().\nПричина: ($result) " . socket_strerror(socket_last_error($socket)) . "<br>";
} else {
    echo "OK.<br>";
}
if (!function_exists('send')) {
    function send  ($socket252, $hex = "") {
     echo "Отправляем запрос ".$hex;
  $in = hex2bin($hex);
  echo " ".$in." ";
  socket_write($socket252, $in, strlen($in));
  echo "OK.<br>"; 
   }
 }  
//
//function send($socket252, $hex = "")
//{
//  echo "Отправляем запрос ".$hex;
//  $in = hex2bin($hex);
//  echo " ".$in." ";
//  socket_write($socket252, $in, strlen($in));
//  echo "OK.<br>";
//}

if (!function_exists('read')) {
    function read  ($socket252)
{
   echo "Читаем ответ:\n\n";
   $out = socket_read($socket252, 2048);
   echo bin2hex($out)."<br>";
   return $out;
}
}

//function read($socket252)
//{
////   echo "Читаем ответ:\n\n";
///   $out = socket_read($socket252, 2048);
//   echo bin2hex($out)."<br>";
//   return $out;
//}

if (!function_exists('dd')) {
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
     
    }

//function dd($data = "")
//{
//	$result = "";
//	$data2 = "";
//	for ( $j = 0; $j < count($data); $j++ )
//	{
//		$data2 = dechex(ord($data[0]));
//		if ( strlen($data2) == 1  )
//		$result = "0".$data2;
//		else
//		$result .= $data2;

//	}
//	return $result;
//}
if (!function_exists('merc_gd')) {
function merc_gd($socket252, $cmd, $factor = 1, $total = 0)
{
	send($socket252, $cmd);
    $result = read($socket252);

	$ret = array();
	
	$start_byte = 1;
	
	if ( $total != 1 )
	{
		for ( $i = 0; $i < 4; $i++ )
		{
         	if ( dechex(ord($result[$start_byte + $i * 3])) >= 40 )
			$result[$start_byte + $i * 3] = chr(dechex(ord($result[$start_byte + $i * 3])) - 40);
			if ( strlen($result) > $start_byte + 2 + $i * 3 )
			$ret[$i] = hexdec(dd($result[$start_byte + $i * 3]).dd($result[$start_byte + $i * 3 + 2]).dd($result[$start_byte + $i * 3 + 1]))*$factor;
		}
	}
	else
		$ret[0] = hexdec(dd($result[$start_byte+1]).dd($result[$start_byte]).dd($result[$start_byte+3]).dd($result[$start_byte+2]))*$factor;
	return $ret;
}}
if (!function_exists('crc16_modbus')) {
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
}}
if (!function_exists('calcCRC')) {
function calcCRC($device252,$msg)
{
 $mess = $device252.$msg;
 $crc = crc16_modbus($mess);
 return $mess.$crc[2].$crc[3].$crc[0].$crc[1];
}}
 
send($socket252, calcCRC($device252,"0101010101010101"));
read($socket252);

# Сила тока по фазам
# =====================================================
$Ia = merc_gd($socket252,calcCRC($device252,"081621"), 0.001);
$It = $Ia[0] + $Ia[1] + $Ia[2];
echo "Ia: $Ia[0] - $Ia[1] - $Ia[2] IaT:$It<br>";
if ($Ia[0]) $this->setProperty("Ia1",$Ia[0]);
if ($Ia[1]) $this->setProperty("Ia2",$Ia[1]);
if ($Ia[2]) $this->setProperty("Ia3",$Ia[2]);
if ($It) $this->setProperty("IaT",$It);
# Мощность по фазам
# =====================================================
$Pv = merc_gd($socket252,calcCRC($device252,"081600"), 0.01);
if ( round($Pv[0], 2) != round($Pv[1] + $Pv[2] + $Pv[3], 2) )
	$error = "error"; else $error = "";
echo "Pv: $Pv[0] - $Pv[1] - $Pv[2] - $Pv[3] $error<br>";
if ($error == "")
{
$this->setProperty("PvT",round($Pv[0],0));
$this->setProperty("Pv1",$Pv[1]);
$this->setProperty("Pv2",$Pv[2]);
$this->setProperty("Pv3",$Pv[3]);
}
# Cosf по фазам
# =====================================================
$Cos = merc_gd($socket252,calcCRC($device252,"081630"), 0.001);
echo "Cos: $Cos[0] - $Cos[1] - $Cos[2] - $Cos[3]<br>";
if ($Cos[0]) $this->setProperty("CosT",$Cos[0]);
if ($Cos[1]) $this->setProperty("Cos1",$Cos[1]);
if ($Cos[2]) $this->setProperty("Cos2",$Cos[2]);
if ($Cos[3]) $this->setProperty("Cos3",$Cos[3]);
# Напряжение по фазам
# =====================================================
$Uv = merc_gd($socket252,calcCRC($device252,"081611"), 0.01);
echo "Uv: $Uv[0] - $Uv[1] - $Uv[2]<br>";
if ($Uv[0]) $this->setProperty("Uv1",round($Uv[0],0));
if ($Uv[1]) $this->setProperty("Uv2",round($Uv[1],0));
if ($Uv[2]) $this->setProperty("Uv3",round($Uv[2],0));

# Показания электроэнергии
# =====================================================
$Tot = merc_gd($socket252,calcCRC($device252,"050000"), 0.001, 1);
echo "Total: $Tot[0]<br>";
if ($Tot[0]) $this->setProperty("Total",round($Tot[0],0));
$Tot = merc_gd($socket252,calcCRC($device252,"050001"), 0.001, 1);
echo "Total T1: $Tot[0]<br>";
if ($Tot[0]) $this->setProperty("Total1",$Tot[0]);
$Tot = merc_gd($socket252,calcCRC($device252,"050002"), 0.001, 1);
echo "Total T2: $Tot[0]<br>";
if ($Tot[0]) $this->setProperty("Total2",$Tot[0]);

echo "Закрываем сокет...";
socket_close($socket252);
echo "OK.\n\n";

 }
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
 function getinfo2() {
 }
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
 function getcounters() {
}
	

/**
* milur_devices edit/add
*
* @access public
*/
 
/**
* milur_devices delete record
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
$property['DESCRIPTION']='Адрес счетчика'; //   <-----------
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
$property['DESCRIPTION']=''; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'Ia2', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']=''; //   <-----------
SQLUpdate('properties',$property); }


$prop_id=addClassProperty($classname, 'Ia3', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']=''; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'IaT', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']=''; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'IP', 0);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']=''; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'LimitValue', 0);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']=''; //   <-----------
SQLUpdate('properties',$property); }


$prop_id=addClassProperty($classname, 'Port', 0);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Порт'; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'Pv1', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Порт'; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'Pv2', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Порт'; //   <-----------
SQLUpdate('properties',$property); }



$prop_id=addClassProperty($classname, 'Pv3', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Порт'; //   <-----------
SQLUpdate('properties',$property); }



$prop_id=addClassProperty($classname, 'PvT', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Порт'; //   <-----------
SQLUpdate('properties',$property); }



$prop_id=addClassProperty($classname, 'Total', 0);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Порт'; //   <-----------
SQLUpdate('properties',$property); }



$prop_id=addClassProperty($classname, 'Uv1', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Порт'; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'Uv2', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Порт'; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'Uv3', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Порт'; //   <-----------
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
// --------------------------------------------------------------------
	
/*
*
* TW9kdWxlIGNyZWF0ZWQgSmFuIDAzLCAyMDE4IHVzaW5nIFNlcmdlIEouIHdpemFyZCAoQWN0aXZlVW5pdCBJbmMgd3d3LmFjdGl2ZXVuaXQuY29tKQ==
*
*/
