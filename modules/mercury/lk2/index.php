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

$link=mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if(isset($_POST['submit']))
{
    // Вытаскиваем из БД запись, у которой логин равняеться введенному
    $query = mysqli_query($link,"SELECT LOGIN, PASSWORD FROM mercury_devices WHERE LOGIN='".mysqli_real_escape_string($link,$_POST['login'])."' LIMIT 1");
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
        echo "<h2><center>Вы ввели неправильный логин/пароль</center></h2>";
    }
//Не прикреплять к IP(не безопасно) <input type="checkbox" name="not_attach_ip"><br>
}
?>
<center><h2>
<form method="POST">

Логин <input name="login" type="text" required><br>
Пароль <input name="password" type="password" required><br>

<input name="submit" type="submit" value="Войти">
</form>

</center></h2>
