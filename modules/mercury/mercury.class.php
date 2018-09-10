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
 if ($this->view_mode=='indata_del') {
   $this->delete($this->id);
   $this->redirect("?data_source=$this->data_source&view_mode=node_edit&id=$pid&tab=indata");
 }	



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
 function getpu() {
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
SQLExec("delete from methods where class_id = (select id from classes where title = 'Mercury')");	 
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
EOD;
  parent::dbInstall($data);

  $data = <<<EOD
 mercury_config: parametr varchar(300)
 mercury_config: value varchar(10000)  
EOD;
   parent::dbInstall($data);

$par['parametr'] = 'EVERY';
$par['value'] = 30;		 
SQLInsert('mercury_config', $par);				
	
$par['parametr'] = 'LASTCYCLE_TS';
$par['value'] = "0";		 
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
