<?php

function AlertBox($status,$Msg){            
            $icon = "warning";
            if($status == "warning")
                $icon="warning";
            else if($status == "error")
                $icon="warning";
            else if($status == "success")
                $icon="ok";
                
            $AlertBox = "<script> Lobibox.notify('$status', {
                                    msg: '$Msg',
                                    icon: 'glyphicon glyphicon-$icon-sign',
                                    position: 'center top',
                                }); </script>";
            echo $AlertBox;
        }
?>