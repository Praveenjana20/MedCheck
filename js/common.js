var GooLat = '', GooLng = '';
function DataAccess(type, params, callback, servdesc) {
    try {
        fnShowProgress();
    }
    catch (ex) {
    }   
    $.ajax({
        url: 'CRUD.php',
        type: type,
        data: params,
        async: false,
        success: function (response) {
            result = JSON.parse(response);
            try {
                fnRemoveProgress();
            }
            catch (ex) { }                          
            if(result.status == "1") {
               callback(servdesc, result.Data); 
            } else if(result.status == "2") {
              window.location.href="index.php";
            }
            else{
               ProAlert("0",result.error);
            }
        },
        error: function (err) {           
            try {
                fnRemoveProgress();
            }
            catch (ex) { }
            console.log(err);
        }
    });
}
function vaildateInput(val, id, error) {
    if (val == undefined || val == null || val == '' || val.trim().length == 0) {
        $(id).css('border-color', 'red');
        $(id).val('');
        if (error != "" && error != undefined) {
            $(id).attr('placeholder', error);
        }
        return false;
    }
    $(id).css('border-color', '');
    return true;
}
function fnShowProgress() {
    $.blockUI({
        message: '<img src="img/assets/loading.gif" />',
        css: { width: '4%', border: '0px solid #FFFFFF', cursor: 'wait', backgroundColor: 'TRANSPARENT' },
        overlayCSS: { backgroundColor: 'TRANSPARENT', opacity: 0.0, cursor: 'wait' }
    });
}

function fnRemoveProgress() {
    $.unblockUI();
}

function ProAlert(status,msg){
    if(status == "1"){
        Lobibox.notify('success', {
            msg: msg,
            icon: 'glyphicon glyphicon-ok-sign',
            position: 'center top',
        });
    }else if(status == "2"){
         Lobibox.notify('warning', {
            msg: msg,
            icon: 'glyphicon glyphicon-warning-sign',
            position: 'center top',
        });
    }else{
         Lobibox.notify('error', {
            msg: msg,
            icon: 'glyphicon glyphicon-remove-sign',
            position: 'center top',
        });
    }
}

function LoadGoogleLoc(id) {
    var location = document.getElementById(id);
    var autocomplete = new google.maps.places.Autocomplete(location);
    google.maps.event.addListener(autocomplete, 'place_changed', function () {
        try {
            var place = autocomplete.getPlace();
            GooLat = place.geometry.location.lat() == undefined ? '' : place.geometry.location.lat();
            GooLng = place.geometry.location.lng() == undefined ? '' : place.geometry.location.lng();
        } catch (ex) { }
    });
}

function Logout(){
    var Data = {action:'LogOut'}
    DataAccess("POST",Data,"",''); 
}


function isProperMobileNumber(Val) { //// VALIDATOR FUNCTION   
   var retVal = true;
    if (!isNumeric(Val)) { retVal = false;}
    if (Val.length < 10) {
        retVal = false;
    }
    return retVal;
}

function isNumeric(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}


function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}