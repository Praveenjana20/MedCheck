<?php
    session_start();
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
    <script src="https://maps.googleapis.com/maps/api/js?libraries=places" type="text/javascript"></script>   
</head>
<body>   
    <div class="snap-drawers">
        <div class="snap-drawer snap-drawer-left">
            <div>
                <h3><?php echo $_SESSION['UsrDispNm'] ?></h3>
                <div class="demo-social">
                </div>
                    <ul>
                        <li><a href="javascript:void(0);" onclick="showHome();">Home</a></li>
                        <li><a href="javascript:void(0);" onclick="showAppo();">Appointments</a></li>
                        <li><a href="javascript:void(0);" onclick="showReqLst();">Request</a></li>
                        <li><a href="javascript:void(0);" onclick="showHistLst();">History</a></li>
                        <!--<li><a href="javascript:void(0);" onclick="showHistLst();">Let's Talk</a></li>-->
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
                        Services
                    </small>
                    <button onclick="btnAdd();" type="button" id="btnAdd" class="btn btn-success pull-right">
                        <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
                        Add
                    </button>
                </h1>
                <ul id="ULServLst" class="list-group">
                    
                </ul>
            </div>
            <div id="divAppoLst" class="form-group col-xs-12" style="display:none;">
               
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
    <script type="text/javascript" src="js/snap.js"></script>
    <script type="text/javascript" src="js/lobibox.js"></script>  
    <script type="text/javascript" src="js/demo.js"></script>
     <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript" src="js/doctor.js"></script>
</body>
</html>
