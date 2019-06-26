
<?
error_reporting(0);
// Страница авторизации

// Функция для генерации случайной строки

function generateCode($length=6) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;
    while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0,$clen)];
    }
    return $code;
}

// Соединямся с БД

chdir(dirname(__FILE__) . '/../../..');
//chdir(dirname(__FILE__) . '/../..');
include_once("./config.php");
//include_once("./lib/loader.php");


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

$link=mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if(isset($_POST['submit']))
{
    // Вытаскиваем из БД запись, у которой логин равняеться введенному
//    $query = mysqli_query($link,"SELECT LOGIN, PASSWORD FROM mercury_devices WHERE LOGIN='".mysqli_real_escape_string($link,$_POST['login'])."' LIMIT 1");
$sqll="SELECT LOGIN, PASSWORD FROM mercury_devices WHERE LOGIN='".mysqli_real_escape_string($link,$_POST['login'])."' LIMIT 1";
//echo $sqll;
   $query = mysqli_query($link,$sqll);
    $data = mysqli_fetch_assoc($query);

    // Сравниваем пароли
//    if($data['user_password'] === md5(md5($_POST['password'])))
    if($data['PASSWORD'] ===$_POST['password'])
    {
        // Генерируем случайное число и шифруем его
        $hash = md5(generateCode(10));

//        if(!empty($_POST['not_attach_ip']))
//        {
            // Если пользователя выбрал привязку к IP
            // Переводим IP в строку
            $insip = $_SERVER['REMOTE_ADDR'];
//        }

        // Записываем в БД новый хеш авторизации и IP
//        mysqli_query($link, "UPDATE mercury_devices SET USERIP='$insip', USERHASH='$hash' WHERE LOGIN='".$data['user_id']."'");
$sql="UPDATE mercury_devices SET USERIP='$insip', USERHASH='$hash' WHERE LOGIN='".$_POST['login']."'";
//echo $sql;
        mysqli_query($link, $sql);



        // Ставим куки
        setcookie("id", $data['login'], time()+60*60*24*30);
        setcookie("login", $_POST['login'], time()+60*60*24*30);
        setcookie("hash", $hash, time()+60*60*24*30,null,null,null,true); // httponly 

        // Переадресовываем браузер на страницу проверки нашего скрипта
        header("Location: check.php"); exit();
    }
    else
    {
        //echo "<h2><center>Вы ввели неправильный логин/пароль</center></h2>";
		echo '<script>
		window.onload = function() {
		var string="<div class=\"alert alert-warning\" role=\"alert\">Вы ввели неправильный логин/пароль</div>";
		document.getElementById("errorShow").innerHTML=string;
		}
		</script>';
    }
//Не прикреплять к IP(не безопасно) <input type="checkbox" name="not_attach_ip"><br>
}
?>

<style>
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
.form-signin input[type="login"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}
.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
</style>
<form class="form-signin" method="POST" align=center>
  <!--img class="mb-4" src="img/mercury.png" alt="" width="72" height="72"-->
  <h1 class="h3 mb-3 font-weight-normal">Вход в личный кабинет</h1>
  <div id="errorShow"></div>
  <label for="inputEmail" class="sr-only">Логин</label>
  <input name="login" type="login" id="inputEmail" class="form-control" placeholder="Логин" required autofocus>
  <label for="inputPassword" class="sr-only">Пароль</label>
  <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Пароль" required>
  <!--div class="checkbox mb-3">
    <label>
      <input type="checkbox" value="remember-me"> Remember me
    </label>
  </div-->
  <button class="btn btn-lg btn-success btn-block" name="submit" type="submit">Войти</button>
  <!--p class="mt-5 mb-3 text-muted">&copy; 2017-{{< year >}}</p-->
</form>


