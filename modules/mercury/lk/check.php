<?
// Скрипт проверки

// Соединямся с БД

//$link=mysqli_connect("localhost", EXT_ACCESS_USERNAME, "EXT_ACCESS_PASSWORD", "testtable");
//echo 'coockie_login:'.$_COOKIE['login']."<br>";
//echo 'coockie_hash:'.$_COOKIE['hash']."<br>";
//echo 'coockie_id:'.$_COOKIE['id']."<br>";
//echo '$_SERVER[REMOTE_ADDR]:'.$_SERVER['REMOTE_ADDR']."<br>";
$link=mysqli_connect('localhost', 'pi', '', 'db_123456');
//echo "________________";

if (isset($_COOKIE['login']) and isset($_COOKIE['hash']))
{
    $query = mysqli_query($link, "SELECT * FROM mercury_devices WHERE LOGIN = '".$_COOKIE['login']."' LIMIT 1");
    $userdata = mysqli_fetch_assoc($query);
//echo $userdata['USERHASH'].'<br>';
//echo $userdata['LOGIN'].'<br>';
//echo $userdata['USERIP'].'<br>';


    if(($userdata['USERHASH']!== $_COOKIE['hash']) or ($userdata['LOGIN'] !== $_COOKIE['login'])
 or (($userdata['USERIP'] !== $_SERVER['REMOTE_ADDR'])  and ($userdata['USERIP'] !== "0")))
    {
        setcookie("id", "", time() - 3600*24*30*12, "/");
        setcookie("hash", "", time() - 3600*24*30*12, "/");
        print "Хм, что-то не получилось";
    }
    else
    {
        print "Доброе время суток! Пользователь:".$userdata['FIO']."(". $userdata['STREET'].")<br><br>";

        print "По данным системы мониторинга, потребление вашего счетчика составляет  ".$userdata['PvT'] .' Вт, напряжение на фазах '.$userdata['U']." В, измеренная сила тока ".$userdata['IaT'].' A.<br><br>';
        print "Регистры счетчика: Тариф 1: ".$userdata['Total1'].", Тариф 2: ".$userdata['Total2'] .'<br><br>';
        print "Расходы за день: ".$userdata['DAY_WATT']." Вт /  ".$userdata['DAY_RUB'] .' руб.<br>';
        print "Расходы за неделю: ".$userdata['WEEK_WATT']." Вт /  ".$userdata['WEEK_RUB'] .'руб.<br>';
        print "Расходы за месяц: ".$userdata['MONTH_WATT']." Вт /  ".$userdata['MONTH_RUB'] .'руб.<br>';
        print "Расходы за год: ".$userdata['YEAR_WATT']." Вт /  ".$userdata['YEAR_RUB'] .'руб.<br>';

    }
}
else
{
    print "Включите куки";
}
?>
