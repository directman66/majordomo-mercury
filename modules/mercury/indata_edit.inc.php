<?php
/*
* @version 0.1 (wizard)
*/
  if ($this->owner->name=='panel') {
   $out['CONTROLPANEL']=1;
  }
  $table_name='mercury_devices';
  $rec=SQLSelectOne("SELECT * FROM $table_name WHERE ID='$id'");
  if ($this->mode=='update') {
   $ok=1;
  
 
	 global $title;
   $rec['TITLE']=$title;
   if ($rec['TITLE']=='') {
    $out['ERR_TITLE']=1;
    $ok=0;
   }
  

	 global $port;
   $rec['PORT']=$port;
   if ($rec['PORT']=='') {
    $out['ERR_PORT']=1;
    $ok=0;
   }

	 global $hexadr;
   $rec['HEXADR']=$hexadr;
   if ($rec['HEXADR']=='') {
    $out['ERR_HEXADR']=1;
    $ok=0;
   }


	 global $ipaddr;
   $rec['IPADDR']=$ipaddr;
   if ($rec['IPADDR']=='') {
    $out['ERR_IPADDR']=1;
    $ok=0;
   }

	 global $model;
   $rec['MODEL']=$model;
   if ($rec['MODEL']=='') {
    $out['ERR_MODEL']=1;
    $ok=0;
   }

	 global $fio;
   $rec['FIO']=$fio;
   if ($rec['FIO']=='') {
    $out['ERR_FIO']=1;
    $ok=0;
   }

	 global $phone;
   $rec['PHONE']=$phone;
   if ($rec['PHONE']=='') {
    $out['ERR_PHONE']=1;
    $ok=0;
   }
	 global $street;
   $rec['STREET']=$street;
   if ($rec['STREET']=='') {
    $out['ERR_STREET']=1;
    $ok=0;
   }


   global $linked_object;
   $rec['LINKED_OBJECT']=$linked_object;
  
   global $linked_property;
   $rec['LINKED_PROPERTY']=$linked_property;
   
   //UPDATING RECORD
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
