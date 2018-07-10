<?php                                                                                                                                                                         
        $cmd = 'ps aux|grep php -c';                                                                                                                                          
        $ret = shell_exec("$cmd");                                                                                                                                            
        $ret = rtrim($ret, "\r\n");                                                                                                                                           
     //   echo "test:".$ret;                                                                                                                                                    
        if($ret > 6) {                                                                                                                                                        
        echo "sussess!";                                                                                                                                                      
        }                                                                                                                                                                     
        else{                                                                                                                                                                 
        echo "fail";                                                                                                                                                          
            }                                                                                                                                                             
?>
