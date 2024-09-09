<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require_once "./configDB.php";

$tableName = "user_log";


if (isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];
    switch ($action) {
        case 'getRawDATA' :
            getRawDATA($DBconnect , $tableName);
            break;
        case 'getPatternDATA' :
            getPatternDATA($DBconnect , $tableName);
            break;
        case 'getEssayDATA' :
            getEssayDATA($DBconnect , $tableName);
            break;
	case 'getScaffoldDATA' :
            getScaffoldDATA($DBconnect , $tableName);
            break;
	default:
        // ...etc...
    }
}else{
    getRawDATA($DBconnect, $tableName);
}

function getRawDATA($DBconnect, $tableName)
{

    $sql = "SELECT * FROM " . $tableName .  " WHERE userid = '" . $_POST['qid'] . "' AND sub_action <> 'KEYDOWN' order by time_lapsed";
    $query = mysqli_query($DBconnect, $sql) or die("Mysql Error in getting : get users");
    $numrows = mysqli_num_rows($query);
    $result = array();
    if($numrows > 0){
        while ($row = mysqli_fetch_assoc($query)) 
        { 
            $row['id'] = intval($row['id']);
            $result[] = $row;
        }
    }
    echo json_encode($result);   // send data as json format
}

function getPatternDATA($DBconnect, $tableName)
{

    $sql = "SELECT * FROM " . $tableName  .  " WHERE userid = '" . $_POST['qid'] . "' GROUP BY uid , process_label, process_starttime order by time_lapsed"; 
    $query = mysqli_query($DBconnect, $sql) or die("Mysql Error in getting : get users");
    $numrows = mysqli_num_rows($query);
    $result = array();
    if($numrows > 0){
        while ($row = mysqli_fetch_assoc($query)) 
        { 
            $row['id'] = intval($row['id']);
            $result[] = $row;
        }
    }
    echo json_encode($result);   // send data as json format
}

function getEssayDATA($DBconnect, $tableName)
{ 
    $sql = "SELECT essay_snapshot FROM " . $tableName . "  WHERE userid = '" . $_POST['qid'] . "' AND length(essay_snapshot) <> 0 order by time_lapsed DESC LIMIT 1";
    $query = mysqli_query($DBconnect, $sql) or die("Mysql Error in getting : get users");
    $numrows = mysqli_num_rows($query);
    $result = "None Found!";
    if($numrows > 0){
        while ($row = mysqli_fetch_assoc($query)) 
        { 
            $result = $row['essay_snapshot'];
        }
    }
    echo $result;
}

function getScaffoldDATA($DBconnect, $tableName)
{

    $quotedAndCommaSeparated =  "'" .implode("', '", $_POST['qid']) . "'";
    $sql = "SELECT user, MAX(CASE WHEN (value = 'sid:1' AND sub_action = 'Message_Displayed' AND  time_lapsed  > (2 * 1000 * 60) AND  time_lapsed  < (6 * 1000 * 60))  THEN clock WHEN (value = 'undefined' AND  time_lapsed  > (2 * 1000 * 60) AND  time_lapsed  < (6 * 1000 * 60))  THEN 'Skipped' ELSE '***' END) AS Scaffold_1, MAX(CASE WHEN ((value = 'sid:1' AND sub_action = 'Message_Closed') OR (sub_action = 'CreateChecklist' AND value LIKE 'sid:1%')) AND  time_lapsed  > (2 * 1000 * 60) AND  time_lapsed  < (6 * 1000 * 60)  THEN clock WHEN (value = 'undefined' AND  time_lapsed  > (2 * 1000 * 60) AND  time_lapsed  < (6 * 1000 * 60))  THEN 'Skipped' ELSE '***' END) AS Scaffold_1_Closed , MAX(CASE WHEN value = 'sid:2' AND sub_action = 'Message_Displayed' AND  time_lapsed  > (7 * 1000 * 60) AND  time_lapsed  < (11 * 1000 * 60) THEN clock WHEN value = 'undefined' AND  time_lapsed  > (7 * 1000 * 60) AND  time_lapsed  < (11 * 1000 * 60) THEN 'Skipped' ELSE '***' END) AS Scaffold_2, MAX(CASE WHEN ((value = 'sid:2' AND sub_action = 'Message_Closed') OR (sub_action = 'CreateChecklist' AND value LIKE 'sid:2%')) AND  time_lapsed  > (7 * 1000 * 60) AND  time_lapsed  < (11 * 1000 * 60) THEN clock WHEN value = 'undefined' AND  time_lapsed  > (7 * 1000 * 60) AND  time_lapsed  < (11 * 1000 * 60) THEN 'Skipped' ELSE '***' END) AS Scaffold_2_Closed, MAX(CASE WHEN value = 'sid:3' AND sub_action = 'Message_Displayed' AND  time_lapsed  > (16 * 1000 * 60) AND  time_lapsed  < (20 * 1000 * 60) THEN clock WHEN value = 'undefined' AND  time_lapsed  > (16 * 1000 * 60) AND  time_lapsed  < (20 * 1000 * 60) THEN 'Skipped' ELSE '***' END) AS Scaffold_3, MAX(CASE WHEN ((value = 'sid:3' AND sub_action = 'Message_Closed') OR (sub_action = 'CreateChecklist' AND value LIKE 'sid:3%')) AND  time_lapsed  > (16 * 1000 * 60) AND  time_lapsed  < (20 * 1000 * 60) THEN clock WHEN value = 'undefined' AND  time_lapsed  > (16 * 1000 * 60) AND  time_lapsed  < (20 * 1000 * 60) THEN 'Skipped' ELSE '***' END) AS Scaffold_3_Closed, MAX(CASE WHEN value = 'sid:4' AND sub_action = 'Message_Displayed'  AND  time_lapsed  > (21 * 1000 * 60) AND  time_lapsed  < (25 * 1000 * 60) THEN clock WHEN value = 'undefined' AND  time_lapsed  > (21 * 1000 * 60) AND  time_lapsed  < (25 * 1000 * 60) THEN 'Skipped'  ELSE '***' END) AS Scaffold_4, MAX(CASE WHEN ((value = 'sid:4' AND sub_action = 'Message_Closed') OR (sub_action = 'CreateChecklist' AND value LIKE 'sid:4%'))  AND  time_lapsed  > (21 * 1000 * 60) AND  time_lapsed  < (25 * 1000 * 60) THEN clock WHEN value = 'undefined' AND  time_lapsed  > (21 * 1000 * 60) AND  time_lapsed  < (25 * 1000 * 60) THEN 'Skipped'  ELSE '***' END) AS Scaffold_4_Closed, MAX(CASE WHEN value = 'sid:5' AND sub_action = 'Message_Displayed' AND  time_lapsed  > (35 * 1000 * 60) AND  time_lapsed  < (39 * 1000 * 60) THEN  clock WHEN value = 'undefined' AND  time_lapsed  > (35 * 1000 * 60) AND  time_lapsed  < (39 * 1000 * 60) THEN  'Skipped' ELSE '***' END) AS Scaffold_5, MAX(CASE WHEN ((value = 'sid:5' AND sub_action = 'Message_Closed') OR (sub_action = 'CreateChecklist' AND value LIKE 'sid:5%')) AND  time_lapsed  > (35 * 1000 * 60) AND  time_lapsed  < (39 * 1000 * 60) THEN  clock WHEN value = 'undefined' AND  time_lapsed  > (35 * 1000 * 60) AND  time_lapsed  < (39 * 1000 * 60) THEN  'Skipped' ELSE '***' END) AS Scaffold_5_Closed, COUNT(*) AS Total_Scaffolds FROM  (SELECT userid as user, time_lapsed  , clock, value, sub_action FROM `user_log` WHERE (sub_action = 'Message_Displayed' OR sub_action = 'Message_Closedxxx' OR sub_action = 'Scaffold_Skipped' OR sub_action = 'CreateChecklistxxx' ) AND userid in ( " . $quotedAndCommaSeparated  . ")  GROUP BY userid, value, sub_action  ORDER BY `user_log`.`userid` , time_lapsed ASC) as t GROUP BY user";

   $query = mysqli_query($DBconnect, $sql) or die("Mysql Error in getting : get user data");

    $numrows = mysqli_num_rows($query);
    $result = array();
    $summary = "<table id = 'scaff_result' width = '100%' style='border: grey solid 1px;'>";
    $summary .= "<tr><th> User ID </th> <th> Scaffold 1  </th> <th> Scaffold 2 </th> <th> Scaffold 3 </th> <th> Scaffold 4 </th> <th> Scaffold 5 </th> <th> Total Scaffolds  <br>(incl. Edits + Misfires) </th> </tr>";
    if($numrows > 0){
        while ($row = mysqli_fetch_assoc($query))
        {

            $summary .= "<tr>";
            $summary .= "<td>" . $row['user'] . "</td>";
	    $summary .= "<td>" . $row['Scaffold_1'] . "</td>"; // "<br/>" . $row['Scaffold_1_Closed'] . "</td>";
            $summary .= "<td>" . $row['Scaffold_2'] . "</td>"; // "<br/>" . $row['Scaffold_2_Closed'] . "</td>";
            $summary .= "<td>" . $row['Scaffold_3'] . "</td>"; // "<br/>" . $row['Scaffold_3_Closed'] . "</td>";
            $summary .= "<td>" . $row['Scaffold_4'] . "</td>"; // "<br/>" . $row['Scaffold_4_Closed'] . "</td>";
            $summary .= "<td>" . $row['Scaffold_5'] . "</td>"; // "<br/>" . $row['Scaffold_5_Closed'] . "</td>";
            $summary .= "<td>TBD</td>" ; //$row['Total_Scaffolds'] . "</td>";

            $value[] = $row['user'];
            $_POST['qid'] = array_diff( $_POST['qid'], $value);
        }
    }else{
        //$summary .= "<tr><td style = 'padding: 5px;'> No Scaffolds Data Found!</td></tr>";
    }

    foreach ($_POST['qid'] as &$id) {
        $summary .= "<tr><td> " . $id .  "</td> <td> *** </td> <td> *** </td> <td> *** </td> <td> *** </td> <td> *** </td> <td> 0 </td> </tr>";
    }

    $summary .= "</table>";
    echo $summary;

}

?>
