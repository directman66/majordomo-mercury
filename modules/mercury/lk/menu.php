<?
   print '

<!doctype html>
<html lang="ru">
	<head>
		<meta charset="utf-8" />
		<title>Личный кабинет</title>	
		<link href="css\style.css" rel="stylesheet" type="text/css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

	</head>
	<body>
		<div class="list">
			<div class="header">
				<div class="title text-g">Система мониторинга инженерных сетей ТСН «Морской Берег»
				</div>
				<div class="in-header">
					<div class="in-title">
							Личный кабинет
					</div>
					<div class="menu">
						<ul>
							<li><a href="?viewmode=elec"><div class="ico img-ico-1"></div><span>Мой счетчик электроэнергии</span></a><div class="line"></div></li>
							<li><a href="?viewmode=made"><div class="ico img-ico-2"></div><span>Насосные станции</span></a><div class="line"></div></li>
							<li><a href="?viewmode=made"><div class="ico img-ico-3"></div><span>Резервный электрогенератор</span></a><div class="line"></div></li>
							<li><a href="?viewmode=made"><div class="ico img-ico-4"></div><span>Трансформаторная подстанция</span></a><div class="line"></div></li>
						</ul>
						<div class="add"><a href="#"><div class="ico img-ico-add"></div><span>Добавить модуль</span></a></div>
					</div>
					<div style="clear:both"></div>
					

				</div> ';


   print '
				<div class="ld"> ';


if (!$userdata['PREDSED']=='1') {

   print 'Пользователь: <span>'.$userdata['FIO'];} 
else
{   print 'Пользователь: <span>'.$userdata['FIO'].'(председатель) ';} 


print '
</span><br>
					Логин: <span>'.$userdata['LOGIN'].'&nbsp;&nbsp;<a href="cpwd.php?login='.$userdata['LOGIN'].'" title="сменить пароль">сменить пароль</a> &nbsp;&nbsp;<a href="index.php" title="выход">выход</a> 
</span>				
				</div>
				<div class="sh">
					Счетчик электроэнергии: <span>Меркурий '.$userdata['MODEL'].'</span><br>
					Серийный номер: <span>'.$userdata['SN'].'</span><br> ';
//if ($userdata['MADETD']) {
print ' 					Дата производства счетчика: <span>'.$userdata['MADETD'].'</span><br> ';
//}
print '				</div>
';
print '				<div style="clear:both"></div>

';

print '<div style="clear:both"></div> 			</div>  ';
