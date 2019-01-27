<?

chdir(dirname(__FILE__) . '/../../..');
include_once("./config.php");
include_once("./lib/loader.php");
include_once("./modules/application.class.php");


$link=mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if(isset($_POST['addnews']))
{


$news = SQLSelect("SELECT * FROM mercury_news");
{
$news['data']=date('Y-m-d H:i:s');;
$news['message']=$this->message;
$news['TITLE']=$this->tema;
SQLInsert('mercury_news', $news);	
 }

}
