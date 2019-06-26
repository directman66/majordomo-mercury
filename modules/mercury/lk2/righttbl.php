<?

$obsh=$userdata['Total1']+$userdata['Total2'];


if ($userdata['Total1']=="") $userdata['Total1']="0";
if ($userdata['Total2']=="") $userdata['Total2']="0";

if ($userdata['Uv1']=="") $userdata['Uv1']="0";
if ($userdata['Uv2']=="") $userdata['Uv2']="0";
if ($userdata['Uv3']=="") $userdata['Uv3']="0";

if ($userdata['PvT']=="") $userdata['PvT']="0";

print '	  			<div class="blank-right">';

print '				

					<div class="p-left p-sh"><b>Показание счетчика:</b></div><div class="p-right">'.round($obsh,2).' кВт</div></p><br>

    				 	<div class="p-left">Тариф 1:</div><div class="p-right">'.round($userdata['Total1'],2).'</div><br>
    				 	<div class="p-left">Тариф 2:</div><div class="p-right">'.round($userdata['Total2'],2).'</div><br>
<br>
					<div class="p-left p-sh"><b>Мгновенные значения:</b></div></p><br>				   
					<div class="p-left">Напряжение и ток на фазе А:</div><div class="p-right">'.round($userdata['Uv1'],2).' В / '.round($userdata['Ia1'],2).' А</div><br>
					<div class="p-left">Напряжение и ток на фазе B:</div><div class="p-right">'.round($userdata['Uv2'],2).' В / '.round($userdata['Ia2'],2).' А</div><br>
					<div class="p-left">Напряжение и ток на фазе C:</div><div class="p-right">'.round($userdata['Uv3'],2).' В / '.round($userdata['Ia3'],2).' А</div><br>
					<div class="p-left">Общая потребляемая мощность:</div><div class="p-right">'.round($userdata['PvT'],2).' Вт</div><br>
 					<div class="p-left">Последний опрос счетчика:</div><div class="p-right">'.date('d-m-Y H:i:s',$userdata['TS']).'</div>
					<div style="clear:both"></div>
					</div>
';
