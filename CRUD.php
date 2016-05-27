<?php
    session_start();
    //$_SESSION['UsrPk'] = 1;    
    $sql_query = "";      
    $Typ = $_POST["action"];
    $UsrFk = $_SESSION['UsrPk'];
    if(empty($UsrFk) || is_null($UsrFk) || $Typ == 'LogOut'){
       session_destroy();
       $result = array("status" => "2");
       echo json_encode($result);       
       exit();
    }
    include 'dbconfig.php';
    // SERVICE CRUD OPERATION START
    if($Typ == 'ServLst'){
        $sql_query = "SELECT DSName,DSDocNm,DSQuaf,IFNULL(DSCost,0) DSCost,DSPk FROM MCDocSerVDtls where DSUsrFk=".$UsrFk;
    }
    else if($Typ == "Pat_ServReq"){
        $ServPk = $_POST["ServicePk"];
        $Dt = $_POST["Date"];
        $FrmTm = $_POST["Stm"];
        $EndTm = $_POST["Etm"]; 
        $Rmks = $_POST["Rmks"]; 
        //PADOCSts = 0 -pending, 1- approved , 2-rejected , 3 - completed
        /*$sql_query = "CREATE TEMPORARY TABLE Temp(tmp_PAUsrFk BIGINT,tmp_PAServFk BIGINT,tmp_PADate VARCHAR,tmp_PAStartTm VARCHAR,tmp_PAEndTm VARCHAR,tmp_PAPatRmks VARCHAR,tmp_PADOCRmks VARCHAR,tmp_PADOCSts BIGINT);".
                    "INSERT Temp INTO VALUES(".$UsrFk.",".$ServPk.",'".$Dt."','".$FrmTm."','".$EndTm."','".$Rmks."','',0)";*/
        $sql_query = " INSERT INTO MCPatAppDtls(PAUsrFk,PAServFk,PADate,PAStartTm,PAEndTm,PAPatRmks,PADOCRmks,PADOCSts) ".
                      " VALUES(".$UsrFk.",".$ServPk.",'".$Dt."','".$FrmTm."','".$EndTm."','".$Rmks."','',0); ";
                    //" SELECT PAUsrFk,PAServFk,PADate,PAStartTm,PAEndTm,PAPatRmks,PADOCRmks,PADOCSts FROM Temp WHERE ".
                    //" NOT EXISTS ( SELECT * FROM MCPatAppDtls WHERE PAUsrFk = ".$UsrFk." AND PAServFk=".$ServPk." AND tmp_PADOCSts = 0); SELECT LAST_INSERT_ID() AS Id";
    }
    else if($Typ == "BkdAppointments"){
        $Filt = $_POST["Filter"];
        $cond = "";
        if($Filt == "All")
            $cond = " " ;        
        if($Filt == "Pending")
            $cond = " AND PADOCSts=0";
        if($Filt == "Approved")
            $cond = " AND PADOCSts=1";
        if($Filt == "Rejected")
            $cond = " AND PADOCSts=2";
        if($Filt == "Completed")
            $cond = " AND PADOCSts=3";
        
        $sql_query = " SELECT *,'".$Filt."' as Filter FROM MCPatAppDtls JOIN MCDocSerVDtls ON DSPk = PAServFk where PAUsrFk = ".$UsrFk . $cond;
    }
    else if($Typ == "UpdateProfile"){
        $Mob = $_POST["MobNum"];
        $Loc = $_POST["Loc"] == null ? "" : $_POST["Loc"];
        $sql_query = " UPDATE McUsrMas SET UsrMob = ".$Mob."  , UsrLoc='".$Loc."' where UsrPk = ".$UsrFk;
    }
    else if($Typ == "ShowProfile" || $Typ == "Pat_ListService" || $Typ == "Pat_ServiceDtl"){
        $condition = "";
        if($Typ == "Pat_ServiceDtl")
            $condition = " WHERE DSPk = ".$_POST["ServPk"];    
        if($Typ == "ShowProfile")
            $sql_query = "SELECT UsrDispNm,IFNULL(UsrMob,'') UsrMob,IFNULL(UsrLoc,'') UsrLoc FROM McUsrMas where UsrPk = ".$UsrFk;
        if($Typ == "Pat_ListService" || $Typ == "Pat_ServiceDtl")
            $sql_query = "SELECT DSName,DSDocNm,DSQuaf,DSCost,DSPk ,IFNULL(DSLocation,'-') DSLocation,DSDesc,DSLocLng,DSLocLat FROM MCDocSerVDtls ".$condition;
    }
    else if($Typ == 'AddServ' || $Typ == 'UptServ'){
        $DSFk = $_POST["DSFk"];
        $name = $_POST["txtName"];
        $docNm = $_POST["txtDocName"];
        $quaf = $_POST["txtQuaf"];
        $cost = $_POST["txtCost"]; 
        $loc = $_POST["txtLoc"];
        $Desc = $_POST["txtDesc"];
        $Lng = $_POST["Lng"];
        $Lat = $_POST["Lat"];
        if($Typ == 'AddServ'){
           $sql_query = "INSERT INTO MCDocSerVDtls(DSUsrFk,DSName,DSDocNm,DSQuaf,DSCost,DSLocation,DSDesc,DSLocLng,DSLocLat) VALUES($UsrFk,'$name','$docNm','$quaf',$cost,'$loc','$Desc','$Lng','$Lng')";
        }else{
           $sql_query = "UPDATE MCDocSerVDtls SET DSName='".$name."',DSDocNm='".$docNm."',DSQuaf='".$quaf."',DSCost=".$cost.",DSLocation='".$loc."',DSDesc='".$Desc."',DSLocLng='".$Lng."',DSLocLat='".$Lat."' WHERE DSPk= ".$DSFk." AND DSUsrFk=".$UsrFk; 
        }
    }
    else if($Typ == 'EditServ'){
        $DSFk = $_POST["DSFk"];       
        $sql_query = "SELECT DSName,DSDocNm,DSQuaf,DSCost,DSLocLat,DSDesc,DSLocLng,DSLocation,DSPk FROM MCDocSerVDtls where DSPk= ".$DSFk." AND DSUsrFk=".$UsrFk;
    }   
    else if($Typ == 'RmvServ'){
        $DCFk = $_POST["DCFk"];       
        $sql_query = "DELETE FROM MCDocSerVDtls WHERE DSPk=".$DCFk;
    }
    // SERVICE CRUD OPERATION END
    
    // APPOINTMENT OPERATION START
    else if($Typ == 'ShwAppo'){            
        $sql_query = "SELECT COUNT(PAUsrFk) AS PAUsrCnt,PADate FROM MCPatAppDtls WHERE PADOCSts = 1 AND PAUsrFk=".$UsrFk." GROUP BY PADate";
    }
    else if($Typ == 'ShwDtAppo'){ 
        $Date = $_POST["Date"];  
        $sql_query = "SELECT UsrDispNm,PAPatRmks,PAStartTm,PAEndTm,PAPk FROM MCPatAppDtls JOIN MCDocSerVDtls ON PAServFk=DSPk JOIN McUsrMas ON UsrPk=PAUsrFk WHERE PADOCSts = 1 AND PADate='".$Date."' AND DSUsrFk=".$UsrFk;
    }
    else if($Typ == "FishPatAppo"){
         $AppoReason = $_POST["AppoReason"];
         $AppoRmvId = $_POST["AppoRmvId"];
         $sql_query = "UPDATE MCPatAppDtls SET PADocRmks='".$AppoReason."',PADOCSts=3 WHERE PAPk=".$AppoRmvId;
    }
    else if($Typ == "RmvPatAppo"){
         $AppoReason = $_POST["AppoReason"];
         $AppoRmvId = $_POST["AppoRmvId"];
         $sql_query = "UPDATE MCPatAppDtls SET PADocRmks='".$AppoReason."',PADOCSts=2 WHERE PAPk=".$AppoRmvId;
    }
    // APPOINTMENT OPERATION FINISH
    
    // PATIENT RWQUEST OPERATION START
    else if($Typ == 'ShwPatReq') {
        $sql_query = "SELECT UsrDispNm,PAPatRmks,PAStartTm,PAEndTm,PAPk FROM MCPatAppDtls JOIN MCDocSerVDtls ON PAServFk=DSPk JOIN McUsrMas ON UsrPk=PAUsrFk WHERE PADOCSts = 0 AND DSUsrFk=".$UsrFk;
    }
    else if($Typ == 'ApprPatAppo'){
       $AppoApprId = $_POST["AppoApprId"]; 
       $sql_query = "UPDATE MCPatAppDtls SET PADOCSts=1 WHERE PAPk=".$AppoApprId;
    }    
    // PATIENT RWQUEST OPERATION END
    
    else if($Typ == 'ShwDocHist'){
        $sql_query = "SELECT UsrDispNm,PAUsrFk FROM MCPatAppDtls JOIN MCDocSerVDtls ON PAServFk=DSPk JOIN McUsrMas ON UsrPk=PAUsrFk WHERE PADOCSts = 3 AND DSUsrFk=".$UsrFk." GROUP BY UsrDispNm,PAUsrFk";
    }
    
    else if($Typ == 'LoadPatDtls'){
        $PatId = $_POST["PatId"]; 
        $sql_query = "SELECT DSDocNm,DSQuaf,PADate,PAStartTm,PAEndTm,PAPatRmks,PADOCRmks FROM MCPatAppDtls JOIN MCDocSerVDtls ON PAServFk=DSPk JOIN McUsrMas ON UsrPk=PAUsrFk WHERE PADOCSts = 3 AND PAUsrFk = ".$PatId."  AND DSUsrFk=".$UsrFk;
    }
    try {
        $result_set=mysqli_query($conn,$sql_query);
        if($result_set)
        {          
            $output = '';            
            $ArrOutput = array();
            if($Typ == 'ServLst' || $Typ == 'EditServ' || $Typ == 'ShwAppo' || $Typ == 'ShwDtAppo' || $Typ == 'ShwPatReq' || $Typ == 'ShwDocHist' || $Typ == 'LoadPatDtls' || $Typ=='ShowProfile' || $Typ == 'Pat_ListService' || $Typ == 'Pat_ServiceDtl' || $Typ == 'BkdAppointments'){
                while($row=mysqli_fetch_row($result_set))
                {
                    if($Typ == 'ServLst'){
                       $output .= '<li class="list-group-item"> '.$row[0] .' </br> Doctor Name:'. $row[1].' '. $row[2].'</br> Cost: &#8377; '. $row[3].'<span onclick="btnDel('.$row[4].');" class="glyphicon glyphicon-trash pull-right" aria-hidden="true"></span> <span onclick="btnUpt('.$row[4].');" style="margin-right:12px;" class="glyphicon glyphicon-edit pull-right" aria-hidden="true"></span></li>';
                    }
                    if($Typ == 'EditServ'){
                       $output .= json_encode(array('DSName' => $row[0],'DSDocNm' => $row[1],'DSQuaf' => $row[2], 'DSCost' => $row[3], 'DSLocLat' => $row[4], 'DSDesc' => $row[5], 'DSLocLng' => $row[6],'DSLocation'=>$row[7],'DSPk' => $row[8]));
                    }
                    if($Typ == 'ShwAppo'){
                        $a = new ArrayObject(array(), ArrayObject::STD_PROP_LIST);
                        $a -> number = $row[0];
                        $ArrOutput["$row[1]"] = $a;
                    }
                    if($Typ == 'ShwDtAppo'){
                        $output .='<li class="list-group-item"> '.$row[0].' </br> Time: '.$row[2].' - '.$row[3].' </br> Remarks: '.$row[1].' '
                                . '</br> <button type="button" onclick="FinishAppo('.$row[4].');" id="btnFinishAppo" class="btn btn-success">
                           <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span> Finish
                           </button> <button type="button" onclick="CancelAppo('.$row[4].');" id="btnCancelAppo" class="btn btn-danger">
                           <span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span> Cancel
                           </button></li>';
                    }
                    if($Typ == 'ShwPatReq'){
                        $output .='<li class="list-group-item"> '.$row[0].' </br> Time: '.$row[2].' - '.$row[3].' </br> Remarks: '.$row[1].' '
                                . '</br> <button type="button" onclick="ApprPatAppo('.$row[4].');" id="btnFinishAppo" class="btn btn-success">
                           <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span> Approve
                           </button> <button type="button" onclick="CancelAppo('.$row[4].');" id="btnCancelAppo" class="btn btn-danger">
                           <span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span> Cancel
                           </button></li>';
                    }
                    if($Typ == 'ShwDocHist'){
                         $output .= '<option value='.$row[1].'>'.$row[0].'</option>';
                    }
                    if($Typ == 'LoadPatDtls'){
                        $output .= '<li class="list-group-item">Doctor Name: '.$row[0].' '. $row[1].' </br> Date: '.$row[2].' </br> Time: '.$row[3].' - '.$row[4].' </br> Patient Remarks: '.$row[5].' </br> Doctor Remarks: '.$row[6].' </li>';
                    }
                    if($Typ == 'ShowProfile' || $Typ == 'Pat_ListService' || $Typ == 'Pat_ServiceDtl' || $Typ == 'Pat_ServReq' || $Typ == 'BkdAppointments'){
                        $a = new ArrayObject(array(), ArrayObject::STD_PROP_LIST);
                        $ColNum = mysqli_num_fields($result_set);
                        $intI = 0;
                        while($intI < $ColNum){                            
                            $field_info = mysqli_fetch_field_direct($result_set, $intI);                            
                            $name = $field_info->name;
                            $a -> $name = $row[$intI];                               
                            $intI++;
                        }                        
                        array_push($ArrOutput, $a);
                    }
                }
            }            
            if($Typ =='Pat_ServReq'){
                $output = "Success";
            }
            if($Typ == "UpdateProfile"){
                $output = $result_set;
            }
            if($Typ == 'ShowProfile' || $Typ == 'Pat_ListService' || $Typ == 'Pat_ServiceDtl' || $Typ == 'BkdAppointments'){
                $output = json_encode($ArrOutput);
            }
            if($output == '' && $Typ == 'ServLst'){
                 $output .= '<li class="list-group-item">No service available.</li>';
            }
            if($Typ == 'ShwAppo'){
                $output = json_encode($ArrOutput);
            }
            if($Typ == 'ShwDtAppo'){
                $output = '<h1 id="heading"><small><span class="glyphicon glyphicon-bell" aria-hidden="true"></span>
                        '.$Date.' Appointments</small></h1><ul class="list-group">'.$output.'</ul>';
            }
            if($output == '' && $Typ == 'ShwPatReq'){
                 $output .= '<li class="list-group-item">No request available.</li>';
            }           
            $result = array("status" => "1","Data" => $output);
            echo json_encode($result);
        }
        else
        {  
            $result = array("status" => "0","error" => mysqli_error($conn));
            echo json_encode($result);
        }
    }catch(Exception $ex){
        $result = array("status" => "0","error" => $ex);
        echo json_encode($result);        
    }
?>