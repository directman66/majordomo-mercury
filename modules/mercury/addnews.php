<?

chdir(dirname(__FILE__) . '/../..');
include_once("./config.php");
include_once("./lib/loader.php");
include_once("./modules/application.class.php");
include_once("./load_settings.php");


debmes('Пришла новая новость', 'mercury');

$link=mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);


if(isset($_POST['addnews']))
{
$tema=$_REQUEST['tema'];
$message=$_REQUEST['message'];
debmes('Добавление новой новости '.$news, 'mercury');


$news = SQLSelect("SELECT * FROM mercury_news");
{
$news['data']=date('Y-m-d H:i:s');;
$news['message']=$tema;
$news['TITLE']=$message;
SQLInsert('mercury_news', $news);	
 }

}

echo "
<html>
  <head>
   <meta http-equiv='Refresh' content='0; URL=".$_SERVER['HTTP_REFERER']."'>
  </head>
</html>";
