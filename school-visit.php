<?php /* Template Name: School Visit */

//get_header();
function redirect_member_to_show_visit($email, $salesforce_id)
{
	$query_string=http_build_query(array("email"=>$email, "salesforce_id"=>$salesforce_id));
	header("Location: /thank?".$query_string);
	exit;
}
function redirect_cancel($email, $salesforce_id)
{
	$query_string=http_build_query(array("email"=>$email, "salesforce_id"=>$salesforce_id));
	header("Location: /create-visit?".$query_string);
	exit;
}




//ensures user authorization
	if(!isset($_GET["email"]) || !isset($_GET["salesforce_id"]))
	{
		echo "You are not authorized to view this page";
		exit;
	}







	$email=$_GET["email"];
	$salesforce_id=$_GET["salesforce_id"];


require(dirname(__FILE__)."/libs/google-doc-matcher.php");
if ( file_exists( ABSPATH . WPINC . '/class-phpmailer.php' ) )
	require(ABSPATH . WPINC . '/class-phpmailer.php');
$teams=get_aacteam_member($email);
$member_row=null;
for($i=0; $i<count($teams); $i++)
{
	$team = $teams[$i];
	if(isset($team["Salesforce ID"]))
	{
	
		$cmp_salesforce_id=$team["Salesforce ID"];
		
		//print_r($cmp_salesforce_id);
		//print_r($teams);
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
 
 //if teacher score too low, email aac
 if((isset($_GET['Submit']) || isset($_GET['Submit&']))){
	 if((isset($_GET['teachScore']) && $_GET['teachScore']<=2))
	  {
		
		 $email_message = 'Hello Marie' . ", \r\n \r\n";
		 $email_message .=$member_row['Company Name'] . ' has scored '. $member_row['Teacher_Name'] . ' at ' . $member_row['School_Name'] . ' a score of ' . $_GET['teachScore'] . ". \r\n \r\n";
		 $email_message .='More information for contacting the company can be found below.' . "\r\n \r\n";
		 $email_message .='Company Name: ' . $member_row['Company Name'] . "\r\n";
		 $email_message .='Contact Name: ' . $member_row['Team Lead Name'] . "\r\n";
		 $email_message .='Contact Email: ' . $member_row['Team_Lead_Email'] . "\r\n";
		 $email_message .='Teacher Name: ' . $member_row['Teacher_Name'] . "\r\n";
		 $email_message .='School Name: ' . $member_row['School_Name'] . "\r\n";
		 $email_message .='Visit Date: ' . $_GET['date'] . "\r\n";
		 $email_message .='Visit Row: ' . $_GET['keyvisit'] . "\r\n";
		 
		 
		 $emailsubject=$member_row['Company Name'] . ' has a problem.';
		 $emailto = 'jacob@lamproslabs.com';
		 $mail= new PHPMailer();
					$mail->IsSMTP();
					$mail->AddAddress($emailto);
					$mail->Subject = $emailsubject;
					$mail->FromName = 'ACC Survey';
					$mail->Body =$email_message;
					$mail->WordWrap = 50;
					if (!$mail->Send()){
					echo 'Message was not sent.';
					echo 'Mailer error: ' . $mail->ErrorInfo;

					} 
	 }
 if(isset($_GET['Submit'])){
	 
	 
	 redirect_member_to_show_visit($email, $salesforce_id);
 }
}
 if(isset($_GET['cancel'])){
	 
	 redirect_cancel($email, $salesforce_id);
 }
 
 $cat =get_survey_category();
 
 date_default_timezone_set('America/New_York');
 
 //set defaults for answers
 
	 $num = numofrows()+1;
	 $date=date('Y-m-d');
	 $time=date('g:i A');
			$teamsize=5;
			$lenvis=60;
			$nxtdate='';
			$nxttime='';
			$category=0;
			$activityDescrip='';
			$itembrought='';
			$Ftrip='no';
			$Tscore=0;
			$need='yes';
			$com='';
 
 $onemonth=date('Y-m-d', strtotime('+1 months'));
 
 $getcat=get_survey_category();

 
 $teachername=$member_row['Teacher_Name'];
 $schoolname=$member_row['School_Name'];
 $contactname=$member_row['Team Lead Name'];
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
<link rel="stylesheet" href="http://clients.reversedout.com/demo/adopt_a_class/css/bootstrap-datetimepicker.min.css"/>
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body>

	<section class="school_listing">
	<div class="container">

		<div class="row gree-bg">
			<div class="col-md-12 col-md-push-0">
		        <div class="recent-visit">
		        	<div class="title"><?php echo $title ?></div>
		            <div id="visitCarousel" class="visits_form carousel slide" data-ride="carousel" data-interval="false">
		            	<iframe name="hidden_iframe" id="hidden_iframe" style="display:none;"></iframe>
						<form method="post" target="hidden_iframe" action="" id="visitform">
							<input type="hidden" id="email" name="email" value="<?php echo $email; ?>" />
							<input type="hidden" id="keyvisit" name="Visit_Key" value="<?php echo $num; ?>" />
							<input type="hidden" id="salesforce_id" name="Salesforce_ID" value="<?php echo $salesforce_id; ?>" />
							<input type="hidden" id="todaydate" name="todaydate" value="<?php echo $todaydate; ?>" />
							<input type="hidden" id="teachername" name="Teacher_Name" value="<?php echo $teachername; ?>" />
							<input type="hidden" id="contactname" name="contactname" value="<?php echo $contactname; ?>" />
							<input type="hidden" id="schoolname" name="School_Name" value="<?php echo $schoolname; ?>" />
							<input type="hidden" id="timestamp" name="Timestamp" value="" />
		                	<div   class="form_stap">
		                    	<ul class="carousel-indicators">
		                        	<li data-target="#myCarousel" class="active"></li>
		                          <li data-target="#myCarousel"></li>
		                          <li data-target="#myCarousel"></li>
		                        </ul>
		                    </div>
							
		                    <div  class="carousel-inner">
							
		                      <div class="item active">
							   
									<?php if((isset($_POST['Submit']) || isset($_POST['Submit&'])) && (('yes'===$_POST["visitscheduled"]) && ($_POST["nextVisitdate"] == ''))){?>
								  <p class="error">Error: Please Remember to fill out the Next Sceduled Visit Date field.</p>
								  <?php } ?>
							   
		                        <div class="col-md-6">
								
		                          	<label>contact</label>
		                              <input type="text" readonly name="Team_Lead_Name" id="contact" value="<?php echo $member_row['Team Lead Name'];  ?>"/>
		                          </div>

		                          <div class="col-md-6">
		                          	<label>date of visit</label>
									<div class="form-group">

		      		                <div class="input-group date form_daters" id="datttt"data-date="<?php echo $date;?>" data-link-field="dtp_input4">
		      	        	            <input class="form-control" type="text" name="Date_of_Visit" id="date"  value="<?php echo $date;?>" readonly >
		      							<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
		                      		</div>
		      							<input type="hidden" id="dtp_input4" value="" />
		      			            </div>
		      		                
										
		      							
		                              
		                          </div>
								  <div class="col-md-6">
		                          	<label>Time of visit</label>
									<div class="form-group">

		      		                <div class="input-group date form_time" id="dattt" data-time="<?php echo $time;?>" data-link-field="dtp_input2">
		      	        	            <input class="form-control" type="text" id="time" name="Time_of_Visit" value="<?php echo $time;?>" readonly >
		      							<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
		                      		</div>
		      							<input type="hidden" id="dtp_input2" value="" />
		      			            </div>
		      		                
										
		      							
		                              
		                          </div>

		                          <div class="col-md-6">
		                          	<label>no. of team members visiting</label>
		                              <select class="selecter" id="numMembersVisit" name="Team_size" style="	background:#fff url(<?php bloginfo('template_directory'); ?>/new-stuff/images/down-arrow.png) no-repeat right center;">
										  <?php for($p=1; $p<=10; $p++){ ?>
										  <option <?php if($teamsize==$p) echo "selected"; ?> value="<?php echo $p; ?>" ><?php echo $p; ?></option>
										<?php } ?>
		                              </select>
		                          </div>

		                          <div class="col-md-6">
		                          	<label>length of visit (mins)</label>

		                              <select class="selecter" id="visitlength" name="Length_Of_Visit" style="	background:#fff url(<?php bloginfo('template_directory'); ?>/new-stuff/images/down-arrow.png) no-repeat right center;">
		                              	<?php for($n=10; $n<=100; $n+=10){ ?>
										  <option <?php if($lenvis==$n) echo "selected"; ?> value="<?php echo $n; ?>"  ><?php echo $n; ?></option>
										<?php } ?>
		                              </select>
		                          </div>
								  
								  
								
		                          <div class="col-md-12 sub_btn">
		                          	<!-- <input type="submit" value="Previous" id="" /> -->

		                            <!-- <button class="button-prev" data-target="#visitCarousel" data-slide="prev">Previous</button> -->
		                            <button class="button-next btn-green" data-target="#visitCarousel" data-slide="next">Next</button>
		                              <!-- <input type="submit" class="btn-green" class="" value="Next" data-target="visitCarousel" data-slide="next" id="" /> -->
		                          </div>
		                      </div> <!-- end item -->

		                      <div class="item">
		                        <div class="col-md-6">
		                        	<label>program categories</label>
		                            <select name="categories"  id="Program_Categories" class="selecter" style="	background:#fff url(<?php bloginfo('template_directory'); ?>/new-stuff/images/down-arrow.png) no-repeat right center;">
		                            	<option disabled <?php if($category===0) echo "selected"; ?> class="disable"> Select Categories</option>
		                            	<?php for($y=1; $y<=count($cat); $y++){ ?>
										  <option <?php if($category===$cat[$y]) echo "selected"; ?>><?php echo $cat[$y]; ?></option>
										<?php } ?>
										
		                            </select>
									
								

		                        </div> 


<!-- <label>program categories</label>
		                            <select  id="categories" style="	background:#fff url(<?php bloginfo('template_directory'); ?>/new-stuff/images/down-arrow.png) no-repeat right center;">
		                            	<option disabled <?php if($category===0) echo "selected"; ?> class="disable"> Select Categories</option>
		                            	<?php for($y=0; $y<count($cat); $y++){ ?>
										  <option <?php if($category===$cat[$y]) echo "selected"; ?>><?php echo $cat[$y]; ?></option>
										<?php } ?>
										
		                            </select>-->
		                    	<div class="col-md-6">
		                        	<label>description of activities</label>
		                            <textarea name="Description_of_Activity" id="activityDesc" cols="1" role="1" placeholder="Enter Your Activities" value="<?php echo $activityDescrip; ?>" ><?php echo $activityDescrip; ?></textarea>
		                        </div>

		                        <div class="col-md-6">
		                        	<label>items you brought to the classroom</label>
		                            <input type="text" placeholder="Enter Your Items" name="Items_Brought_to_Classroom" id="items" value="<?php echo $itembrought; ?>" />
		                        </div>

		                        <div class="col-md-6">
		                        	<label>class field trip ?</label>
		                            <select class="selecter" name="Class_Field_trip" id="fieldTrip" style="	background:#fff url(<?php bloginfo('template_directory'); ?>/new-stuff/images/down-arrow.png) no-repeat right center;">
		                            	<option <?php if($Ftrip=='no') echo "selected"; ?> value="no">No</option>
		                            	<option <?php if($Ftrip=='yes') echo "selected"; ?> value="yes">Yes</option>
		                            </select>
		                        </div>

		                        <div class="col-md-12 sub_btn">
		                          <button class="button-prev" data-target="#visitCarousel" data-slide="prev">Back</button>
		                          <button class="button-next btn-green" data-target="#visitCarousel" data-slide="next">Next</button>
		                        	<!-- <input type="submit" value="Previous" id="" />
		                            <input type="submit" class="btn-green" class="" value="Next"  id="" /> -->
		                        </div>
		                      </div><!-- end item -->

		                      <div class="item">
							  
								<div class="col-md-6">
									<label>Have you scheduled your next visit?</label>
									
									<select class="selecter" name="visitscheduled" id="visitscheduled" style="	background:#fff url(<?php bloginfo('template_directory'); ?>/new-stuff/images/down-arrow.png) no-repeat right center;">
		                            	<option selected value="no">No</option>
		                            	<option value="yes">Yes</option>
		                            </select>
								  </div>
								  
								  
								  
		                          <div class="col-md-6 nextvis" hidden>
		                          <label>Next scheduled visit date</label>

		                          	<div class="form-group">

		      		                <div class="input-group date form_date" id="datttt"data-date="<?php echo $onemonth;?>" data-link-field="dtp_input1">
		      	        	            <input class="form-control" type="text" name="Next_Scheduled_Visit_Date" id="nextVisitdate" value="<?php echo $nxtdate;?>" readonly >
		      							<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
		                      		</div>
		      							<input  type="hidden" name="dtp_input1" id="dtp_input1" value="" />
		      			            </div>
		                          </div>
								  
								  <div class="col-md-6 nextvis" hidden>
		                          <label>Next scheduled visit time</label>

		                          	<div class="form-group">

		      		                <div class="input-group date form_time" id="datt"data-date="<?php echo $onemonth;?>" data-link-field="dtp_input3">
		      	        	            <input class="form-control" type="text" name="Next_Scheduled_Visit_Time"  id="nextVisitTime" value="<?php echo $nxttime;?>" readonly >
		      							<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
		                      		</div>
		      							<input type="hidden" id="dtp_input3" value="" />
		      			            </div>
									
		                          </div>
							  
							  
		                        <div class="col-md-6">
		                        	<label>IS YOUR TEACHER RESPONSIVE (5=HIGH) ?</label>
		                            <select class="selecter" id="teachScore"  name="Is_Teacher_Responsive" style="	background:#fff url(<?php bloginfo('template_directory'); ?>/new-stuff/images/down-arrow.png) no-repeat right center;">
		    	                        <option <?php if($Tscore==0) echo "selected"; ?> disabled selected class="disable"> Select Your Teacher Responsive</option>
		                            	<option <?php if($Tscore==1) echo "selected"; ?> value="1">1: Disappointed with interaction</option>
		                                <option <?php if($Tscore==2) echo "selected"; ?> value="2">2: Interaction could be improved</option>
		                                <option <?php if($Tscore==3) echo "selected"; ?> value="3">3: OK</option>
		                                <option <?php if($Tscore==4) echo "selected"; ?> value="4">4: Happy with Teacher</option>
		                                <option <?php if($Tscore==5) echo "selected"; ?> value="5">5: Delighted with teacher</option>
		                            </select>
		                        </div>

		                    	<div class="col-md-6">
		                        	<label>is the aac program meeting your needs</label>
		                            <select class="selecter" name="Is_AAC_Meeting_Needs" id="meetNeeds" style="	background:#fff url(<?php bloginfo('template_directory'); ?>/new-stuff/images/down-arrow.png) no-repeat right center;">
		                            	<option <?php if($need=='yes') echo "selected"; ?> value="yes"> Yes </option>
		                                <option <?php if($need=='no') echo "selected"; ?> value="no"> No </option>
		                            </select>
		                        </div>

		                        <div class="col-md-6">
		                        	<label>comments</label>
		                            <textarea cols="1" role="1" placeholder="Enter Your Comments" value="<?php echo $com; ?>" name="General_Comments" id="comments" ><?php echo $com; ?></textarea>
		                        </div>


		                        <div class="col-md-12 sub_btn last-btn">
		                          <button class="button-prev" data-target="#visitCarousel" data-slide="prev">Back</button>
		                        	<input type="submit" name="Submit" onclick="postVisitToGoogle()" id="Submit" class="btn-green" value="Save" alt="Save"  />
									<?php //if(!isset($_GET["key"])){ ?>
		                            <!--<input type="submit" class="sub_bor" name="Submit&" alt="Save & New" value="Save & New" onclick="postVisitToGoogle()" id="Save & New" />
									<?php //} ?>
		                            <input type="submit" class="sub_bor" name="cancel" alt="cancel" value="cancel" id="cancel" />-->
		                        </div>
		                      </div><!-- end item -->
		                    </div> <!-- end carousel-inner -->
		                </form>
		            </div>
		        </div>
		    </div>
		</div>
	</div>
	</section>

<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript">
    
	//send form data to google spreadsheets
	
	function postVisitToGoogle(){
		var visit_key=$('#keyvisit').val();
		var salesforce_id=$('#salesforce_id').val();
		var date=$('#date').val();
		var email=$('#email').val();
		var teachScore=$('#teachScore').val();
		var now = $.now();
		$('#timestamp').val(now);
		document.getElementById("visitform").action = "https://script.google.com/macros/s/AKfycbx71DXvnjipOE9LIZfWtDxWQ8Hbw8gpxyVNkTmxUb4hFYtl4_xl/exec";
		document.getElementById("visitform").submit();
		window.location.replace("http://aacfoundation.com/school-visit/?email="+ email +"&salesforce_id="+ salesforce_id +"&teachScore="+ teachScore +"&keyvisit="+ visit_key +"&date="+ date +"&Submit=Save");
		}
		//e.preventDefault();
		/*var request = new XMLHttpRequest();
		var visit_key=$('#keyvisit').val();
		var salesforce_id=$('#salesforce_id').val();
		var contact=$('#contact').val();
		var date=$('#date').val();
		var time=$('#time').val();
		var teachername=$('#teachername').val();
		var teamleadname=$('#contactname').val();
		var schoolname=$('#schoolname').val();
		var numMembersVisit=$('#numMembersVisit').val();
		var visitlength=$('#visitlength').val();
		
		var visitscheduled = $('#visitscheduled').val();
		var nextVisitDate = $('#nextVisitdate').val();
		var nextVisitTime = $('#nextVisitTime').val();
		// for(var i=0; i < "<?php echo json_encode(count(cat)); ?>"; i++ ){
		// var idd = "#" + i;
		// var categories=categories+$(idd).val();
		// }
		var categories=$('#categories option:selected').text();
		var activityDesc=$('#activityDesc').val();
		var items=$('#items').val();
		var fieldTrip=$('#fieldTrip').val();
		var teachScore=$('#teachScore').val();
		var meetNeeds=$('#meetNeeds').val();
		var comments=$('#comments').val();
		//if((( visitscheduled && nextVisitDate) || (visitscheduled == 'no' ))){
			https://script.google.com/macros/s/AKfycbz943dA52waopACblCh-mjmaKWnSmCZD-AFBZ5eVSjzryxol5k/exec
		// $.ajax({
	var url = "https://docs.google.com/forms/d/e/1FAIpQLScs2Yg6SQwNb6uVvCI70cdQ95GyjVH72oGi7smeggHpmx_8Qg/formResponse";
	var data = {	"entry_1084690048":visit_key, 
			"entry_311653049":salesforce_id,
			"entry_1006245311":teamleadname,
			"entry_2047816185":schoolname,
			"entry_266157094":teachername,
			"entry_211170082":date,
			"entry_589643215":time,
			"entry_637191887":numMembersVisit,
			"entry_1969436851":visitlength,
			"entry_531205057":nextVisitDate,
			"entry_882828132":nextVisitTime,
			"entry_561321973":categories,
			"entry_2045113815":activityDesc,
			"entry_1485338854":items,
			"entry_1855288476":fieldTrip,
			"entry_232601874":teachScore,
			"entry_897531914":meetNeeds,
			"entry_1008936496":comments
			};
			request.open('POST', url, true);
request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
request.send(data);
	// type:"POST",
	// dataType:"json",
	// statusCode: {
                    // 0: function() {
                            // console.log("unknown");
                            // window.location.href = "contact_confirm/index.html";
                    // },
                    // 200: function() {
                            // console.log("success");
                            // window.location.href = "contact_confirm/index.html";
                    // }
	
		// });
		//}
	} */
</script>  

<script type="text/javascript" src="http://clients.reversedout.com/demo/adopt_a_class/js/bootstrap-datetimepicker.js"></script>

<script type="text/javascript">

	
	$('#visitscheduled').change(function() {
		 if ($('#visitscheduled').val()=== 'yes'){
		 $('.nextvis').show();
		 } else{
			 $('.nextvis').hide();
		 }
  
});
	
	
	$('.selecter').change(function () {
            $(this).css('color', '#858585');
 });
	
$('.button-next').on('click', function(){
$('body').scrollTop(0);
});
	
	
       
	
	// $('.form_daters').datetimepicker(
	
	// {
       // //language:  'fr',
        // weekStart: 1,
        // todayBtn:  1,
		// autoclose: 1,
		// todayHighlight: 1,
		// startView: 2,
		// forceParse: 0,
        // showMeridian: 1,
		// format: 'yyyy-mm-dd hh:ii P',
		// endDate: new Date()
		
		
    // });
	
	
	$('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
		
        showMeridian: 1,
		format: 'yyyy-mm-dd hh:ii P',
		startDate: new Date()
		
    });
	$('.form_daters').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0,
		format: 'yyyy-mm-dd',
		endDate: new Date()
		
    }).on("show", function(){
$(".table-condensed .prev").css('visibility', 'hidden');
$(".table-condensed .switch").text("Pick Date");
$(".table-condensed .next").css('visibility', 'hidden');
});
	$('.form_date').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0,
		format: 'yyyy-mm-dd',
		startDate: new Date()
    }).on("show", function(){
$(".table-condensed .prev").css('visibility', 'hidden');
$(".table-condensed .switch").text("Pick Date");
$(".table-condensed .next").css('visibility', 'hidden');
});
	$('.form_timers').datetimepicker({
        //language:  'fr',
        weekStart: 1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 1,
		minView: 0,
		maxView: 1,
		showMeridian: 1,
		format: 'H:ii P',
		forceParse: 0
    });
	$('.form_time').datetimepicker({
        //language:  'fr',
        weekStart: 1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 1,
		minView: 0,
		maxView: 1,
		pickDate: false,
		showMeridian: 1,
		format: 'H:ii P',
		forceParse: 0
    }).on("show", function(){
$(".table-condensed .prev").css('visibility', 'hidden');
$(".table-condensed .switch").text("Pick Time");
$(".table-condensed .next").css('visibility', 'hidden');
});
	
</script>

</body>
</html>

<?php //get_footer(); ?>
