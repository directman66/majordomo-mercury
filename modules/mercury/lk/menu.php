<?php
   print '

<!doctype html>
<html lang="ru">
	<head>
		<meta charset="utf-8" />
		<title>Личный кабинет</title>	
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="css\style.css">
		<link rel="stylesheet" href="bootstrap4\dist\css\bootstrap.min.css" >
		<script src="bootstrap4\dist\js\jquery.min.js"></script>
		<script src="bootstrap4\dist\js\popper.min.js"></script>
		<script src="bootstrap4\dist\js\bootstrap.min.js"></script>
	</head>
	<body>

		<nav class="navbar navbar-expand-md navbar-dark bg-success fixed-top">
		  <a class="navbar-brand d-md-none d-xl-inline-block d-lg-none" href="#">Личный кабинет</a>
		  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		  </button>

		  <div class="collapse navbar-collapse" id="navbarsExampleDefault">
			<ul class="navbar-nav mr-auto">
			  <li class="nav-item active">
			   <img src="img/s1.png" class="float-left">
				<a class="nav-link" href="?viewmode=elec">Мой счетчик электроэнергии<span class="sr-only">(current)</span></a>
			  </li>
			  <li class="nav-item">
			   <img src="img/s2.png" class="float-left">
				<a class="nav-link pull-right" href="?viewmode=made">Насосные станции<span class="sr-only">(current)</span></a>
			  </li>
			   <li class="nav-item">
			   <img src="img/s3.png" class="float-left">
				<a class="nav-link pull-right" href="?viewmode=made">Резервный электрогенератор<span class="sr-only">(current)</span></a>
			  </li>
			  <li class="nav-item">
			   <img src="img/s4.png" class="float-left">
				<a class="nav-link pull-right" href="?viewmode=made">Трансформаторная подстанция<span class="sr-only">(current)</span></a>
			  </li>
			</ul>
			<div>
			  <ul class="navbar-nav mr-auto">
			  <li class="nav-item dropdown">
				<a class="nav-link" href="#" id="dropdown01" data-toggle="dropdown" aria-expanded="false"><div class="row">';
print '	
<div class="d-md-none d-lg-flex">';
	if (!$userdata['PREDSED']=='1') {
		print 'Пользователь: '.$userdata['FIO'];
	} 
	else
	{  
		print 'Пользователь: '.$userdata['FIO'].' (председатель) ';
	} 				
	print'
				  <br>Логин: '.$userdata['LOGIN'].'
				  </div>
				  <div class="dropdown-toggle pull-right"></div></div>
				</a>
				<div class="dropdown-menu dropdown-menu-xl-right dropdown-menu-lg-right dropdown-menu-md-right" style="min-width:300px;" aria-labelledby="dropdown01">
				  <h6 class="dropdown-header">Счетчик электроэнергии</h6>
				  <div class="px-4">
					<label>Меркурий '.$userdata['MODEL'].'</label>
				  </div>
				  <h6 class="dropdown-header">Серийный номер</h6>
				  <div class="px-4">
					<label>'.$userdata['SN'].'</label>
				  </div>
				  <h6 class="dropdown-header">Дата производства счетчика</h6>
				  <div class="px-4">
					<label>'.$userdata['MADEDT'].'</label>
				  </div>
				  
				  <div role="separator" class="dropdown-divider"></div>
				  <a class="dropdown-item" href="cpwd.php?login='.$userdata['LOGIN'].'">Сменить пароль</a>
				  <a class="dropdown-item text-danger" href="index.php">Выход</a>
				</div>
			  </li>
			  </ul>
			</div>
		  </div>
		</nav>		
';

