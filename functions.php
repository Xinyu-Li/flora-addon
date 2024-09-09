<?php
ini_set('display_errors', '1');

//********DB Credentials*****************
require_once('./admin/config.php');
//***************************************


header('Access-Control-Allow-Origin: *');

switch ($_SERVER['HTTP_ORIGIN']) { 

    // URLS permitted for CORS Access-Control-Allow-Origin
    // case 'https://www.floralearn.org': case 'http://www.floralearn.org': 
    // case 'https://floralearn.org': case 'http://floralearn.org': 
    // case 'http://127.0.0.1': case 'http://localhost:8888': case 'https://127.0.0.1':

    default:
    header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    break;
}

$source = $_POST['source'];

if ($source == "flora"){  
// DB Credentials
  $servername = $_servername;
  $username = $_username ;
  $password = $_password;
  $dbname = $_dbname;

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  $conn->set_charset('utf8mb4');

  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  } 
  //echo "DB Connected successfully";

 $funct = $_POST['func'];
 $para1 = $_POST['para1']; // uid variable passed as param1
 $para2 = $_POST['para2']; // id for scaffold
 $para3 = "en"; // langauge variable "en", "de", "nl"
 if (isset($_POST["para3"])) {
    $para3 = $_POST['para3'];  // moodle-language
 }




  if ($funct == "GET_USER_PREVIOUS_SESSION"){
      $qry = "SELECT *, CURRENT_TIMESTAMP  FROM user_log WHERE uid = '$para1' AND sub_action = 'ESSAY_TASK_START'  ORDER BY time_lapsed DESC LIMIT 1";

      // Perform query
      $result = mysqli_query($conn , $qry);
      $rows = array();
      while($r = mysqli_fetch_assoc($result)) {
          $rows[] = $r;
      } 
      //Send the array back as a JSON object
      echo json_encode($rows);
    
 }elseif ($funct == "GET_USER_PREVIOUS_ESSAY"){
      $qry = "SELECT *  FROM user_log WHERE uid = '$para1' AND action = 'ESSAY' AND ((sub_action = 'SAVE') OR (sub_action = 'TYPE') OR (sub_action = 'CLOSE') )  ORDER BY time_lapsed DESC LIMIT 1";

      // Perform query
      $result = mysqli_query($conn , $qry);
      $rows = array();
      while($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
      } 
      //Send the array back as a JSON object
      echo json_encode($rows);

 }elseif ($funct == "UPDATE_PATTERN_LABELS_V3"){

      $sql = "UPDATE user_log SET process_label='-' WHERE uid = '$para1'" ;
      if ($conn->query($sql) === TRUE) {
        // echo json_encode("Record updated successfully " . $sql);
      } else {
        //echo json_encode("Error updating record: " . $conn->error);
      }

      updateProcessLabel_V3($para1 , $conn);

 }elseif ($funct == "DELETE_USER_PREVIOUS_SESSION"){

    $sql = "DELETE FROM user_log WHERE uid = '$para1'";

    if ($conn->query($sql) === TRUE) {
        echo "true";
    } else {
        echo "Error deleting record: " . $conn->error;
    }

} elseif ($funct == "CHECK_USER_TIMEUP"){

    $qry = "SELECT *, CURRENT_TIMESTAMP  FROM user_log WHERE uid = '$para1' AND (sub_action = 'ESSAY_TASK_END' OR sub_action = 'ESSAY_TASK_START')   ORDER BY time_lapsed";

    // Perform query
    $result = mysqli_query($conn , $qry);
    $rows = array();
    while($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
    } 
    //Send the array back as a JSON object
    echo json_encode($rows);
    
 }elseif ($funct == "GET_GENERALISED_SCAFFOLDS_IMPROVED"){
   
    if ($para3 == "zh-cn"){
      $qry = "SELECT * FROM scaffolds_cn_improved WHERE id = '$para2' ";
    }else{
      $qry = "SELECT * FROM scaffolds_improved WHERE id = '$para2' and language = '$para3' ";
    }

    // Perform query
    $result = mysqli_query($conn , $qry);
    $rows = array();
    while($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
    } 
    //Send the array back as a JSON object
    echo json_encode($rows, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
 
 }elseif ($funct == "GET_GENERALISED_SCAFFOLDS"){
    $qry = "SELECT * FROM scaffolds WHERE id = '$para2' and language = '$para3' ";

    // Perform query
    $result = mysqli_query($conn , $qry);
    $rows = array();
    while($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
    } 
    //Send the array back as a JSON object
    echo json_encode($rows,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

 }elseif ($funct == "GET_PERSONALISED_SCAFFOLDS_IMPROVED"){
    // Reset Pattern labels
     $sql = "UPDATE user_log SET process_label='-' WHERE uid = '$para1'" ;
          if ($conn->query($sql) === TRUE) {
            updateProcessLabel_V3($para1 , $conn);

            // Check if patterns existed in prior block
            $q = "";
            switch ((int) $para2) {
              case 1:
                $q = "SELECT uid, SUM(case when action_label = 'NAVIGATION' then 1 else 0 end) as option1, SUM(case when ((process_label = 'MC.O.2') AND (action_label = 'RUBRIC')) then 1 else 0 end) as option2, SUM(case when ((process_label = 'MC.O.2') AND (action_label = 'GENERAL_INSTRUCTION')) then 1 else 0 end) as option3,SUM(case when ((process_label = 'MC.O.3') OR (process_label = 'HC.EO.6')) then 1 else 0 end) as option4 from user_log where uid = '$para1' and block = '$para2' group by uid";
              break;
              case 2:
               $q = "SELECT uid, SUM(case when ((action_label = 'EDIT_ANNOTATION') AND ((process_label = 'HC.EO.6') OR  (process_label = 'LC.F.1') OR (process_label = 'LC.F.4')) ) then 1 else 0 end) as option1, SUM(case when action_label = 'NAVIGATION' then 1 else 0 end) as option2, SUM(case when process_label = 'MC.M.4' then 1 else 0 end) as option3, SUM(case when process_label = 'MC.P.4' then 1 else 0 end) as option4 from user_log where uid = '$para1' and block = '$para2' group by uid";
              break;
              case 3:
                $q = "SELECT uid, SUM(case when (((process_label = 'MC.M.1') OR (process_label = 'MC.M.7') OR (process_label = 'MC.E.1')) AND (action_label = 'READ_ANNOTATION')) then 1 else 0 end) as option1, SUM(case when ((action_label = 'GENERAL_INSTRUCTION') AND (process_label = 'MC.E.1')) then 1 else 0 end) as option2, SUM(case when ((process_label = 'MC.E.2') AND (LENGTH(essay_snapshot) > 0) )then 1 else 0 end) as option3, SUM(case when ((process_label = 'MC.E.1') AND (action_label = 'RUBRIC')) then 1 else 0 end) as option4 from user_log where uid = '$para1' and block = '$para2' group by uid";
              break;
              case 4:
                $q = "SELECT uid, SUM(case when process_label = 'MC.M.4' then 1 else 0 end) as option1, SUM(case when (((process_label = 'MC.M.3') OR (process_label = 'HC.EO.2')) AND (action_label = 'RUBRIC')) then 1 else 0 end) as option2, SUM(case when ((process_label = 'HC.EO.3') OR (process_label = 'HC.EO.1')) then 1 else 0 end) as option3, SUM(case when ((process_label = 'HC.EO.4') OR (process_label = 'MC.M.6') AND (process_label = 'MC.M.7')) then 1 else 0 end) as option4  from user_log where uid = '$para1' and block = '$para2' group by uid";
              break;
              case 5:
                $q = "SELECT uid,  SUM(case when (((process_label = 'MC.M.3') OR (process_label = 'HC.EO.2') OR (process_label = 'MC.E.2')) AND (action_label = 'RUBRIC')) then 1 else 0 end) as option1,  SUM(case when process_label = 'HC.EO.3' then 1 else 0 end) as option2, SUM(case when (((process_label = 'MC.M.3') OR (process_label = 'HC.EO.2') OR (process_label = 'MC.E.2')) AND (action_label = 'GENERAL_INSTRUCTION'))  then 1 else 0 end) as option3, SUM(case when (process_label = 'MC.M.4') then 1 else 0 end) as option4 from user_log where  uid = '$para1' and block = '$para2' group by uid";
              break;
              default:
            }


            $count = array();

            $result_1 = mysqli_query($conn , $q);
            $num_rows_1 = mysqli_num_rows($result_1);
            while($r_ = mysqli_fetch_assoc($result_1)) {
              $count[] = $r_;
            }

            
            if ($para3 == "zh-cn"){
              $qry = "SELECT * FROM scaffolds_cn_improved WHERE id = '$para2'";
            }else{
              $qry = "SELECT * FROM scaffolds_improved WHERE id = '$para2' and language = '$para3' ";
            }


             // Perform query
             $result = mysqli_query($conn , $qry);
             $rows = array();
             while($r = mysqli_fetch_assoc($result)) {
              // Perform query
               if ($num_rows_1 == 1){
                  $r['option1_enabled'] = $count[0]['option1'] == "0"? "1" : "-1";
                  $r['option2_enabled'] = $count[0]['option2'] == "0"? "1" : "-1";
                  $r['option3_enabled'] = $count[0]['option3'] == "0"? "1" : "-1";
                  $r['option4_enabled'] = $count[0]['option4'] == "0"? "1" : "-1";
               }
               $rows[] = $r;
             } 
             //Send the array back as a JSON object
             echo json_encode($rows);
        }

 }elseif ($funct == "GET_PERSONALISED_SCAFFOLDS"){
     
     // Reset Pattern labels
     $sql = "UPDATE user_log SET process_label='-' WHERE uid = '$para1'" ;
          if ($conn->query($sql) === TRUE) {
            updateProcessLabel_V3($para1 , $conn);
            
            // Check if patterns existed in prior block
            $q = "";
            switch ((int) $para2) {
              case 1:
                $q = "SELECT uid, SUM(case when ((process_label = 'MC.O.2') AND (action_label = 'GENERAL_INSTRUCTION')) then 1 else 0 end) as option1,SUM(case when ((process_label = 'MC.O.2') AND (action_label = 'RUBRIC')) then 1 else 0 end) as option2, SUM(case when action_label = 'NAVIGATION' then 1 else 0 end) as option3, SUM(case when ((process_label = 'MC.O.3') OR (process_label = 'HC.EO.6')) then 1 else 0 end) as option4 from user_log where uid = '$para1' and block = '$para2' group by uid";
              break;
              case 2:
               $q = "SELECT uid, SUM(case when action_label = 'NAVIGATION' then 1 else 0 end) as option1, SUM(case when process_label = 'MC.P.4' then 1 else 0 end) as option2, SUM(case when ((action_label = 'EDIT_ANNOTATION') AND ((process_label = 'HC.EO.6') OR  (process_label = 'LC.F.1') OR (process_label = 'LC.F.4')) ) then 1 else 0 end) as option3, SUM(case when process_label = 'MC.M.4' then 1 else 0 end) as option4 from user_log where uid = '$para1' and block = '$para2' group by uid";
              break;
              case 3:
                $q = "SELECT uid, SUM(case when (((process_label = 'MC.M.1') OR (process_label = 'MC.M.7') OR (process_label = 'MC.E.1')) AND (action_label = 'READ_ANNOTATION')) then 1 else 0 end) as option1, SUM(case when ((process_label = 'MC.E.1') AND (action_label = 'RUBRIC')) then 1 else 0 end) as option2, SUM(case when ((process_label = 'MC.E.2') AND (LENGTH(essay_snapshot) > 0) )then 1 else 0 end) as option3, SUM(case when ((action_label = 'GENERAL_INSTRUCTION') AND (process_label = 'MC.E.1')) then 1 else 0 end) as option4 from user_log where uid = '$para1' and block = '$para2' group by uid";
              break;
              case 4:
                $q = "SELECT uid,  SUM(case when ((process_label = 'HC.EO.3') OR (process_label = 'HC.EO.1')) then 1 else 0 end) as option1,  SUM(case when (((process_label = 'MC.M.3') OR (process_label = 'HC.EO.2')) AND (action_label = 'RUBRIC')) then 1 else 0 end) as option2, SUM(case when process_label = 'MC.M.4' then 1 else 0 end) as option3, SUM(case when ((process_label = 'HC.EO.4') OR (process_label = 'MC.M.6') AND (process_label = 'MC.M.7')) then 1 else 0 end) as option4  from user_log where uid = '$para1' and block = '$para2' group by uid";
              break;
              case 5:
                $q = "SELECT uid,  SUM(case when (((process_label = 'MC.M.3') OR (process_label = 'HC.EO.2') OR (process_label = 'MC.E.2')) AND (action_label = 'RUBRIC')) then 1 else 0 end) as option1,  SUM(case when (process_label = 'MC.M.4') then 1 else 0 end) as option2, SUM(case when process_label = 'HC.EO.3' then 1 else 0 end) as option3, SUM(case when (((process_label = 'MC.M.3') OR (process_label = 'HC.EO.2') OR (process_label = 'MC.E.2')) AND (action_label = 'GENERAL_INSTRUCTION'))  then 1 else 0 end) as option4 from user_log where  uid = '$para1' and block = '$para2' group by uid";
              break;
              default:
            }


          $count = array();

          $result_1 = mysqli_query($conn , $q);
          $num_rows_1 = mysqli_num_rows($result_1);
          while($r_ = mysqli_fetch_assoc($result_1)) {
              $count[] = $r_;
          }

        
          // Special check for Chinese langauge support - since due to  special character format, data is in a separate Table.
          if ($para3 == "zh-cn"){
            $qry = "SELECT * FROM scaffolds_cn_improved WHERE id = '$para2'";
          }else{
           $qry = "SELECT * FROM scaffolds WHERE id = '$para2' and language = '$para3' ";
          }


              // Perform query
              $result = mysqli_query($conn , $qry);
              $rows = array();
              while($r = mysqli_fetch_assoc($result)) {
               // Perform query
                if ($num_rows_1 == 1){
                  $r['option1_enabled'] = $count[0]['option1'] == "0"? "1" : "-1";
                  $r['option2_enabled'] = $count[0]['option2'] == "0"? "1" : "-1";
                  $r['option3_enabled'] = $count[0]['option3'] == "0"? "1" : "-1";
                  $r['option4_enabled'] = $count[0]['option4'] == "0"? "1" : "-1";
                }
                $rows[] = $r;
              } 
              //Send the array back as a JSON object
                echo json_encode($rows);

      }

  }else{
     echo json_encode("Invalid Request");
  }


 

}else{
  echo "Hacking Activity";
}




 function str_ends_with($haystack,$needle,$case=true) {
      $expectedPosition = strlen($haystack) - strlen($needle);
      if ($case){
          return strrpos($haystack, $needle, 0) === $expectedPosition;
      }
      return strripos($haystack, $needle, 0) === $expectedPosition;
  }

  function performSelect($qry, $param , $conn){
    $result = mysqli_query($conn , $qry); 
    $num_rows = mysqli_num_rows($result);
    return $num_rows ; 
  }

function updateProcessLabel_V3($uid , $con){

 $process_label = "-";
 $process_from = 0;
 $process_to = 0;
 $_time_lapsed = []; 
 $_action_label = []; 
 $_last_time_lapsed = 0;
 $num_rows = 0;

 $qry = "SELECT uid, prev_time_lapsed, prev_row, prev_label, time_lapsed
      FROM (
          SELECT  @prev_row := @row as prev_row
        , @prev_label := @status as prev_label
              , @prev_time_lapsed := CASE WHEN @time_lapsed=time_lapsed
                AND @member = uid AND @status=action_label 
              THEN @time_lapsed - 5 ELSE @time_lapsed END as prev_time_lapsed
              , @row := CASE WHEN @status=action_label 
                AND @member = uid
              THEN @row + 1 ELSE 1 END as row
              , @member := uid as uid
              , @status := action_label as action_label
              , @time_lapsed := time_lapsed as time_lapsed
          FROM user_log d 
              , (SELECT  @row := 0, @status := '', @member := 0, @time_lapsed := 0)  v WHERE uid = '$uid' AND process_label = '-'
      ORDER BY `time_lapsed` ASC
       ) as n
    WHERE (prev_row = 1) OR ( ((prev_row = 2) OR (prev_label <> action_label)) AND prev_label in ('WRITE_ESSAY','GENERAL_INSTRUCTION', 'RUBRIC' , 'IRRELEVANT_READING' , 'RELEVANT_READING' , 'IRRELEVANT_REREADING' , 'RELEVANT_REREADING') )  
   ORDER BY uid, time_lapsed ASC
   ";

  $result = mysqli_query($con , $qry); 
  $num_rows = mysqli_num_rows($result);
  
  while($row = mysqli_fetch_assoc($result)) {
      $_id = ""; // have non unique _time_lapsed
      $_time_lapsed[] = $row['prev_time_lapsed'];
      $_action_label[] = $row['prev_label'];
      $_last_time_lapsed = $row['time_lapsed'];
  }

  for ($x = 0; $x < 10; $x++) {
      $_time_lapsed[] = (int) $_last_time_lapsed + $x;
      $_action_label[] = "DUMMY_ACTION_" . $x;
  }


  while (count($_action_label) >= 10)
  {

     // Start with the highest priority patterns and move down the list

  // The first two patterns are unique as they have 'READING'
  // type actions starred - i.e. we want to match one, two or
  // three contiguous elements of the same type. 
     $activation_count = (int) ($_time_lapsed[0] / 60000);
     $pattern = array();
  // PATTERN PRIORITY 1
  if ((str_ends_with($_action_label[0],'RELEVANT_READING') || (str_ends_with($_action_label[0],'REREADING'))) &&
      ($_action_label[1] ==='NAVIGATION') &&
      (($_action_label[2] === 'GENERAL_INSTRUCTION') || ($_action_label[2] === 'RUBRIC')|| ($_action_label[2] === 'READ_ANNOTATION')) &&
      ($_action_label[3]=='NAVIGATION') &&
      (($_action_label[4] === 'RELEVANT_READING') || ($_action_label[4] === 'RELEVANT_REREADING')) ) {
      $pattern = array( "label" =>"MC.E.1", "category" => "MC", "sub_category" => "E", "sub_pattern" =>  "1", "span" =>  5, "priority" =>  1, "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[5], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1], $_action_label[2], $_action_label[3], $_action_label[4] ));
  } else if ((str_ends_with($_action_label[0],'RELEVANT_READING') || (str_ends_with($_action_label[0],'REREADING'))) &&
       (($_action_label[1] === 'GENERAL_INSTRUCTION') || ($_action_label[1] === 'RUBRIC')|| ($_action_label[1] === 'READ_ANNOTATION')) &&
       ((str_ends_with($_action_label[2],'RELEVANT_READING') || (str_ends_with($_action_label[2],'REREADING'))))) {
      $pattern = array( "label" =>"MC.E.1", "category" => "MC", "sub_category" => "E", "sub_pattern" =>  "1", "span" =>  3, "priority" =>  1,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[3], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1], $_action_label[2] ));
  } else if ((str_ends_with($_action_label[0],'RELEVANT_READING') || (str_ends_with($_action_label[0],'REREADING'))) &&
       ($_action_label[1] ==='NAVIGATION') &&
       ((($_action_label[2] === 'GENERAL_INSTRUCTION') && ($_action_label[3] === 'GENERAL_INSTRUCTION')) || (($_action_label[2] === 'RUBRIC') && ($_action_label[3] === 'RUBRIC')) || (($_action_label[2] === 'READ_ANNOTATION') && ($_action_label[3] === 'READ_ANNOTATION'))) &&
       ($_action_label[4]=='NAVIGATION') &&
       (($_action_label[5] === 'RELEVANT_READING') || ($_action_label[5] === 'RELEVANT_REREADING')) ) {
      $pattern = array( "label" =>"MC.E.1", "category" => "MC", "sub_category" => "E", "sub_pattern" =>  "1", "span" =>  6, "priority" =>  1,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[5], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1], $_action_label[2], $_action_label[3], $_action_label[4], $_action_label[5] ));
  } else if ((str_ends_with($_action_label[0],'RELEVANT_READING') || (str_ends_with($_action_label[0],'REREADING'))) &&
       ((($_action_label[2] === 'GENERAL_INSTRUCTION') && ($_action_label[1] === 'GENERAL_INSTRUCTION')) || (($_action_label[2] === 'RUBRIC') && ($_action_label[1] === 'RUBRIC')) || (($_action_label[2] === 'READ_ANNOTATION') && ($_action_label[1] === 'READ_ANNOTATION'))) &&
       ((str_ends_with($_action_label[3],'RELEVANT_READING') || (str_ends_with($_action_label[3],'REREADING'))))) {
      $pattern = array( "label" =>"MC.E.1", "category" => "MC", "sub_category" => "E", "sub_pattern" =>  "1", "span" =>  4, "priority" =>  1,  "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[3], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1], $_action_label[2], $_action_label[3] ));
  } else if ((str_ends_with($_action_label[0],'RELEVANT_READING') || (str_ends_with($_action_label[0],'REREADING'))) &&
       ($_action_label[1] ==='NAVIGATION') &&
       ((($_action_label[2] === 'GENERAL_INSTRUCTION') && ($_action_label[3] === 'GENERAL_INSTRUCTION') && ($_action_label[4] === 'GENERAL_INSTRUCTION')) || (($_action_label[2] === 'RUBRIC') && ($_action_label[3] === 'RUBRIC') && ($_action_label[4] === 'RUBRIC')) || (($_action_label[2] === 'READ_ANNOTATION') && ($_action_label[3] === 'READ_ANNOTATION') && ($_action_label[4] === 'READ_ANNOTATION'))) &&
       ($_action_label[5]=='NAVIGATION') &&
       (($_action_label[6] === 'RELEVANT_READING') || ($_action_label[6] === 'RELEVANT_REREADING')) ) {
      $pattern = array( "label" =>"MC.E.1", "category" => "MC", "sub_category" => "E", "sub_pattern" =>  "1", "span" =>  7, "priority" =>  1,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[7], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1], $_action_label[2], $_action_label[3], $_action_label[4], $_action_label[5], $_action_label[6] ));
  } else if ((str_ends_with($_action_label[0],'RELEVANT_READING') || (str_ends_with($_action_label[0],'REREADING'))) &&
       ((($_action_label[2] === 'GENERAL_INSTRUCTION') && ($_action_label[3] === 'GENERAL_INSTRUCTION') && ($_action_label[1] === 'GENERAL_INSTRUCTION')) || (($_action_label[2] === 'RUBRIC') && ($_action_label[3] === 'RUBRIC') && ($_action_label[1] === 'RUBRIC')) || (($_action_label[2] === 'READ_ANNOTATION') && ($_action_label[3] === 'READ_ANNOTATION') && ($_action_label[1] === 'READ_ANNOTATION'))) &&
       ((str_ends_with($_action_label[4],'RELEVANT_READING') || (str_ends_with($_action_label[4],'REREADING'))))) {
      $pattern = array( "label" =>"MC.E.1", "category" => "MC", "sub_category" => "E", "sub_pattern" =>  "1", "span" =>  5, "priority" =>  1,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[3], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1], $_action_label[2], $_action_label[3], $_action_label[4] ));



  // PATTERN PRIOTITY 2
  } else if (($_action_label[0] === 'OPEN_ESSAY') &&
        ($_action_label[1] ==='NAVIGATION') &&
       (($_action_label[2] === 'GENERAL_INSTRUCTION') || ($_action_label[2] === 'RUBRIC')|| ($_action_label[2] === 'READ_ANNOTATION')) &&
        ($_action_label[3] ==='NAVIGATION') &&
       ((str_ends_with($_action_label[4],'RELEVANT_READING')) || (str_ends_with($_action_label[4],'RELEVANT_REREADING')) || ($_action_label[4] === 'OPEN_ESSAY')) ) {
      $pattern = array( "label" =>"MC.E.2", "category" => "MC", "sub_category" => "E", "sub_pattern" =>  "2", "span" =>  5, "priority" =>  2,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[5], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1], $_action_label[2], $_action_label[3], $_action_label[4] ));
  } else if (($_action_label[0] === 'OPEN_ESSAY') &&
       (($_action_label[1] === 'GENERAL_INSTRUCTION') || ($_action_label[1] === 'RUBRIC')|| ($_action_label[1] === 'READ_ANNOTATION')) &&
       ((str_ends_with($_action_label[2],'RELEVANT_READING')) || (str_ends_with($_action_label[2],'RELEVANT_REREADING')) || ($_action_label[2] === 'OPEN_ESSAY')) ) {
      $pattern = array( "label" =>"MC.E.2", "category" => "MC", "sub_category" => "E", "sub_pattern" =>  "2", "span" =>  3, "priority" =>  2,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[3], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1], $_action_label[2] ));
  } else if (($_action_label[0] === 'OPEN_ESSAY') &&
        ($_action_label[1] ==='NAVIGATION') &&
       ((($_action_label[2] === 'GENERAL_INSTRUCTION') && ($_action_label[3] === 'GENERAL_INSTRUCTION')) || (($_action_label[2] === 'RUBRIC') && ($_action_label[3] === 'RUBRIC')) || (($_action_label[2] === 'READ_ANNOTATION') && ($_action_label[3] === 'READ_ANNOTATION'))) &&
        ($_action_label[4] ==='NAVIGATION') &&
       ((str_ends_with($_action_label[5],'RELEVANT_READING')) || (str_ends_with($_action_label[5],'RELEVANT_REREADING')) || ($_action_label[5] === 'OPEN_ESSAY')) ) {
      $pattern = array( "label" =>"MC.E.2", "category" => "MC", "sub_category" => "E", "sub_pattern" =>  "2", "span" =>  6, "priority" =>  2,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[5], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1], $_action_label[2], $_action_label[3], $_action_label[4], $_action_label[5] ));
  } 

  else if (($_action_label[0] === 'OPEN_ESSAY') &&
       ((($_action_label[2] === 'GENERAL_INSTRUCTION') && ($_action_label[1] === 'GENERAL_INSTRUCTION')) || (($_action_label[2] === 'RUBRIC') && ($_action_label[1] === 'RUBRIC')) || (($_action_label[2] === 'READ_ANNOTATION') && ($_action_label[1] === 'READ_ANNOTATION'))) &&
       ((str_ends_with($_action_label[3],'RELEVANT_READING')) || (str_ends_with($_action_label[3],'RELEVANT_REREADING')) || ($_action_label[3] === 'OPEN_ESSAY')) ) {
      $pattern = array( "label" =>"MC.E.2", "category" => "MC", "sub_category" => "E", "sub_pattern" =>  "2", "span" =>  4, "priority" =>  2,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[4], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1], $_action_label[2], $_action_label[3] ));
  } else if (($_action_label[0] === 'OPEN_ESSAY') &&
        ($_action_label[1] ==='NAVIGATION') &&
       ((($_action_label[2] === 'GENERAL_INSTRUCTION') && ($_action_label[3] === 'GENERAL_INSTRUCTION') && ($_action_label[4] === 'GENERAL_INSTRUCTION')) || (($_action_label[2] === 'RUBRIC') && ($_action_label[3] === 'RUBRIC') && ($_action_label[4] === 'RUBRIC')) || (($_action_label[2] === 'READ_ANNOTATION') && ($_action_label[3] === 'READ_ANNOTATION') && ($_action_label[4] === 'READ_ANNOTATION'))) &&
        ($_action_label[5] ==='NAVIGATION') &&
       ((str_ends_with($_action_label[6],'RELEVANT_READING')) || (str_ends_with($_action_label[6],'RELEVANT_REREADING')) || ($_action_label[6] === 'OPEN_ESSAY')) ) {
      $pattern = array( "label" =>"MC.E.2", "category" => "MC", "sub_category" => "E", "sub_pattern" =>  "2", "span" =>  7, "priority" =>  2,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[7], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1], $_action_label[2], $_action_label[3], $_action_label[4], $_action_label[5], $_action_label[6] ));
  } else if (($_action_label[0] === 'OPEN_ESSAY') &&
       ((($_action_label[2] === 'GENERAL_INSTRUCTION') && ($_action_label[3] === 'GENERAL_INSTRUCTION') && ($_action_label[1] === 'GENERAL_INSTRUCTION')) || (($_action_label[2] === 'RUBRIC') && ($_action_label[3] === 'RUBRIC') && ($_action_label[1] === 'RUBRIC')) || (($_action_label[2] === 'READ_ANNOTATION') && ($_action_label[3] === 'READ_ANNOTATION') && ($_action_label[1] === 'READ_ANNOTATION'))) &&
       ((str_ends_with($_action_label[4],'RELEVANT_READING')) || (str_ends_with($_action_label[4],'RELEVANT_REREADING')) || ($_action_label[4] === 'OPEN_ESSAY')) ) {
      $pattern = array( "label" =>"MC.E.2", "category" => "MC", "sub_category" => "E", "sub_pattern" =>  "2", "span" =>  5, "priority" =>  2,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[5], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1], $_action_label[2], $_action_label[3], $_action_label[4] ));

  // The rest are much simpler!
        } else if ((($_action_label[0] === 'GENERAL_INSTRUCTION') || ($_action_label[0] === 'RUBRIC')) && ($_action_label[1] ==='RELEVANT_READING')) {
      $pattern = array( "label" =>"MC.O.1", "category" => "MC", "sub_category" => "O", "sub_pattern" =>  "1", "span" =>  2, "priority" =>  3,  "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[2], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1] ));
  } else if ((($_action_label[0] === 'GENERAL_INSTRUCTION') || ($_action_label[0] === 'RUBRIC')) && ($_action_label[1] ==='NAVIGATION') && ($_action_label[2] ==='RELEVANT_READING')) {
      $pattern = array( "label" =>"MC.O.1", "category" => "MC", "sub_category" => "O", "sub_pattern" =>  "1", "span" =>  3, "priority" =>  3,  "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[3], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1], $_action_label[2] ));
  } else if (($_action_label[0] === 'PLANNER') && ($_action_label[1] ==='RELEVANT_READING')) {
      $pattern = array( "label" =>"MC.P.1", "category" => "MC", "sub_category" => "P", "sub_pattern" =>  "1", "span" =>  2, "priority" =>  4,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[2], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1] ));
  } else if (($_action_label[0] === 'PLANNER') && ($_action_label[1] ==='NAVIGATION') && ($_action_label[2] ==='RELEVANT_READING')) {
      $pattern = array( "label" =>"MC.P.1", "category" => "MC", "sub_category" => "P", "sub_pattern" =>  "1", "span" =>  3, "priority" =>  4,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[3], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1], $_action_label[2] ));
  } else if ((($_action_label[0] === 'GENERAL_INSTRUCTION') || ($_action_label[0] === 'RUBRIC')) &&($_action_label[1] === 'OPEN_ESSAY') && ($_action_label[2] === 'WRITE_ESSAY')  && ($_action_label[3] === 'WRITE_ESSAY') ) { // add condition that there are more than one WRITE_ESSAY elements (a3.count>1) ??
      $pattern = array( "label" =>"HC.EO.2", "category" => "HC", "sub_category" => "EO", "sub_pattern" =>  "2", "span" =>  2, "priority" =>  5,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[4], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1], $_action_label[2] , $_action_label[3] ));
  }else if ((($_action_label[0] === 'GENERAL_INSTRUCTION') || ($_action_label[0] === 'RUBRIC')) &&($_action_label[1] === 'OPEN_ESSAY') && ($_action_label[2] === 'WRITE_ESSAY') ) { // add condition that there are more than one WRITE_ESSAY elements (a3.count>1) ??
      $pattern = array( "label" =>"HC.EO.2", "category" => "HC", "sub_category" => "EO", "sub_pattern" =>  "2", "span" =>  2, "priority" =>  5,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[3], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1], $_action_label[2] ));
  } else if ((($_action_label[0] === 'GENERAL_INSTRUCTION') || ($_action_label[0] === 'RUBRIC')) &&
       ($_action_label[1] === 'NAVIGATION') &&   // opening essay tool causes a NAVIGATION TOO
       ($_action_label[2]=== 'OPEN_ESSAY' ) && ($_action_label[3] === 'WRITE_ESSAY') && ($_action_label[4] === 'WRITE_ESSAY') ) {
      $pattern = array( "label" =>"HC.EO.2", "category" => "HC", "sub_category" => "EO", "sub_pattern" =>  "2", "span" =>  3, "priority" =>  5,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[5], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1], $_action_label[2], $_action_label[3] , $_action_label[4]));
  } else if ((($_action_label[0] === 'GENERAL_INSTRUCTION') || ($_action_label[0] === 'RUBRIC')) &&
       ($_action_label[1] === 'NAVIGATION') &&   // opening essay tool causes a NAVIGATION TOO
       ($_action_label[2]=== 'OPEN_ESSAY' ) && ($_action_label[3] === 'WRITE_ESSAY') ) {
      $pattern = array( "label" =>"HC.EO.2", "category" => "HC", "sub_category" => "EO", "sub_pattern" =>  "2", "span" =>  3, "priority" =>  5,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[4], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1], $_action_label[2], $_action_label[3] ));
  } else if ((($_action_label[0] === 'IRRELEVANT_REREADING') || ($_action_label[0] === 'RELEVANT_REREADING')) && ($_action_label[1] === 'OPEN_ESSAY') && ($_action_label[2] === 'WRITE_ESSAY') && ($_action_label[3] === 'WRITE_ESSAY') ) { // add condition that there are more than one WRITE_ESSAY elements (a3.count>1) ??
      $pattern = array( "label" =>"HC.EO.1", "category" => "HC", "sub_category" => "EO", "sub_pattern" =>  "1", "span" =>  2, "priority" =>  6,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[4], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1] , $_action_label[2] , $_action_label[3]));
  }else if ((($_action_label[0] === 'IRRELEVANT_REREADING') || ($_action_label[0] === 'RELEVANT_REREADING')) && ($_action_label[1] === 'OPEN_ESSAY') && ($_action_label[2] === 'WRITE_ESSAY') ) { // add condition that there are more than one WRITE_ESSAY elements (a3.count>1) ??
      $pattern = array( "label" =>"HC.EO.1", "category" => "HC", "sub_category" => "EO", "sub_pattern" =>  "1", "span" =>  2, "priority" =>  6,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[3], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1] , $_action_label[2]));
  } else if ((($_action_label[0] === 'IRRELEVANT_REREADING') || ($_action_label[0] === 'RELEVANT_REREADING')) &&
       ($_action_label[1] === 'NAVIGATION') &&   // opening essay tool causes a NAVIGATION TOO
       ($_action_label[2]=== 'OPEN_ESSAY') && ($_action_label[3] === 'WRITE_ESSAY') && ($_action_label[4] === 'WRITE_ESSAY') ) {
      $pattern = array( "label" =>"HC.EO.1", "category" => "HC", "sub_category" => "EO", "sub_pattern" =>  "1", "span" =>  3, "priority" =>  6,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[5], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1], $_action_label[2] , $_action_label[3], $_action_label[4]));
  } else if ((($_action_label[0] === 'IRRELEVANT_REREADING') || ($_action_label[0] === 'RELEVANT_REREADING')) &&
       ($_action_label[1] === 'NAVIGATION') &&   // opening essay tool causes a NAVIGATION TOO
       ($_action_label[2]=== 'OPEN_ESSAY') && ($_action_label[3] === 'WRITE_ESSAY') ) {
      $pattern = array( "label" =>"HC.EO.1", "category" => "HC", "sub_category" => "EO", "sub_pattern" =>  "1", "span" =>  3, "priority" =>  6,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[4], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1], $_action_label[2] , $_action_label[3]));
  } else if ((($_action_label[0] === 'IRRELEVANT_READING') || ($_action_label[0] === 'RELEVANT_READING')) &&
       (($_action_label[1] === 'EDIT_ANNOTATION')) &&
       (($_action_label[2] === 'IRRELEVANT_READING') || ($_action_label[2] === 'RELEVANT_READING')) ) {
      $pattern = array( "label" =>"LC.F.1", "category" => "LC", "sub_category" => "F", "sub_pattern" =>  "1", "span" =>  3, "priority" =>  7,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[3], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1], $_action_label[2] ));
  } else if (($_action_label[0] === 'RELEVANT_READING') &&
       ($_action_label[1] ==='NAVIGATION') &&
       ($_action_label[2] === 'IRRELEVANT_READING') &&
       ($_action_label[3] && ($_action_label[3]=='NAVIGATION')) &&
       ($_action_label[4] && ($_action_label[4] === 'IRRELEVANT_READING')) ) {
      $pattern = array( "label" =>"LC.F.3", "category" => "LC", "sub_category" => "F", "sub_pattern" =>  "3", "span" =>  5, "priority" =>  8,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[5], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1], $_action_label[2], $_action_label[3], $_action_label[4] ));
  } else if ((($_action_label[0] === 'IRRELEVANT_READING') || ($_action_label[0] === 'RELEVANT_READING')) &&
       ($_action_label[1] === 'NAVIGATION') &&
       (($_action_label[2] === 'IRRELEVANT_READING') || ($_action_label[2] === 'RELEVANT_READING')) ) {
      $pattern = array( "label" =>"LC.F.2", "category" => "LC", "sub_category" => "F", "sub_pattern" =>  "2", "span" =>  3, "priority" =>  9,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[3], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1], $_action_label[2] ));
  } else if ((($_action_label[0] === 'GENERAL_INSTRUCTION') || ($_action_label[0] === 'RUBRIC')) && 
       ($_action_label[1] === 'PLANNER') &&
       ($activation_count < 15) ) { 
      // REVISIT - hard wired time and what about focus/blur on planner?
      $pattern = array( "label" =>"MC.P.2", "category" => "MC", "sub_category" => "P", "sub_pattern" =>  "2", "span" =>  2, "priority" =>  10,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[2], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1] ));
  } else if ((($_action_label[1] === 'GENERAL_INSTRUCTION') || ($_action_label[1] === 'RUBRIC')) && 
       ($_action_label[0] === 'PLANNER') &&
       ($activation_count < 15) ) { 
      // REVISIT - hard wired time and what about focus/blur on planner?
      $pattern = array( "label" =>"MC.P.2", "category" => "MC", "sub_category" => "P", "sub_pattern" =>  "2", "span" =>  2, "priority" =>  10,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[2], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1] ));
  } else if ((($_action_label[0] === 'GENERAL_INSTRUCTION') || ($_action_label[0] === 'RUBRIC')) &&
       ($_action_label[1] === 'PLANNER') &&
       ($activation_count >= 15) ) { 
      $pattern = array( "label" =>"MC.M.2", "category" => "MC", "sub_category" => "M", "sub_pattern" =>  "2", "span" =>  2, "priority" =>  11,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[2], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1] ));
  } else if ((($_action_label[1] === 'GENERAL_INSTRUCTION') || ($_action_label[1] === 'RUBRIC')) &&
       ($_action_label[0] === 'PLANNER') &&
       ($activation_count >= 15) ) { 
      $pattern = array( "label" =>"MC.M.2", "category" => "MC", "sub_category" => "M", "sub_pattern" =>  "2", "span" =>  2, "priority" =>  11,  "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[2], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1] ));
  } else if ((($_action_label[0] === 'GENERAL_INSTRUCTION') || ($_action_label[0] === 'RUBRIC')) && (($_action_label[1] === 'EDIT_ANNOTATION'))) {
      $pattern = array( "label" =>"MC.O.3", "category" => "MC", "sub_category" => "O", "sub_pattern" =>  "3", "span" =>  2, "priority" =>  12,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[2], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1] ));
  } else if ((($_action_label[1] === 'GENERAL_INSTRUCTION') || ($_action_label[1] === 'RUBRIC')) && (($_action_label[0] === 'EDIT_ANNOTATION'))) {
      $pattern = array( "label" =>"MC.O.3", "category" => "MC", "sub_category" => "O", "sub_pattern" =>  "3", "span" =>  2, "priority" =>  12,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[2], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1] ));
  } else if ((($_action_label[0] === 'NAVIGATION') && ($_action_label[1] === 'READ_ANNOTATION')) ||
       (($_action_label[1] === 'NAVIGATION') && ($_action_label[0] === 'READ_ANNOTATION')))  { 
      $pattern = array( "label" =>"MC.M.1", "category" => "MC", "sub_category" => "M", "sub_pattern" =>  "1", "span" =>  2, "priority" =>  13,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[2], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1] ));
  } else if ((($_action_label[0] === 'WRITE_ESSAY') || ($_action_label[0] === 'OPEN_ESSAY')) &&
       (($_action_label[1] === 'PLANNER') || ($_action_label[1] === 'GENERAL_INSTRUCTION') || ($_action_label[1] === 'RUBRIC')) &&
       ($activation_count >= 15) ) { 
      $pattern = array( "label" => "MC.M.3", "category" => "MC", "sub_category" => "M", "sub_pattern" =>  "3", "span" =>  2, "priority" =>  14,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[2], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1] ));
  } else if ((($_action_label[1] === 'WRITE_ESSAY') || ($_action_label[1] === 'OPEN_ESSAY')) &&
       (($_action_label[0] === 'PLANNER') || ($_action_label[0] === 'GENERAL_INSTRUCTION') || ($_action_label[0] === 'RUBRIC')) &&
       ($activation_count >= 15)) { 
      $pattern = array( "label" =>"MC.M.3", "category" => "MC", "sub_category" => "M", "sub_pattern" =>  "3", "span" =>  2, "priority" =>  14,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[2], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1] ));
  } else if ((($_action_label[0] === 'GENERAL_INSTRUCTION') || ($_action_label[0] === 'RUBRIC')) && ($_action_label[1] ==='NAVIGATION')) {
      $pattern = array( "label" =>"MC.O.4", "category" => "MC", "sub_category" => "O", "sub_pattern" =>  "4", "span" =>  2, "priority" =>  15,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[2], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1] ));
  } else if ((($_action_label[1] === 'GENERAL_INSTRUCTION') || ($_action_label[1] === 'RUBRIC')) && (($_action_label[0] === 'NAVIGATION'))) {
      $pattern = array( "label" =>"MC.O.4", "category" => "MC", "sub_category" => "O", "sub_pattern" =>  "4", "span" =>  2, "priority" =>  15,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[2], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1] ));
  } else if ((($_action_label[0] === 'GENERAL_INSTRUCTION') || ($_action_label[0] === 'RUBRIC')) && (($_action_label[1] === 'GENERAL_INSTRUCTION') || ($_action_label[1] === 'RUBRIC'))) {
      $pattern = array( "label" =>"MC.O.2", "category" => "MC", "sub_category" => "O", "sub_pattern" =>  "2", "span" =>  2, "priority" =>  16,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[2], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1] ));
  } else if (($_action_label[0] === 'GENERAL_INSTRUCTION') || ($_action_label[0] === 'RUBRIC')) {
      $pattern = array( "label" =>"MC.O.2", "category" => "MC", "sub_category" => "O", "sub_pattern" =>  "2", "span" =>  1, "priority" =>  16,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[1], "userid" =>  $uid, "actions" => array($_action_label[0] ));
  } else if ((($_action_label[0] === 'WRITE_ESSAY') && ($_action_label[1] === 'READ_ANNOTATION')) || 
       (($_action_label[1] === 'WRITE_ESSAY') && ($_action_label[0] === 'READ_ANNOTATION')) ) {
      $pattern = array( "label" =>"HC.EO.4", "category" => "HC", "sub_category" => "EO", "sub_pattern" =>  "4", "span" =>  2, "priority" =>  17,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[2], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1] ));
  } else if (($_action_label[0] === 'WRITE_ESSAY') && ($_action_label[1] === 'WRITE_ESSAY') && ($_action_label[2] === 'WRITE_ESSAY') ) {
      $pattern = array( "label" =>"HC.EO.3", "category" => "HC", "sub_category" => "EO", "sub_pattern" =>  "3", "span" =>  2, "priority" =>  18,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[2], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1]));
  }else if (($_action_label[0] === 'WRITE_ESSAY') && ($_action_label[1] === 'WRITE_ESSAY')) {
      $pattern = array( "label" =>"HC.EO.3", "category" => "HC", "sub_category" => "EO", "sub_pattern" =>  "3", "span" =>  2, "priority" =>  18,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[1], "userid" =>  $uid, "actions" => array($_action_label[0]));
  } else if ($_action_label[0] === 'WRITE_ESSAY') {
      $pattern = array( "label" =>"HC.EO.3", "category" => "HC", "sub_category" => "EO", "sub_pattern" =>  "3", "span" =>  1, "priority" =>  18,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[1], "userid" =>  $uid, "actions" => array($_action_label[0] ));
  } else if ((($_action_label[0] === 'IRRELEVANT_READING') || ($_action_label[0] === 'RELEVANT_READING')) &&
       (($_action_label[1] === 'EDIT_ANNOTATION') || ($_action_label[1] === 'READ_ANNOTATION') )) {
      $pattern = array( "label" =>"LC.F.4", "category" => "LC", "sub_category" => "F", "sub_pattern" =>  "4", "span" =>  2, "priority" =>  19,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[2], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1] ));
  } else if ((($_action_label[1] === 'IRRELEVANT_READING') || ($_action_label[1] === 'RELEVANT_READING')) &&
       (($_action_label[0] === 'EDIT_ANNOTATION') || ($_action_label[0] === 'READ_ANNOTATION') )) {
      $pattern = array( "label" =>"LC.F.4", "category" => "LC", "sub_category" => "F", "sub_pattern" =>  "4", "span" =>  2, "priority" =>  19,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[2], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1] ));
  } else if ((($_action_label[0] === 'IRRELEVANT_READING') || ($_action_label[0] === 'RELEVANT_READING')) &&
       (($_action_label[1] === 'IRRELEVANT_READING') || ($_action_label[1] === 'RELEVANT_READING')) ) {
      $pattern = array( "label" =>"LC.F.5", "category" => "LC", "sub_category" => "F", "sub_pattern" =>  "5", "span" =>  2, "priority" =>  20,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[2], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1] ));
  } else if (($_action_label[0] === 'PLANNER') && ($activation_count < 15)) {
      $pattern = array( "label" =>"MC.P.3", "category" => "MC", "sub_category" => "P", "sub_pattern" =>  "3", "span" =>  1, "priority" =>  21,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[1], "userid" =>  $uid, "actions" => array($_action_label[0] ));
  } else if (($_action_label[0] === 'PLANNER') && ($activation_count >= 15) ) {
      $pattern = array( "label" =>"MC.M.5", "category" => "MC", "sub_category" => "M", "sub_pattern" =>  "5", "span" =>  1, "priority" =>  22,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[1], "userid" =>  $uid, "actions" => array($_action_label[0] ));
  } else if ($_action_label[0] === 'SEARCH_ANNOTATION') {
      $pattern = array( "label" =>"MC.M.6", "category" => "MC", "sub_category" => "M", "sub_pattern" =>  "6", "span" =>  1, "priority" =>  23,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[1], "userid" =>  $uid, "actions" => array($_action_label[0] ));
  } else if ($_action_label[0] === 'SEARCH_CONTENT') {
      $pattern = array( "label" =>"MC.P.4", "category" => "MC", "sub_category" => "P", "sub_pattern" =>  "4", "span" =>  1, "priority" =>  24,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[1], "userid" =>  $uid, "actions" => array($_action_label[0] ));
  } else if ($_action_label[0] === 'TIMER') {
      $pattern = array( "label" =>"MC.M.4", "category" => "MC", "sub_category" => "M", "sub_pattern" =>  "4", "span" =>  1, "priority" =>  25,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[1], "userid" =>  $uid, "actions" => array($_action_label[0] ));
      //    } else if (($_action_label[0] === 'HIGHLIGHT_READING') || ($_action_label[0] === 'NOTE_READING')) {
  } else if ($_action_label[0] === 'READ_ANNOTATION') {
      $pattern = array( "label" =>"MC.M.7", "category" => "MC", "sub_category" => "M", "sub_pattern" =>  "7", "span" =>  1, "priority" =>  26,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[1], "userid" =>  $uid, "actions" => array($_action_label[0] ));
  } else if ($_action_label[0] === 'ANNOTATION_LABELLING') {
      $pattern = array( "label" =>"HC.EO.5", "category" => "HC", "sub_category" => "EO", "sub_pattern" =>  "5", "span" =>  1, "priority" =>  27,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[1], "userid" =>  $uid, "actions" => array($_action_label[0] ));
  } else if ($_action_label[0] === 'EDIT_ANNOTATION') {
      $pattern = array( "label" =>"HC.EO.6", "category" => "HC", "sub_category" => "EO", "sub_pattern" =>  "6", "span" =>  1, "priority" =>  28,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[1], "userid" =>  $uid, "actions" => array($_action_label[0] ));
  } else if (($_action_label[0] === 'RELEVANT_REREADING') && ($_action_label[1] === 'RELEVANT_REREADING')) {
      $pattern = array( "label" =>"LC.R.1", "category" => "LC", "sub_category" => "R", "sub_pattern" =>  "1", "span" =>  2, "priority" =>  29,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[2], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1] ));
  } else if ($_action_label[0] === 'RELEVANT_REREADING') {
      $pattern = array( "label" =>"LC.R.1", "category" => "LC", "sub_category" => "R", "sub_pattern" =>  "1", "span" =>  1, "priority" =>  29,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[1], "userid" =>  $uid, "actions" => array($_action_label[0] ));
  } else if (($_action_label[0] === 'IRRELEVANT_REREADING') && ($_action_label[1] === 'IRRELEVANT_REREADING')) {
      $pattern = array( "label" =>"LC.R.2", "category" => "LC", "sub_category" => "R", "sub_pattern" =>  "2", "span" =>  2, "priority" =>  30,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[2], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1] ));
  } else if ($_action_label[0] === 'IRRELEVANT_REREADING') {
      $pattern = array( "label" =>"LC.R.2", "category" => "LC", "sub_category" => "R", "sub_pattern" =>  "2", "span" =>  1, "priority" =>  30,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[1], "userid" =>  $uid, "actions" => array($_action_label[0] ));
  } else if (($_action_label[0] === 'IRRELEVANT_READING') && ($_action_label[1] === 'IRRELEVANT_READING')) {
      $pattern = array( "label" =>"LC.F.6", "category" => "LC", "sub_category" => "F", "sub_pattern" =>  "6", "span" =>  2, "priority" =>  31,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[2], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1] ));
  } else if ($_action_label[0] === 'IRRELEVANT_READING') {
      $pattern = array( "label" =>"LC.F.6", "category" => "LC", "sub_category" => "F", "sub_pattern" =>  "6", "span" =>  1, "priority" =>  31,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[1], "userid" =>  $uid, "actions" => array($_action_label[0] ));
  } else if (($_action_label[0] === 'RELEVANT_READING') && ($_action_label[1] === 'RELEVANT_READING')) {
      $pattern = array( "label" =>"LC.F.7", "category" => "LC", "sub_category" => "F", "sub_pattern" =>  "7", "span" =>  2, "priority" =>  32,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[2], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1] ));  
  } else if ($_action_label[0] === 'RELEVANT_READING') {
      $pattern = array( "label" => "LC.F.7", "category" => "LC", "sub_category" => "F", "sub_pattern" =>  "7", "span" =>  1, "priority" =>  32,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[1], "userid" =>  $uid, "actions" => array($_action_label[0] ));  
  } else if (($_action_label[0] === 'OPEN_ESSAY') &&
       (str_ends_with($_action_label[1],'RELEVANT_READING') || (str_ends_with($_action_label[1],'REREADING')))) { 
      $pattern = array( "label" =>"MC.M.8", "category" => "MC", "sub_category" => "M", "sub_pattern" =>  "8", "span" =>  2, "priority" =>  33,  "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[2], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1] ));
  } else if (($_action_label[1] === 'OPEN_ESSAY') &&
       (str_ends_with($_action_label[0],'RELEVANT_READING') || (str_ends_with($_action_label[0],'REREADING')))) { 
      $pattern = array( "label" =>"MC.M.8", "category" => "MC", "sub_category" => "M", "sub_pattern" =>  "8", "span" =>  2, "priority" =>  33,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[2], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1] ));
  }

  // special cases
  if ($_action_label[0] == "ESSAY_TASK_END"){

     $pattern = array( "label" => "ESSAY_TASK_END", "category" => "", "sub_category" => "", "sub_pattern" =>  "", "span" =>  1, "priority" =>  34,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[0] + 1 , "userid" =>  $uid, "actions" => array($_action_label[0] ));
  }

  if ($_action_label[0] == "ESSAY_TASK_START"){
     $pattern = array( "label" => "ESSAY_TASK_START", "category" => "", "sub_category" => "", "sub_pattern" =>  "", "span" =>  1, "priority" =>  34,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[1], "userid" =>  $uid, "actions" => array($_action_label[0] ));
  }

  if ($_action_label[0] == "OFF_TASK"){
     $pattern = array( "label" => "OFF_TASK", "category" => "", "sub_category" => "", "sub_pattern" =>  "", "span" =>  1, "priority" =>  34,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[1], "userid" =>  $uid, "actions" => array($_action_label[1] ));
  }

  if (empty($pattern)) {
      $pattern = array( "label" => "NO_PATTERN", "category" => "", "sub_category" => "", "sub_pattern" =>  "", "span" =>  1, "priority" =>  34,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[1], "userid" =>  $uid, "actions" => array($_action_label[0] ));
  }


    $_pattern = $pattern['label'] ; // ' . " | " . implode("," , $pattern) . " | " . implode("," , $pattern['actions']);
    $_pattern_st_time = (int) $pattern['start_time'];
    $_pattern_end_time = (int)$pattern['end_time'];

    if ($_pattern_st_time == $_pattern_end_time){
      $_pattern_end_time = $_pattern_end_time + 1;
    }

    $_pattern_to = $_pattern_end_time - 1;

    $sql = "UPDATE user_log SET process_label = '$_pattern' , process_endtime = '$_pattern_to', process_starttime = '$_pattern_st_time' WHERE uid = '$uid' AND time_lapsed >=" . $_pattern_st_time . " AND time_lapsed <= "  . $_pattern_to;

      if ($con->query($sql) === TRUE) {
        //echo "Record updated successfully";
      } else {
        //echo "Error updating record: " . $conn->error;
      }

    // Remove patterns
    $_action_label = array_slice($_action_label, count($pattern['actions'])); 
    $_time_lapsed = array_slice($_time_lapsed, count($pattern['actions'])); 

  }
    //echo json_encode("Done updating user:" . $uid);
 }
 

?>
