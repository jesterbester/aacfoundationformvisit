<?php /* Template Name: Create Visit */


//get_header();
function redirect_member_to_show_visit($email, $salesforce_id)
{
	$query_string=http_build_query(array("email"=>$email, "salesforce_id"=>$salesforce_id));
	header("Location: /school-visit?".$query_string);
	exit;
}
//checks if user is logged in, then determine if email and saleforce_id is get or post
$start =false;
if(!isset($_POST["email"]) || !isset($_POST["salesforce_id"])){
if(!isset($_GET["email"]) || !isset($_GET["salesforce_id"])){
	echo "You are not authorized to view this page";
	exit;
}
$start =true;
}


if($start){
	$email=$_GET["email"];
$salesforce_id=$_GET["salesforce_id"];
}	
else{
	$email=$_POST["email"];
$salesforce_id=$_POST["salesforce_id"];
}


require(dirname(__FILE__).'/libs/google-doc-matcher.php');

$teams=get_aacteam_member($email);
$member_row=null;
//team to correct salesforce_id
for($i=0; $i<count($teams); $i++)
{
	$team = $teams[$i];
	if(isset($team["Salesforce ID"]))
	{
	
		$cmp_salesforce_id=$team["Salesforce ID"];
		
	
		if($salesforce_id==$cmp_salesforce_id)
		{
			$member_row=$team;
			 
			break;
			
		}
	}
}

if($member_row==null)
{
	echo "Invalid credentials";
	exit;
}
 
 if(isset($_POST['Submit'])){
	 
	 redirect_member_to_show_visit($email, $salesforce_id);
 }
 //create array of pasts visits
 $visits=array();
 $visits=get_school_visit($salesforce_id);
	$header_line=array(
		'1' => 'Timestamp',
		'2' => 'Visit_Key',
		'3' => 'Salesforce_ID',
		'4' => 'Date_of_Visit',
		'5' => 'Team_size',
		'6' => 'Length_Of_Visit',
		'7' => 'Next_Scheduled_Visit_Date',
		'8' => 'Next_Scheduled_Visit_Time',
		'9' => 'Program_Category',
		'10' => 'Description_of_Activity',
		'11' => 'Items_Brought_to_Classroom',
		'12' => 'Class_Field_trip',
		'13' => 'Is_Teacher_Responsive',
		'14' => 'Is_AAC_Meeting_Needs',
		'15' => 'General_Comments'
	);
if(!empty($visits)){
 $recentvisit=mostrecent($visits, $header_line[4]);
}
 
 
 $title = "" . $member_row['Company Name'] . " Team at " . $member_row['Teacher_Name'] . "'s classroom at " . $member_row['School_Name'] ."" ;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<!-- Basic Page Needs -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- Mobile Specific Metas -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>..:: Adopt A Class ::..</title>
<meta name="description" content="">
<meta name="author" content="">
<!-- Google Font -->
<link href="<?php bloginfo('template_directory'); ?>/new-stuff/style.css" rel="stylesheet" type="text/css" >

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body>

<section class="create">
<div class="container">
<div class="row">
	<div class="col-md-12">
            
	<form method="post" action="/create-visit">
		
		<div class="btn" >
		<a href="http://aacfoundation.com/school-visit/?email=<?php echo $email; ?>&salesforce_id=<?php echo $salesforce_id; ?>">Create New Team Lead School Visit</a>
		</div>
	</form>
    </div>
</div>
</div>
</section>
 

<section class="school_listing">
<div class="container">
<div class="row">

	<!--<div class="col-md-12">
    	<div class="view-all">
        	<a href="#">
            	View All
	        	<span class="pull-right"><img src="<?php bloginfo('template_directory'); ?>/new-stuff/images/down-arrow.png"></span>
            </a>
        </div>
    </div>
</div>-->
<div class="row gree-bg">
	<div class="col-md-12 col-md-push-0">
        <div class="recent-visit">
        	<div class="title">Recent <?php echo $title; ?> Visits</div>
            




			<ul class="school-listing">
			<?php
			$allvisitkeys=array();
for($h=0; $h<count($recentvisit); $h++){
$row=$recentvisit[$h];

		$assoc_row=array();
	
	$key=$row['Visit_Key'];
	if(!in_array($key,  $allvisitkeys)){
	$allvisitkeys[$h]=$key
?>
            	<li class="col-md-6">
                	<div class="listing">
	                	<div class="date-time">
            	        	<div class="date"><?php echo date("M" ,strtotime($row['Date_of_Visit'])); ?><span><?php echo date("j" ,strtotime($row['Date_of_Visit'])); ?></span></div>
    	                    <div class="time"><?php echo date("D" ,strtotime($row['Date_of_Visit'])); ?> <br> <?php echo date("g:i A" ,strtotime($row['Time_of_Visit'])); ?> <br><?php echo $row['Length_Of_Visit'];?> min.</div>
							
        	    	    </div>
                    	<div class="address">
                    		<a  rel="bookmark"><?php echo $member_row['School_Name']; ?></a>
    	                    <p><?php echo $row['Team_size'] ;?> TEAM MEMBERS VISITED</p>
                        </div>
                    </div>
                </li>
			<?php 
}
}
 ?>
                
            </ul>
        </div>
    </div>
</div>
</div>
</section>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>

<?php //get_footer(); ?>
