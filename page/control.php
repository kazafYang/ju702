<?php                                                                                                                                                                         
        $cmd = 'ps -ef|grep php|grep -v grep -c';                                                                                                                                          
        $ret = shell_exec("$cmd");                                                                                                                                            
        $ret = rtrim($ret, "\r\n");                                                                                                                                           
     //   echo "test:".$ret;                                                                                                                                                    
        if($ret == 8) {                                                                                                                                                        
        echo "sussess!";                                                                                                                                                      
        }                                                                                                                                                                     
        else{                                                                                                                                                                 
        echo "fail";                                                                                                                                                          
            }                                                                                                                                                             
?>
