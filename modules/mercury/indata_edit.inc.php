<?php
/*
* @version 0.1 (wizard)
*/
  if ($this->owner->name=='panel') {
   $out['CONTROLPANEL']=1;
  }
  $table_name='mercury_devices';
  $rec=SQLSelectOne("SELECT * FROM $table_name WHERE ID='$id'");

//sg('test.merk',$this->mode);
  if ($this->mode=='update') {
   $ok=1;
  
 
	 global $title;

   $rec['TITLE']=$title;
sg('test.merktitle',$title);

  

	 global $port;
   $rec['PORT']=$port;
/*
   if ($rec['PORT']=='') {
    $out['ERR_PORT']=1;
    $ok=0;
   }
*/

	 global $hexadr;
   $rec['HEXADR']=$hexadr;
/*
   if ($rec['HEXADR']=='') {
    $out['ERR_HEXADR']=1;
    $ok=0;
   }
*/


	 global $ipaddr;
   $rec['IPADDR']=$ipaddr;
/*
   if ($rec['IPADDR']=='') {
    $out['ERR_IPADDR']=1;
    $ok=0;
   }
*/

	 global $model;
   $rec['MODEL']=$model;

	 global $fio;
   $rec['FIO']=$fio;




	 global $phone;
   $rec['PHONE']=$phone;

	 global $street;

   $rec['STREET']=$street;

   
   //UPDATING RECORD
//sg('test.merk',$ok);
   if ($ok) {
    if ($rec['ID']) {
     SQLUpdate($table_name, $rec); // update
    } else {
     $new_rec=1;
     $rec['ID']=SQLInsert($table_name, $rec); // adding new record
    }
    $out['OK']=1;
   } else {
    $out['ERR']=1;
   }
  }

  if (is_array($rec)) {
   foreach($rec as $k=>$v) {
    if (!is_array($v)) {
     $rec[$k]=htmlspecialchars($v);
    }
   }
  }
  outHash($rec, $out);
