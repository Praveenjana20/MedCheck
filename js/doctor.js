var snapper;
$(document).ready(function () {
    LoadGoogleLoc("txtLoc");
    snapper = new Snap({
        element: document.getElementById('content')
    });
    
    var Dt = new Date();
    
    $(".responsive-calendar").responsiveCalendar({time: Dt.getFullYear() + "-" + addLeadingZero(Dt.getMonth() + 1),onDayClick: function (events) { 
            key = $(this).data('year')+'-'+addLeadingZero( $(this).data('month') )+'-'+addLeadingZero( $(this).data('day')); 
            showAppLst(key,events[key]); 
        }
    });
    
    var Data = {action:'ServLst'}
    DataAccess("POST",Data,fnDocSerResult,"ServLst");
});

 function addLeadingZero(num) {
    if (num < 10) {
      return "0" + num;
    } else {
      return "" + num;
    }
  }

function btnAdd() {
    $("#subheading").html('<small><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Add Service <span id="closeServ" onclick="btnClose();" class="glyphicon glyphicon-remove pull-right" aria-hidden="true"></span></small>');
    $("#btnSubmit").attr("onclick","btnAddRUptServ(0);");
    $("#btnSubmit").html('<span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span> Add');
    $('#divmain').hide();
    $('#divCalender').hide();
    $('#divAppoLst').hide();
    $('#divRequest').hide();
    $('#divHistory').hide();
    $('#divAdd').show();
}

function btnUpt(id) {
    var Data = {action:'EditServ',DSFk:id}
    DataAccess("POST",Data,fnDocSerResult,'EditServ');    
}

function btnClose() {
    window.location.reload();
}

function showHome() {   
    $("#deskTitle").html("Home");
    $('#divAdd').hide();
    $('#divAppoLst').hide();
    $('#divRequest').hide();
    $('#divCalendar').hide();
    $('#divHistory').hide();
    $('#divmain').show();
    snapper.close();
}

function showHistLst() { 
    $('#DivDocPatHist').html('');
    snapper.close();
    var Data = {action:'ShwDocHist'}
    DataAccess("POST",Data,fnDocSerResult,'ShwDocHist');  
}

function showAppo() {     
    snapper.close();
    var Data = {action:'ShwAppo'}
    DataAccess("POST",Data,fnDocSerResult,'ShwAppo');   
}

function showAppLst(dt,num) {
    if(num == undefined || num == null){
         ProAlert("2","No Appointment is exist for " + dt);
         return false;
    }
    var Data = {action:'ShwDtAppo',Date:dt}
    DataAccess("POST",Data,fnDocSerResult,'ShwDtAppo');   
}

function showReqLst() {   
    snapper.close();
    var Data = {action:'ShwPatReq'}
    DataAccess("POST",Data,fnDocSerResult,'ShwPatReq');   
}

function CancelAppo(id) {
    Lobibox.prompt('text', {
        title: 'Please enter reason',
        attrs: {
            placeholder: ""
        },callback: function (lobibox, type) {
            if (type === 'ok') {
               var Data = {action:'RmvPatAppo',AppoReason:lobibox.getValue(),AppoRmvId : id}
               DataAccess("POST",Data,fnDocSerResult,'RmvPatAppo'); 
            }
       }
    });
}

function ApprPatAppo(id){
     var Data = {action:'ApprPatAppo',AppoApprId : id}
     DataAccess("POST",Data,fnDocSerResult,'ApprPatAppo'); 
}

function FinishAppo(id) {
    Lobibox.prompt('text', {
        title: 'Please enter patient remarks',
        attrs: {
            placeholder: ""
        },callback: function (lobibox, type) {
            if (type === 'ok') {
               var Data = {action:'FishPatAppo',AppoReason:lobibox.getValue(),AppoRmvId : id}
               DataAccess("POST",Data,fnDocSerResult,'FishPatAppo'); 
            }
       }
    });
}

function btnDel(id) {    
    Lobibox.confirm({
        title: 'Warning',
        msg: 'Are you sure to delete ? ',
        icon: false,
        callback: function (lobibox, type) {
            if (type === 'yes') {
                var Data = {action:'RmvServ',DCFk:id};
                DataAccess("POST",Data,fnDocSerResult,"RmvServ");
            }
        }
    });    
}

function LoadPatDtls(sender){
    $('#DivDocPatHist').html('');
    if($(sender).val() == 0 || $(sender).val() == undefined || $(sender).val() == null){
        return false;
    }
    var Data = {action:'LoadPatDtls',PatId : $(sender).val()}
    DataAccess("POST",Data,fnDocSerResult,'LoadPatDtls'); 
}

function btnAddRUptServ(id){
    var IsErr = false, IsFocus = false;
    var txtName = $("#txtName").val();
    var IsVaild = true;
    IsVaild = vaildateInput(txtName, "#txtName", "Please Enter Name");
    if (!IsVaild) { IsErr = true; if (!IsFocus) { $("#txtName").focus(); IsFocus = true; } }    
    
    var txtDocName = $("#txtDocName").val();
    var IsVaild = true;
    IsVaild = vaildateInput(txtDocName, "#txtDocName", "Please Enter Doctor Name");
    if (!IsVaild) { IsErr = true; if (!IsFocus) { $("#txtDocName").focus(); IsFocus = true; } }
    
    var txtQuaf = $("#txtQuaf").val();
    var IsVaild = true;
    IsVaild = vaildateInput(txtQuaf, "#txtQuaf", "Please Enter Qualification");
    if (!IsVaild) { IsErr = true; if (!IsFocus) { $("#txtQuaf").focus(); IsFocus = true; } }

    var txtCost = $('#txtCost').val();
    IsVaild = true;
    IsVaild = vaildateInput(txtCost, "#txtCost", "Please Enter Cost");
    if (!IsVaild) { IsErr = true; if (!IsFocus) { $("#txtCost").focus(); IsFocus = true; } }

    var txtLoc = $("#txtLoc").val();
    IsVaild = true;
    IsVaild = vaildateInput(txtLoc, "#txtLoc", "Please Enter Location");
    if (!IsVaild) {          
        $("#txtLoc").css("border-color", "red");
        IsErr = true; if (!IsFocus) { $("#txtLoc").focus(); IsFocus = true; }
    }
    
    var Desc = $("#txtDesc").val();
    IsVaild = vaildateInput(Desc, "#txtDesc", "Please Enter Description");
    if (!IsVaild) { IsErr = true; if (!IsFocus) { $("#txtDesc").focus(); IsFocus = true; } }  

    if (IsErr) {
        return false;
    }
    var action =  id == 0 ? 'AddServ' : 'UptServ';
    var Data = {action:action,txtName:txtName,txtDocName:txtDocName,txtQuaf:txtQuaf,txtCost:txtCost,txtLoc:txtLoc,txtDesc:Desc,Lng:GooLng,Lat:GooLat,DSFk:id}
    DataAccess("POST",Data,fnDocSerResult,action);
}

function fnDocSerResult(servdesc,Data){    
     if(servdesc == "ServLst"){
         $('#ULServLst').html(Data);
     }
     else if(servdesc == "ApprPatAppo"){
        ProAlert("1","Request Accepted.");
        showReqLst();
     }
    else if(servdesc == "AddServ"){
        ProAlert("1","Service Added Successfully.");
        $("#txtName").val('');
        $("#txtDocName").val('');
        $("#txtQuaf").val('');
        $('#txtCost').val('');
        $("#txtLoc").val('');
        $("#txtDesc").val('');   
    }
    else if(servdesc == "EditServ"){
        var result = JSON.parse(Data);
        $("#subheading").html('<small><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Update Service <span id="closeServ" onclick="btnClose();" class="glyphicon glyphicon-remove pull-right" aria-hidden="true"></span></small>');
        $("#btnSubmit").attr("onclick","btnAddRUptServ("+result.DSPk+");");
        $("#btnSubmit").html('<span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span> Update');
        $('#divmain').hide();
        $('#divCalender').hide();
        $('#divAppoLst').hide();
        $('#divRequest').hide();
        $('#divProf').hide();
        $('#divHistory').hide();
        $("#txtName").val(result.DSName);
        $("#txtDocName").val(result.DSDocNm);
        $("#txtQuaf").val(result.DSQuaf);
        $('#txtCost').val(result.DSCost);
        $("#txtLoc").val(result.DSLocation);
        $("#txtDesc").val(result.DSDesc);
        GooLat = result.DSLocLat;
        GooLng = result.DSLocLng;
        $('#divAdd').show();
    }
    else if(servdesc == "UptServ"){
         ProAlert("1","Service Updated Successfully.");
    }
    else if(servdesc == "RmvServ"){
        window.location.reload();
    }
    else if(servdesc == "ShwAppo"){     
        $(".responsive-calendar").responsiveCalendar('clearAll');
        $(".responsive-calendar").responsiveCalendar('edit',JSON.parse(Data));
        $("#deskTitle").html("Appointments");
        $('#divmain').hide();
        $('#divAdd').hide();
        $('#divAppoLst').hide();
        $('#divRequest').hide();
        $('#divProf').hide();
        $('#divHistory').hide();
        $('#divCalendar').show();
    }
    else if(servdesc == "ShwDtAppo"){
        $("#divAppoLst").html(Data);
        $('#divmain').hide();
        $('#divAdd').hide();
        $('#divCalendar').hide();
        $('#divRequest').hide();
        $('#divProf').hide();
        $('#divHistory').hide();
        $('#divAppoLst').show();
    }
    else if(servdesc == "RmvPatAppo" || servdesc == "FishPatAppo"){
        showAppo();
    }
    else if(servdesc == "ShwPatReq"){
        $("#divRequest").html(Data);
        $("#deskTitle").html("Patient Request");
        $('#divmain').hide();
        $('#divAdd').hide();
        $('#divCalendar').hide();
        $('#divAppoLst').hide();
        $('#divHistory').hide();
        $('#divRequest').show();
    }
    else if(servdesc == "ShwDocHist"){        
        $("#patLst").html('');
        Data = '<option value="0" selected="selected">Select The Patient Name</option>' +  Data;
        $("#patLst").html(Data);
        $("#patLst").select2({
            width: '100%',
            theme: "classic",
        });    
        $("#deskTitle").html("History");
        $('#divAdd').hide();
        $('#divAppoLst').hide();
        $('#divRequest').hide();
        $('#divCalendar').hide();
        $('#divmain').hide();
        $('#divHistory').show();
    }
    else if(servdesc == 'LoadPatDtls'){
        $('#DivDocPatHist').html(Data);
    }
}