<?
print '
<html lang="ru">
	<head>
		<meta charset="utf-8" />
		<title>Личный кабинет</title>	
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="bootstrap4\dist\css\bootstrap.min.css" >
		<script src="bootstrap4\dist\js\jquery.min.js"></script>
		<script src="bootstrap4\dist\js\popper.min.js"></script>
		<script src="bootstrap4\dist\js\bootstrap.min.js"></script>
	</head>
	<body>
';

print '<header class="navbar navbar-expand-md fixed-top bg-success">
  <div class="container d-flex justify-content-center">
    <span class="text-white">Система мониторинга инженерных сетей ТСН «Морской Берег»</span>
  </div>
</header>
';

//$sql="SELECT * FROM mercury_devices WHERE LOGIN = '".$_COOKIE['login']."'";
//	$query= mysqli_query($db,$sql);
//    	$userdata = mysqli_fetch_assoc($query);

print '<style>
html,
body {
  height: 100%;
}

body {
  display: flex;
  align-items: center;
  padding-top: 40px;
  padding-bottom: 40px;
  background-color: #f5f5f5;
}

.form-signin {
  width: 100%;
  max-width: 330px;
  padding: 15px;
  margin: auto;
}
.form-signin .checkbox {
  font-weight: 400;
}
.form-signin .form-control {
  position: relative;
  box-sizing: border-box;
  height: auto;
  padding: 10px;
  font-size: 16px;
}
.form-signin .form-control:focus {
  z-index: 2;
}
</style>';

print '<form action="/modules/mercury/cpwde.php" method="post" class="form-signin" enctype="multipart/form-data" name="frmEdit" align=center>
  <!--img class="mb-4" src="img/mercury.png" alt="" width="72" height="72"-->
  <h1 class="h3 mb-3 font-weight-normal">Логин: '.$_GET['login'].'</h1>
  <div id="errorShow"></div>
  <label for="inputEmail" class="sr-only">Старый пароль</label>
  <input name="oldpwd" type="password" id="inputEmail" class="form-control" placeholder="Старый пароль" required autofocus>
  
  <label for="inputEmail2" class="sr-only">Новый пароль</label>
  <input name="newpwd1" type="password" id="inputEmail2" class="form-control" placeholder="Новый пароль" required >
  
  <label for="inputEmail3" class="sr-only">Новый пароль (повторно)</label> 
  <input name="newpwd2" type="password" id="inputEmail3" class="form-control" placeholder="Новый пароль (повторно)" required >
    
  
  <input type="hidden" name="login" value="'.$_GET['login'].'">
  <!--div class="checkbox mb-3">
    <label>
      <input type="checkbox" value="remember-me"> Remember me
    </label>
  </div-->
  <button class="btn btn-lg btn-success btn-block" name="submit" type="submit">Применить</button>
  <!--p class="mt-5 mb-3 text-muted">&copy; 2017-{{< year >}}</p-->
</form>';

