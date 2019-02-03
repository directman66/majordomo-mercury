<?
print '
<html lang="ru">
	<head>
		<meta charset="utf-8" />
		<title>Личный кабинет</title>	
		<link href="css\style.css" rel="stylesheet" type="text/css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

	</head>
	<body>
';

print '		<div class="list">
			<div class="header">
				<div class="title text-g">Система мониторинга инженерных сетей ТСН «Морской Берег»
				</div></div></div>
';

//$sql="SELECT * FROM mercury_devices WHERE LOGIN = '".$_COOKIE['login']."'";
//	$query= mysqli_query($db,$sql);
//    	$userdata = mysqli_fetch_assoc($query);

print '
<center><h2>
Логин: '.$_GET['login'].'<br>


<form action="/modules/mercury/cpwde.php" method="post" enctype="multipart/form-data" name="frmEdit" class="form-horizontal">

Старый &nbsp;&nbsp;пароль&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input name="oldpwd" type="text" required><br>
Новый &nbsp;&nbsp;пароль &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="newpwd1" type="password" required><br>
Новый пароль (повторно)&nbsp;<input name="newpwd2" type="password" required><br>
<input type="hidden" name="login" value="'.$_GET['login'].'">

<input name="submit" type="submit" value="Применить">


</form>

</center></h2>
';
