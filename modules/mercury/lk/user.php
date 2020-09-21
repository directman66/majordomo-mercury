<?php
error_reporting(0);
//date_default_timezone_set('Asia/Yekaterinburg');
date_default_timezone_set('Asia/Novosibirsk');

/////////////////////////////////////

print '<div class="container-fluid"> ';
print '<div class="row"> ';


print '<div class="col-lg-4 col-md-6">';
//print ' <div class="container-fluid">';

		  require(DIR_MODULES.'mercury/lk/righttbl.php');
print '</div> ';

//print ' <div class="container-fluid">';
print '<div class="col-lg-4 col-md-6">';

		  require(DIR_MODULES.'mercury/lk/graphuser.php');
print '</div>';



print '<div class="col-lg-4 col-md-6">';
 		  require(DIR_MODULES.'mercury/lk/msgbar.php');
print '</div></div></div>';

print '</div> ';
print '</div> ';

print '	</body></html>'; 


/////////////////////////
/////////////////////////
/////////////////////////
/////////////////////////
/////////////////////////



