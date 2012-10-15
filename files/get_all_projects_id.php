<?php
{
$sw_lat=$_GET['sw_lat'];
$sw_lng=$_GET['sw_lng'];
$ne_lat=$_GET['ne_lat'];
$ne_lng=$_GET['ne_lng'];
$count_of_found_projects=0;
$return_array=array();

}
header("Content-type: text/xml");
echo "<?xml version='1.0' ?>";
echo "<xml_data>";
{
require_once "initilize_connection.php";
mysql_select_db("budget_db1",$con);
$query="select * from projects_table";
$result_set=mysql_query($query,$con);

while($result_array=mysql_fetch_array($result_set))
{

$p_id=$result_array["project_id"];
$query1="select * from projects_map_data_table where project_id=$p_id";
$result_set1=mysql_query($query1);
while($result_array1=mysql_fetch_array($result_set1))
{
$shape_id=$result_array1["shape_id"];
$shape_type=$result_array1["shape_type"];
$is_there=false;
switch($shape_type)
{
case "circle":
				 $is_there=check_circle($shape_id,$con,$sw_lat,$sw_lng,$ne_lat,$ne_lng,$return_array);
				 break;
				
case "rectangle":
				$is_there=check_rectangle($shape_id,$con,$sw_lat,$sw_lng,$ne_lat,$ne_lng,$return_array);
				break;
				
case "polyline":
                $is_there=check_polyline($shape_id,$con,$sw_lat,$sw_lng,$ne_lat,$ne_lng,$return_array);
				break;
}
if($is_there)
{
  $return_array["$p_id"]="is there";
  break;
}
}

}
mysql_close($con);
//write data here

echo "<bound_data>";
echo "<south_west><lat>$sw_lat</lat><lng>$sw_lng</lng></south_west>";
echo "<north_east><lat>$ne_lat</lat><lng>$ne_lng</lng></north_east>";
echo "</bound_data>";
echo "<project_ids>";
foreach($return_array as $i=>$v)
{
echo "<project_id>".$i."</project_id>";
}
echo "</project_ids>";
echo "</xml_data>";


}

function check_circle($id,$con,$sw_lat,$sw_lng,$ne_lat,$ne_lng,$return_array)
{
//$con=mysql_connect("localhost:65452","anuj","anuj");
$query_circle="select * from map_data_shapes_circle_table where circle_id=$id";
$result_circle=mysql_query($query_circle,$con);
$circle_array=mysql_fetch_array($result_circle);
$center_id=$circle_array["center_lat_lng_id"];
$circle_radius=$circle_array["radius"];

$lat_query="select * from lat_lng_table where lat_lng_id=$center_id";
$result_circle=mysql_query($lat_query);
$circle_array=mysql_fetch_array($result_circle);
$center_lat=$circle_array["lat"];
$center_lng=$circle_array["lng"];
if(compare_lat_lng_in_bound($sw_lat,$sw_lng,$ne_lat,$ne_lng,$center_lat,$center_lng))
{
return true;
}
return false;
}
function check_rectangle($id,$con,$sw_lat,$sw_lng,$ne_lat,$ne_lng,$return_array)
{
$query_rectangle="select * from map_data_shapes_rectangle_table where rectangle_id=$id";
$result_rect=mysql_query($query_rectangle,$con);
$rect_array=mysql_fetch_array($result_rect);
$rect_id=$rect_array["rectangle_id"];
$sw_id=$rect_array["south_west_lat_lng_id"];
$ne_id=$rect_array["north_east_lat_lng_id"];

$query_rectangle="select * from lat_lng_table where lat_lng_id=$sw_id";
$result_rect=mysql_query($query_rectangle,$con);
$rect_array=mysql_fetch_array($result_rect);
$rect_sw_lat=$rect_array["lat"];
$rect_sw_lng=$rect_array["lng"];
if(compare_lat_lng_in_bound($sw_lat,$sw_lng,$ne_lat,$ne_lng,$rect_sw_lat,$rect_sw_lng))
{
return true;
}
$query_rectangle="select * from lat_lng_table where lat_lng_id=$ne_id";
$result_rect=mysql_query($query_rectangle,$con);
$rect_array=mysql_fetch_array($result_rect);
$rect_ne_lat=$rect_array["lat"];
$rect_ne_lng=$rect_array["lng"];
if(compare_lat_lng_in_bound($sw_lat,$sw_lng,$ne_lat,$ne_lng,$rect_ne_lat,$rect_ne_lng))
{
$return_array["$id"]=array("id"=>$id,"lat"=>$rect_ne_lat,"lng"=>$rect_ne_lng);
return true;
}

return false;
}
function check_polyline($id,$con,$sw_lat,$sw_lng,$ne_lat,$ne_lng,$return_array)
{
$poly_query="select * from map_data_shapes_poly_array_table where poly_id=$id";
$result_poly=mysql_query($poly_query,$con);
while($poly_array=mysql_fetch_array($result_poly))
{
$poly_lat_lng_id=$poly_array["lat_lng_id"];
$poly_query="select * from lat_lng_table where lat_lng_id=$poly_lat_lng_id";
$result_of=mysql_query($poly_query,$con);
$result_ss=mysql_fetch_array($result_of);
if(compare_lat_lng_in_bound($sw_lat,$sw_lng,$ne_lat,$ne_lng,$result_ss["lat"],$result_ss["lng"]))
{
return true;
}
}
return false;
}

function compare_lat_lng_in_bound($sw_lat,$sw_lng,$ne_lat,$ne_lng,$lat,$lng)
{
$x1=$ne_lat;
$y1=$ne_lng;
$x2=$sw_lat;
$y2=$sw_lng;
$a=$lat;
$b=$lng;
if($x1>$x2)
{
if($a<$x1)
{
if($a>$x2)
{
  if($y1>$y2)
  {
     if($b<$y1)
	 {
	    if($b>$y2)
		{
          return true;
		}
		else
		{
		return false;
		}
	 }
	 else
	 {
	 return false;
	 }
  }
}
else
{
return false;
}
}
else
{
return false;
}
}
echo "<error>true</error>";
return false;
 
}
?>