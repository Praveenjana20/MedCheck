<?php
    session_start();
    try{
    $Pat_UsrPk = $_SESSION['UsrPk'];
    if($Pat_UsrPk == null || $Pat_UsrPk == ""){
        header("Location: index.php");
        }
    }
    catch(Exception $E){
        header("Location: index.php");
    }
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>MED Check</title>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/responsive.bootstrap.min.css">
    <link rel="stylesheet" href="css/lobibox.min.css">
    <link href="css/jquery-ui.css" type="text/css" rel="stylesheet">
    <link href="css/jquery.ui.timepicker.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="js/jquery-1.12.0.min.js"></script>
    <script src="js/jquery-ui.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/responsive.bootstrap.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-3.3.2.min.js"></script>
    <script type="text/javascript" src="js/jquery.blockUI.js"></script>
    <link rel="stylesheet" type="text/css" href="css/snap.css" />
    <link rel="stylesheet" type="text/css" href="css/demo.css" />
    <link href="css/responsive-calendar.css" rel="stylesheet">
    <script src="js/responsive-calendar.js"></script>    
    <link href="js/select2/select2.css" rel="stylesheet" type="text/css" />
    <link href="js/select2/select2-bootstrap.css" rel="stylesheet" type="text/css" />
    <script src="js/select2/select2.js" type="text/javascript"></script>
    <script src="js/jquery.ui.timepicker.js" type="text/javascript"></script>
    <script type='text/javascript' src="http://maps.googleapis.com/maps/api/js?libraries=places&geometry"></script>
    <style>
        #App-tbl tr td{
            padding:5px 0;
        }
    </style>
</head>
<body>   
    <div class="snap-drawers">
        <div class="snap-drawer snap-drawer-left">
            <div>
                <h4><?php echo "how are you ". $_SESSION['UsrDispNm']. " ?" ?></h4>
                <div class="demo-social">
                </div>
                    <ul>
                    <li><a href="javascript:void(0);" onclick="showHome();">Home</a></li>
                    <li><a href="javascript:void(0);" onclick="showProf();">Profile</a></li>
                    <li><a href="javascript:void(0);" onclick="showBookedAppo();">Appointments</a></li>
<!--                    <li><a href="javascript:void(0);" onclick="showReqLst();">Contact Specialist</a></li>                    -->
                    </ul>
                <div>
                </div>
            </div>
        </div>
        <div class="snap-drawer snap-drawer-right"></div>
    </div>

    <div id="content" class="snap-content">
        <div id="toolbar">
            <a href="#" id="open-left"></a>
            <h1 id="deskTitle">Home</h1>           
            <a href="javascript:void(0);" onclick="Logout();" id="logout"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span></a> 
        </div>
        <div id="snap-content-body">
            <div id="divmain" class="form-group col-xs-12">
                <h1 id="heading">
                    <small>
                        <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
                        Services List
                    </small>                    
                    <button onclick="$('#ServFilter').toggle();" type="button" id="btnAdd" class="btn btn-success pull-right">                        
                        <span class="glyphicon glyphicon-filter" aria-hidden="true"></span>
                        Filter
                    </button>                    
                    <small >
<!--                        style="font-size:22px;margin-top:8px;border-radius:7px;outline:none;" -->
                    <input id="ServFilter" placeholder="Filter Services" type="text" style="display:none;width:90%;margin:6px;" class="form-control"/>                
                    </small>                    
                    </h1>                    
                <ul id="ULServLst" class="list-group">
                    
                </ul>
            </div>
            <div id="divAppoLst" class="form-group col-xs-12" style="display:none;">
<!--               <h1 id="heading">
                    <small>
                        <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
                        Booked List
                    </small>                    
                    <button type="button" id="btnAdd" class="btn btn-success pull-right">
                        <span class="glyphicon glyphicon-filter" aria-hidden="true"></span>
                        Filter
                    </button>
                   <small>
                   <input id="BkdFilter" type="text" class="form-control" style="display:none;width:90%;margin:6px;"/>
                   </small>
                </h1>
                <ul id="ULBkAppoLst" class="list-group">
                    
                </ul>-->
        <ul id="BkdAppMntUL" class="nav nav-tabs">
            <li class="active"><a id="AllBked" data-toggle="tab" href="#App-All">All</a></li>
            <li><a data-toggle="tab" href="#App-Pending">Pending</a></li>
            <li><a data-toggle="tab" href="#App-Rejected">Rejected</a></li>
            <li><a data-toggle="tab" href="#App-Approved">Approved</a></li>
            <li><a data-toggle="tab" href="#App-Completed">Completed</a></li>
        </ul>

        <div class="tab-content">
            <div id="App-All" class="tab-pane fade in active">
                <ul id="ULApAll" class="list-group ApptList"></ul>
            </div>            
            <div id="App-Pending" class="tab-pane fade">
                <ul id="ULApPnd" class="list-group ApptList"></ul>
            </div>            
            <div id="App-Rejected" class="tab-pane fade">   
                <ul id="ULApRej" class="list-group ApptList"></ul>
            </div>
            <div id="App-Approved" class="tab-pane fade">   
                <ul id="ULApAppr" class="list-group ApptList"></ul>
            </div>
            <div id="App-Completed" class="tab-pane fade">   
                <ul id="ULApComp" class="list-group ApptList"></ul>
            </div>            
        </div>
            </div>
            <div id="divServDetails" class="form-group col-xs-12" style="display:none;">
                <h1 id="heading">
                    <small>
                        <span class="glyphicon glyphicon-check" aria-hidden="true"></span>
                        <span id="Det_ServName"></span>
                    </small>
                    <button onclick="showHome()" type="button" id="btnAdd" class="btn btn-success pull-right">
                        <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>
                        Back
                    </button>
                </h1>
                <ul id="ServDetailsUL" class="list-group">
                    
                </ul>
                   <!-- Modal -->
<div class="modal fade" id="myMapModal" role="dialog">
    <div class="modal-dialog" style="z-index:9999999999;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <h4 class="modal-title">Route Map</h4>

            </div>
            <div class="modal-body">
                <div class="container" style="width:100%">
                    <div class="row">
                        <table id="rt-tbl" style="width:100%;white-space:pre-wrap">
                                    <tr><td>From</td><td>:</td><td id="rt-frm"> </td></tr>
                                    <tr><td>To</td><td>:</td><td id="rt-to"> </td></tr>
                        </table>
                        <div id="map-canvas" style="margin-top:10px;height:380px;display:table-row;width: 100%;float:left;"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
            </div>
            <div id="divRequest" class="form-group col-xs-12" style="display:none;">
               
            </div>
            <div id="divHistory" class="form-group col-xs-12" style="display:none;">
                <div class="form-group col-sm-12">
                    <div class="col-sm-8">
                        <select onchange="LoadPatDtls(this);" id="patLst"></select>
                    </div>
                </div>
                <div id="DivDocPatHist" class="form-group col-sm-12">
                </div>
            </div>
            <div id="divProf" class="form-horizontal" style="display:none;">
                <form>
                    <div class="form-group col-sm-8">
                        <label class="control-label col-sm-2">Name:</label>
                        <div class="col-sm-8">
                            <input type="text" placeholder="Name" class="form-control" id="txtProfName" disabled readonly/>
                        </div>
                    </div>
                    <div class="form-group col-sm-8">
                        <label class="control-label col-sm-2">Mobile Number:</label>
                        <div class="col-sm-8">
                            <input type="text" onkeypress="return isNumber(event)"  maxlength="10" placeholder="Add Mobile number" class="form-control" id="txtProfMob">
                        </div>
                    </div>
                    <div class="form-group col-sm-8">
                        <label class="control-label col-sm-2">Location:</label>
                        <div class="col-sm-8">
                            <input type="text" placeholder="Add Location" class="form-control" id="txtProfLoc">
                        </div>
                    </div>                   
                    <div class="form-group col-sm-8">
                        <div class="col-sm-8 text-center">
                            <button type="button" onclick="ProfbtnSubmit();" id="btnProfSubmit" class="btn btn-success">
                                <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>
                                Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="divAdd" class="form-horizontal" style="display:none;">
                    <div class="col-sm-8">
                        <h1 id="subheading"></h1>
                    </div>
                    <div class="form-group col-sm-8">
                        <label class="control-label col-sm-2">Name:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="txtName">
                        </div>
                    </div>
                    <div class="form-group col-sm-8">
                        <label class="control-label col-sm-2">Doctor Name:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="txtDocName">
                        </div>
                    </div>
                    <div class="form-group col-sm-8">
                        <label class="control-label col-sm-2">Educational Qualification:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="txtQuaf">
                        </div>
                    </div>
                    <div class="form-group col-sm-8">
                        <label class="control-label col-sm-2">Cost:</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="txtCost">
                        </div>
                    </div>
                    <div class="form-group col-sm-8">
                        <label class="control-label col-sm-2">Location:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="txtLoc">
                        </div>
                    </div>
                    <div class="form-group col-sm-8">
                        <label class="control-label col-sm-2">Description:</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="txtDesc"></textarea>
                        </div>
                    </div>
                    <div class="form-group col-sm-8">
                        <div class="col-sm-8 text-center">
                            <button type="button" id="btnSubmit" class="btn btn-success">
                                <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>
                                Add
                            </button>
                        </div>
                    </div>
            </div>
            <div id="divCalendar" class="container" style="display:none;">
                <div class="responsive-calendar">
                    <div class="controls">
                        <a class="pull-left" data-go="prev"><div class="btn btn-primary">Prev</div></a>
                        <h4><span data-head-year></span> <span data-head-month></span></h4>
                        <a class="pull-right" data-go="next"><div class="btn btn-primary">Next</div></a>
                    </div><hr />
                    <div class="day-headers">
                        <div class="day header">Mon</div>
                        <div class="day header">Tue</div>
                        <div class="day header">Wed</div>
                        <div class="day header">Thu</div>
                        <div class="day header">Fri</div>
                        <div class="day header">Sat</div>
                        <div class="day header">Sun</div>
                    </div>
                    <div class="days" data-group="days">

                    </div>
                </div>
            </div>           
        </div>
    </div>               
    
      <!--Book Modal -->
<div class="modal fade" id="ReqModal" role="dialog">
    <div class="modal-dialog" style="z-index:9999999;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <h4 class="modal-title">Send Request</h4>
            </div>
            <div class="modal-body">
                <div class="container" style="width:100%">
                    <div class="row">
                        <table cellspacing="10" id="App-tbl" style="width:100%;">
                            <tr><td>Date </td><td><input id="App-Dt" readonly type="text"  class="form-control"/> </td></tr>
                            <tr><td>Start Time</td><td><input id="App-Stm" readonly type="text" class="form-control"/> </td></tr>
                            <tr><td>End Time</td><td> <input id="App-Etm" readonly type="text"  class="form-control"/></td></tr>
                            <tr><td>Remarks</td><td> <textarea style="resize: none;" id="App-Rmks" class="form-control"></textarea></td></tr>
                        </table>                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="fnSendServiceRequest()">Send Request</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    
    <script type="text/javascript" src="js/snap.js"></script>
    <script type="text/javascript" src="js/lobibox.js"></script>  
    <script type="text/javascript" src="js/demo.js"></script>
     <script type="text/javascript" src="js/common.js"></script>
     <script src="js/Patient.js" type="text/javascript"></script>
</body>

</html>


 