<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/Devp/UserType.css" rel="stylesheet" type="text/css"/>
        <script src="js/jquery.min.js" type="text/javascript"></script>
        <script src="js/bootstrap.min.js" type="text/javascript"></script>
       <script>
       function fnChooseUserTyp(Typ){
        if(Typ == "" || Typ == null || Typ == undefined){return;}    
        $("#UsrTypeText").val(Typ);
            $("#usrtyp").submit();
       }
       </script>
    </head>
    <body>
          <?php                          
            include 'dbconfig.php';
            $db_host = $host;
            $db_user=$user;
            $db_password=$password;
            $db_database = $datbase;
            
            $Lg_email = "";
            $Lg_Pwd = "";
            $ErrText = "";  
            $URL="";
            
            session_start();   
            if(isset($_SESSION["UsrPk"])){                
                 if(isset($_POST["UsrTyp"])){
                        $UserType = $_POST["UsrTyp"];
                        fnUpdateUserType($_SESSION["UsrPk"],$UserType);
                        $UserType = fnGetUserType($_SESSION["UsrPk"]);
                        if($UserType == "1"){
                            $_SESSION["UsrTyp"] = "1";
                            $URL="Doctor.php";
                        }
                        else if($UserType == "2"){
                            $_SESSION["UsrTyp"] = "2";
                            $URL="Patient.php";
                        }       
                        if($URL != "")
                        echo ("<script type='text/javascript'>location.href='$URL'</script>");   
                    }
                    else{
                        $UserType =$_SESSION["UsrTyp"];
                        if($UserType == "1"){
                            $_SESSION["UsrTyp"] = "1";
                            $URL="Doctor.php";
                        }
                        else if($UserType == "2"){
                            $_SESSION["UsrTyp"] = "2";
                            $URL="Patient.php";
                        }
                        if($URL != "")
                        echo ("<script type='text/javascript'>location.href='$URL'</script>");   
                    }
            }               
            else{
                session_destroy();
                $URL="index.php";
                if($URL != "")
                echo ("<script type='text/javascript'>location.href='$URL'</script>");   
            }          
          
          function fnUpdateUserType($UsrPk,$UsrTyp){ // User type 1= doctor , 2 = patients
            global $db_host, $db_user, $db_password, $db_database;                        
            $conn = mysqli_connect($db_host, $db_user, $db_password, $db_database);        
            $sql = " UPDATE McUsrMas SET UsrTyp =$UsrTyp WHERE UsrPk= $UsrPk";                                                            
            $selectresult = mysqli_query($conn,$sql);                                       
            mysqli_close($conn);               
            return $selectresult;
          }
          
          function fnGetUserType($UsrPk){ // User type 1= doctor , 2 = patients
            global $db_host, $db_user, $db_password, $db_database;                        
            $retUserType=0;
            $conn = mysqli_connect($db_host, $db_user, $db_password, $db_database);        
            $sql = " SELECT * FROM McUsrMas WHERE UsrPk=".$UsrPk;                                                            
            $result = mysqli_query($conn,$sql);                                                                 
            if($result){
                if(mysqli_num_rows($result)>0){
                    $row = mysqli_fetch_assoc($result);
                    $retUserType = $row["UsrTyp"];
                }
            }
            mysqli_close($conn); 
            return $retUserType;
          }
          
        ?>     
        <div class="outer">
        <div class="container middle">
            <div class="card card-container">                        
                    <div class="col" onclick="fnChooseUserTyp(1)">
                        <img src="img/doc-icon.jpg" alt="doctor" />
                        <p>Doctor</p>
                    </div>
                    <div class="col" onclick="fnChooseUserTyp(2)">
                        <img src="img/patient-icon.png" alt="Patient" />
                        <p>Patient</p>
                    </div>          
                <form id="usrtyp" style="display: none" action="UserType.php" method="post" name="usrtyp">                    
                    <input type="text" name="UsrTyp" id="UsrTypeText" />
                </form>
            </div><!-- /card-container -->
        </div><!-- /container -->              
    </div>
    </body>
</html>
