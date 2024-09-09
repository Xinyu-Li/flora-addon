<?php

// **** IMPORTANT *******************************************************
// Change/Verify the URLS in line #23 -> function get_ActionLabel()
//***********************************************************************

$navigation_delta = 6.0; // number of seconds before which reading becomes navigation

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

function get_ActionLabel($uid, $action, $sub_action, $value, $time_lapsed , $conn , $uri) {
// page URL and their categorisations
$page_info = array(
    "https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=1" => "GENERAL_INSTRUCTION",
    "https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=2" => "RUBRIC",
    "https://floralearn.org/lms/mod/page/view.php?id=2" => "RELEVANT",
    "https://floralearn.org/lms/mod/page/view.php?id=3" => "IRRELEVANT",
    "https://floralearn.org/lms/mod/page/view.php?id=4" => "RELEVANT",
    "https://floralearn.org/lms/mod/page/view.php?id=5" => "IRRELEVANT",
    "https://floralearn.org/lms/mod/page/view.php?id=6" => "RELEVANT",
    "https://floralearn.org/lms/mod/page/view.php?id=7" => "RELEVANT",
    "https://floralearn.org/lms/mod/page/view.php?id=8" => "RELEVANT",
    "https://floralearn.org/lms/mod/page/view.php?id=9" => "RELEVANT",
    "https://floralearn.org/lms/mod/page/view.php?id=10" => "RELEVANT",
    "https://floralearn.org/lms/mod/page/view.php?id=11" => "RELEVANT",
    "https://floralearn.org/lms/mod/page/view.php?id=12" => "IRRELEVANT",
    "https://floralearn.org/lms/mod/page/view.php?id=13" => "RELEVANT",
    "https://floralearn.org/lms/mod/page/view.php?id=14" => "IRRELEVANT",
    "https://floralearn.org/lms/mod/page/view.php?id=15" => "RELEVANT",
    "https://floralearn.org/lms/mod/page/view.php?id=16" => "RELEVANT",
    "https://floralearn.org/lms/mod/page/view.php?id=17" => "IRRELEVANT"
);

$action_label = "";
$prev_action_label = "";

$qry = "SELECT id FROM user_log WHERE uid = '$uid' AND action = 'PAGE' AND sub_action = 'LOAD' AND  url = '$uri' AND action_label <> 'NAVIGATION'";
  $result = mysqli_query($conn , $qry); 
  $num_rows = mysqli_num_rows($result);
  if ( $num_rows <=1){
    $prev_action_label .= "_READING";
  }else{
    $prev_action_label .= "_REREADING";
  }

switch ($action) {
  case "PAGE":
    if ($sub_action == "LOAD") {

        if ($value != ""){

          $action_label .= $page_info[$value];
          if (!in_array($action_label, ['GENERAL_INSTRUCTION','RUBRIC'])){
            $qry = "SELECT id FROM user_log WHERE uid = '$uid' AND action = '$action' AND sub_action = '$sub_action' AND value = '$value' AND action_label <> 'NAVIGATION'";
            $result = mysqli_query($conn , $qry); 
            $num_rows = mysqli_num_rows($result);

            if ( $num_rows == 0){
              $action_label .= "_READING";
            }else{
              $action_label .= "_REREADING";
            }
          }
        }else{
          $action_label = "ERROR - NO VALUE";
        }

    if (( $uri == "https://floralearn.org/lms/mod/feedback/complete.php?id=18") || ($uri == "https://floralearn.org/lms/mod/feedback/complete.php")){
        $action_label = "ESSAY_SUBMIT";
    } 

            // check time spent on previous page
      $qry = "SELECT * FROM user_log WHERE uid = '$uid' AND action_label<>'NAVIGATION' ORDER BY time_lapsed DESC LIMIT 1";
      $result = mysqli_query($conn , $qry); 
      $num_rows = mysqli_num_rows($result);
      $row_id = ""; $act = ""; $sub_act = "";
      $curr_elapsed_time = 0;
      if ($num_rows == 1){
          while($r = mysqli_fetch_assoc($result)) {
           $rows[] = $r;
           $row_id = $r['id'];
           $act = $r['action'];
           $sub_act = $r['sub_action'];
           $prev_event_time = $r['time_lapsed'];
          }

      if( ( ($act == "PAGE") && ($sub_act == "LOAD")) ){
          $time_diff = (float) (((int) $time_lapsed  - (int) $prev_event_time) / 1000); // get time diff in seconds
          //$action_label  = $time_diff;
        if ((float)$time_diff <= (float)$navigation_delta ){ // check for 6 sec rule
            $sql = "UPDATE user_log SET action_label='NAVIGATION' WHERE uid = '$uid' AND id = '$row_id'";
          if ($conn->query($sql) === TRUE) {
              //echo "Record updated successfully";
          } else {
              echo "Error updating record: " . $conn->error;
          }
      
        }
      }
     }
    }

    break;
  case "TABLE OF CONTENTS":
  if ($sub_action == "CLICK") {
    $action_label = "NAVIGATION";
  }
    break;

  case "ESSAY":
  if (($sub_action == "OPEN") || ($sub_action == "FOCUS")) {
    $action_label = "OPEN_ESSAY";
  }elseif (($sub_action == "TYPE") || ($sub_action == "KEYDOWN") || ($sub_action == "PASTETEXT") || ($sub_action == "AFTER PASTE") ){
    $action_label = "WRITE_ESSAY";
  }else if (($sub_action == "BLUR") || ($sub_action == "SAVE") || ($sub_action == "CLOSE")) {
    $action_label = $page_info[$uri];
    if (!in_array($action_label, ['GENERAL_INSTRUCTION','RUBRIC'])){
        $action_label .= $prev_action_label;
    }
  }
    break;

  case "PLANNER":
  if (($sub_action == "OPEN") || ($sub_action == "FOCUS")) {
    $action_label = "PLANNER";
  }elseif (($sub_action == "BLUR") || ($sub_action == "SAVE") || ($sub_action == "CLOSE")) {
    $action_label = $page_info[$uri];
    if (!in_array($action_label, ['GENERAL_INSTRUCTION','RUBRIC'])){
        $action_label .= $prev_action_label;
    }
  }
    break;
  case "TIMER":
  if ($sub_action == "OPEN") {
    $action_label = "TIMER";
  }elseif (($sub_action == "CLOSE")) { 
    $action_label = $page_info[$uri];
    if (!in_array($action_label, ['GENERAL_INSTRUCTION','RUBRIC'])){
        $action_label .= $prev_action_label;
    }
  }elseif (($sub_action == "ESSAY_TASK_START")) { 
       $action_label = "ESSAY_TASK_START";
  }elseif (($sub_action == "ESSAY_TASK_END")) { 
       $action_label = "ESSAY_TASK_END";
  }
    break;
  case "CONTENT":
  if ($sub_action == "SEARCH") {
    $action_label = "SEARCH_CONTENT";
  }
    break;

  case "Annotation":
  if ($sub_action == "SearchBox") {
     if ($value == "opened") {
          $action_label = "SEARCH_ANNOTATION";
      }elseif ($value == "closed") {
        $action_label = $page_info[$uri];
        if (!in_array($action_label, ['GENERAL_INSTRUCTION','RUBRIC'])){
            $action_label .= $prev_action_label;
        }
      }
  }elseif ($sub_action == "TextSelected"){
      $action_label = "EDIT_ANNOTATION";
  }elseif(($sub_action == "Created") || ($sub_action == "Updated") || ($sub_action == "Deleted")) {
      $action_label = "EDIT_ANNOTATION";
    }
    break;
  case "Annotation-SideBar":
    if($sub_action == "Opened") {
        $qry = "SELECT action, sub_action FROM user_log WHERE uid = '$uid' ORDER BY time_lapsed DESC LIMIT 1 ";
            $result = mysqli_query($conn , $qry); 
            //$num_rows = mysqli_num_rows($result);
            while($r = mysqli_fetch_assoc($result)) {
              $rows[] = $r;
              $_action = $r['action'];
              $_sub_action = $r['sub_action'];
            }

            if ($_sub_action == "TextSelected"){
              $action_label = "EDIT_ANNOTATION";
            }else{
              $action_label = "READ_ANNOTATION";
            }
        
    }elseif ($sub_action  == "Closed") {
        $action_label = $page_info[$uri];
        if (!in_array($action_label, ['GENERAL_INSTRUCTION','RUBRIC'])){
            $action_label .= $prev_action_label;
        }
      }
  break;
  case "Scaffold":
    if (($sub_action == "ToDoList_Closed") || ($sub_action == "Message_Closed")) { 
      $action_label = $page_info[$uri];
      if (!in_array($action_label, ['GENERAL_INSTRUCTION','RUBRIC'])){
          $action_label .= $prev_action_label;
      }
    }else{
      $action_label = "SCAFFOLD";
    }
    break;

  default:
    $action_label = "-";
 }
 return $action_label;
}


function get_ProcessLabel($uid, $action, $sub_action, $value, $time_lapsed , $conn, $uri, $max_pattern_len = 7)
{
   updateProcessLabel($uid , $conn, $max_pattern_len = 7);
   return "-";
}

function updateProcessLabel($uid , $con, $wait_len = 7){

 $process_label = "-";
 $process_from = 0;
 $process_to = 0;


 $qry = "SELECT uid, time_lapsed, action_label
    FROM (
        SELECT @row := CASE WHEN @status=action_label 
              AND @member = uid
            THEN @row + 1 ELSE 1 END as row
            , @member := uid as uid
            , @status := action_label as action_label
            , time_lapsed
        FROM user_log d 
            , (SELECT @row := 0, @status := '', @member := 0) v WHERE  uid = '$uid' AND process_label = '-'
        ORDER BY uid, time_lapsed
    ) as n 
    WHERE row = 1 
    ORDER BY uid, time_lapsed
    LIMIT 10";

   $result = mysqli_query($con , $qry); 
   $num_rows = mysqli_num_rows($result);
  while($row = mysqli_fetch_assoc($result)) {
  $_id = ""; // have non unique _time_lapsed
      $_time_lapsed[] = $row['time_lapsed'];
      $_action_label[] = $row['action_label'];
    }

 if ($_action_label[0] == "ESSAY_TASK_END"){
          $process_label = "ESSAY_TASK_END";
          $process_from = $_time_lapsed[0];
          $process_to = $_time_lapsed[0] + 1;

        $sql = "UPDATE user_log SET process_label='$process_label' WHERE uid = '$uid' AND time_lapsed >= '$process_from' AND time_lapsed < '$process_to'";
        if ($con->query($sql) === TRUE) {
          //echo "Record updated successfully";
        } else {
          echo "Error updating record: " . $conn->error;
        }
        return 0;
 }

 if ($num_rows < $wait_len){
        return $num_rows;
 }else{


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
  } else if (($_action_label[0] === 'OPEN_ESSAY') &&
       ((($_action_label[2] === 'GENERAL_INSTRUCTION') && ($_action_label[1] === 'GENERAL_INSTRUCTION')) || (($_action_label[2] === 'RUBRIC') && ($_action_label[1] === 'RUBRIC')) || (($_action_label[2] === 'READ_ANNOTATION') && ($_action_label[1] === 'READ_ANNOTATION'))) &&
       ((str_ends_with($_action_label[3],'RELEVANT_READING')) || (str_ends_with($_action_label[3],'RELEVANT_REREADING')) || ($_action_label[3] === 'OPEN_ESSAY')) ) {
      $pattern = array( "label" =>"MC.E.2", "category" => "MC", "sub_category" => "E", "sub_pattern" =>  "2", "span" =>  4, "priority" =>  2,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[4], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1], $_action_label[2], $_action_label[3] ));
  } else if (($_action_label[0] === 'OPEN_ESSAY') &&
        ($_action_label[1] ==='NAVIGATION') &&
       ((($_action_label[2] === 'GENERAL_INSTRUCTION') && ($_action_label[3] === 'GENERAL_INSTRUCTION') && ($_action_label[4] === 'GENERAL_INSTRUCTION')) || (($_action_label[2] === 'RUBRIC') && ($_action_label[3] === 'RUBRIC') && ($_action_label[4] === 'RUBRIC')) || (($_action_label[2] === 'READ_ANNOTATION') && ($_action_label[3] === 'READ_ANNOTATION') && ($_action_label[4] === 'READ_ANNOTATION'))) &&
        ($_action_label[5] ==='NAVIGATION') &&
       ((str_ends_with($_action_label[6],'RELEVANT_READING')) || (str_ends_with($_action_label[6],'RELEVANT_REREADING')) || ($_action_label[6] === 'OPEN_ESSAY')) ) {
      $pattern = array( "label" =>"MC.E.2", "category" => "MC", "sub_category" => "E", "sub_pattern" =>  "2", "span" =>  7, "priority" =>  2,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[5], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1], $_action_label[2], $_action_label[3], $_action_label[4], $_action_label[5], $_action_label[6] ));
  } else if (($_action_label[0] === 'OPEN_ESSAY') &&
       ((($_action_label[2] === 'GENERAL_INSTRUCTION') && ($_action_label[3] === 'GENERAL_INSTRUCTION') && ($_action_label[1] === 'GENERAL_INSTRUCTION')) || (($_action_label[2] === 'RUBRIC') && ($_action_label[3] === 'RUBRIC') && ($_action_label[1] === 'RUBRIC')) || (($_action_label[2] === 'READ_ANNOTATION') && ($_action_label[3] === 'READ_ANNOTATION') && ($_action_label[1] === 'READ_ANNOTATION'))) &&
       ((str_ends_with($_action_label[4],'RELEVANT_READING')) || (str_ends_with($_action_label[4],'RELEVANT_REREADING')) || ($_action_label[4] === 'OPEN_ESSAY')) ) {
      $pattern = array( "label" =>"MC.E.2", "category" => "MC", "sub_category" => "E", "sub_pattern" =>  "2", "span" =>  5, "priority" =>  2,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[3], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1], $_action_label[2], $_action_label[3], $_action_label[4] ));

  // The rest are much simpler!
        } else if ((($_action_label[0] === 'GENERAL_INSTRUCTION') || ($_action_label[0] === 'RUBRIC')) && ($_action_label[1] ==='RELEVANT_READING')) {
      $pattern = array( "label" =>"MC.O.1", "category" => "MC", "sub_category" => "O", "sub_pattern" =>  "1", "span" =>  2, "priority" =>  3,  "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[2], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1] ));
  } else if ((($_action_label[0] === 'GENERAL_INSTRUCTION') || ($_action_label[0] === 'RUBRIC')) && ($_action_label[1] ==='NAVIGATION') && ($_action_label[2] ==='RELEVANT_READING')) {
      $pattern = array( "label" =>"MC.O.1", "category" => "MC", "sub_category" => "O", "sub_pattern" =>  "1", "span" =>  3, "priority" =>  3,  "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[3], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1], $_action_label[2] ));
  } else if (($_action_label[0] === 'PLANNER') && ($_action_label[1] ==='RELEVANT_READING')) {
      $pattern = array( "label" =>"MC.P.1", "category" => "MC", "sub_category" => "P", "sub_pattern" =>  "1", "span" =>  2, "priority" =>  4,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[2], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1] ));
  } else if (($_action_label[0] === 'PLANNER') && ($_action_label[1] ==='NAVIGATION') && ($_action_label[2] ==='RELEVANT_READING')) {
      $pattern = array( "label" =>"MC.P.1", "category" => "MC", "sub_category" => "P", "sub_pattern" =>  "1", "span" =>  3, "priority" =>  4,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[3], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1], $_action_label[2] ));
  } else if ((($_action_label[0] === 'GENERAL_INSTRUCTION') || ($_action_label[0] === 'RUBRIC')) &&($_action_label[1] === 'OPEN_ESSAY') && ($_action_label[2] === 'WRITE_ESSAY') ) { // add condition that there are more than one WRITE_ESSAY elements (a3.count>1) ??
      $pattern = array( "label" =>"HC.E/O.2", "category" => "HC", "sub_category" => "EO", "sub_pattern" =>  "2", "span" =>  2, "priority" =>  5,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[3], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1], $_action_label[2] ));
  } else if ((($_action_label[0] === 'GENERAL_INSTRUCTION') || ($_action_label[0] === 'RUBRIC')) &&
       ($_action_label[1] === 'NAVIGATION') &&   // opening essay tool causes a NAVIGATION TOO
       ($_action_label[2]=== 'OPEN_ESSAY' ) && ($_action_label[3] === 'WRITE_ESSAY') ) {
      $pattern = array( "label" =>"HC.E/O.2", "category" => "HC", "sub_category" => "EO", "sub_pattern" =>  "2", "span" =>  3, "priority" =>  5,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[4], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1], $_action_label[2], $_action_label[3] ));
  } else if ((($_action_label[0] === 'IRRELEVANT_REREADING') || ($_action_label[0] === 'RELEVANT_REREADING')) && ($_action_label[1] === 'OPEN_ESSAY') && ($_action_label[2] === 'WRITE_ESSAY') ) { // add condition that there are more than one WRITE_ESSAY elements (a3.count>1) ??
      $pattern = array( "label" =>"HC.E/O.1", "category" => "HC", "sub_category" => "EO", "sub_pattern" =>  "1", "span" =>  2, "priority" =>  6,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[3], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1] , $_action_label[2]));
  } else if ((($_action_label[0] === 'IRRELEVANT_REREADING') || ($_action_label[0] === 'RELEVANT_REREADING')) &&
       ($_action_label[1] === 'NAVIGATION') &&   // opening essay tool causes a NAVIGATION TOO
       ($_action_label[2]=== 'OPEN_ESSAY') && ($_action_label[3] === 'WRITE_ESSAY') ) {
      $pattern = array( "label" =>"HC.E/O.1", "category" => "HC", "sub_category" => "EO", "sub_pattern" =>  "1", "span" =>  3, "priority" =>  6,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[4], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1], $_action_label[2] , $_action_label[3]));
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
      $pattern = array( "label" =>"MC.M.3", "category" => "MC", "sub_category" => "M", "sub_pattern" =>  "3", "span" =>  2, "priority" =>  14,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[2], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1] ));
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
      $pattern = array( "label" =>"HC.E/O.4", "category" => "HC", "sub_category" => "EO", "sub_pattern" =>  "4", "span" =>  2, "priority" =>  17,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[2], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1] ));
  } else if (($_action_label[0] === 'WRITE_ESSAY') && ($_action_label[1] === 'WRITE_ESSAY')) {
      $pattern = array( "label" =>"HC.E/O.3", "category" => "HC", "sub_category" => "EO", "sub_pattern" =>  "3", "span" =>  2, "priority" =>  18,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[2], "userid" =>  $uid, "actions" => array($_action_label[0], $_action_label[1] ));
  } else if ($_action_label[0] === 'WRITE_ESSAY') {
      $pattern = array( "label" =>"HC.E/O.3", "category" => "HC", "sub_category" => "EO", "sub_pattern" =>  "3", "span" =>  1, "priority" =>  18,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[1], "userid" =>  $uid, "actions" => array($_action_label[0] ));
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
      $pattern = array( "label" =>"HC.E/O.5", "category" => "HC", "sub_category" => "EO", "sub_pattern" =>  "5", "span" =>  1, "priority" =>  27,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[1], "userid" =>  $uid, "actions" => array($_action_label[0] ));
  } else if ($_action_label[0] === 'EDIT_ANNOTATION') {
      $pattern = array( "label" =>"HC.E/O.6", "category" => "HC", "sub_category" => "EO", "sub_pattern" =>  "6", "span" =>  1, "priority" =>  28,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[1], "userid" =>  $uid, "actions" => array($_action_label[0] ));
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

  if ($_action_label[0] == "ESSAY_TASK_START"){
     $pattern = array( "label" => "ESSAY_TASK_START", "category" => "", "sub_category" => "", "sub_pattern" =>  "", "span" =>  1, "priority" =>  34,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[1], "userid" =>  $uid, "actions" => array($_action_label[0] ));

  }


  if (empty($pattern)) {
      $pattern = array( "label" => "NO_PATTERN", "category" => "", "sub_category" => "", "sub_pattern" =>  "", "span" =>  1, "priority" =>  34,   "start_time" =>   $_time_lapsed[0], "end_time" =>  $_time_lapsed[1], "userid" =>  $uid, "actions" => array($_action_label[0] ));
  }

    $_pattern = $pattern['label'];
    $_pattern_st_time = (int) $pattern['start_time'];
    $_pattern_end_time = (int)$pattern['end_time'];

  
    if ($_pattern_st_time == $_pattern_end_time){
    $_pattern_end_time = $_pattern_end_time + 1;
    }

    $sql = "UPDATE user_log SET process_label = '$_pattern' WHERE uid = '$uid' AND time_lapsed >=" . $_pattern_st_time . " AND time_lapsed < "  . $_pattern_end_time;

    if ($con->query($sql) === TRUE) {
      //echo "Record updated successfully";
    } else {
      echo "Error updating record: " . $conn->error;
    }

 return $num_rows;
 }

}

?>
