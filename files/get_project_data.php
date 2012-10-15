<?php
header("Content-type: text/xml");
echo "<xml_data>";
$flow_prog=0;
$project_id=0;
$project_id=$_GET['project_id'];
if($project_id=="")
{
echo "none";
echo "<project_id>0</project_id>";
}
else
{
$con=mysql_connect("localhost:65452","anuj","anuj");
mysql_select_db("budget_db1",$con);
$query="select count(*) from projects_table where project_id=$project_id";
$results=mysql_query($query);
$ss=mysql_fetch_array($results);
if($ss[0]==0)
echo "<project_id>-1</project_id>";
else
echo "<project_id>".$project_id."</project_id>";
{
$query="select * from projects_table where project_id=$project_id";
$result_set=mysql_query($query);
$result_array=mysql_fetch_array($result_set);
$p_name=$result_array["project_name"];
$p_category=$result_array["project_categories"];
$p_desc=$result_array["project_description"];
$p_auth=$result_array["project_authority"];
$p_contractor=$result_array["project_contractor"];
$p_estimated_cost=$result_array["project_estimated_cost"];
$p_incurred_cost=$result_array["project_incurred_cost"];
$p_start_date=$result_array["project_start_date"];
$p_end_date=$result_array["project_end_date"];
$p_contract_date=$result_array["project_contract_date"];
}
{
echo "<project_information>";

echo "<column>";
echo "<data_type>string</data_type>";
echo "<name>name</name>";
echo "<value>".$p_name."</value>";
echo "</column>";

echo "<column>";
echo "<data_type>string</data_type>";
echo "<name>authority</name>";
echo "<value>".$p_auth."</value>";
echo "</column>";

echo "<column>";
echo "<data_type>string</data_type>";
echo "<name>description</name>";
echo "<value>".$p_desc."</value>";
echo "</column>";

echo "<column>";
echo "<data_type>string</data_type>";
echo "<name>category</name>";
echo "<value>".$p_category."</value>";
echo "</column>";

echo "<column>";
echo "<data_type>string</data_type>";
echo "<name>contractor</name>";
echo "<value>".$p_contractor."</value>";
echo "</column>";

echo "<column>";
echo "<data_type>number</data_type>";
echo "<name>estimated cost</name>";
echo "<value>".$p_estimated_cost."</value>";
echo "</column>";

echo "<column>";
echo "<data_type>number</data_type>";
echo "<name>incurred cost</name>";
echo "<value>".$p_incurred_cost."</value>";
echo "</column>";

echo "<column>";
echo "<data_type>date</data_type>";
echo "<name>start date</name>";
echo "<value>".$p_start_date."</value>";
echo "</column>";

echo "<column>";
echo "<data_type>date</data_type>";
echo "<name>end date</name>";
echo "<value>".$p_end_date."</value>";
echo "</column>";

echo "<column>";
echo "<data_type>date</data_type>";
echo "<name>contract date</name>";
echo "<value>".$p_contract_date."</value>";
echo "</column>";
echo "</project_information>";
}


$query="select * from projects_map_data_table where project_id=$project_id";
$result_set=mysql_query($query);
echo "<map_information>";
while($result_array=mysql_fetch_array($result_set))
{
$shape=$result_array["shape_type"];
$shape_id=$result_array["shape_id"];
//remove shape_id after completion
echo "<shape><type>".$shape."</type><id>".$shape_id."</id>";
switch($shape)
{
case "circle": 
              {
               $query="select * from map_data_shapes_circle_table where circle_id=$shape_id";
               $circle_result=mysql_query($query);
               $circle_set=mysql_fetch_array($circle_result);
               $radius=$circle_set["radius"];  
			   $center_id=$circle_set["center_lat_lng_id"];
			   $query="select * from lat_lng_table where lat_lng_id=$center_id";
			   $circle_result=mysql_query($query);
			   $circle_set=mysql_fetch_array($circle_result);
			   $center_lat=$circle_set["lat"];
			   $center_lng=$circle_set["lng"];
			   echo "<map_data>";
			   echo "<center>";
			   echo "<lat>".$center_lat."</lat>";
			   echo "<lng>".$center_lng."</lng>";
			   echo "</center>";
			   echo "<radius>".$radius."</radius>";
			   echo "</map_data>";
			   }
			   break;
case "rectangle" :
                  {
                  $query="select * from map_data_shapes_rectangle_table where rectangle_id=$shape_id";
                  $rectangle_result=mysql_query($query);
				  $rectangle_set=mysql_fetch_array($rectangle_result);
				  $sw_id=$rectangle_set["south_west_lat_lng_id"];
				  $ne_id=$rectangle_set["north_east_lat_lng_id"];
				  $query="select * from lat_lng_table where lat_lng_id=$sw_id";
				  $rectangle_result=mysql_query($query);
				  $rectangle_set=mysql_fetch_array($rectangle_result);
				  $sw_lat=$rectangle_set["lat"];
				  $sw_lng=$rectangle_set["lng"];
				  		  
				  $query="select * from lat_lng_table where lat_lng_id=$ne_id";
				  $rectangle_result=mysql_query($query);
				  $rectangle_set=mysql_fetch_array($rectangle_result);
				  $ne_lat=$rectangle_set["lat"];
				  $ne_lng=$rectangle_set["lng"];
				  
				  echo "<map_data>";
				  echo "<north_east><lat>".$ne_lat."</lat><lng>".$ne_lng."</lng></north_east>";
				  echo "<south_west><lat>".$sw_lat."</lat><lng>".$sw_lng."</lng></south_west>";
				  echo "</map_data>";
				  }
                break;
				
case "polyline" : 
                  {
				  $query="select * from map_data_shapes_poly_table where poly_id=$shape_id";
				  $poly_result=mysql_query($query);
				  $poly_set=mysql_fetch_array($poly_result);
				  $grid_count=$poly_set["grid_count"];
				  
				  echo "<map_data>";
				  echo "<grid_count>".$grid_count."</grid_count>";
				  
				  $query="select * from map_data_shapes_poly_array_table where poly_id=$shape_id";
				  $poly_result=mysql_query($query);
				  while($poly_set=mysql_fetch_array($poly_result))
				  {
				    echo "<poly>";
				    $id=$poly_set["id"];
					$lat_lng_id=$poly_set["lat_lng_id"];
					$grid_no=$poly_set["grid_no"]+1;
					$grid_div_no=$poly_set["grid_div_no"];
					$query="select * from lat_lng_table where lat_lng_id=$lat_lng_id";
					$r_poly=mysql_query($query);
					$s_poly=mysql_fetch_array($r_poly);
					$poly_lat=$s_poly["lat"];
					$poly_lng=$s_poly["lng"];
					echo "<grid_no>".$grid_no."</grid_no>";
					echo "<grid_div_no>".$grid_div_no."</grid_div_no>";
					echo "<lat>".$poly_lat."</lat>";
					echo "<lng>".$poly_lng."</lng>";
					echo "</poly>";
				  }
				  
				  }
                  echo "</map_data>";
                break;
				



}

echo "</shape>";
}
echo "</map_information>";

mysql_close($con);
echo "</xml_data>";
}
?>