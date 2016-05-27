<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Register</title>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/Devp/Login.css" rel="stylesheet" type="text/css"/>
        <script src="js/jquery.min.js" type="text/javascript"></script>
        <script src="js/bootstrap.min.js" type="text/javascript"></script>        
    </head>
    <body>
          <?php
            include 'dbconfig.php';
            $db_host = $host;
            $db_user=$user;
            $db_password=$password;
            $db_database = $datbase;
          $ErrText = "";
          $Reg_usrnm = "";
          $Reg_email = "";
          $Reg_Pwd="";
          $Reg_CnfPwd="";
        if(isset($_POST["RegBtn"])){
            $Reg_usrnm = trim($_POST["inputName"]);
            $Reg_email = trim($_POST["inputEmail"]);
            $Reg_Pwd = trim($_POST["inputPassword"]);            
            $Reg_CnfPwd = trim($_POST["CnfPassword"]);           
            if(empty($Reg_usrnm)){
                $ErrText .= "Enter User Name <br/> ";                
            }
            if(empty($Reg_email) || !filter_var($Reg_email, FILTER_VALIDATE_EMAIL)){
                $ErrText .= "Invalid Email Address. <br/> ";                
            }
            if(empty($Reg_Pwd)){
                $ErrText .= "Type Password. <br/> ";                
            }
             if($Reg_Pwd != $Reg_CnfPwd){
                $ErrText .= "Password and confirm password should be same. <br/> ";                 
             }
            
            if($ErrText == ""){
                //mysqli_connect($db_host, $db_user, $db_password, $db_database);
                //echo "success ||||||||||";\
                $UsrExists = fnCheckUsrExists($Reg_email);
                if($UsrExists){
                    $ErrText .= "User email already exists.";
                }
                else{
                    $statusIn = fnRegister($Reg_usrnm,$Reg_email,$Reg_Pwd);
                    if($statusIn)
                        $ErrText .= "Successfully Registered. <a style='color:green;' href='index.php'>Login to use App.</a>";
                    else
                        $ErrText .= "Error occured";                
                }
                
            }           
        }
        
        function fnCheckUsrExists($email){
            global $db_host, $db_user, $db_password, $db_database;                        
            $conn = mysqli_connect($db_host, $db_user, $db_password, $db_database);        
            $sql = " SELECT 1 FROM McUsrMas WHERE UsrEmail = '$email' AND UsrDelId=0; ";                                                            
            $selectresult = mysqli_query($conn,$sql);                                       
            mysqli_close($conn);               
            if(mysqli_num_rows($selectresult)>0){
            return TRUE;
            }            
            else{
                return FALSE;
            }
        }
        
        function fnRegister($UsrName,$email,$Pwd){    
            global $db_host, $db_user, $db_password, $db_database;
            $sql = " INSERT INTO McUsrMas(UsrTyp,UsrDispNm,UsrEmail,UsrPwd,UsrIsCnf,UsrPicPath,UsrActDt,UsrDelId) ".
                   " VALUES(0,'$UsrName','$email','$Pwd',0,'',NOW(),0); ";       
            $conn = mysqli_connect($db_host, $db_user, $db_password, $db_database);        
            $Qsts = mysqli_query($conn,$sql);               
            mysqli_close($conn);               
            return $Qsts;
        }
        
        ?>      
    <div class="container">
        <div class="card card-container">                        
            <img id="profile-img" class="profile-img-card" src="img/icon-mc.png" />
            <p id="profile-name" class="profile-name-card"></p>
            <form name="Reg-form" action="Register.php" method="post" class="form-signin">
                <label id="ErrText" name="ErrText" style="color:red"><?php echo $ErrText ?></label>
                <span id="reauth-email" class="reauth-email"></span>
                <input value="<?php echo $Reg_usrnm ?>" type="text" name="inputName" id="inputName" class="form-control" placeholder="User Name" required autofocus>
                <input value="<?php echo $Reg_email ?>" type="email" name="inputEmail" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
                <input type="password" name="inputPassword" id="inputPassword" class="form-control" placeholder="Enter Password" required>
                <input type="password" name="CnfPassword" id="CnfPassword" class="form-control" placeholder="Confirm Password" required>                
                <button type="submit" name="RegBtn" id="RegBtn" class="btn btn-lg btn-primary btn-block btn-signin">Register</button>
            </form>
            <a href="index.php" class="forgot-password">
                Already have an account?
            </a>
        </div><!-- /card-container -->
    </div><!-- /container -->              
    </body>
</html>
