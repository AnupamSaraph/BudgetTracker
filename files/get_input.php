<?php

$req="";
$type_w="";
$req=$_GET['w'];
$type_w=$_GET['type'];
require_once "initilize_connection.php";
mysql_select_db("budget_db1",$con);
if($type_w=="name")
{

	
	$query="select count(*) from projects_table WHERE project_name='$req'";

	$result = mysql_query($query,$con);
	$sd=mysql_fetch_array($result);
	
	if($sd[0]==0)
	{
	echo "{found:false}";
	}
	else
	{
	echo "{found:true}";
	}
}

else if($type_w=="authority"){
		$query="select Distinct project_authority from projects_table WHERE project_authority LIKE '%$req%';";
		$result = mysql_query($query,$con) or die(mysql_error());		
		//echo $query."<br/>";
		$tre="";
		$cnt=0;
      while($d=mysql_fetch_array($result)and ($cnt<6))
      {
	  $cnt++;
      $tre=$tre."'$d[0]',";
	  }
	  
	  $temp_tre=implode(",",explode(",",$tre));
	  $tb=substr($temp_tre,0,strlen("$temp_tre")-1);
	  echo "{data_array:[$tb],count:$cnt}";
	}

else if($type_w=="contractor"){
		$query="select Distinct project_contractor from projects_table WHERE project_contractor LIKE '%$req%';";
		
		$result = mysql_query($query,$con) or die(mysql_error());
		$tre="";
		$cnt=0;
      while($d=mysql_fetch_array($result) and $cnt<6)
      {
      $tre=$tre."'$d[0]',";
      $cnt++;
	  }
	  $ta=implode(",",explode(",",$tre));
	  $tb=substr($ta,0,strlen("$ta")-1);
	  echo "{data_array:[$tb],count:$cnt}";
		
		}

else {
	echo "";
		}

mysql_close($con);
?>