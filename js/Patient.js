
var directionsDisplay;
var directionsService = new google.maps.DirectionsService();
var geometry = google.maps.geometry;

var snapper;
$(document).ready(function () {
    /* Location Details */
    $("#App-Dt").datepicker({
        minDate:0,
        dateFormat: 'yy-mm-dd' 
    });
    
    $("#App-Stm").timepicker({
        showPeriod: true,
        showLeadingZero: true
    });
    $("#App-Etm").timepicker({
        showPeriod: true,
        showLeadingZero: true
    });
    var Data = {action:'ShowProfile'}
    DataAccess("POST",Data,fnPatResult,'ShowProfile');   
    
    LoadGoogleLoc("txtProfLoc");
    snapper = new Snap({
        element: document.getElementById('content')
    });
    
    var Dt = new Date();
    
    $(".responsive-calendar").responsiveCalendar({time: Dt.getFullYear() + "-" + addLeadingZero(Dt.getMonth() + 1),onDayClick: function (events) { 
            key = $(this).data('year')+'-'+addLeadingZero( $(this).data('month') )+'-'+addLeadingZero( $(this).data('day')); 
            showAppLst(key,events[key]); 
        }
    });  
    fnListServices();
    $("#ServFilter").keyup(function(){
       var txt = $(this).val().toLowerCase();    
       if(txt.trim() == "" || txt == null){$("#ULServLst li").show();return;}
       $("#ULServLst li").each(function(){
           var show = false;
          $(this).find("span").each(function(){
              var spnTxt = $(this).text().toLowerCase();
              if(spnTxt.indexOf(txt) >= 0){
                  show = true;
              }
          });
          if(!show){
          $(this).hide();    
          }
          else{
          $(this).show();        
          }
       });
    });
    
    $("#BkdAppMntUL li").click(function(){
       var txt = $(this).text().trim();
       if(txt == "All"){
           fnLoadBookedAppointments(1);
       }
       else if(txt == "Pending"){
           fnLoadBookedAppointments(2);
       }
       else if(txt == "Rejected"){
            fnLoadBookedAppointments(3);
       }
       else if(txt == "Approved"){
            fnLoadBookedAppointments(4);
       }
       else if(txt == "Completed"){
            fnLoadBookedAppointments(5);
       }
    });
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


function showHome() {   
    $("#deskTitle").html("Home");
    $('#divAdd').hide();
    $('#divAppoLst').hide();    
    $('#divRequest').hide();
    $('#divCalendar').hide();
    $('#divHistory').hide();
    $("#divProf").hide();
    $("#divServDetails").hide();
    $('#divmain').show();
    fnListServices();
    snapper.close();
}


function showProf() {   
    var Data = {action:'ShowProfile'}
    DataAccess("POST",Data,fnPatResult,'ShowProfile');   
    $("#deskTitle").html("Profile");
    $("#divServDetails").hide();
    $('#divAdd').hide();
    $('#divAppoLst').hide();
    $('#divRequest').hide();
    $('#divCalendar').hide();
    $('#divHistory').hide();
    $('#divmain').hide();
    $("#divProf").show();
    snapper.close();
}
function showBookedAppo() {
    $("#deskTitle").html("Booked Appointments");
    $('#divAdd').hide();
    $('#divAppoLst').show();
    $('#divRequest').hide();
    $('#divCalendar').hide();
    $('#divHistory').hide();
    $('#divmain').hide();
    $("#divProf").hide();
    $("#divServDetails").hide();
    $("#AllBked").trigger("click");
    //fnLoadBookedAppointments(1);
    snapper.close();
}

function ApprPatAppo(id){
     var Data = {action:'ApprPatAppo',AppoApprId : id}
     DataAccess("POST",Data,fnDocSerResult,'ApprPatAppo'); 
}

function ProfbtnSubmit(){
    var MobNo = $("#txtProfMob").val();
    var Location = $("#txtProfLoc").val();    
    if(!isProperMobileNumber(MobNo)){
        ProAlert("2","Mobile number is not valid.");
        return;
    }
    var Data = {action:'UpdateProfile',MobNum: MobNo , Loc : Location};
    DataAccess("POST",Data,fnPatResult,'UpdateProfile');     
}


function fnPatResult(servdesc,Data){
    if(servdesc == "Pat_ServReq"){
        if(Data == "Success"){
            $('#ReqModal').attr('ServPk','');
            ProAlert("1","Your Request has been Sent ");
        }
        else{
            ProAlert("3",Data);
        }
        $("#App-Dt").val('');
        $("#App-Stm").val('');
        $("#App-Etm").val('');
        $("#App-Rmks").val('');
        $('#ReqModal').modal("hide");
    }
    if(servdesc == "UpdateProfile"){
        if(Data == "true" || Data == true){
            ProAlert("1","Updated Successfully.");
        }
        else{   ProAlert("0","Not Updated.");}
    }   
    else if(servdesc == "BkdAppointments"){
        $(".ApptList").html('');
        var result = JSON.parse(Data);
        if(result == null || result.length == 0){
            $(".ApptList").html("<li class='list-group-item'>No List found.</li>")
            return;
        }
        var li = "";
        for (var i = 0; i < result.length; i++) {
                li +="<li class='list-group-item'><p>Service : "+result[i].DSName+"</p>"+
                     "<p>Doctor Name: "+result[i].DSDocNm+"</p>"+
                    "<p>Date and Time: "+ result[i].PADate +" "+result[i].PAStartTm+" - "+result[i].PAEndTm+"</p>"+
                    "<p>Location: "+result[i].DSLocation+"</p>"+
                    "</li>";
        }
        if(result[0].Filter == "All"){            
            $("#ULApAll").append(li);
        }
        if(result[0].Filter == "Pending"){            
            $("#ULApPnd").append(li);
        }
        if(result[0].Filter == "Rejected"){            
            $("#ULApRej").append(li);
        }
        if(result[0].Filter == "Approved"){            
            $("#ULApAppr").append(li);
        }
        if(result[0].Filter == "Completed"){            
            $("#ULApComp").append(li);
        }
    }
    else if(servdesc == "ShowProfile"){
        var result = JSON.parse(Data);
        if(result == null || result.length == 0){
            return;
        }
        $("#txtProfName").val(result[0].UsrDispNm);
        $("#txtProfMob").val(result[0].UsrMob);
        $("#txtProfLoc").val(result[0].UsrLoc);
    }
    else if(servdesc == "Pat_ListService"){
        $("#ULServLst").html('');
        var result = JSON.parse(Data);
        if(result == null || result.length == 0){
            return;
        }
        var li = "";
        for (var i = 0; i < result.length; i++) {
            li +="<li class='list-group-item'>"+
                    "<p>Service : <span class='SerNm'>"+result[i].DSName+ "</span>"+
                    "<button onclick='fnShowServDetails("+result[i].DSPk+")'  type='button' class='btn btn-default pull-right'> " +
                    "<span class='glyphicon glyphicon-eye-open' aria-hidden='true'></span> " +
                    "</button></p>" +
                    " <p>Doctor : <span class='DocNm'>"+result[i].DSDocNm+" "+ result[i].DSQuaf+"</span></p>"+
                    "<p>Cost : &#8377; <span class='SerCost'>"+result[i].DSCost+"</span></p>"+
                    "<p>Location : <span class='SerLoc'>"+result[i].DSLocation+"</span></p></li>";
        }
        $("#ULServLst").append(li);
    }
    else if(servdesc == 'Pat_ServiceDtl'){
        $("#ServDetailsUL").html('');
        $("#Det_ServName").text('');
        var result = JSON.parse(Data);
        if(result == null || result.length == 0){
            return;
        }       
        var li = "";
        $("#Det_ServName").text(result[0].DSName);
        for (var i = 0; i < result.length; i++) {
            li +="<li class='list-group-item'>Doctor Name :  "+result[i].DSDocNm+"</li>"+
                    "<li class='list-group-item'>Specialisaion : " +result[i].DSQuaf + "</li>"+
                    "<li class='list-group-item'>Cost : &#8377; "+result[i].DSCost +" </li>"+
                    "<li class='list-group-item' >Location : <span  id='ServLocation' >"+result[i].DSLocation +"</span></li>"+
                    "<li class='list-group-item'>Service Description : "+result[i].DSDesc+"</li>"+
                    "<li class='list-group-item'> <a data-toggle='modal' data-target='#ReqModal'><button onclick='fnSetServPk("+result[i].DSPk+")' class='btn btn-success'>Send Req.</button></a>"+
                    "<button style='margin:0 10px;' onclick='fnLoadRouteMap("+result[i].DSLocLng+","+result[i].DSLocLat+")' class='btn btn-success'>See Map</button></li>";
            //<a data-toggle='modal' data-target='#menu2'>            
        }
        $("#ServDetailsUL").append(li);
        $(".dtl-tbl tr td").css({"padding":"10px 10px;"});
    }
}
function fnSetServPk(Pk){
    $('#ReqModal').attr('ServPk',Pk);
}

function fnSendServiceRequest(){
    var ServPk = $("#ReqModal").attr("servpk");
    if(ServPk == "" || ServPk == null || ServPk == undefined){
        ProAlert("0","Try Again Latter");
        return;
    }
    var Dt = $("#App-Dt").val();
    var FrmTm = $("#App-Stm").val();
    var EndTm = $("#App-Etm").val();
    var Rmk = $("#App-Rmks").val();
    if(!isValid(Dt) || !isValid(FrmTm) || !isValid(EndTm) || !isValid(Rmk)){
        ProAlert("2","Enter All details to send request.");
        return;
    }
    var Data = { action:'Pat_ServReq', ServicePk:ServPk, Date: Dt, Stm :FrmTm, Etm:EndTm , Rmks : Rmk};
    DataAccess("POST",Data,fnPatResult,'Pat_ServReq');      
}


function isValid(val){
    var retVal =true;
    if(val == null || val.trim() == "" || val==undefined){
        retVal = false;
    }
    return retVal;
}
function fnListServices(){
    var Data = {action:'Pat_ListService'};
    DataAccess("POST",Data,fnPatResult,'Pat_ListService');     
}

function fnShowServDetails(Pk){    
    $("#deskTitle").html("Service Details");    
    $('#divAdd').hide();
    $('#divAppoLst').hide();
    $('#divRequest').hide();
    $('#divCalendar').hide();
    $('#divHistory').hide();
    $('#divmain').hide();
    $("#divProf").hide();
    $("#divServDetails").show();
    var Data = {action:'Pat_ServiceDtl',ServPk: Pk};
    DataAccess("POST",Data,fnPatResult,'Pat_ServiceDtl');     
    snapper.close();
}
function fnLoadRouteMap(Lng,Lat,Location){
    Location = $("#ServLocation").text().trim();
    if(Location == "" || Location == null){
        ProAlert("0","Route Map facility is not available for this.");
        return;
    }
    var CurLoc = $("#txtProfLoc").val().trim();
    if(CurLoc == "" || CurLoc == null){
        ProAlert("0","Set Your Location in Profile to see route Map.");
        return;
    }
    $("#map-canvas").html('');
    $("#myMapModal").modal();    
    $('#myMapModal').on('hidden.bs.modal', function () {
        snapper.enable();
    });
    initialize(Location);
    //fnLoadMapRoute("map-div");    
    var pos1 = Location;
    var pos2 = CurLoc;
    $("#rt-frm").text(pos1);
    $("#rt-to").text(pos2);
    var pos1_Ltng;
    var pos2_Ltng;    
    $.ajaxSetup({
            async: false
        });
    //if(Lat == null || Lng == null){                        
        var url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' + pos1 + '&sensor=false';
        $.getJSON(url, null, function (data) {
            var p = data.results[0].geometry.location
            pos1_Ltng = new google.maps.LatLng(p.lat, p.lng);
        });
        url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' + pos2 + '&sensor=false';
        $.getJSON(url, null, function (data) {
            var p = data.results[0].geometry.location
            pos2_Ltng = new google.maps.LatLng(p.lat, p.lng);
        });
    /*}
    else{
        //pos1_Ltng = new google.maps.LatLng(Lat, Lng);
        pos1_Ltng = new google.maps.LatLng(Lng, Lat);
        url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' + pos2 + '&sensor=false';
        $.getJSON(url, null, function (data) {
            var p = data.results[0].geometry.location
            pos2_Ltng = new google.maps.LatLng(p.lat, p.lng);
        });
    }*/
     fnPlotRouteinMap(pos1_Ltng, pos2_Ltng);
    //$(".modal-backdrop").css("background-color","transparent");    
    snapper.disable();
}

/*
 * MAP  
 * 
 */
var map;        
var myCenter=new google.maps.LatLng(22, 77);
var marker=new google.maps.Marker({
    position:myCenter
});

function initialize(Location) {     
    if(typeof Location == "object" || Location == null || Location == undefined || Location == "")  {
        myCenter=new google.maps.LatLng(22, 77);
        marker=new google.maps.Marker({
            position:myCenter
        });
    }
    else{
        $.ajaxSetup({
            async: false
        });
        var url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' + Location + '&sensor=false';
        $.getJSON(url, null, function (data) {
            var p = data.results[0].geometry.location
            myCenter = new google.maps.LatLng(p.lat, p.lng);
            marker=new google.maps.Marker({
                position:myCenter
            });
        });
    }
    var mapProp = {
      center:myCenter,
      zoom: 6,
      draggable: true,
      scrollwheel: true,
      mapTypeId:google.maps.MapTypeId.ROADMAP
  };
  
  map=new google.maps.Map(document.getElementById("map-canvas"),mapProp);
  //marker.setMap(map);  
  google.maps.event.addListener(marker, 'click', function() {      
    infowindow.setContent(contentString);
    infowindow.open(map, marker);    
  }); 
  directionsDisplay = new google.maps.DirectionsRenderer();
  directionsDisplay.setMap(map);
};
google.maps.event.addDomListener(window, 'load', initialize);

google.maps.event.addDomListener(window, "resize", resizingMap());

$('#myMapModal').on('show.bs.modal', function() {
   //Must wait until the render of the modal appear, thats why we use the resizeMap and NOT resizingMap!! ;-)
   resizeMap();
})

function resizeMap() {
   if(typeof map =="undefined") return;
   setTimeout( function(){resizingMap();} , 400);
}

function resizingMap() {
    if(typeof map =="undefined") return;
   var center = map.getCenter();
   google.maps.event.trigger(map, "resize");
   map.setCenter(center); 
}

function fnPlotRouteinMap(start, end) {
    //start = new google.maps.LatLng(-40.321, 175.54);
    //end = new google.maps.LatLng(-38.942, 175.76);
    var request = {
        origin: start,
        destination: end,
        //waypoints: waypts,
        optimizeWaypoints: true,
        travelMode: google.maps.DirectionsTravelMode.DRIVING
    };
    directionsService.route(request, function (response, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(response);
            var route = response.routes[0];
            var bounds = response.routes[0].bounds;
            map.setCenter(bounds.getCenter());
        }
    });
}

function calcDistance(p1, p2) {
    try {
        geometry = google.maps.geometry;
        var dist = geometry.spherical.computeDistanceBetween(p1, p2);
        var kms = (dist / 1000).toFixed(2);
        return kms;
    }
    catch (e) { console.log(e) }
}



// MAP

function fnLoadBookedAppointments(Filter){ // 1=all , 2= pending , 3= rejected    
    var Action = ""
    if(Filter == 1){
        Action = "All";
    }
    else if(Filter == 2){
        Action = "Pending";
    }
    else if(Filter == 3){
        Action = "Rejected";
    }
    else if(Filter == 4){
        Action = "Approved";
    }
    else if(Filter == 5){
        Action = "Completed";
    }
    var Data = {action:'BkdAppointments',Filter : Action}
    DataAccess("POST",Data,fnPatResult,'BkdAppointments');  
}

