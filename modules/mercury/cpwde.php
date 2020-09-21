<?php

chdir(dirname(__FILE__) . '/../..');
include_once("./config.php");
//include_once("./lib/loader.php");
//include_once("./modules/application.class.php");
//include_once("./load_settings.php");


//debmes('Пришла новая новость', 'mercury');

$link=mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);


$br= "login: ".$_POST['login']." oldpwd: ".$_POST['oldpwd'] ."  newpwd1:".$_POST['newpwd1']." newpwd2:".$_POST['newpwd2']."<br>";

 echo "<script type='text/javascript'>";
 echo "alert('".$br."');";
 echo "</script>";




if(
(isset($_POST['login']))
&& (isset($_POST['oldpwd']))
&& (isset($_POST['newpwd1']))
&& (isset($_POST['newpwd2']))
)
{
if (($_POST['oldpwd']==$_POST['newpwd1'])
&&($_POST['newpwd2']==$_POST['newpwd1']))
{
 echo "<script type='text/javascript'>";
 echo "alert('Старый и новый пароли должны отличаться');";
 echo "</script>";
}

if (($_POST['oldpwd']<>$_POST['newpwd1'])&&
 ($_POST['newpwd2']<>$_POST['newpwd1']))
{
 echo "<script type='text/javascript'>";
 echo "alert('Введеные пароли отличаются');";
 echo "</script>";
}


if (
($_POST['oldpwd']<>$_POST['newpwd1'])
&&($_POST['newpwd2']==$_POST['newpwd1']))
{


$sql="SELECT * FROM mercury_devices WHERE LOGIN = '".$_POST['login']."'";
	$query= mysqli_query($link,$sql);
    	$userdata = mysqli_fetch_assoc($query);
 
    if($userdata['PASSWORD']<> $_POST['oldpwd']) {
 echo "<script type='text/javascript'>";
 echo "alert('Старый пароль не совпадает');";
 echo "</script>";
}
else {

$sql="update mercury_devices set PASSWORD='".$_POST['newpwd1']."' where LOGIN  = '".$_POST['login']."'";
$mysqli= mysqli_query($link,$sql);

 echo "<script type='text/javascript'>";
 echo "alert('Готово');";
 echo "</script>";
}}}
else 
{
 echo "<script type='text/javascript'>";
 echo "alert('Введите все обязательные поля');";
 echo "</script>";
}
//redirect("/modules/mercury/lk");
//header('url=/modules/mercury/lk');
header('Location: /modules/mercury/lk',true, 301);
//echo "<html>  <head>   <meta http-equiv='Refresh' content='0; URL=".$_SERVER['HTTP_REFERER']."'>  </head> </html>";
