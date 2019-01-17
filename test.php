<?php 
#var_dump($argv);
#var_dump(explode(' ',$argv[1]));

exec("netstat -anp | grep 0.0.0.0:4000 |awk '{print $7}'",$resp);


var_dump($resp);
var_dump(explode('/',$resp[0])[0]);
