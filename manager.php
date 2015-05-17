<?php
if(!defined('ROOT')) exit('No direct script access allowed');
$rlink=_link("modules")."&mod=logbook&report=";
?>
<style>
html,body {
	width:100%;
	height:100%;
	overflow:hidden;
}
#logpage {
	width:100%;
	height:100%;
	overflow:auto;
}
.logpgmsg {
	width:50%;height:40%;margin:auto;margin-top:10%;padding:20px;
}
</style>
<div id=popupdiv class='ui-widget-content' title='Select Log Report To View' style='display:none;'>
<select id=reportselector size=2 style='width:100%;height:100%;border:2px solid #aaa;font:bold 15px Georgia;' class='ui-widget-content ui-corner-all'>
	<?php
		$f=APPROOT.TEMPLATE_LOG_FOLDER;
		$fs=scandir($f);
		unset($fs[0]);unset($fs[1]);
		foreach($fs as $lf) {
			$lf=str_replace(".rpt","",$lf);
			$lf=str_replace(".RPT","",$lf);
			$lt=$lf;
			$lt=str_replace("_"," ",$lt);
			$lt=ucwords($lt);
			echo "<option value='$lf'>$lt Report</option>";
		}
		$f=ROOT.TEMPLATE_LOG_FOLDER;
		$fs=scandir($f);
		unset($fs[0]);unset($fs[1]);
		foreach($fs as $lf) {
			$lf=str_replace(".rpt","",$lf);
			$lf=str_replace(".RPT","",$lf);
			$lt=$lf;
			$lt=str_replace("_"," ",$lt);
			$lt=ucwords($lt);
			echo "<option value='$lf'>$lt Report</option>";
		}
	?>
</select>
</div>
<div id=logpage class='page ui-widget-content'></div>
<script language=javascript>
$(function() {
	$("#popupdiv:ui-dialog").dialog("destroy");
	$("#popupdiv").dialog({
			width:400,
			height:500,
			modal:true,
			closeOnEscape:false,
			resizable:false,
			show:'slide',
			buttons: {
				Run:function() {
					if($("#reportselector").val()!=null) {
						s="<div id=logpgmsg class='logpgmsg ui-widget-header'>";
						s+="<div class='ajaxloading'>Loading Log Report ...</div>";
						s+="</div>";
						$("#logpage").html(s);
						loadLogReport($("#reportselector").val());
						$(this).dialog("close");
					}
				},
				Cancel:function() {
					$(this).dialog("close");
				},
			},
			open: function(event, ui) { $(".ui-dialog-titlebar-close").hide(); },
			close:function(event, ui) {
				if($("#logpage").html().trim().length==0) {
					s="<div id=logpgmsg class='logpgmsg ui-widget-header'>";
					s+="<h1 style='font-size:3em;' align=center><img src='media/images/unknown.png' width=48px height=48px alt='.' style='margin-right:30px;'/>No Log Report Loaded ...</h1>";
					s+="</div>";
					$("#logpage").html(s);
				}
			},
		});
});
function loadLogReport(lg) {
	lnk="<?=$rlink?>"+lg;
	$("#logpage").load(lnk);
}
</script>
