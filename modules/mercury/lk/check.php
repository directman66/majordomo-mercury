<?
//date_default_timezone_set('Asia/Yekaterinburg');
error_reporting(0);
date_default_timezone_set('Asia/Novosibirsk');



// Скрипт проверки


chdir(dirname(__FILE__) . '/../../..');
//chdir(dirname(__FILE__) . '/../..');
 include_once("./config.php");
// include_once("./lib/loader.php");
// include_once(DIR_MODULES."application.class.php");
// $db = new mysql(DB_HOST, '', DB_USER, DB_PASSWORD, DB_NAME); //connecting to database

$db=mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// include_once("./load_settings.php");

//---------------------------- MySQL

//$db=new mysql(DB_HOST, '', DB_USER, DB_PASSWORD, DB_NAME);

//$link=mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
//$link=new mysql(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
//$db=new mysql(DB_HOST, '', DB_USER, DB_PASSWORD, DB_NAME);
//echo "________________";

if (isset($_COOKIE['login']) and isset($_COOKIE['hash']))
{
$sql="SELECT * FROM mercury_devices WHERE LOGIN = '".$_COOKIE['login']."'";
//$userdata=SQLSelectOne($sql);

  //      echo  $_COOKIE['hash']; 

	$query= mysqli_query($db,$sql);
    	$userdata = mysqli_fetch_assoc($query);


//    $query = mysqli_query($link, "SELECT * FROM mercury_devices WHERE LOGIN = '".$_COOKIE['login']."' LIMIT 1");
//    $userdata = mysqli_fetch_assoc($query);
//echo $userdata['USERHASH'].'<br>';
//echo $userdata['LOGIN'].'<br>';
//echo $userdata['USERIP'].'<br>';


    if(($userdata['USERHASH']!== $_COOKIE['hash']) or ($userdata['LOGIN'] !== $_COOKIE['login'])
// or (($userdata['USERIP'] !== $_SERVER['REMOTE_ADDR'])  and ($userdata['USERIP'] !== "0"))
)
    {
        setcookie("id", "", time() - 3600*24*30*12, "/");
        setcookie("hash", "", time() - 3600*24*30*12, "/");
        print "Хм, что-то не получилось"; 
//print "<br>";
//print $userdata['USERHASH']."=".$_COOKIE['hash'];
//print "<br>";
//print $userdata['LOGIN']." !== ".$_COOKIE['login'];
//print "<br>";
//print $userdata['USERIP']." !== ".$_SERVER['REMOTE_ADDR'];


    }
    else
    {

///echo DIR_MODULES.'mercury/lk3';
		 require(DIR_MODULES.'mercury/lk/menu.php');


if (($_GET['viewmode']=='elec')||(!$_GET['viewmode'])) 
//if (($_GET['viewmode']=='elec')) 

{


if (!$userdata['PREDSED']=='1') {
		  require(DIR_MODULES.'mercury/lk/user.php');
				}

		else 
				{

		  require(DIR_MODULES.'mercury/lk/predsed.php');

			       }
}

else {
		  require(DIR_MODULES.'mercury/lk/made.php');



}}
}
else
{
    print "Включите куки";
}





