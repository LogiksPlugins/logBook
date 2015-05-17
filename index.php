<?php
if(!defined('ROOT')) exit('No direct script access allowed');
user_admin_check(true);

if(isset($_REQUEST["report"])) {
	$rpt=$_REQUEST["report"];
	$rpt=findLogReport($rpt);
	if(file_exists($rpt)) {
		loadModule("reports");
		//loadReportFromFile(str_replace(ROOT,"",$rpt));
		loadLogReportFromFile(str_replace(ROOT,"",$rpt));
	} else {
		echo "<style>body {overflow:hidden;}</style>";
		dispErrMessage("Requested Log Report Not Found.","404:Log-Report Not Found",404);
	}
} elseif(isset($_REQUEST["mode"]) && strtolower($_REQUEST["mode"])=="manager") {
	include "manager.php";
} else {
	echo "<style>body {overflow:hidden;}</style>";
	dispErrMessage("Requested Action Not Found.","404:Log-Report Not Found",404);
}
function findLogReport($rpt) {
	$arr=array();
	if(defined("TEMPLATE_LOG_FOLDER")) {
		array_push($arr,APPROOT.TEMPLATE_LOG_FOLDER.$rpt.".rpt");
	}
	array_push($arr,ROOT.TEMPLATE_LOG_FOLDER.$rpt.".rpt");

	foreach($arr as $f) {
		if(file_exists($f) && is_file($f)) {
			return $f;
		}
	}
}
function loadLogReportFromFile($rptFile) {
	$rptData=array();

	$rptData['id']=md5(SITENAME._timeStamp().rand(1000,9999999));
	$rptData['title']="";
	$rptData['header']="";
	$rptData['footer']="";
	$rptData['engine']="grid";
	$rptData['style']="";
	$rptData['script']="";
	$rptData['toolbtns']="*";
	$rptData['actionlink']="";
	$rptData['datatable_table']="";
	$rptData['datatable_cols']="";
	$rptData['datatable_colnames']="";
	$rptData['datatable_hiddenCols']="";
	$rptData['datatable_where']="";
	$rptData['datatable_params']="";


	$data=file_get_contents($rptFile);
	$data=explode("\n",$data);
	foreach($data as $d) {
		if(strlen($d)>1 && strpos(" ".$d,"#")!=1 && strpos($d,"=")>1) {
			$d=explode("=",$d);
			if(strlen($d[0])>0) {
				$er=$d[0];
				unset($d[0]);
				$rptData[$er]=processQ(implode("=",$d));
			}
		}
	}

	$_SESSION["LOG_".$rptData['id']]=array();
	$_SESSION["LOG_".$rptData['id']]["table"]=$rptData["datatable_table"];
	$_SESSION["LOG_".$rptData['id']]["cols"]=$rptData["datatable_cols"];
	$_SESSION["LOG_".$rptData['id']]["where"]=$rptData["datatable_where"];

	$rptData['dataSource']="services/?site=".SITENAME."&scmd=loggrid&action=load&sqlsrc=session&sqlid=LOG_".$rptData["id"];
	printReport($rptData);
}
?>

