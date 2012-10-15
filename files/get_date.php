<?php
//echo '<select id="date_day_selector_in">';

$type_d=$_GET['month'];
$type_v=(int)$_GET['year'];
$month_no=array("january"=>1,"feburary"=>2,"march"=>3,"april"=>4,"may"=>5,"june"=>6,"july"=>7,"august"=>8,"september"=>9,"october"=>10,"november"=>11,"december"=>12);
$type_d=$month_no[$type_d];
for($st=27;$st<32;$st++)
{
if(checkdate($type_d,$st,$type_v))
{
$last_d=$st;
}
else
break;
}
echo '{days:[';
$temp_d="";
for($kk=1;$kk<=$last_d;$kk++)
{
$temp_d=$temp_d.'"'.$kk.'"'.',';
}
$temp_d=substr($temp_d,0,-1);
echo $temp_d;
echo ']}';
?>