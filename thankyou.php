<?php /* Template Name: Thank You */

function redirect_member_to_show_visit($email, $salesforce_id)
{
	$query_string=http_build_query(array("email"=>$email, "salesforce_id"=>$salesforce_id));
	header("Location: /create-visit?".$query_string);
	exit;
}

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


if(isset($_POST["Submit"]))
{
	redirect_member_to_show_visit($email, $salesforce_id);
}

//get_header(); ?>

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

<section class="main-banner" style="background:url(http://aacfoundation.com/wp-content/themes/aac/new-stuff/images/main-banner.jpg); background-size: cover; background-repeat: no-repeat; background-position: top center;"">
<div class="container text-center">
<!-- Header -->
<div class="row logo">
	<div class="col-md-8 col-md-offset-2 thankyou">
   	  <img src="<?php bloginfo('template_directory'); ?>/new-stuff/images/right-icon.png" alt="Right Icon">
      <h2>Thank You</h2>
      <span>for telling us about your most recent classroom visit!</span>
      <p>All entries will be reviewed and used for reporting purposes.  We may contact you with any questions or concerns.</p>
	  <p>Have any photos or activity materials you'd like to share with AAC? Got questions, feedback or comments about this app? Contact Melanie Ervin, Communications Lead at melanie@aacfoundation.com</p>
    </div>
</div>
<form method="post" action="/thank">
		<input type="hidden" id="email" name="email" value="<?php echo $email; ?>">
		<input type="hidden" id="salesforce_id" name="salesforce_id" value="<?php echo $salesforce_id; ?>">
		<input type="submit" name="Submit" id="Submit"  value="Previous Visits">
</form>




<!-- Header -->
</div>
</section>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>
<?php //get_footer(); ?>