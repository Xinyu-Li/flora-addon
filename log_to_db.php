<?php


//********DB Credentials*****************
require_once('./admin/config.php');
//***************************************

header('Access-Control-Allow-Origin: *');

//ini_set('display_errors', '1');
require_once('traceParser.php');

function formatSeconds( $seconds )
{
  $hours = 0;
  $milliseconds = str_replace( "0.", '', round($seconds - floor( $seconds ),4) );

  if ( $seconds > 3600 )
  {
    $hours = floor( $seconds / 3600 );
  }
  $seconds = $seconds % 3600;
  return str_pad( $hours, 2, '0', STR_PAD_LEFT )
       . gmdate( ':i:s', $seconds )
       . ($milliseconds ? ".$milliseconds" : '')
  ;
}


switch ($_SERVER['HTTP_ORIGIN']) {
   default:
    // permitted Access-Control-Allow-Origin
    // case 'http://127.0.0.1': case 'http://localhost:8888': case 'http://127.0.0.1':
    // case 'https://www.floralearn.cn': case 'http://www.floralearn.cn': 
    // case 'https://floralearn.cn': case 'http://floralearn.cn':    // change the URLS to be permitted including subdomains

    header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
//  header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Requested-With');
   // break;
}


$source = $_POST['source'];
$data = json_decode($_POST['data'],true, 8192);

if  (($source == "scaffold") || ($source == "WebGazer") || ($source == "hypothesis") || ($source == "flora-website") || ($source == "flora")){  

$block = "";
$block_desc = "";

$block1_max = 2 * 60 * 1000; // 2.5 * 2 minutes
$block2_max = 7 * 60 * 1000; // 2.5 * 7 minutes
$block3_max = 16 * 60 * 1000; // 2.5 * 16 minutes
$block4_max = 21 * 60 * 1000; // 2.5 * 21 minutes
$block5_max = 35 * 60 * 1000; // 2.5 * 35 minutes
$_max = 45  * 60 * 1000; // 2 * 60 minutes

// DB Credentials
  $servername = $_servername;
  $username = $_username ;
  $password = $_password;
  $dbname = $_dbname;


 if ($source == "hypothesis"){

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  } 

  $from_uri = mysqli_real_escape_string($conn,$_POST['url']);
  $log_uri = serialize($_POST['logServer']);

  $url = $from_uri;
  list($e1,$e2, $_url, $_folder)  = explode("/", $url, 4);

    
  if (($_url == "floralearn.org") || ($_url == "www.floralearn.org") ){

  $d = $_POST['data'];
  $u =  $_POST['uid'];
  $uid =  $_POST['userid'];
  $src = $_SERVER['HTTP_ORIGIN'];
  $session_start = $_POST['session_start'];
  $time_lapsed = $_POST['time_lapsed'];
  $timestamp = $_POST['timestamp'];
  $action = $_POST['action'];
  $sub_action = $_POST['sub_action'];
  $value = mysqli_real_escape_string($conn, $_POST['value']);

  $essay = "";

if ($u == "-99"){
list($a1,$a2)  = explode("_", $uid, 2);

  $u =   $a1;
  $uid =  $a2;
}
    $qry = "SELECT *  FROM user_log WHERE uid = '$u' AND sub_action = 'ESSAY_TASK_START'  ORDER BY time_lapsed DESC LIMIT 1";
    // Perform query
    $result = mysqli_query($conn , $qry);
    $num_rows = mysqli_num_rows($result);
    if ($num_rows == 1){
      $rows = array();
      while($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
      }
      $essay_start = $rows[0]['session_start'];
      $curr_time = $_POST['time_lapsed'];
      $session_start = $essay_start;
      $time_lapsed =  (int)$curr_time - (int)$essay_start;
      $uid = $rows[0]['userid'];

      $u = $rows[0]['uid'];

    $qry = "SELECT * , CURRENT_TIMESTAMP  FROM user_log WHERE uid = '$u' AND source = 'flora'  ORDER BY time_lapsed DESC LIMIT 1";
    // Perform query
    $result = mysqli_query($conn , $qry);
    $num_rows = mysqli_num_rows($result);
    if ($num_rows == 1){
      $rows = array();
      while($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
      }
      $essay = mysqli_real_escape_string($conn, $rows[0]['essay_snapshot']);
    }

     $clock = formatSeconds(((int) $time_lapsed)/1000);
     $action_label = get_ActionLabel($u, $action, $sub_action, $value, $time_lapsed, $conn , $url );
     $process_label = "-"; // get_ProcessLabel($u, $action, $sub_action, $value, $time_lapsed, $conn, $url );

     $time_lapsed = (int) $time_lapsed;

    if (($time_lapsed >= 0) && ($time_lapsed <= $block1_max)){
     $block = "1";
    $block_desc = "Orientation";
    }elseif (($time_lapsed > $block1_max) && ($time_lapsed <= $block2_max)){
     $block = "2";
    $block_desc = "Reading";
    }elseif(($time_lapsed > $block2_max) && ($time_lapsed <= $block3_max)){
     $block = "3";
    $block_desc = "Monitoring of Reading";
    }elseif(($time_lapsed > $block3_max) && ($time_lapsed <= $block4_max)){
     $block = "4";
    $block_desc = "Writing";
    }elseif(($time_lapsed > $block4_max) && ($time_lapsed <= $block5_max)){
     $block = "5";
    $block_desc = "Monitoring of writing";
    }elseif(($time_lapsed > $block5_max) && ($time_lapsed <= $_max)){
    $block = "6";
    $block_desc = "Final block";
    }

    $log = "INSERT INTO user_log(source,userid,uid, session_start, block, block_desc, time_lapsed, clock, timestamp,url, action, sub_action, value, logServer, log_obj, essay_snapshot, action_label, process_label) VALUES ('$source', '$uid', '$u', '$session_start', '$block', '$block_desc','$time_lapsed', '$clock', '$timestamp', '$from_uri', '$action','$sub_action', '$value', '$log_uri', '$d', '$essay', '$action_label', '$process_label' )";

    if ($conn->query($log) === TRUE) {    
    $last_id = $conn->insert_id;
      echo "New record created successfully. Last inserted ID is: " . $last_id;
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
   }
  }
  $conn->close(); 

  }else{

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    //echo "DB Connected successfully";
    $d = $_POST['data'];
    $u =  $_POST['uid'];
    $uid =  $_POST['userid'];
    //var_dump($data);
    $src = $_SERVER['HTTP_ORIGIN'];
    $session_start = $_POST['session_start'];
    $time_lapsed = $_POST['time_lapsed'];
    $timestamp = $_POST['timestamp'];
    $action = $_POST['action'];
    $sub_action = $_POST['sub_action'];
    $value = mysqli_real_escape_string($conn, $_POST['value']);
    $from_uri = mysqli_real_escape_string($conn,$_POST['url']);
    $log_uri = serialize($_POST['logServer']);
    $essay = mysqli_real_escape_string($conn, $_POST['essay']);

    $clock = formatSeconds(((int) $time_lapsed)/1000);
    $action_label = get_ActionLabel($u, $action, $sub_action, $value, $time_lapsed, $conn , $from_uri);
    $process_label = get_ProcessLabel($u, $action, $sub_action, $value, $time_lapsed, $conn, $from_uri);

    $time_lapsed = (int) $time_lapsed;
    if (($time_lapsed >= 0) && ($time_lapsed <= $block1_max)){
     $block = "1";
    $block_desc = "Orientation";
    }elseif (($time_lapsed > $block1_max) && ($time_lapsed <= $block2_max)){
     $block = "2";
    $block_desc = "Reading";
    }elseif(($time_lapsed > $block2_max) && ($time_lapsed <= $block3_max)){
     $block = "3";
    $block_desc = "Monitoring of Reading";
    }elseif(($time_lapsed > $block3_max) && ($time_lapsed <= $block4_max)){
     $block = "4";
    $block_desc = "Writing";
    }elseif(($time_lapsed > $block4_max) && ($time_lapsed <= $block5_max)){
     $block = "5";
    $block_desc = "Monitoring of writing";
    }elseif(($time_lapsed > $block5_max) && ($time_lapsed <= $_max)){
    $block = "6";
    $block_desc = "Final block";
    }

    if ($action == "SCAFFOLD_LOG"){
        $log = "INSERT INTO scaffold_log(source,userid,uid, session_start, block, block_desc, time_lapsed, clock,  timestamp,url, action, sub_action, value, logServer, log_obj, essay_snapshot, action_label, process_label) VALUES ('$source', '$uid', '$u', '$session_start','$block', '$block_desc', '$time_lapsed', '$clock', '$timestamp', '$from_uri', '$action','$sub_action', '$value', '$log_uri', '$d', '$essay', '$action_label', '$process_label')";

    }else{

        $log = "INSERT INTO user_log(source,userid,uid, session_start, block, block_desc, time_lapsed, clock,  timestamp,url, action, sub_action, value, logServer, log_obj, essay_snapshot, action_label, process_label) VALUES ('$source', '$uid', '$u', '$session_start','$block', '$block_desc', '$time_lapsed', '$clock', '$timestamp', '$from_uri', '$action','$sub_action', '$value', '$log_uri', '$d', '$essay', '$action_label', '$process_label')";
    }


    if ($conn->query($log) === TRUE) {    
     $last_id = $conn->insert_id;
        echo "New record created successfully. Last inserted ID is: " . $last_id;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close(); 
    }
  
   }else{
  echo "Hacking Activity";
}
?>
