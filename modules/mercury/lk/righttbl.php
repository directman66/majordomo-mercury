<?

$obsh=$userdata['Total1']+$userdata['Total2'];


if ($userdata['Total1']=="") $userdata['Total1']="0";
if ($userdata['Total2']=="") $userdata['Total2']="0";

if ($userdata['Uv1']=="") $userdata['Uv1']="0";
if ($userdata['Uv2']=="") $userdata['Uv2']="0";
if ($userdata['Uv3']=="") $userdata['Uv3']="0";

if ($userdata['PvT']=="") $userdata['PvT']="0";

print '
	<div class="card border-dark mb-3">
		<div class="card-body text-dark">			
			<div class="row">
				<h5 class="card-title mr-auto">Показание счетчика:</h5>
				<h5 class="card-title">'.round($obsh,2).' кВт</h5>
			</div><hr>
			<div class="row">
				<p class="card-title mr-auto">Тариф 1</p>
				<h6 class="card-title">'.round($userdata['Total1'],2).' кВт</h6>
			</div>						
			<div class="row">
				<p class="card-title mr-auto">Тариф 2</p>
				<h6 class="card-title">'.round($userdata['Total2'],2).' кВт</h6>
			</div>	
		<h5 class="card-title">Мгновенные значения:</h5><hr>
			<div class="row">
				<p class="card-title mr-auto">Напряжение и ток на фазе А:</p>
				<h6 class="card-title">'.round($userdata['Uv1'],2).' В / '.round($userdata['Ia1'],2).' А</h6>
			</div>						
			<div class="row">
				<p class="card-title mr-auto">Напряжение и ток на фазе B:</p>
				<h6 class="card-title">'.round($userdata['Uv2'],2).' В / '.round($userdata['Ia2'],2).' А</h6>
			</div>	
			<div class="row">
				<p class="card-title mr-auto">Напряжение и ток на фазе C:</p>
				<h6 class="card-title">'.round($userdata['Uv3'],2).' В / '.round($userdata['Ia3'],2).' А</h6>
			</div>	
			<div class="row">
				<p class="card-title mr-auto">Общая потребляемая мощность:</p>
				<h6 class="card-title">'.round($userdata['PvT'],2).' Вт</h6>
			</div>	
			<div class="row">
				<p class="card-title mr-auto">Последний опрос счетчика:</p>
				<h6 class="card-title">'.date('d-m-Y H:i:s',$userdata['TS']).'</h6>
			</div>							
		</div>
	</div>
';
