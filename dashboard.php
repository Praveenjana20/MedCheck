<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
if(isset($_POST['logout'])){
    session_destroy();   
    $URL="index.php";
    echo ("<script type='text/javascript'>location.href='$URL'</script>");
}
else{
echo " Pk " . $_SESSION["UsrPk"];
echo " Name : "  . $_SESSION["UsrDispNm"];
}
?>
<html>
    <head>
        <title>dashboard</title>
    </head>
    <body>
        <form name="GoBackForm" action="dashboard.php" method="post">
            <input name="logout" type="submit" value="Logout"/>
        </form>
        <a href="UserType.php">set User Type</a>
    </body>    
</html>