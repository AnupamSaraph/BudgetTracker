<?php
/*
cookies,authentication,session creation
*/
session_start();
$s_id=session_id();
$collected_data=array();
$data_type=$_POST['data_type'];

if($data_type=="map")
{
 $_SESSION["$s_id"]["test"]="true";
 $shape_type=$_POST['shape_type'];
 $index_no_of_shape=$_POST['index_no'];

 switch($shape_type)
 {
  case "circle":
         $center_lat=$_POST['center_lat'];
		 $center_lng=$_POST['center_lng'];
		 $radius=$_POST['radius'];
		  $c=$_SESSION["$s_id"]['count_of_shape'];
          $c++;
         $_SESSION["$s_id"]['count_of_shape']=$c;
		 $_SESSION["$s_id"]["map_data"]["$index_no_of_shape"]=array("shape"=>"circle","center"=>array("lat"=>"$center_lat","lng"=>"$center_lng"),"radius"=>"$radius");
     	break;
  case "rectangle":
             $sw_lat=$_POST['south_west_lat'];
		     $sw_lng=$_POST['south_west_lng'];
		     $ne_lat=$_POST['north_east_lat'];
	    	 $ne_lng=$_POST['north_east_lng'];
			 $c=$_SESSION["$s_id"]['count_of_shape'];
          $c++;
         $_SESSION["$s_id"]['count_of_shape']=$c;
         $_SESSION["$s_id"]["map_data"]["$index_no_of_shape"]=array("shape"=>"rectangle","south_west"=>array("lat"=>"$sw_lat","lng"=>"$sw_lng"),"north_east"=>array("lat"=>"$ne_lat","lng"=>"$ne_lng"));
        break;
  case "polyline":
         $grid_no=$_POST['grid_index_no'];
         $first_lat=$_POST['first_lat'];
		 $first_lng=$_POST['first_lng'];
		 $second_lat=$_POST['second_lat'];
		 $second_lng=$_POST['second_lng'];
		 $third_lat=$_POST['third_lat'];
		 $third_lng=$_POST['third_lng'];
		 $fourth_lat=$_POST['fourth_lat'];
		 $fourth_lng=$_POST['fourth_lng'];
		 
		 if(!(isset($_SESSION["$s_id"]["map_data"]["$index_no_of_shape"])))
		 {
		 $_SESSION["$s_id"]["map_data"]["$index_no_of_shape"]=array("shape"=>"polyline","data"=>array(),"count"=>0 );
		 $c=$_SESSION["$s_id"]['count_of_shape'];
          $c++;
         $_SESSION["$s_id"]['count_of_shape']=$c;
		 }
		 $_SESSION["$s_id"]["map_data"]["$index_no_of_shape"]["data"]["$grid_no"]=array("first"=>array("lat"=>"$first_lat","lng"=>"$first_lng"),"second"=>array("lat"=>"$second_lat","lng"=>"$second_lng"),"third"=>array("lat"=>"$third_lat","lng"=>"$third_lng"),"fourth"=>array("lat"=>"$fourth_lat","lng"=>"$fourth_lng"));
		 $_SESSION["$s_id"]["map_data"]["$index_no_of_shape"]["count"]++;
			
//$_SESSION["s_id"]["map_data"]["$index_no_of_shape"]["data"]["$grid_no"]=array("first"=>array("lat"=>"$first_lat","lng"=>"$first_lng"),"second"=>array("lat"=>"$second_lat","lng"=>"$second_lng"),"third"=>array("lat"=>"$third_lat","lng"=>"$third_lng"),"fourth"=>array("lat"=>"$fourth_lat","lng"=>"$fourth_lng"));
		 
		break;
 case "polygon":
 break;
 }
 //$_SESSION["$s_id"]["map_data"]["$_index_no_of_shape"]=array();
 echo "map";
}
else if($data_type=="project")
{
   $count_data=$_POST['data_count'];
   $_SESSION["$s_id"]['project_data']['data_count']=$_POST['data_count'];
   $_SESSION["$s_id"]["project_data"]["data"]=array();
   for($ccnt=0;$ccnt<$count_data;$ccnt++)
   {
     $ns=$ccnt."name";
     $name_s=$_POST["$ns"];
	 $vs=$ccnt."value";
	 $value_s=$_POST["$vs"];
	 $ds=$ccnt."data_type";
	 $data_type_s=$_POST["$ds"];
     $_SESSION["$s_id"]["project_data"]["data"]["$ccnt"]=array("name"=>"$name_s","value"=>"$value_s","data_type"=>"$data_type_s");
   }
    echo $_POST['data_type'];
}

else if($data_type=="none")
{
//generate session id here
echo "{session_id:'".session_id()."'}";


if(isset($_SESSION["$s_id"]))
{
unset($_SESSION["$s_id"]);
}

$_SESSION["$s_id"]=array("count_of_shape"=>0, "map_data"=>array(),"project_data"=>array("project_name"=>"",""=>"","project_authority"=>"","project_contractor"=>"","project_estimated_cost"=>"","project_incurred_cost"=>"","project_start_date"=>"","project_end_date"=>"","info_created_on"=>"","project_categories"=>"","project_contract_date"=>"","project_description"=>""));
}
else if($data_type=="final")
{
//save session data to database and return true
//1 for successful and 0 for otherwise
$return_status=0;
require_once "initilize_connection.php";
//$con=mysql_connect("localhost:65452","anuj","anuj");
mysql_select_db("budget_db1",$con);
$query="";

for($iik=0;$iik<$_SESSION["$s_id"]["project_data"]['data_count'];$iik++)
{
$vv=$_SESSION["$s_id"]['project_data']["data"]["$iik"];
if($vv["data_type"]=="date")
{
$tempoo=$vv["value"];
$month_no=array("january"=>1,"feburary"=>2,"march"=>3,"april"=>4,"may"=>5,"june"=>6,"july"=>7,"august"=>8,"september"=>9,"october"=>10,"november"=>11,"december"=>12);
$temp_d=explode("-",$tempoo);
$tempoo=$temp_d[0]."-".$month_no[$temp_d[1]]."-".$temp_d[2];
$vv["value"]=$tempoo;
}
switch($vv["name"])
{
case "name":$project_name=$vv["value"]; break;
case "authority" :$project_authority=$vv["value"]; break;
case "contractor" :$project_contractor=$vv["value"]; break;
case "cost_estimated" :$project_estimated_cost=$vv["value"]; break;
case "cost_estimated" :$project_estimated_cost=$vv["value"]; break;
case "cost_incurred" :$project_incurred_cost=$vv["value"]; break;
case "date_of_contract" :$project_contract_date=$vv["value"]; break;
case "start_date": $project_start_date=$vv["value"]; break;
case "end_date" :$project_end_date=$vv["value"]; break;
case "description" :$project_description=$vv["value"]; break;
case "category" : $project_categories=$vv["value"]; break;
default :break;
}

}
$info_created_on=date("Y-m-d");
$query="insert into projects_table(project_name,information_created_by_user_id,project_authority,project_contractor,project_estimated_cost,project_incurred_cost,project_start_date,project_end_date,info_created_on,project_categories,project_contract_date,project_description) values(\"$project_name\",1,\"$project_authority\",\"$project_contractor\",\"$project_estimated_cost\",\"$project_incurred_cost\",\"$project_start_date\",\"$project_end_date\",\"$info_created_on\",\"$project_categories\",\"$project_contract_date\",\"$project_description\")";
if(mysql_query($query,$con))
{
$return_status=1;
$kkk=mysql_query("select last_insert_id() from projects_table",$con);
$vaa=mysql_fetch_array($kkk);
$proj_id=$vaa[0];
}
else
{
$return_status=132;
}
//get the id of prject data inserted

//////////save all the shapes

for($dd=1;$dd<=$_SESSION["$s_id"]['count_of_shape'];$dd++)
{
$d=$dd-1;
switch($_SESSION["$s_id"]['map_data']["$d"]["shape"])
{
case "circle":
         //get projects id ,
        //insert center point in lat lng then using that id insert circle data
        $lat=$_SESSION["$s_id"]['map_data']["$d"]["center"]["lat"];
        $lng=$_SESSION["$s_id"]['map_data']["$d"]["center"]["lng"]; 
        $radius=$_SESSION["$s_id"]['map_data']["$d"]["radius"];
        
        $query="insert into lat_lng_table(lat,lng) values($lat,$lng)"; 
		mysql_query($query,$con);
		$kkk=mysql_query("select last_insert_id() from lat_lng_table",$con);
        $vaa=mysql_fetch_array($kkk);
		$lat_lng_id=$vaa[0];
        $query="insert into map_data_shapes_circle_table(radius,center_lat_lng_id) values($radius,$lat_lng_id)";
		mysql_query($query,$con);
		$kkk=mysql_query("select last_insert_id() from map_data_shapes_circle_table",$con);
        $vaa=mysql_fetch_array($kkk);
		$circle_id=$vaa[0];
        $query="insert into projects_map_data_table(project_id,shape_type,shape_id,created_on) values($proj_id,'circle',$circle_id,now())";
        mysql_query($query,$con);
		break;
case "rectangle":

        $sw_lat=$_SESSION["$s_id"]['map_data']["$d"]["south_west"]["lat"];
        $sw_lng=$_SESSION["$s_id"]['map_data']["$d"]["south_west"]["lng"];
        $ne_lat=$_SESSION["$s_id"]['map_data']["$d"]["north_east"]["lat"];
        $ne_lng=$_SESSION["$s_id"]['map_data']["$d"]["north_east"]["lng"];
        $query="insert into lat_lng_table(lat,lng) values($sw_lat,$sw_lng)";
		mysql_query($query,$con);
		$kkk=mysql_query("select last_insert_id() from lat_lng_table",$con);
        $vaa=mysql_fetch_array($kkk);
		$sw_lat_id=$vaa[0];
        $query="insert into lat_lng_table(lat,lng) values($ne_lat,$ne_lng)";
		mysql_query($query,$con);
		$kkk=mysql_query("select last_insert_id() from lat_lng_table",$con);
        $vaa=mysql_fetch_array($kkk);
        $ne_lat_id=$vaa[0];
		$query="insert into map_data_shapes_rectangle_table(south_west_lat_lng_id,north_east_lat_lng_id) values($sw_lat_id,$ne_lat_id)";
		mysql_query($query,$con);
		$kkk=mysql_query("select last_insert_id() from map_data_shapes_rectangle_table",$con);
        $vaa=mysql_fetch_array($kkk);
		$rect_id=$vaa[0];
		$query="insert into projects_map_data_table(project_id,shape_type,shape_id,created_on) values($proj_id,'rectangle',$rect_id,now())";
		mysql_query($query,$con);

        break;
	
case "polyline":
        
        $temp_array=$_SESSION["$s_id"]['map_data']["$d"]["data"];
		$grid_no=0;
		$count=$_SESSION["$s_id"]['map_data']["$d"]["count"];
		$query="insert into map_data_shapes_poly_table(grid_count)values($count)";
		mysql_query($query);
		$kkk=mysql_query("select last_insert_id() from map_data_shapes_poly_table",$con);
        $vaa=mysql_fetch_array($kkk);
		$poly_id=$vaa[0];
        
        for($jji=0;$jji<$count;$jji++)
		{
		$grid_no=$grid_no+1;
        $f_lat=$temp_array[$jji]["first"]["lat"];
        $f_lng=$temp_array[$jji]["first"]["lng"];
        $s_lat=$temp_array[$jji]["second"]["lat"];
        $s_lng=$temp_array[$jji]["second"]["lng"];
        $t_lat=$temp_array[$jji]["third"]["lat"];
        $t_lng=$temp_array[$jji]["third"]["lng"];
        $fo_lat=$temp_array[$jji]["fourth"]["lat"];
        $fo_lng=$temp_array[$jji]["fourth"]["lng"];
		
        $query="insert into lat_lng_table(lat,lng) values($f_lat,$f_lng)";
		mysql_query($query);
		$kkk=mysql_query("select last_insert_id() from lat_lng_table",$con);
        $vaa=mysql_fetch_array($kkk);
		$fi_id=$vaa[0];
		$query="insert into map_data_shapes_poly_array_table(poly_id,lat_lng_id,grid_no,grid_div_no) values($poly_id,$fi_id,$jji,1)";
		mysql_query($query);
		
		$query="insert into lat_lng_table(lat,lng) values($s_lat,$s_lng)";
		mysql_query($query);
		$kkk=mysql_query("select last_insert_id() from lat_lng_table",$con);
        $vaa=mysql_fetch_array($kkk);
		$se_id=$vaa[0];
		$query="insert into map_data_shapes_poly_array_table(poly_id,lat_lng_id,grid_no,grid_div_no) values($poly_id,$se_id,$jji,2)";
		mysql_query($query);
		
		$query="insert into lat_lng_table(lat,lng) values($t_lat,$t_lng)";
		mysql_query($query);
		$kkk=mysql_query("select last_insert_id() from lat_lng_table",$con);
        $vaa=mysql_fetch_array($kkk);
		$t_id=$vaa[0];
		$query="insert into map_data_shapes_poly_array_table(poly_id,lat_lng_id,grid_no,grid_div_no) values($poly_id,$t_id,$jji,3)";
		mysql_query($query);
		
		$query="insert into lat_lng_table(lat,lng) values($fo_lat,$fo_lng)";
		mysql_query($query);
		$fo_id;  
		$kkk=mysql_query("select last_insert_id() from lat_lng_table",$con);
        $vaa=mysql_fetch_array($kkk);
		$fo_id=$vaa[0];
		$query="insert into map_data_shapes_poly_array_table(poly_id,lat_lng_id,grid_no,grid_div_no) values($poly_id,$fo_id,$jji,4)";
		mysql_query($query);		
		
		}
		$query="insert into projects_map_data_table(project_id,shape_type,shape_id,created_on)values($proj_id,'polyline',$poly_id,now())";	
		mysql_query($query);
        break;
case "polygon": 

break;
}

}

////////////////////


echo $return_status;
//delete all session data
unset($_SESSION['$s_id']);
//then insert shapes data
mysql_close($con);

}
else
{
echo "no data type found";
}
?>