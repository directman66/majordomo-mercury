<?

chdir(dirname(__FILE__) . '/../..');
include_once("./config.php");
include_once("./lib/loader.php");
include_once("./modules/application.class.php");
include_once("./load_settings.php");


//debmes('Удаление новости '.$_POST['id'], 'mercury');
debmes('Удаление новости '.$_GET['id'], 'mercury');



$link=mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);


if(isset($_GET['id']))
{
$sql="delete from  mercury_news where id=".$_GET['id'];
debmes($sql, 'mercury');
SQLExec($sql);

}

echo "
<html>
  <head>
   <meta http-equiv='Refresh' content='0; URL=".$_SERVER['HTTP_REFERER']."'>
  </head>
</html>";
