<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Forgot Password</title>
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
            session_start();   
            if(isset($_SESSION["UsrPk"])){
                $URL="dashboard.php";
                echo ("<script type='text/javascript'>location.href='$URL'</script>");   
            }        
            else{
                session_destroy();
            }            
            $db_host = $host;
            $db_user=$user;
            $db_password=$password;
            $db_database = $datbase;
            $fg_email = "";
            $ErrText = "";
        if(isset($_POST['forgotBtn'])){            
            $fg_email = $_POST['inputEmail'];
            $Exists = fnCheckUsrExists($fg_email);
            if($Exists == "0"){
                $ErrText .= "Email Id not exists.";
            }
            else{
                $msg = 'Your Password is : '.$Exists;
                $mail_sts = mail($fg_email, 'Forgot Password',$msg);
                if($mail_sts)
                    $ErrText .= "Mail sent successfully with your password. ";
                else 
                    $ErrText .= "Mail Not sent.";
            }
        }         
        function fnCheckUsrExists($email){
            global $db_host, $db_user, $db_password, $db_database;                        
            $conn = mysqli_connect($db_host, $db_user, $db_password, $db_database);        
            $sql = " SELECT UsrPwd FROM McUsrMas WHERE UsrEmail = '".$email."' AND UsrDelId=0; ";                                                            
            $selectresult = mysqli_query($conn,$sql);                                                   
            if($selectresult){                            
                $retVal="";
                if(mysqli_num_rows($selectresult)>0){
                    $row=mysqli_fetch_row($selectresult);
                    $retVal = $row[0];
                }            
                else{
                    $retVal = "0";
                }
            }
            else{
                    $retVal =  "0";
            }
            mysqli_close($conn);   
            return $retVal;
        }
        ?>
     
    <div class="container">
        <div class="card card-container">                        
            <img id="profile-img" class="profile-img-card" src="img/icon-mc.png" />
            <p id="profile-name" class="profile-name-card"></p>
            <form name="login-form" action="forgotpassword.php" method="post" class="form-signin">
                <label name="ErrText" style="color:red"><?php echo $ErrText ?></label>
                <span id="reauth-email" class="reauth-email"></span>
                <input type="email" value="<?php echo $Lg_email ?>" name="inputEmail" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>               
                <button name="forgotBtn" class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Send Email</button>
            </form>           
            <a href="index.php" class="forgot-password">
                Already have an account?
            </a>
            <a href="Register.php" class="forgot-password">
                Register Here
            </a>
        </div><!-- /card-container -->
    </div><!-- /container -->              
    </body>
</html>
