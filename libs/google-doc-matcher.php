<?php

/*
* Get google spreadsheet for email
*/

function get_aacteam_member($email)
{
	$email = strtolower($email);
	$csv = file_get_contents("https://docs.google.com/spreadsheets/d/1_V8xH5UmJBoWWMtXjkaGaLoaBsgYhLb4dHAN-a6WXag/export?format=csv");
	if(empty($csv) || $csv==null)
	{
		return array();
	}
	$lines=explode(PHP_EOL, $csv);
	$header_line=array();
	if(count($lines)>=1)
	{
		$header_line=str_getcsv($lines[0]);
	}
	$matched_rows=array();
	for($i=1; $i<count($lines); $i++)
	{
		$row=str_getcsv($lines[$i]);
		$assoc_row=array();
		for($j=0; $j<count($header_line) && $j<count($row); $j++)
		{
			$key=$header_line[$j];
			$assoc_row[$key] = $row[$j];
		}
		$email_field = "Team_Lead_Email";
		if(isset($assoc_row[$email_field]))
		{
			$cmp_email = strtolower($assoc_row[$email_field]);
			if($email==$cmp_email)
			{
				array_push($matched_rows, $assoc_row);
			}
		}
	}
	return $matched_rows;
}
/*
* Gets number of rows for making visit key
*/
function numofrows(){
	$csv = file_get_contents("https://docs.google.com/spreadsheets/d/1wpH0wfMK8fmdC8GdMDnUIw3x0OzMSMznBMZPQDwHTrk/export?format=csv");	
	
	if(empty($csv) || $csv==null)
	{		

		return array();	
		
	}	
	
	$lines=explode(PHP_EOL, $csv);		
			
	
	$header_line=array();	
		
	if(count($lines)>=1)	
	{

			$header_line=str_getcsv($lines[0]);	
			
	}	
	
	$matched_rows=array();	
	
	for($i=1; $i<count($lines); $i++)	
	{

		$row=str_getcsv($lines[$i]);		
		$assoc_row=array();		
		
		for($j=0; $j<count($header_line) && $j<count($row); $j++)		
		{	
			
			$key=$header_line[$j];			
			$assoc_row[$key] = $row[$j];		
			
		}		
		$salesforce_id_field = "Visit_Key";				
		
		if(isset($assoc_row[$salesforce_id_field]))		
		{			
	
			$cmp_salesforce_id = strtolower($assoc_row[$salesforce_id_field]);			
			
		
		}	
	}
	return $cmp_salesforce_id;
}

/*
* Get school visits from google docs
*/
	
function get_school_visit( $salesforce_id){
	
	$salesforce_id = strtolower($salesforce_id);	
	
	$csv = file_get_contents("https://docs.google.com/spreadsheets/d/1wpH0wfMK8fmdC8GdMDnUIw3x0OzMSMznBMZPQDwHTrk/export?format=csv");	
	
	if(empty($csv) || $csv==null)	
	{
		
			return array();	
		
	}	
		
		$lines=explode(PHP_EOL, $csv);	
		
		$header_line=array();	
		
	if(count($lines)>=1)	
	{

			$header_line=str_getcsv($lines[0]);	
			
	}	
	
	$matched_rows=array();	
	
	for($i=1; $i<count($lines); $i++)	
	{

		$row=str_getcsv($lines[$i]);		
		$assoc_row=array();		
		
		for($j=0; $j<count($header_line) && $j<count($row); $j++)		
		{	
			
			$key=$header_line[$j];			
			$assoc_row[$key] = $row[$j];		
			
		}		
		$salesforce_id_field = "Salesforce_ID";				
		
		if(isset($assoc_row[$salesforce_id_field]))		
		{			
	
			$cmp_salesforce_id = strtolower($assoc_row[$salesforce_id_field]);			
			
			if($salesforce_id==$cmp_salesforce_id)			
			{				
				
				array_push($matched_rows, $assoc_row);			
				
			}	
		
		}	
	}	
	
	return $matched_rows;
}

/*
* Sorts visits by most recent
*/

function mostrecent($a, $subkey){
	foreach($a as $k => $v)
	{

		$b[$k] = strtotime($v[$subkey]);	
	
	}	
	arsort($b);	
	foreach($b as $key=>$val)
	{		
		
		$c[]=$a[$key];	
	
	}	
	
		return $c;	
}

function get_survey_category()
{
	
	$csv = file_get_contents("https://docs.google.com/spreadsheets/d/1GQAJG882vBE5wsyVteHwCDPmH4DVx6kSVXicqTUD4jA/export?format=csv");
	if(empty($csv) || $csv==null)
	{
		return array();
	}
	$lines=explode(PHP_EOL, $csv);
	
	
	$matched_rows=array();
	for($i=1; $i<count($lines); $i++)
	{
		$row=str_getcsv($lines[$i]);
	
		$matched_rows[$i]=$row[0];
		
	}
	return $matched_rows;
}
?>