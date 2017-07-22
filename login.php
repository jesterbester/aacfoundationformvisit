<?php /* Template Name: Log In */

//get_header();

function redirect_member_to_survey($email, $salesforce_id)
{
	$query_string=http_build_query(array("email"=>$email, "salesforce_id"=>$salesforce_id));
	header("Location: /create-visit?".$query_string);
	exit;
}

$action="";
if(isset($_POST["action"]))
{
	$action=$_POST["action"];
}
$login_failure=false;
$member_rows=array();
//used incase email is linked to two accounts
if($action=="select-school")
{
	if(!isset($_POST["salesforce_id"]))
	{
		echo "No school selected. Please go back and select a school";
		exit;
	}
	$email = $_POST["email"];
	$salesforce_id = $_POST["salesforce_id"];
	redirect_member_to_survey($email, $salesforce_id);
}
else //logs in if correct email found
{
	if(isset($_POST["email"]))
	{
		require_once(dirname(__FILE__)."/libs/google-doc-matcher.php");
		$email = $_POST["email"];
		$member_rows = get_aacteam_member($email);
		if(count($member_rows)==0)
		{
			$login_failure = true;
		}
		else if(count($member_rows)==1)
		{
			$member_row=$member_rows[0];
			$salesforce_id="";
			if(isset($member_row["Salesforce ID"]))
			{
				$salesforce_id = $member_row["Salesforce ID"];
			}
			redirect_member_to_survey($email, $salesforce_id);
		}
	}
}

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

<section class="main-banner" style="background:url(<?php bloginfo('template_directory'); ?>/new-stuff/images/main-banner.jpg); background-size: cover; background-repeat: no-repeat; background-position: top center;">
	<div class="container text-center">
		<!-- Header -->
		<div class="row logo">
			<div class="col-md-8 col-md-offset-2">
				<a href="index.html"><img src="<?php bloginfo('template_directory'); ?>/new-stuff/images/logo.png" alt="Logo"></a>
			</div>
		</div>
		<div class="row">
			<div class="col-md-10 col-md-offset-1 login-form">
				<?php if(count($member_rows)>1){ ?>
					<form method="post" action="/log-in">
						<input type="hidden" name="email" value="<?php echo $_POST["email"]; ?>" />
						<input type="hidden" name="action" value="select-school" />
						<div class="col-md-6 text center select-school-form">
							<label>Select a classroom</label>
							<ul>
								<?php
								//if multiple accounts share an email it asks for class room
								for($i=0; $i<count($member_rows); $i++)
								{
									$member_row=$member_rows[$i];
									$salesforce_id = "";
									if(isset($member_row["Salesforce ID"]))
									{
										$salesforce_id = $member_row["Salesforce ID"];
									}
									$school_name = "";
									$teacher_name = '';
									if(isset($member_row["School_Name"]) && isset($member_row["Teacher_Name"]))
									{
										$school_name = $member_row["School_Name"];
										if(empty($school_name))
										{
											$school_name = "[NO SCHOOL NAME GIVEN]";
										}
										$teacher_name = $member_row["Teacher_Name"];
										if(empty($teacher_name))
										{
											$teacher_name = "[NO TEACHER NAME GIVEN]";
										}
									}
									elseif((!isset($member_row["School_Name"])) && isset($member_row["Teacher_Name"]))
									{
										$school_name = "[NO SCHOOL NAME ROW IN DATABASE]";
										$teacher_name = $member_row["Teacher_Name"];
										if(empty($teacher_name))
										{
											$teacher_name = "[NO TEACHER NAME GIVEN]";
										}
									}
									elseif(isset($member_row["School_Name"]) && (!isset($member_row["Teacher_Name"])))
									{
										$teacher_name = "[NO TEACHER NAME ROW IN DATABASE]";
										$school_name = $member_row["School_Name"];
										if(empty($school_name))
										{
											$school_name = "[NO SCHOOL NAME GIVEN]";
										}
									}
									else
									{
										$school_name = "[NO SCHOOL NAME ROW IN DATABASE]";
										$teacher_name = "[NO TEACHER NAME ROW IN DATABASE]";
									}
									?><li><input type="radio" name="salesforce_id" value="<?php echo $salesforce_id; ?>" <?php if($i==0){ echo 'checked'; } ?>/> <?php echo $teacher_name . "'s classroom at " . $school_name; ?></li><?php
								}
								?>
							</ul>
							<input type="submit" value="Select" />
						</div>
					</form>
				<?php } else { ?>
				<iframe name="hidden_iframe" id="hidden_iframe" style="display:none;"></iframe>
					<form method="post" target="hidden_iframe" id="bademailform" action="https://script.google.com/macros/s/AKfycbyF4Mn9nVNEyFZOPxrqA8b0a_uNthq3nyhhNtJCMICmCdmwUhYz/exec">
					
					<?php if($login_failure){ ?>
						<div hidden class="col-md-6">
						<?php $failemail = $_POST['email'];
						?>
						
							

								<input type="hidden" name="Timestamp" id="timestamp" value="" />
								<input type="hidden" name="Failed_Login_Email" id="failemail" value="<?php echo $failemail;?>" />
								
							
							</div>
						<?php } ?>
						</form>
						<form method="POST" action="/log-in"
						<div class="col-md-6">
							<label>Email</label>
							<input type="text" name="email" id="name" style="background:url(<?php bloginfo('template_directory'); ?>/new-stuff/images/user-icon.png); background-repeat: no-repeat; background-position: left;" required />
							<!--<span>incorrect your Email Address</span> --><!--lol what--> <!-- I know great engrish -->
						</div>

						<
						
						
						<input type="submit" <?php if($login_failure){ ?> onclick="postContactToGoogle()" <?php } ?> value="Sign In" />
						</form>
						
						
						<?php if($login_failure){ ?>
						
						
							
								<div style="color:#FFFFFF" class="loginfailerror">Email address not found. Please try again. If problems continue to occur please contact Melanie Ervin, Communications Lead at melanie@aacfoundation.com.</div>
								
								
						<?php } ?>
						
						
				<?php } ?>
			</div>
		</div>
		<!-- Header -->
	</div>
</section>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script type="text/javascript">
window.onload = function () {
	var input = document.getElementById("failemail");
	if(input && input.value){
		var now = $.now();
		$("#timestamp").val(now);
		document.getElementById("bademailform").submit();
	}
	}
</script>	
</body>
</html>

<?php //get_footer(); ?>
