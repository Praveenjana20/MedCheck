<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <script src="js/jquery.min.js" type="text/javascript"></script>
        <link rel="stylesheet" href="css/lobibox.min.css">
         <script type="text/javascript" src="js/lobibox.js"></script>  
        <script type="text/javascript" src="js/demo.js"></script>
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/Devp/Login.css" rel="stylesheet" type="text/css"/>        
        <script src="js/bootstrap.min.js" type="text/javascript"></script>
    </head>
    <body>
          <?php            
            include 'AlertBox.php';
            include 'dbconfig.php';
            $URL = "";
            $db_host = $host;
            $db_user=$user;
            $db_password=$password;
            $db_database = $datbase;
            $Lg_email = "";
            $Lg_Pwd = "";
            $ErrText = "";
          
            session_start();   
            if(isset($_SESSION["UsrPk"])){
                    $UserTyp = fnGetUserType($_SESSION["UsrPk"]);
                    if($UserTyp == "1"){
                        $_SESSION["UsrTyp"] = "1";
                        $URL="Doctor.php";
                    }
                    else if($UserTyp == "2"){
                        $_SESSION["UsrTyp"] = "2";
                         $URL="Patient.php";
                    }
                    else{
                        $_SESSION["UsrTyp"] = "0";
                        $URL="UserType.php";
                    }
                    echo ("<script type='text/javascript'>location.href='$URL'</script>");   
            }        
            else{
                session_destroy();
            }         
        if(isset($_POST["loginBtn"])){
            $Lg_email = $_POST["inputEmail"];
            $Lg_Pwd=$_POST["inputPassword"];            
            if(empty($Lg_email) || !filter_var($Lg_email, FILTER_VALIDATE_EMAIL)){
                $ErrText .= "Invalid Email Address. <br/> ";                
            }
            if(empty($Lg_Pwd)){
                $ErrText .= "Type Password. <br/> ";                
            }
            
            if($ErrText == ""){
                $Lg_UsrPk = fnCheckUsrExists($Lg_email,$Lg_Pwd);
                if($Lg_UsrPk==0){
                    //$ErrText .= "User Name/Password wrong ";                                                            
                    AlertBox("error","User Name/Password wrong.");
                }
                else{  
                    $UserTyp = fnGetUserType($Lg_UsrPk);                    
                    if($UserTyp == "1"){
                        $_SESSION["UsrTyp"] = "1";
                        $URL="Doctor.php";
                    }
                    else if($UserTyp == "2"){
                        $_SESSION["UsrTyp"] = "2";
                         $URL="Patient.php";
                    }
                    else{
                        $_SESSION["UsrTyp"] = "0";
                        $URL="UserType.php";
                    }                   
                    echo ("<script type='text/javascript'>location.href='$URL'</script>");
                }
            }           
        }                               
        
        function fnCheckUsrExists($email,$Pwd){
            global $db_host, $db_user, $db_password, $db_database;                        
            try{
            $sql = " SELECT * FROM McUsrMas WHERE UsrEmail = '$email' AND UsrPwd='$Pwd' AND UsrDelId=0; ";                                                            
            $conn = mysqli_connect($db_host, $db_user, $db_password, $db_database);                    
            $result = mysqli_query($conn,$sql);                                                              
            if($result){
                if(mysqli_num_rows($result)>0){
                    $row = mysqli_fetch_assoc($result);                                
                    session_start();
                    $_SESSION["UsrPk"] = $row["UsrPk"];
                    $_SESSION["UsrDispNm"] = $row["UsrDispNm"];
                    mysqli_close($conn);    
                    return $row["UsrPk"];
                }            
            }
            else{
                mysqli_close($conn);    
                return 0;
            }
            }
            catch(Exception $ex){
                AlertBox("error",var_dump($ex));
            }
        }
        
         function fnGetUserType($UsrPk){ // User type 1= doctor , 2 = patients
            global $db_host, $db_user, $db_password, $db_database;                        
            try{
            $retUserType=0;
            $sql = " SELECT * FROM McUsrMas WHERE UsrPk= $UsrPk";                                                            
            $conn = mysqli_connect($db_host, $db_user, $db_password, $db_database);                    
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
           catch(Exception $ex){
                AlertBox("error",var_dump($ex));
            }
         }
        
        ?>
        <!--<form action="" method="POST">            
            <div class="">
            <input type="text" placeholder="User Name" tabindex="1"/>
            <input type="password" placeholder="Password" tabindex="2"/>
            <input type="button" value="Sign In" tabindex="3"/>
            </div>
        </form>-->
    <div class="container">
        <div class="card card-container">                        
            <img id="profile-img" class="profile-img-card" src="img/icon-mc.png" />
            <p id="profile-name" class="profile-name-card"></p>
            <form name="login-form" action="index.php" method="post" class="form-signin">
                <label name="ErrText" style="color:red"><?php echo $ErrText ?></label>
                <span id="reauth-email" class="reauth-email"></span>
                <input type="email" value="<?php echo $Lg_email ?>" name="inputEmail" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
                <input type="password" name="inputPassword" id="inputPassword" class="form-control" placeholder="Password" required>
                <!--<div id="remember" class="checkbox">
                    <label>
                        <input type="checkbox" value="remember-me"> Remember me
                    </label>
                </div>-->
                <button name="loginBtn" class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Sign in</button>
            </form>
            <a href="forgotpassword.php" class="forgot-password">
                Forgot the password?
            </a> &nbsp;
            <a href="Register.php" class="forgot-password">
                Register Here
            </a>
        </div><!-- /card-container -->
    </div><!-- /container -->              
    </body>
</html>
