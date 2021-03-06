<?php global $arrDefined; ?>
<link href="<?php echo $base_url ?>asset/js/ui/jquery-ui-1-9-2.css" rel="stylesheet" type="text/css">
<link href="<?php echo $base_url ?>asset/css/override.jquery-ui.css" rel="stylesheet" type="text/css">
<script src="<?php echo $base_url ?>asset/js/ui/jquery-ui-1-9-2.js" type="text/javascript"></script>
<script src="<?php echo $base_url ?>asset/js/ui/jquery-ui.customize-autocomplete.js" type="text/javascript"></script>
<script src="<?php echo $base_url ?>asset/js/ui/jquery-ui-timepicker-addon.js" type="text/javascript" ></script>
<div class="full-width">
  <div class="content t-left"> <?php echo $message;?>
    <form id="fCreateIncident" method="post" action="<?php echo $base_url?>incident/incident/update_incident_submit" onsubmit="return onValidation()">
      <input type="hidden" name="src_from" value="<?php echo $oIncident['source_from'] ?>"/>
      <input type="hidden" name="src_id" value="<?php echo $oIncident['source_id'] ?>"/>
      <input type="hidden" name="itsm_incident_id" value="<?php echo $oIncident['itsm_incident_id'] ?>"/>
      <!--<input type="hidden" name="alert_id" value="<?php echo $oIncident['_id'] ?>"/> -->
      <input type="hidden" name="attachments" value="<?php echo implode(';', $arrAttachLink) ?>"/>
      <table class="table_01" width="100%" cellpadding="0" cellspacing="0">
        <thead>
          <tr class="table-title">
            <th colspan="4"><p class="drag">UPDATE INCIDENT</p></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th class="w5">Status</th>
            <td class="w20">Open &nbsp;
            <?php $this->tpl->load_anchor_icon(array('img'=> ICON_IMG_EDIT, 'href'=> $base_url.'incident/incident/update_incident_status?incidentid='.$oIncident['itsm_incident_id']  )); ?> </td>
            <th class="w5">Area</th>
            <td><select id="drparea" name="area" class="wp120">
                <option value="">&nbsp </option>
                <?php foreach ($arrArea as $oArea) { ?>
                <option value="<?php echo $oArea->name?>" <?php if($oIncident['area'] == $oArea->name) { echo 'selected="selected"'; } ?>><?php echo $oArea->name ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <th class="w5">Department</th>
            <td id="celDepartment"><select id="drpdepartment" name="department" class="wp100">
                <?php foreach ($arrDepartment as $oDepartment) { ?>
                <option <?php if ($oDepartment->name == $strDepartment) { ?>selected="selected"<?php } ?> value="<?php echo $oDepartment->name?>"><?php echo $oDepartment->name ?></option>
                <?php } ?>
              </select></td>
            <th>Sub Area</th>
            <td id="celSubArea"><select id="drpsubarea" name="subarea" class="wp200">
                <option area="" value="">&nbsp </option>
                <?php foreach ($arrSubarea as $oSubarea) { ?>
                <option area="<?php echo strtolower($oSubarea->area) ?>" value="<?php echo $oSubarea->id ?>" <?php if($oIncident['subarea'] == $oSubarea->name) { echo 'selected="selected"'; } ?>><?php echo $oSubarea->name ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <th>Outage Start&nbsp;<span class="require_mark">*</span></th>
            <td><input type="text" name="outage_start" id="outagedate" value="<?php echo date('Y-m-d H:i:s'); ?>"></td>
            <th>Affected Service&nbsp;<span class="require_mark">*</span></th>
            <td id="celProduct" class="w20"><select id="cboProduct" name="product" class="wp100">
                <option value="">&nbsp </option>
                <?php foreach ($arrService as $oService) { ?>
                <option <?php if ($oService->name == $strProduct) { ?>selected="selected"<?php } ?> value="<?php echo $oService->name ?>"><?php echo $oService->name ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <th>Downtime Start</th>
            <td><input class="wp120" type="text" name="downtime_start" id="downtimestart"></td>
            <th>Affected CI</th>
            <td><input class="wp120" type="text" disabled="disabled" name="affected_ci" id="affected_ci"></td>
          </tr>
          <!-- <tr>
            <th>KB</th>
            <td colspan="3"><table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td class="w20" id="celKB"><select class="wp100" id="drpkb" name="drpkb" disabled="disabled">
                      <option value="0" link="#" type="&nbsp;">&nbsp;</option>
                    </select>
                  <td class="w20" id="celKBLink"><a class="kb-link" href="#" id="kbLink"><img src="./asset/images/icons/follow_me.png" height="25"></a></td>
                  <td id="celSupportType"><!--<a href="#" target="_blank">Tài liệu hướng dẫn</a></td>
                </tr>
              </table></td>
          </tr> -->
          <tr>
            <th>Bug category</th>
            <td><select id="drpbugcategory" name="bugcategory" class="wp100">
                <option value="">&nbsp </option>
                <?php foreach ($arrBugCategory as $oBugCategory) { ?>
                <option value="<?php echo $oBugCategory->bug_category_key ?>" <?php if($oIncident['bug_category'] == $oBugCategory->bug_category_name) { echo 'selected="selected"'; } ?>><?php echo $oBugCategory->bug_category_name ?></option>
                <?php } ?>
              </select></td>
            <th>Assignment Group &nbsp;<span class="require_mark">*</span></th>
            <td id="celAssignmentGroup"><select id="drpassignmentgroup" name="assignment_group" class="wp200">
                <option value="">&nbsp </option>
              </select></td>
          </tr>
          <tr>
            <th>Bug Unit</th>
            <td id="celBugUnit"><select id="drpbugunit" name="bugunit" class="wp200">
                <option value="">&nbsp </option>
                <?php foreach ($arrBugUnit as $oBugUnit) { ?>
                <option value="<?php echo $oBugUnit->unit_key ?>" <?php if($oIncident['unit'] == $oBugUnit->unit_name) { echo 'selected="selected"'; } ?>><?php echo $oBugUnit->unit_name ?></option>
                <?php } ?>
              </select></td>
            <th>Assignee&nbsp;<span class="require_mark">*</span></th>
            <td id="celAssignee"><select id="drpassignee" name="assignee" class="wp100">
                <option value="">&nbsp </option>
              </select></td>
          </tr>
          <tr>
            <td colspan="2">&nbsp;</td>
            <!-- <th>Product type</th>
            <td id="celProductType"><select id="drpproducttype" name="drpproducttype" class="wp100" disabled="disabled">
                <option value="">&nbsp </option>
              </select></td>-->
            <th>Impact&nbsp;<span class="require_mark">*</span></th>
            <td>
				<select name="impact_level" id="drpimpactlevel" class="wp100">
					<option value="6" <?php if($iAlertImpactLevel == 6) { ?> selected="selected" <?php } ?> >6</option>
					<option value="5" <?php if($iAlertImpactLevel == 5) { ?> selected="selected" <?php } ?> >5</option>
					<option value="4" <?php if($iAlertImpactLevel == 4) { ?> selected="selected" <?php } ?> >4</option>
					<option value="3" <?php if($iAlertImpactLevel == 3) { ?> selected="selected" <?php } ?> >3</option>
					<option value="2" <?php if($iAlertImpactLevel == 2) { ?> selected="selected" <?php } ?> >2</option>
					<option value="1" <?php if($iAlertImpactLevel == 1) { ?> selected="selected" <?php } ?> >1</option>
				</select>
				<?php if($oIncident['source_from'] == 'CS') { ?>
					<input type="checkbox" id="AutoUpdateImpactLevel" name="auto_update_impact_level" value="1" <?php if(intval($oIncident['auto_update_impact_level']) == 1) { ?> checked <?php } ?>> Auto update by customer case
				<?php } ?>
			</td>
          </tr>
          <tr>
            <th>Location</th>
            <td><select name="location" id="drpLocation" class="wp100">
                <option value="">&nbsp;</option>
                <?php foreach($arrLocation as $oLocation){ ?>
                <option value="<?php echo $oLocation->value ?>" <?php if($oIncident['location'] == $oLocation->description) { echo 'selected="selected"'; } ?> ><?php echo $oLocation->description ?></option>
                <?php } ?>
              </select></td>
            <th>Urgency&nbsp;<span class="require_mark">*</span></th>
            <td><select name="urgency_level" id="drpurgencylevel" class="wp100">
                <option value="5">5</option>
                <option value="4" selected="selected">4</option>
                <option value="3">3</option>
                <option value="2">2</option>
                <option value="1">1</option>
              </select></td>
          </tr>
          <tr>
            <th rowspan="4">Related Records</th>
            <td rowspan="4" style="padding-left: 20px"><b>Incident fixed by change</b><br />
              <input type="text" class="w20" name="related_id" id="txtRelatedId" value="">
              <br />
              <br />
              <b>Incident caused by change</b><br />
              <input type="text" class="w20" name="related_id_change" id="txtRelatedIdChange" value=""></td>
            <th>CCU Times</th>
            <td><input type="text" name="ccu_time" id="txtCCUTime" class="w20" value=""></td>
          </tr>
          <tr>
            <th>CCU/Connection/ Transaction</th>
            <td><input type="text" name="user_impacted" id="txtUserImpacted" class="w20" value=""></td>
          </tr>
          <tr>
            <th>E.U.I (by CS channel)</th>
            <td><input type="text" name="customer_case" id="txtCustomerImpacted" class="w20" value="<?php echo $oIncident['customer_case'] ?>"></td>
          </tr>
          <tr>
            <th>Caused by external service</th>
            <td><input type="checkbox" id="chkCauseByExt" name="is_cause_by_ext" value="t">
              &nbsp;&nbsp;
              <select id="drpcausebyexternal" name="cause_by_ext" class="w20">
                <option value="">&nbsp </option>
                <?php foreach($arrCausedByDept as $oRcause){ ?>
                <option value="<?php echo $oRcause->value ?>" <?php if($oIncident['caused_by_external'] == $oRcause->description) { echo 'selected="selected"'; } ?>><?php echo $oRcause->description ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <th>Critical Asset&nbsp;<span id="require_critical_asset" class="require_mark" style="display:none">*</span></th>
            <td colspan="3" id="celCriticalAsset"><select id="cboCriticalAsset" name="critical_asset" class="w50">
                <option value="">&nbsp </option>
              </select></td>
          </tr>
          <tr>
            <th>Title&nbsp;<span class="require_mark">*</span></th>
            <td colspan="3"><input type="text" name="title" id="txttitle" class="w50" value=""></td>
          </tr>
          <tr>
            <th>Description&nbsp;<span class="require_mark">*</span></th>
            <td colspan="3"><textarea rows="5" class="w98" name="description" id="txtdescription"><?php echo $strDescription ?></textarea></td>
          </tr>
          <?php if(count($arrAttachment) > 0){ ?>
          <tr>
            <th>Attachments</th>
            <td colspan="3">
            	<a target="_blank" href="<?php echo $arrAttachment[0]['link'] ?>"><?php echo htmlentities($arrAttachment[0]['filename_alias']) ?></a>&nbsp;<?php if($arrAttachment[0]['is_file_saved']==0) {?><span class="att_warning" id="<?php echo $arrAttachment[0]['_id'] ?>">File chưa được download về nên không thể up lên ITSM</span><?php } ?>;
            	<?php for($i=1;$i<count($arrAttachment);$i++) {?>
            	<a target="_blank" href="<?php echo $arrAttachment[$i]['link'] ?>"><?php echo htmlentities($arrAttachment[$i]['filename_alias']) ?></a>&nbsp;<?php if($arrAttachment[$i]['is_file_saved']==0) {?><span class="att_warning" id="<?php echo $arrAttachment[$i]['_id'] ?>">File chưa được download về nên không thể up lên ITSM</span><?php } ?>;
            	<?php } ?>
            </td>
          </tr>

          <!-- <tr>
            <td colspan="3"></td>
          </tr> -->

          <?php } ?>
          <tr>
            <th>SDK Info</th>
            <td valign="top" colspan="3"><table width="100%" cellpadding="2" cellspacing="0">
                <tr>
                  <td align="right" width="18%"><b>New Rootcause Category&nbsp;</b></td>
                  <td align="left" class="w20"><select id="drpNewRootcauseCategory" name="root_cause_category" class="wp200">
                      <option value="">&nbsp;</option>
                      <?php foreach($arrNewRootCause as $oRcause){ ?>
                      <option value="<?php echo $oRcause->value ?>" <?php if($oIncident['rootcause_category'] == $oRcause->description) { echo 'selected="selected"'; } ?>><?php echo $oRcause->description ?></option>
                      <?php } ?>
                    </select></td>
                  <td align="right" class="w15"><b>Is Downtime&nbsp;</b></td>
                  <td><select id="drpIsDowntime" name="is_downtime" class="wp120">
                      <option value="">&nbsp </option>
                      <option value="t" <?php if($oIncident['is_downtime'] == 'Yes') { echo 'selected="selected"'; } ?>>Yes</option>
                      <option value="f" <?php if($oIncident['is_downtime'] == 'No') { echo 'selected="selected"'; } ?>>No</option>
                    </select></td>
                </tr>
                <tr>
                  <td align="right"><b>Resolved by&nbsp;</b></td>
                  <td><select id="drpresolvedby" name="resolvedby" class="wp200">
                      <option value="">&nbsp;</option>
                      <?php $oIncident['resolved_by'] = strtolower(trim($oIncident['resolved_by']));?>
                      <option value="SDK" <?php if($oIncident['resolved_by'] == 'sdk') { echo 'selected="selected"'; } ?>>SDK</option>
             		  <option value="SE" <?php if($oIncident['resolved_by'] == 'se') { echo 'selected="selected"'; } ?>>SE</option>
             		  <option value="SDK and SE" <?php if($oIncident['resolved_by'] == 'sdk and se') { echo 'selected="selected"'; } ?>>SDK and SE</option>
                    </select></td>
                  <td align="right" rowspan="2"><b>SDK Note&nbsp;</b></td>
                  <td rowspan="2"><textarea id="txtsdknote" name="sdk_note" style="width: 98%" rows="3"><?php echo @$oIncident['sdknote'] ?></textarea></td>
                </tr>
                <tr>
                  <td align="right"><b>Detector&nbsp;</b></td>
                  <td><select id="drpsdkdetector" name="detector" class="wp200">
                      <option value="">&nbsp;</option>
                      <?php foreach($arrDetector as $oDetector){ ?>
                      <option value="<?php echo $oDetector->value ?>" <?php if($oIncident['detector'] == $oDetector->description) { echo 'selected="selected"'; } ?>><?php echo $oDetector->description ?></option>
                      <?php } ?>
                    </select></td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <th colspan="4"> <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td class="t-left"><!--<input type="button" class="styled-button" id="btnMappingSDKProductToITSMProduct" value="Mapping Product" onclick="openMappingProductPopup();">
                    &nbsp;&nbsp;
                    <input type="button" class="styled-button" id="btnMappingSDKDepartmentToITSMDepartment" value="Mapping Department" onclick="openMappingDepartmentPopup();">
                    &nbsp;&nbsp;
                    <input type="button" class="styled-button" id="btnSetAssignmentGroup" value="Set AssignmentGroup" onclick="openSetAssignmentGroupPopup();">-->&nbsp;</td>
                  <td class="t-right" style="padding-right: 20px"><input type="submit" id="btn-update-incident-step2" value="Update"></td>
                </tr>
              </table>
            </th>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript">
var strAlertMsg = "<?php echo (str_replace(array("\r\n", "\n"), " ", $oIncident['title'])) ?>";
var nIncompleteAttachmentCount = <?php echo count($arrIncompleteAttach) ?>;
var arrIncompleteAttachment = <?php echo json_encode($arrIncompleteAttach) ?>;
var strDirIncident = '<?php echo $incident_directory ?>';
var arrCriticalAssetRequire = <?php echo json_encode($arrDefined['critical_asset_require']) ?>;

$(function() {
	$('#outagedate, #downtimestart').datetimepicker({
		dateFormat: 'yy-mm-dd',
		timeFormat: 'hh:mm:ss'
	});
	var product_val         = $('#cboProduct option:selected').val();
	FillDataRelatedToProduct(product_val);
	LoadAssignmentGroup();
	LoadCriticalAsset();
	SetCriticalAssetRequire();
	PrepareTitle();

	DepartmentBindChange();
	ProductBindChange();
	AreaBindChange();
	BugCategoryBindChange();
	TestIncompleteAttachment();
});

function TestIncompleteAttachment(){
	var arrTMP = new Array();
	for(var i=0;i<arrIncompleteAttachment.length;i++){
		if(arrIncompleteAttachment[i].downloaded == 0){
			CheckAttachmentExists(i);
		} else {
			if(arrIncompleteAttachment[i].warning == 1){
				var strId = "#" + arrIncompleteAttachment[i]._id.$id;
				// alert($(strId).attr('id'));
				$(strId).remove();
				arrIncompleteAttachment[i].warning = 0;
				nIncompleteAttachmentCount--;
			}
		}
	}

	if(nIncompleteAttachmentCount > 0){
		setTimeout(function(){TestIncompleteAttachment()}, <?php echo ATTACHMENT_CHECK_INTERVAL ?>);
	}
}

function CheckAttachmentExists(nIndex){
	var strTarget = arrIncompleteAttachment[nIndex].base64_link;
	var nResult = 0;
	var strUrl = base_url + strDirIncident + 'incident/ajax_check_url_exists?url=' + strTarget;
	$.get(strUrl, function(strResponse){
		arrIncompleteAttachment[nIndex].downloaded = strResponse;
	});
}

function LoadCriticalAsset() {
	var department_name      = $('#drpdepartment option:selected').text();
	var critical_asset_url	 = base_url + 'incident/incident/ajax_get_critical_asset_of_department/critical_asset/cboCriticalAsset/w50?department=all';

	if($.trim(department_name) != ''){
		critical_asset_url = base_url + 'incident/incident/ajax_get_critical_asset_of_department/critical_asset/cboCriticalAsset/w50?department=' + department_name;
	}
	var strCriticalAssetHtml = AjaxLoad(critical_asset_url);
	$("#celCriticalAsset").html(strCriticalAssetHtml);

}

function PrepareTitle() {
	// var department_name      = $('#drpdepartment option:selected').text();
	// var product_name         = $('#cboProduct option:selected').text();
	$('#txttitle').val(strAlertMsg);
}

function LoadAssignmentGroup() {
	var department_name      = $('#drpdepartment option:selected').text();
	var product_name         = $('#cboProduct option:selected').text();
	var assignment_group_url = base_url + 'incident/incident/ajax_get_assignment_group_of_product_and_department/assignment_group/drpassignmentgroup/wp200?department=' + department_name + '&product=' + product_name ;

	var strAssignmentGroupHtml = AjaxLoad(assignment_group_url);
	$("#celAssignmentGroup").html(strAssignmentGroupHtml);

	$("#drpassignmentgroup").bind('change', function(){
		LoadAssignee();
	});
	LoadAssignee();
}

function LoadAssignee(){
	var impact_level = $('#drpimpactlevel').val();
	var product   = $('#cboProduct').val();
	var assignment_group = $('#drpassignmentgroup option:selected').text();

	var url = base_url + 'incident/incident/ajax_get_assignee_of_assignment_group/assignee/drpassignee/wp100?product=' + product + '&impact=' + impact_level + '&assignment_grp=' + assignment_group;
	var strAssigneeHtml = AjaxLoad(url);
	$("#celAssignee").html(strAssigneeHtml);

	/* if($("#drpassignee").val() != null && $("#drpassignee").val() != ''){
				$("#celAssignee").append('<input id="btnCallAssignee" onclick="callAssignee()" value="" style="border: 0;background: url(' + base_url + 'asset/images/icons/phone.png) no-repeat;width: 20px;height: 20px;margin-left: 10px;outline:none;cursor: pointer">');
			}
	$("#drpassignee").change(function(){
		if($("#drpassignee").val() != null && $("#drpassignee").val() != ''){
		$("#btnCallAssignee").attr('title','Call ' + $("#drpassignee").val());
		}
	});
	$("#btnCallAssignee").attr('title','Call ' + $("#drpassignee").val());
	*/
}

function FillDataRelatedToProduct(productId){
		if(productId != ''){
			var impact_level = $('#drpimpactlevel').val();

			//showProductNote();
			$("#drpbugunit").val("");
			$("#drpbugcategory").val("");
			var url = base_url + 'incident/incident/ajax_get_department_of_product/department/drpdepartment/wp100?product=' + productId;
			var strHtml = AjaxLoad(url);
			$("#celDepartment").html(strHtml);
			DepartmentBindChange();
			PrepareTitle();
		}
}

function ProductBindChange() {
	$("#cboProduct").combobox().bind('change', function(){
			FillDataRelatedToProduct(this.options[this.selectedIndex].value);
			LoadAssignmentGroup();
	});
}

function DepartmentBindChange() {
	$("#drpdepartment").bind('change', function(){
			var department_name      = $('#drpdepartment option:selected').text();
			var product_name         = $('#cboProduct option:selected').text();

			var product_url 		 = base_url + 'incident/incident/ajax_get_product_of_department/product/cboProduct/wp100?department=all';
			var critical_asset_url	 = base_url + 'incident/incident/ajax_get_critical_asset_of_department/critical_asset/cboCriticalAsset/w50?department=all';

			if($.trim(department_name) != ''){
				product_url = base_url + 'incident/incident/ajax_get_product_of_department/product/cboProduct/wp100?department=' + department_name;
				critical_asset_url = base_url + 'incident/incident/ajax_get_critical_asset_of_department/critical_asset/cboCriticalAsset/w50?department=' + department_name;
			}

			var strProductHtml = AjaxLoad(product_url);
			$("#celProduct").html(strProductHtml);

			var strCriticalAssetHtml = AjaxLoad(critical_asset_url);
			$("#celCriticalAsset").html(strCriticalAssetHtml);

			ProductBindChange();
			PrepareTitle();

			$("#drpassignmentgroup").children().remove().end().append('<option selected value="">&nbsp;</option>') ;
			$("#drpassignee").children().remove().end().append('<option selected value="">&nbsp;</option>') ;

			SetCriticalAssetRequire();
	});
}

function SetCriticalAssetRequire(){
	var department_name      = $('#drpdepartment option:selected').text();
	$("#require_critical_asset").css("display","none");
	if(arrCriticalAssetRequire.indexOf(department_name.toLowerCase())!==-1){
		$("#require_critical_asset").css("display","inline");
	}
}

function AreaBindChange() {
	$("#drparea").bind('change', function(){
		var area_name = $('#drparea').val();
		var url = base_url + 'incident/incident/ajax_get_subarea_of_area/subarea/drpsubarea/wp200?area=all';
		if ( area_name != "" )
		{
			area_name = encodeURIComponent(area_name);
			url = base_url + 'incident/incident/ajax_get_subarea_of_area/subarea/drpsubarea/wp200?area=' + area_name;
		}
		var strHtml = AjaxLoad(url);
		$("#celSubArea").html(strHtml);
	});
}

function BugCategoryBindChange() {
	$("#drpbugcategory").bind('change', function(){
		var bug_category = $('#drpbugcategory').val();
		//$("#celBugUnit").load(webroot + 'helper/getbugunit/' + bug_category + '/drpbugunit/w240');
		if ( bug_category != "" )
		{
			bug_category = encodeURIComponent(bug_category);
			var url = base_url + 'incident/incident/ajax_get_bug_unit_by_category/bugunit/drpbugunit/wp200?bug_category=' + bug_category;

			var strHtml = AjaxLoad(url);
			$("#celBugUnit").html(strHtml);
		}
	});
}

function onValidation(){
		var message = '';

		if($("#cboProduct").val()==''){
			message += 'Vui lòng chọn Product\n';
		}
		if($("#drpassignmentgroup").val()==null || $("#drpassignmentgroup").val()==''){
			message += 'Vui lòng chọn assignment Group\n';
		}
		if($("#drpassignee").val()==null || $("#drpassignee").val()==''){
			message += 'Vui lòng chọn assignee\n';
		}
		if($("#txttitle").val()==''){
			message += 'Vui lòng nhập title\n';
		}
		if($("#txtdescription").val()==''){
			message += 'Vui lòng nhập description\n';
		}
		if($("#outagedate").val()==''){
			message += 'Vui lòng chọn outage start\n';
		}

		var department_name = $('#drpdepartment option:selected').text();
		if(arrCriticalAssetRequire.indexOf(department_name.toLowerCase())!==-1){
			if($("#cboCriticalAsset").val()==''){
				message += 'Vui lòng chọn critical asset\n';
			}
		}


		if(message == ''){
			return true;
		} else {
			alert(message);
			return false;
		}
	}
</script>
