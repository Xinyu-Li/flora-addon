
<html lang = "en">

 <head>
 <title>Install Log Database</title>
      <link href = "https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel = "stylesheet">
      
      <style>
         body {
            padding-top: 40px;
            padding-bottom: 40px;
            //background-color: #ADABAB;
         }
         
         .form-signin {
            max-width: 330px;
            padding: 15px;
            margin: 0 auto;
            color: #017572;
         }
         
         .form-signin .form-signin-heading,
         .form-signin .checkbox {
            margin-bottom: 10px;
         }
         
         .form-signin .checkbox {
            font-weight: normal;
         }
         
         .form-signin .form-control {
            position: relative;
            height: auto;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            padding: 10px;
            font-size: 16px;
         }
         
         .form-signin .form-control:focus {
            z-index: 2;
         }
         
         .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
            border-color:#017572;
         }
         
         .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-color:#017572;
         }
         
         h2{
            text-align: center;
            color: #017572;
         }
      </style>
      

 </head>
    
   <body>
      
      <h2>FLoRA Log DB Installation</h2> 
      
         
         <?php
         echo "<div class = \"container form-signin\" style=\"min-width: 700px; text-align:left;\">";
            $msg = '';
            
            if (isset($_POST['db_user']) && !empty($_POST['db_pass']) && !empty($_POST['db_host'])) {

               $servername = $_POST['db_host'];
               $username = $_POST['db_user'];
               $password = $_POST['db_pass'];
               $dbname = "flora";

               // Create connection
               $conn = new mysqli($servername, $username, $password);
               // Check connection
               if ($conn->connect_error) {
                 die("Connection failed: " . $conn->connect_error);
               }

               // sql to create table
               $sql = "CREATE DATABASE IF NOT EXISTS `flora` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;";
               
               if ($conn->query($sql) === TRUE) {
                 echo "Output <br/> ========<br/><br/>";
                 echo "&#9989;&#160;&#160; -> Database 'flora' created successfully";
               } else {
                 echo "&#10060;&#160;&#160; -> Error creating database: " . $conn->error;
               }

               $conn->close();
              

              // Create connection
               $conn = new mysqli($servername, $username, $password, $dbname);

               // Check connection
               if ($conn->connect_error) {
                 die("Connection failed: " . $conn->connect_error);
               }

               
               // sql to create table
               $sql = "CREATE TABLE IF NOT EXISTS `scaffolds` (
                 `id` varchar(3) NOT NULL,
                 `title` varchar(64) NOT NULL,
                 `content1` varchar(512) NOT NULL,
                 `content2` varchar(512) NOT NULL,
                 `language` char(4) NOT NULL,
                 `option1_text` varchar(128) NOT NULL,
                 `option2_text` varchar(128) NOT NULL,
                 `option3_text` varchar(128) NOT NULL,
                 `option4_text` varchar(128) NOT NULL,
                 `option1_short` varchar(128) NOT NULL,
                 `option2_short` varchar(128) NOT NULL,
                 `option3_short` varchar(128) NOT NULL,
                 `option4_short` varchar(128) NOT NULL,
                 `option1_enabled` int(11) NOT NULL,
                 `option2_enabled` int(11) NOT NULL,
                 `option3_enabled` int(11) NOT NULL,
                 `option4_enabled` int(11) NOT NULL,
                 `option1_link` varchar(128) NOT NULL,
                 `option2_link` varchar(128) NOT NULL,
                 `option3_link` varchar(128) NOT NULL,
                 `option4_link` varchar(128) NOT NULL,
                 `type` varchar(64) NOT NULL
               ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

               if ($conn->query($sql) === TRUE) {
                 echo "<br/> &#9989;&#160;&#160; ---> Table 'scaffold' created successfully";
               } else {
                 echo "<br/> &#10060;&#160;&#160; ---> Error creating table: " . $conn->error;
               }


               // sql to create table
               $sql = "CREATE TABLE IF NOT EXISTS scaffolds_cn_improved (
                  id varchar(3) NOT NULL,
                  title varchar(128) CHARACTER SET utf16 NOT NULL,
                  content1 varchar(1024) CHARACTER SET utf16 NOT NULL,
                  content2 varchar(512) CHARACTER SET utf16 NOT NULL,
                  language char(5) NOT NULL,
                  option1_text varchar(128) CHARACTER SET utf16 NOT NULL,
                  option2_text varchar(128) CHARACTER SET utf16 NOT NULL,
                  option3_text varchar(128) CHARACTER SET utf16 NOT NULL,
                  option4_text varchar(128) NOT NULL,
                  option1_short varchar(128) CHARACTER SET utf16 NOT NULL,
                  option2_short varchar(128) CHARACTER SET utf16 NOT NULL,
                  option3_short varchar(128) CHARACTER SET utf16 NOT NULL,
                  option4_short varchar(128) NOT NULL,
                  option1_enabled int(11) NOT NULL,
                  option2_enabled int(11) NOT NULL,
                  option3_enabled int(11) NOT NULL,
                  option4_enabled int(11) NOT NULL,
                  option1_link varchar(128) NOT NULL,
                  option2_link varchar(128) NOT NULL,
                  option3_link varchar(128) NOT NULL,
                  option4_link varchar(128) NOT NULL,
                  type varchar(64) NOT NULL
                  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

               if ($conn->query($sql) === TRUE) {
                 echo "<br/> &#9989;&#160;&#160; ---> Table 'scaffolds_cn_improved' created successfully";
               } else {
                 echo "<br/> &#10060;&#160;&#160; ---> Error creating table: " . $conn->error;
               }


               // sql to create table
               $sql = "CREATE TABLE IF NOT EXISTS  scaffolds_improved (
                          id varchar(3) NOT NULL,
                          title varchar(128) NOT NULL,
                          content1 varchar(512) NOT NULL,
                          content2 varchar(512) NOT NULL,
                          language char(5) NOT NULL,
                          option1_text varchar(128) NOT NULL,
                          option2_text varchar(128) NOT NULL,
                          option3_text varchar(128) NOT NULL,
                          option4_text varchar(128) NOT NULL,
                          option1_short varchar(128) NOT NULL,
                          option2_short varchar(128) NOT NULL,
                          option3_short varchar(128) NOT NULL,
                          option4_short varchar(128) NOT NULL,
                          option1_enabled int(11) NOT NULL,
                          option2_enabled int(11) NOT NULL,
                          option3_enabled int(11) NOT NULL,
                          option4_enabled int(11) NOT NULL,
                          option1_link varchar(128) NOT NULL,
                          option2_link varchar(128) NOT NULL,
                          option3_link varchar(128) NOT NULL,
                          option4_link varchar(128) NOT NULL,
                          type varchar(64) NOT NULL
                        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

               if ($conn->query($sql) === TRUE) {
                 echo "<br/> &#9989;&#160;&#160; ---> Table 'scaffolds_improved ' created successfully";
               } else {
                 echo "<br/> &#10060;&#160;&#160; ---> Error creating table: " . $conn->error;
               }

               // sql to create table
               $sql = "CREATE TABLE IF NOT EXISTS user_log (
                    id int(11) NOT NULL,
                    source varchar(100) NOT NULL,
                    userid varchar(256) NOT NULL,
                    uid varchar(256) NOT NULL,
                    session_start varchar(96) DEFAULT NULL,
                    block varchar(8) NOT NULL,
                    block_desc varchar(128) NOT NULL,
                    time_lapsed bigint(20) NOT NULL,
                    clock varchar(512) NOT NULL,
                    timestamp varchar(256) NOT NULL,
                    url varchar(256) NOT NULL,
                    action varchar(256) NOT NULL,
                    sub_action varchar(256) NOT NULL,
                    value longtext NOT NULL,
                    logServer varchar(256) NOT NULL,
                    server_time timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                    log_obj varchar(5000) NOT NULL,
                    granular_time timestamp(6) NOT NULL DEFAULT current_timestamp(6),
                    essay_snapshot longtext NOT NULL,
                    action_label varchar(128) NOT NULL,
                    action_delta bigint(20) NOT NULL DEFAULT -99,
                    process_label varchar(128) NOT NULL,
                    process_starttime bigint(20) NOT NULL DEFAULT -99,
                    process_endtime bigint(20) NOT NULL DEFAULT -99
                  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

               if ($conn->query($sql) === TRUE) {
                 echo "<br/>  &#9989;&#160;&#160; ---> Table 'user_log ' created successfully";
               } else {
                 echo "<br/> &#10060;&#160;&#160; ---> Error creating table: " . $conn->error;
               }


               $sql = "ALTER TABLE user_log ADD PRIMARY KEY (id);";
               if ($conn->query($sql) === TRUE) {
                 echo "<br/>  &#9989;&#160;&#160; ---> Table 'user_log ' key created successfully";
               } else {
                 echo "<br/> &#10060;&#160;&#160; ---> Error creating table key: " . $conn->error;
               }

               $sql = "ALTER TABLE user_log MODIFY id int(11) NOT NULL AUTO_INCREMENT;";
               if ($conn->query($sql) === TRUE) {
                 echo "<br/>  &#9989;&#160;&#160; ---> Table 'user_log ' auto-increment created successfully";
               } else {
                 echo "<br/> &#10060;&#160;&#160; ---> Error creating table auto-increment: " . $conn->error;
               }


               // sql to create table
               $sql = "CREATE TABLE IF NOT EXISTS scaffold_log (
                    id int(11) NOT NULL,
                    source varchar(100) NOT NULL,
                    userid varchar(256) NOT NULL,
                    uid varchar(256) NOT NULL,
                    session_start varchar(96) DEFAULT NULL,
                    block varchar(8) NOT NULL,
                    block_desc varchar(128) NOT NULL,
                    time_lapsed bigint(20) NOT NULL,
                    clock varchar(512) NOT NULL,
                    timestamp varchar(256) NOT NULL,
                    url varchar(256) NOT NULL,
                    action varchar(256) NOT NULL,
                    sub_action varchar(256) NOT NULL,
                    value longtext NOT NULL,
                    logServer varchar(256) NOT NULL,
                    server_time timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                    log_obj varchar(5000) NOT NULL,
                    granular_time timestamp(6) NOT NULL DEFAULT current_timestamp(6),
                    essay_snapshot longtext NOT NULL,
                    action_label varchar(128) NOT NULL,
                    action_delta bigint(20) NOT NULL DEFAULT -99,
                    process_label varchar(128) NOT NULL,
                    process_starttime bigint(20) NOT NULL DEFAULT -99,
                    process_endtime bigint(20) NOT NULL DEFAULT -99
                  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

               if ($conn->query($sql) === TRUE) {
                 echo "<br/>  &#9989;&#160;&#160; ---> Table 'scaffold_log ' created successfully";
               } else {
                 echo "<br/> &#10060;&#160;&#160; ---> Error creating table: " . $conn->error;
               }


               $sql = "ALTER TABLE scaffold_log ADD PRIMARY KEY (id);";
               if ($conn->query($sql) === TRUE) {
                 echo "<br/>  &#9989;&#160;&#160; ---> Table 'scaffold_log' key created successfully";
               } else {
                 echo "<br/> &#10060;&#160;&#160; ---> Error creating table key: " . $conn->error;
               }

               $sql = "ALTER TABLE scaffold_log MODIFY id int(11) NOT NULL AUTO_INCREMENT;";
               if ($conn->query($sql) === TRUE) {
                 echo "<br/>  &#9989;&#160;&#160; ---> Table 'scaffold_log' auto-increment created successfully";
               } else {
                 echo "<br/> &#10060;&#160;&#160; ---> Error creating table auto-increment: " . $conn->error;
               }




               // delete data
               $delete = "DELETE FROM `scaffolds` WHERE 1=1;";
               if ($conn->query($delete) === TRUE) {
                 // echo("<br /> ... Resetting previous tables.");
               }else{
                  echo("<br />  &#10060;&#160;&#160; ... Unable to reset previous tables.");
               }
               
               $delete = "DELETE FROM `scaffolds_cn_improved` WHERE 1=1;";
               if ($conn->query($delete) === TRUE) {
                 // echo("<br /> ... Resetting previous tables.");
               }else{
                  echo("<br />  &#10060;&#160;&#160; ... Unable to reset previous tables.");
               }
               
               $delete = "DELETE FROM `scaffolds_improved` WHERE 1=1;";
               if ($conn->query($delete) === TRUE) {
                 // echo("<br /> ... Resetting previous tables.");


                $myfile = fopen("cphp", "w") or die("Unable to open/write to file! Please temporarily chmod 777 for /admin/config.php");
                fwrite($myfile, "<?php\n");
                fwrite($myfile, "\$_servername  = '". $_POST['db_host'] . "';\n");
                fwrite($myfile, "\$_username = '" . $_POST['db_user'] . "';\n");
                fwrite($myfile, "\$_password = '" . $_POST['db_pass'] . "';\n");
                fwrite($myfile, "\$_dbname = '" . "flora" . "';\n");
                fwrite($myfile, "?>");
                fclose($myfile);

                echo "<br/>&#9989;&#160;&#160; ----> Config file created successfully";
               }else{
                  echo("<br />  &#10060;&#160;&#160; ... Unable to create config file. Please enable temporary write access to your web root/admin/ folder.");
               }



               $sql = "INSERT INTO scaffolds (id, title, content1, content2, language, option1_text, option2_text, option3_text, option4_text, option1_short, option2_short, option3_short, option4_short, option1_enabled, option2_enabled, option3_enabled, option4_enabled, option1_link, option2_link, option3_link, option4_link, type) VALUES ('1', 'Understand the task', 'Es ist wichtig zu verstehen, worum es bei dieser Aufgabe geht.', 'Basierend auf deinem bisherigen Lernverhalten, empfehlen wir dir folgende Schritte: ', 'de', 'Überprüfe die Lernziele und Anweisungen.', 'Überprüfe die Aufsatz-Rubrik', 'Verwende das Menü, um dir einen Überblick zu verschaffen, und überfliege die Texte', 'Verarbeite Informationen durch Notizen', 'Allgemeine Anweisungen', 'Rubrik', 'Navigationsmenü', 'Notizen', 1, 1, 1, 1, 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=1', 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=2', '', '', 'scaffold'), ('1', 'Understand the task', 'It is important to understand what the task is about.', 'Which are the most helpful steps for you to understand the task? \r\n\r\n(Please select from the recommended options below)', 'en', 'Check the learning goals and instructions', 'Check the essay rubric', 'Use menu to get an overview and skim text', 'Process information by taking notes', 'Instructions page', 'Rubric page', 'Navigation panel', 'Annotation tool', 1, 1, 1, 1, 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=1', 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=2', '', '', 'scaffold'),('1', 'Understand the task', 'It is important to understand what the task is about.', 'Which are the most helpful steps for you to understand the task? \r\n\r\n(Please select from the recommended options below)', 'nl', 'Check the learning goals and instructions', 'Check the essay rubric', 'Use menu to get an overview and skim text', 'Process information by taking notes', 'Instructions page', 'Rubric page', 'Navigation panel', 'Annotation tool', 1, 1, 1, 1, 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=1', 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=2', '', '', 'scaffold'),('2', 'Start reading', 'Es ist wichtig, die Informationen über die Themen zu lesen.', 'Welche Schritte sind für dich am hilfreichsten, um die Aufgabe zu verstehen?', 'de', 'Wähle aus, was du lesen möchtest.', 'Suche nach spezifischen Informationen\r\n', 'Notiere und strukturiere wichtige Informationen\r\n', 'Überprüfe die übrige Zeit', 'Navigationsmenü', 'Such-Tool', 'Notizen', 'Timer', 1, 1, 1, 1, '', '', '', 'a:.letime', 'scaffold'),('2', 'Start reading', 'It is important to read information about the topics.', 'Which are the most helpful steps for you to understand the text so as to do the task? (Please select from the recommended options below)', 'en', 'Select what to read', 'Search for (specific) information', 'Note down important information', 'Check the time left', 'Navigation panel', 'Search tool', 'Annotation tool', 'Timer tool', 1, 1, 1, 1, '', '', '', 'a:.letime', 'scaffold'),('2', 'Start reading', 'It is important to read information about the topics.', 'Which are the most helpful steps for you to understand the text so as to do the task? (Please select from the recommended options below)', 'nl', 'Select what to read', 'Search for (specific) information', 'Note down important information', 'Check the time left', 'Navigation panel', 'Search tool', 'Annotation tool', 'Timer tool', 1, 1, 1, 1, '', '', '', 'a:.letime', 'scaffold'),('3', 'Monitor reading', 'Es ist wichtig, die aufgabenrelevanten Informationen zu lesen und dein Lernen zu überprüfen.', 'Welche Schritte sind für dich am hilfreichsten, um dein Lesen zu überprüfen?', 'de', 'Überprüfe das bisher Gelernte mit Hilfe der Notizen', 'Überprüfe die Aufsatz-Rubrik', 'Überprüfe im Aufsatz, was du als nächstes liest', 'Überprüfe die Lernziele und Anweisungen', 'Notizen', 'Rubrik', 'Aufsatz', 'Allgemeine Anweisungen', 1, 1, 1, 1, '', 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=2', 'a:.leessay', 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=1', 'scaffold'),('3', 'Monitor reading', 'It is important to read relevant information and review your reading', 'Which are the most helpful steps for you to review your reading? (Please select from the recommended options below)', 'en', 'Review annotations to check learning so far', 'Check the essay rubric', 'Check essay to determine what to read next', 'Review the learning goals and instructions', 'Go to Annotation tool', 'Go to Rubric Page', 'Go to Essay', 'Go to Instruction', 1, 1, 1, 1, '', 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=2', 'a:.leessay', 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=1', 'scaffold'),('3', 'Monitor reading', 'It is important to read relevant information and review your reading', 'Which are the most helpful steps for you to review your reading? (Please select from the recommended options below)', 'nl', 'Review annotations to check learning so far', 'Check the essay rubric', 'Check essay to determine what to read next', 'Review the learning goals and instructions', 'Go to Annotation tool', 'Go to Rubric Page', 'Go to Essay', 'Go to Instruction', 1, 1, 1, 1, '', 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=2', 'a:.leessay', 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=1', 'scaffold'), ('4', 'Start essay', 'Es ist wichtig, einen guten Aufsatz zu schreiben.', 'Welche Schritte sind für dich am hilfreichsten, um an deinem Aufsatz zu arbeiten? ', 'de', 'Entwirf den Aufsatz, indem du das Gelernte in Stichpunkte überträgst', 'Überprüfe die Aufsatz-Rubrik', 'Überprüfe die übrige Zeit ', 'Schreibe mit Hilfe der Notizen den Aufsatz', 'Aufsatz', 'Rubrik', 'Timer', 'Notizen', 1, 1, 1, 1, 'a:.leessay', 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=2', 'a:.letime', '', 'scaffold'),('4', 'Start essay', 'It is important to write a good essay.', 'Which are the most helpful steps for you to work on your essay? (Please select from the recommended options below)', 'en', 'Draft essay by transferring learning to main points', 'Review the essay rubric', 'Check the remaining time', 'Write the essay with help from notes', 'Essay tool', 'Rubric page', 'Timer', 'Annotation tool', 1, 1, 1, 1, 'a:.leessay', 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=2', 'a:.letime', '', 'scaffold'),('4', 'Start essay', 'It is important to write a good essay.', 'Which are the most helpful steps for you to work on your essay? (Please select from the recommended options below)', 'nl', 'Draft essay by transferring learning to main points', 'Review the essay rubric', 'Check the remaining time', 'Write the essay with help from notes', 'Essay tool', 'Rubric page', 'Timer', 'Annotation too', 1, 1, 1, 1, 'a:.leessay', 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=2', 'a:.letime', '', 'scaffold'),('5', 'Monitor essay', 'Es ist wichtig, relevante Informationen zu notieren und dein Geschriebenes zu überprüfen.', 'Welche Schritte sind für dich am hilfreichsten, um dein Geschriebenes zu überprüfen? ', 'de', 'Überprüfe die Aufsatz-Rubrik', 'Überprüfe die übrige Zeit ', 'Schreibe mit Hilfe der Notizen den Aufsatz', 'Kontrolliere die Lernziele und Anweisungen', 'Rubrik', 'Timer', 'Aufsatz', 'Allgemeine Anweisungen', 1, 1, 1, 1, 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=2', 'a:.letime', 'a:.leessay', 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=1', 'scaffold'),('5', 'Monitor essay', 'It is important to write relevant information and check your writing.', 'Which are the most helpful steps for you to check your writing? (Please select from the recommended options below)', 'en', 'Review the essay rubric', 'Check the timer to manage your time', 'Edit your essay', 'Check the learning goals and instructions', 'Rubrics page', 'Timer tool', 'Essay tool', 'Instructions page', 1, 1, 1, 1, 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=2', 'a:.letime', 'a:.leessay', 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=1', 'scaffold'),('5', 'Monitor essay', 'It is important to write relevant information and check your writing.', 'Which are the most helpful steps for you to check your writing? (Please select from the recommended options below)', 'nl', 'Review the essay rubric', 'Check the timer to manage your time', 'Edit your essay', 'Check the learning goals and instructions', 'Rubrics page', 'Timer tool', 'Essay tool', 'Instructions page', 1, 1, 1, 1, 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=2', 'a:.letime', 'a:.leessay', 'u:https://floralearn.org/lms/mod/lesson/view.php?id=1&pageid=1', 'scaffold');";

               if ($conn->query($sql) === TRUE) {
                 echo "<br/>&#9989;&#160;&#160; -----> New 'scaffold' data created successfully";
               } else {
                 echo "<br/> &#10060;&#160;&#160; -----> Error: " . $sql . "<br>" . $conn->error;
               }

               $sql = "INSERT INTO `scaffolds_cn_improved` (`id`, `title`, `content1`, `content2`, `language`, `option1_text`, `option2_text`, `option3_text`, `option4_text`, `option1_short`, `option2_short`, `option3_short`, `option4_short`, `option1_enabled`, `option2_enabled`, `option3_enabled`, `option4_enabled`, `option1_link`, `option2_link`, `option3_link`, `option4_link`, `type`) VALUES
               ('1', '理解任务要求', '准确理解读写任务的内容和要求是至关重要的', '基于你目前为止对学习行为，我们推荐如下有益的学习步骤：', 'zh_cn', '用导航栏浏览文本并在脑海中构建全局感', '认真阅读短文的评分量规（rubric）', '确保你明确学习目标和任务要求', 'Process information by taking notes', '导航栏', '评分量规', '任务说明页', 'Annotation tool', 1, 1, 1, 0, '', 'u:/lms/mod/lesson/view.php?id=14&pageid=2', 'u:/lms/mod/lesson/view.php?id=14&pageid=1', '', 'scaffold'),
               ('2', '开启高质量阅读', '高效且高质量地阅读材料中不同主题的信息是很有必要的', '基于你目前为止对学习行为，我们推荐如下有益的学习步骤：', 'zh_cn', '把读到的要点用笔记工具记录下来', '用导航栏思考并选择下一步该读什么', '监控自己的阅读时间进度', 'Check the time left', '标注工具', '导航栏', '倒计时', 'Timer tool', 1, 1, 1, 0, '', '', '\0a:.letime', '', 'scaffold'),
               ('3', '调控阅读进程', '确保只阅读跟任务相关页面，并思考阅读和写作的关系是学习成功的关键', '基于你目前为止对学习行为，我们推荐如下有益的学习步骤：', 'zh_cn', '回顾你在阅读中的标注并检查自己学到了什么', '复习学习目标和任务要求确保所读内容跟任务相关', '基于短文的写作进度和整体构思有选择性地阅读', 'Review the learning goals and instructions', '标注工具', '任务说明页', '你的短文', 'Go to Instruction', 1, 1, 1, 0, '', 'u:/lms/mod/lesson/view.php?id=14&pageid=1', 'a:.leessay', '', 'scaffold'),
               ('4', '开启短写作', '尽早开启写作，并认真撰写一篇高质量的短文是该任务成功的核心', '基于你目前为止对学习行为，我们推荐如下有益的学习步骤：', 'zh_cn', '监控自己还剩下多长时间写作', '重新阅读短文的评分量规（rubric）', '将所读的要点内容转化为自己的语言去撰写短文', 'Write the essay with help from notes', '倒计时', '评分量规', '你的短文', 'Annotation tool', 1, 1, 1, 0, '\0a:.letime', 'u:/lms/mod/lesson/view.php?id=14&pageid=2', 'a:.leessay', '', 'scaffold'),
               ('5', '完善你的短文', '在任务的最后，按照任务说明和评分量规完善短文才能拿到高分', '基于你目前为止对学习行为，我们推荐如下有益的学习步骤：', 'zh_cn', '根据评分量规检查或改进自己短文的写作', '完善你的短文，并确保完整性和篇幅达标', '再次检查学习目标和写作主题，避免跑题', 'Check the learning goals and instructions', '评分量规', '你的短文', '任务说明页', 'Algemene instructie', 1, 1, 1, 0, 'u:/lms/mod/lesson/view.php?id=11&pageid=2', 'a:.leessay', 'u:/lms/mod/lesson/view.php?id=14&pageid=1', '', 'scaffold');";

               if ($conn->query($sql) === TRUE) {
                 echo "<br/>&#9989;&#160;&#160; -----> New 'scaffolds_cn_improved' data created successfully";
               } else {
                 echo "<br/> &#10060;&#160;&#160; -----> Error: " . $sql . "<br>" . $conn->error;
               }

               $sql = "INSERT INTO scaffolds_improved (id, title, content1, content2, language, option1_text, option2_text, option3_text, option4_text, option1_short, option2_short, option3_short, option4_short, option1_enabled, option2_enabled, option3_enabled, option4_enabled, option1_link, option2_link, option3_link, option4_link, type) VALUES
                  ('1', 'Sample Pompt Title 1', 'Sample Main Message # 1', 'Sample Secondary Message 1', 'eg', 'Option 1 Text', 'Option 2 Text', 'Option 3 Text', 'Option 4 Text', 'Tool 1', 'Tool 2', 'Tool 3', 'Tool 4', 1, 1, 1, 1, '', '', '', '', 'scaffold'),
                  ('1', '\0Understand the task', 'It is important to understand what the task is about.', 'Which are the most helpful steps for you to understand the task? \r\n\r\n(Please select from the recommended options below)', 'en', 'Use menu to get an overview and skim text', 'Check the essay rubric', 'Check the learning goals and instructions', 'Process information by taking notes', 'Navigation panel', 'Rubric', 'Instructions page', 'Annotation tool', 1, 1, 1, 0, '', 'u:https://www.floraproject.org/moodle/mod/lesson/view.php?id=11&pageid=2', 'u:https://www.floraproject.org/moodle/mod/lesson/view.php?id=11&pageid=1', '', 'scaffold'),
                  ('1', '\0Begrijp de taak', 'Om goed te presteren is het belangrijk om te begrijpen waar deze taak over gaat.', 'Welke stappen zijn het meest behulpzaam voor jou om de taak te begrijpen?', 'nl', 'Scan het menu en de teksten om een overzicht te krijgen', 'Controleer de rubric', 'Controleer de algemene instructie', 'Process information by taking notes', 'Menu', 'Rubric', 'Algemene instructie', 'Annotation tool', 1, 1, 1, 0, '', 'u:https://www.floraproject.org/moodle/mod/lesson/view.php?id=11&pageid=2', 'u:https://www.floraproject.org/moodle/mod/lesson/view.php?id=11&pageid=1', '', 'scaffold'),
                  ('2', 'Sample Prompt Title 2', 'Sample Main Message # 2', 'Sample Secondary Message 2', 'eg', 'Option 1 Text', 'Option 2 Text', 'Option 3 Text', 'Option 4 Text', 'Tool 1', 'Tool 2', 'Tool 3', 'Tool 4', 1, 1, 1, 1, '', '', '', '', 'scaffold'),
                  ('2', 'Start reading ', 'It is important to read information about the topics.', 'Which are the most helpful steps for you to understand the text so as to do the task? (Please select from the recommended options below)', 'en', 'Note down important information', 'Select what to read', 'Check the time left', 'Check the time left', 'Annotation tool', 'Navigation page', 'Timer', 'Timer tool', 1, 1, 1, 0, '', '', 'a:.letime', '', 'scaffold'),
                  ('2', '\0Start lezen', 'Het is belangrijk om informatie over de onderwerpen te lezen.', 'Welke stappen zijn het meest behulpzaam voor jou om de teksten te begrijpen in relatie tot de taak?', 'nl', 'Noteer en organiseer belangrijke informatie', 'Selecteer wat je gaat lezen', 'Controleer de resterende tijd', 'Check the time left', 'Notities', 'Menu', 'Timer', 'Timer tool', 1, 1, 1, 0, '', '', 'a:.letime', '', 'scaffold'),
                  ('3', 'Sample Prompt Title 3', 'Sample Main Message # 3', 'Sample Secondary Message 3', 'eg', 'Option 1 Text', 'Option 2 Text', 'Option 3 Text', 'Option 4 Text', 'Tool 1', 'Tool 2', 'Tool 3', 'Tool 4', 1, 1, 1, 1, '', '', '', '', 'scaffold'),
                  ('3', '\0Monitor reading', 'It is important to read relevant information and review your reading', 'Which are the most helpful steps for you to review your reading? (Please select from the recommended options below)', 'en', 'Review annotations to check learning so far', 'Review the learning goals and instructions', 'Check essay to determine what to read next', 'Review the learning goals and instructions', 'Annotation tool', 'Instructions page', 'Essay', 'Go to Instruction', 1, 1, 1, 0, '', 'u:https://www.floraproject.org/moodle/mod/lesson/view.php?id=11&pageid=1', 'a:.leessay', '', 'scaffold'),
                  ('3', 'Controleer lezen', 'Om goed te presteren is het belangrijk om relevante informatie te lezen en na te denken over wat je leest.', 'Welke stappen zijn het meest behulpzaam voor jou om jouw lezen te controleren?', 'nl', 'Herlees notities om wat je geleerd hebt te controleren', 'Herlees de leerdoelen en instructies', 'Stel het essay op door wat je geleerd hebt te vertalen naar kernpunten', 'Review the learning goals and instructions', 'Notities', 'Algemene instructie', 'Essay', 'Go to Instruction', 1, 1, 1, 0, '', 'u:https://www.floraproject.org/moodle/mod/lesson/view.php?id=11&pageid=1', 'a:.leessay', '', 'scaffold'),
                  ('4', 'Sample  Prompt Title 4', 'Sample Main Message # 4', 'Sample Secondary Message 4', 'eg', 'Option 1 Text', 'Option 2 Text', 'Option 3 Text', 'Option 4 Text', 'Tool 1', 'Tool 2', 'Tool 3', 'Tool 4', 1, 1, 1, 1, '', '', '', '', 'scaffold'),
                  ('4', '\0Start essay', 'It is important to write a good essay.', 'Which are the most helpful steps for you to work on your essay? (Please select from the recommended options below)', 'en', 'Check the remaining time', 'Check the essay rubric', 'Draft essay by transferring learning to main points', 'Write the essay with help from notes', 'Timer', 'Rubric', 'Essay', 'Annotation tool', 1, 1, 1, 0, 'a:.letime', 'u:https://www.floraproject.org/moodle/mod/lesson/view.php?id=11&pageid=2', 'a:.leessay', '', 'scaffold'),
                  ('4', '\0Start essay', 'Het is belangrijk om een goed essay te schrijven.', 'Welke stappen zijn het meest behulpzaam voor jou om aan jouw essay te werken?', 'nl', 'Controleer de resterende tijd', 'Herlees de essay-rubric', 'Stel het essay op door wat je geleerd hebt te vertalen naar kernpunten', 'Write the essay with help from notes', 'Timer', 'Rubric', 'Essay', 'Annotation too', 1, 1, 1, 0, 'a:.letime', 'u:https://www.floraproject.org/moodle/mod/lesson/view.php?id=11&pageid=2', 'a:.leessay', '', 'scaffold'),
                  ('5', 'Sample Prompt Title 5', 'Sample Main Message # 5', 'Sample Secondary Message 5', 'eg', 'Option 1 Text', 'Option 2 Text', 'Option 3 Text', 'Option 4 Text', 'Tool 1', 'Tool 2', 'Tool 3', 'Tool 4', 1, 1, 1, 1, '', '', '', '', 'scaffold'),
                  ('5', '\0Monitor essay', 'It is important to write relevant information and check your writing.', 'Which are the most helpful steps for you to check your writing? (Please select from the recommended options below)', 'en', 'Check the essay rubric', 'Edit your essay', 'Check the learning goals and instructions', 'Check the learning goals and instructions', 'Rubric page', 'Essay', 'Instructions page', 'Algemene instructie', 1, 1, 1, 0, 'u:https://www.floraproject.org/moodle/mod/lesson/view.php?id=11&pageid=2', 'a:.leessay', 'u:https://www.floraproject.org/moodle/mod/lesson/view.php?id=11&pageid=1', '', 'scaffold'),
                  ('5', 'Controleer essay', 'Het is belangrijk om relevante informatie op te schrijven en na te denken over wat je opschrijft.', 'Welke stappen zijn het meest behulpzaam voor jou om jouw tekst te controleren?', 'nl', 'Herlees de essay-rubric', 'Pas jouw essay aan', 'Controleer de leerdoelen en instructies', 'Check the learning goals and instructions', 'Rubric', 'Essay', 'Algemene instructie', 'Algemene instructie', 1, 1, 1, 0, 'u:https://www.floraproject.org/moodle/mod/lesson/view.php?id=11&pageid=2', 'a:.leessay', 'u:https://www.floraproject.org/moodle/mod/lesson/view.php?id=11&pageid=1', '', 'scaffold');";

               if ($conn->query($sql) === TRUE) {
                 echo "<br/> &#9989;&#160;&#160; -----> New 'scaffolds_improved' data created successfully";
               } else {
                 echo "<br/> &#10060;&#160;&#160; -----> Error: " . $sql . "<br>" . $conn->error;
               }

               $conn->close();

                // Create connection
               $conn = new mysqli($servername, $username, $password, 'moodle');

               // Check connection
               if ($conn->connect_error) {
                 die("Connection failed: " . $conn->connect_error);
               }


               // update global config tables

            $sql = "UPDATE moodle.mdl_config  SET value = '<span id=\'user_c\' style=\"display:none\"></span>\r\n\r\n<script>\r\n\r\n// Please edit this configuration to meet your requirements\r\n/* ==========  Beginning of Configurations  ========== */\r\nvar task_course_id = \"course-4\"; // Primary or main essay writing course ID\r\nvar training_task_id = \"course-3\";  // Training Course ID\r\nvar scaffold_option = \"IMPROVED\" ; // IMPROVED OR STANDARD\r\nvar task_landing_page = \"/lms/mod/lesson/view.php?id=8&pageid=1\";  // landing page (General Instruction Page) for Main Task    \r\n\r\nlocalStorage.setItem(\"mainTask\", 45);\r\nlocalStorage.setItem(\"trainingTask\", 20);\r\nlocalStorage.setItem(\"essayLen\", 400);\r\n\r\n/* ==========  End of Configurations  ========== */\r\n\r\nvar root_url = window.location.protocol + \"//\" + window.location.host ; \r\nvar webServerUrl = root_url + \"/\"; \r\nlocalStorage.setItem(\'task_course_id\',  task_course_id);\r\nlocalStorage.setItem(\'training_task_id\',  training_task_id);\r\nif(window.location.href == (root_url + \"/lms/login/index.php\")){\r\n   localStorage.clear();\r\n}\r\n</script>\r\n\r\n<script src= \"/flora/Timer/jquery.min.js\"></script>\r\n<script type=\"text/javascript\" src=  \"/flora/js/jquery-ui.min.js\"></script>\r\n<link rel=\"stylesheet\" href=  \"/flora/js/jquery-ui.css\">\r\n\r\n<style>\r\n// Handle \"Select One\" in multiple choice questions.\r\n.prompt{\r\n   display:none;\r\n }\r\n\r\n  #progressbar {\r\n    margin-top: 20px;\r\n  }\r\n \r\n  .progress-label {\r\n    font-weight: bold;\r\n    text-shadow: 1px 1px 0 #fff;\r\n  }\r\n \r\n  .ui-dialog-titlebar-close {\r\n    display: none;\r\n  }\r\n\r\n #page-footer {\r\n     display: none;\r\n }\r\n\r\n .ui-dialog-titlebar-close {\r\n    visibility: hidden;\r\n }\r\n\r\n</style>\r\n\r\n<script>\r\nif (localStorage.getItem(\"grp\") != null){\r\nif ((localStorage.getItem(\"u_course\") == training_task_id) && (localStorage.getItem(\"grp\") == \"CN\"))\r\n{\r\n    if (document.getElementById(\"block-region-side-pre\")) {\r\n            document.getElementById(\"block-region-side-pre\").style.display = \"none\";\r\n        }\r\n  if(window.location.href == root_url + \"/lms/mod/book/view.php?id=20&chapterid=4\"){\r\n    //window.location.href =root_url + \"/lms/mod/book/view.php?id=20&chapterid=5\";\r\n    }\r\n   }\r\n}\r\n\r\n// some important global parameters\r\nvar qryRESPONSE = null;\r\n\r\nfunction gPostAjax(url, data, success) {\r\n    let params = typeof data == \'string\' ? data : Object.keys(data).map(\r\n            function(k){ return encodeURIComponent(k) + \'=\' + encodeURIComponent(data[k]) }\r\n        ).join(\'&\');\r\n    var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject(\"Microsoft.XMLHTTP\");\r\n    xhr.open(\'POST\', url);\r\n    xhr.onreadystatechange = function() {\r\n        if (xhr.readyState>3 && xhr.status==200) { success(xhr.responseText); }\r\n    };\r\n    xhr.setRequestHeader(\'X-Requested-With\', \'XMLHttpRequest\');\r\n    xhr.setRequestHeader(\'Content-Type\', \'application/x-www-form-urlencoded\');\r\n    xhr.send(params);\r\n    return xhr;\r\n  }\r\n\r\nfunction gDBQuery(_func, _para1, _para2){\r\n  gPostAjax(root_url + \'/functions.php\', \'source=\' + \'flora\' + \'&func=\' + _func +  \'&para1=\' + _para1 + \'&para2=\' + _para2, function (_res) { \r\nif (_func == \"GET_USER_PREVIOUS_SESSION\"){\r\nqryRESPONSE =JSON.parse(_res); \r\nsetTimer(JSON.parse(_res));\r\n}else if (_func == \"GET_USER_PREVIOUS_ESSAY\"){\r\nsetEssay(JSON.parse(_res));\r\n}else if (_func == \"DELETE_USER_PREVIOUS_SESSION\"){\r\nResetSession(JSON.parse(_res));\r\n}else if (_func == \"CHECK_USER_TIMEUP\"){\r\nEndSession(JSON.parse(_res));\r\n}\r\n  console.log(JSON.parse(_res));\r\n }); \r\n}\r\n\r\nfunction gLogData(action, subaction, value){\r\n    // set values as per the parameters\r\n     gLOG.action = action;\r\n     gLOG.sub_action = subaction;\r\n     gLOG.value = value;\r\n      let now = new Date();\r\n      gLOG.url = window.location.href;\r\n      gLOG.timestamp = now.toLocaleString(\'en-EN\');\r\n     if(document.getElementById(\"logServer\")){\r\n        gLOG.logServer = document.getElementById(\"logServer\").innerHTML;\r\n     }\r\n    if (gLOG.uid !== \"\"){\r\n        var dta = gLOG;\r\n        gPostAjax(root_url + \'/log_to_db.php\', \'source=\' + dta.source +  \'&action=\' + dta.action +  \'&uid=\' + dta.uid + \'&logServer=\' + dta.logServer + \'&sub_action=\' + dta.sub_action + \'&timestamp=\' + dta.timestamp + \'&userid=\' + dta.username + \'&value=\' + dta.value + \'&data=\' + JSON.stringify(dta) + \'&url=\' + dta.url, function (_dt) { console.log(_dt); }); \r\n      }\r\n  }\r\n\r\nfunction ResetSession(d){\r\nconsole.log(\"Resetting Session\");\r\nconsole.log(d);\r\n}\r\n</script>' WHERE name = 'additionalhtmlhead'";                           

            if ($conn->query($sql) === TRUE) {
                 echo "<br/> &#9989;&#160;&#160; -----> New 'additionalhtmlhead' data in config table";
               } else {
                 echo "<br/> &#10060;&#160;&#160; -----> Error: " . $sql . "<br>" . $conn->error;
               }

            $sql = "UPDATE moodle.mdl_config  SET value = '<script>\r\nif (localStorage.getItem(\"user_c\") != null){\r\ndocument.getElementById(\"user_c\").innerHTML = localStorage.getItem(\"user_c\");\r\n  localStorage.setItem(\"grp\",(localStorage.getItem(\"usertext\").split(\'_\'))[1]);\r\n  localStorage.setItem(\'moodle-lang\', $(\'html\').attr(\'lang\'));\r\n}\r\n</script>\r\n\r\n<div id=\"start_dialog\" title=\" Start Main Essay Activity\">\r\nThis task has a time limit of 45 minutes.  Note that only <b> 1 attempt </b> is allowed. Are you sure you want enter the task?\r\n</div>\r\n\r\n<div id=\"home\" title=\"Test Account Verification\">\r\nYou are  currently using a Test account which has multiple privileges. How would you like to proceed?\r\n</div>\r\n\r\n<div id=\"dialog\" title=\"Resetting User Session\">\r\n  <div class=\"progress-label\">Initializing Database...</div>\r\n  <div id=\"progressbar\"></div>\r\n</div>\r\n\r\n<script>\r\nif (window.location.href != (root_url + \"/lms/course/view.php?id=\" + task_course_id.substr(-1) )){\r\n    $(\"#home\").hide();\r\n    $(\".progress-label\").hide();\r\n}\r\n</script>' WHERE name = 'additionalhtmltopofbody'";                           

            if ($conn->query($sql) === TRUE) {
                 echo "<br/> &#9989;&#160;&#160; -----> New 'additionalhtmltopofbody' data in config table";
               } else {
                 echo "<br/> &#10060;&#160;&#160; -----> Error: " . $sql . "<br>" . $conn->error;
               }


              $sql = "UPDATE moodle.mdl_config  SET value = '<script src= \"/flora/Timer/jquery.min.js\"></script>\r\n<script type=\"text/javascript\" src=  \"/flora/js/jquery-ui.min.js\"></script>\r\n<script>\r\nvar start_dialog;\r\nvar progress_dialog;\r\nvar home_dialog;\r\n\r\nif ((document.getElementsByClassName(\"logininfo\")[0].innerHTML != \"You are not logged in.\")&& (document.getElementsByClassName(\"logininfo\")[0].innerHTML != \"You are currently using guest access\")){\r\nvar USER_id = (new URL(document.getElementsByClassName(\"logininfo\")[0].getElementsByTagName(\"a\")[0].href).searchParams).get(\"id\");\r\n//var USER_session = (new URL(document.getElementsByClassName(\"logininfo\")[0].getElementsByTagName(\"a\")[1].href).searchParams).get(\"sesskey\");\r\nlocalStorage.setItem(\"uid\", USER_id);\r\nif(document.getElementsByClassName(\"usertext\").length != 0){\r\nlocalStorage.setItem(\"usertext\", document.getElementsByClassName(\"usertext\")[0].innerHTML.replace(/ /g,\"_\"));\r\nlocalStorage.setItem(\"user_c\", localStorage.getItem(\"uid\") + \"_\" + localStorage.getItem(\"usertext\")) ;\r\n  localStorage.setItem(\"grp\",(localStorage.getItem(\"usertext\").split(\'_\'))[1]);\r\n}\r\n}\r\n\r\n// fix navigation panel\r\nif (document.querySelectorAll(\'[aria-controls=\"nav-drawer\"]\')[0] != undefined){\r\nif (document.querySelectorAll(\'[aria-controls=\"nav-drawer\"]\')[0].getAttribute(\'aria-expanded\') == \"false\"){\r\n     document.querySelectorAll(\'[aria-controls=\"nav-drawer\"]\')[0].click();\r\n}\r\ndocument.querySelectorAll(\'[aria-controls=\"nav-drawer\"]\')[0].style.display = \"none\";\r\n}\r\nif (document.getElementById(\"page-navbar\")){\r\n   document.getElementById(\"page-navbar\").style.display = \"none\";\r\n}\r\n\r\n// get essay data HACK + global LOGGER\r\n\r\nvar gLOG = {\r\n  source : \"flora\",\r\n  url: window.location.href,\r\n  uid: \"000\",\r\n  username: \"anonymous\",\r\n  action : \"PAGE\",\r\n  sub_action: \"LOAD\",\r\n  value: \"Value\",\r\n  timestamp: \"DATE TIME\",\r\n  logServer: \"URL_OF_LOGSERVER\",\r\n  tool_essay: \"CLOSED\",\r\n  tool_planner: \"CLOSED\",\r\n  tool_timer: \"CLOSED\",\r\n  tool_hyp_sidebar: \"CLOSED\",\r\n  tool_hyp_search: \"CLOSED\",\r\n  tool_scaffold: \"CLOSED\",\r\n  tool_todoList: \"CLOSED\",\r\n  tool_notification: \"CLOSED\",\r\n  tool_mouse: \"x y t\",\r\n  tool_eyetracker: \"x y t\"\r\n};\r\n\r\nif (localStorage.length > 0){\r\n    var u_id = (localStorage.getItem(\"uid\") == null) ? \"-99\" : localStorage.getItem(\"uid\");\r\n    var user_name = (localStorage.getItem(\"username\") == null) ? \"-99\" : localStorage.getItem(\"username\");\r\n    var essay = (localStorage.getItem(\"essay\") == null) ? \"null\" : localStorage.getItem(\"essay\");\r\n    gLOG.uid = u_id; gLOG.username = user_name; gLOG.value = essay;\r\n}\r\n\r\n$(\"#start_dialog\").dialog({\r\n      autoOpen: false,\r\n      modal: true\r\n    });\r\n    var targetUrl = root_url + task_landing_page ;\r\n        $(\"#start_dialog\").dialog({\r\n      buttons : {\r\n        \"Yes - Continue\" : function() {\r\n           window.location.href = targetUrl; \r\n           window.location.href = targetUrl; \r\n        },\r\n        \"No - Go to Homepage\" : function() {\r\n               window.location.href =root_url + \"/lms/my/\";\r\n               //$(this).dialog(\"close\");\r\n        }\r\n      }\r\n    });\r\n\r\n\r\nif ($(\'html\').attr(\'lang\') == \"nl\"){\r\n\r\n$(\".ui-dialog-title\")[0].innerHTML = \"Start essay-opdracht\";\r\n$(\"#start_dialog\").html(\"De opdracht heeft een tijdslimiet van 45 minuten. Je krijgt slechts <b>1 poging </b>. Weet je zeker dat je met de opdracht wilt beginnen?\");\r\n\r\n $(\"#start_dialog\").dialog({\r\n      buttons : {\r\n        \"Ja – doorgaan\" : function() {\r\n           window.location.href = targetUrl; \r\n           window.location.href = targetUrl; \r\n        },\r\n        \"Nee – ga naar de startpagina\" : function() {\r\n               window.location.href =root_url + \"/lms/my/\";\r\n               //$(this).dialog(\"close\");\r\n        }\r\n      }\r\n    });  \r\n}\r\n\r\nif ($(\'html\').attr(\'lang\') == \"de\"){\r\n\r\n$(\".ui-dialog-title\")[0].innerHTML = \"Aufsatzaufgabe starten\";\r\n$(\"#start_dialog\").html(\"Die Aufgabe hat ein Zeitlimit von 45 Minuten. Sie erhalten nur <b>1 Versuch </b>. Möchten Sie die Aufgabe wirklich starten?\");\r\n\r\n $(\"#start_dialog\").dialog({\r\n      buttons : {\r\n        \"Ja – weiter\" : function() {\r\n          // window.location.href = targetUrl; \r\n           window.location.href = targetUrl; \r\n        },\r\n        \"Nein – gehe zur Startseite\" : function() {\r\n               window.location.href =root_url + \"/lms/my/\";\r\n               //$(this).dialog(\"close\");\r\n        }\r\n      }\r\n    }); \r\n}\r\n\r\nfunction launch_Task(){\r\nlocalStorage.setItem(\"usertext\", document.getElementsByClassName(\"usertext\")[0].innerHTML.replace(/ /g,\"_\"));\r\nlocalStorage.setItem(\"user_c\", localStorage.getItem(\"uid\") + \"_\" + localStorage.getItem(\"usertext\")) ;\r\n  localStorage.setItem(\"grp\",(localStorage.getItem(\"usertext\").split(\'_\'))[1]);\r\n progress_dialog.dialog( \"open\" );\r\n\r\n  // start_dialog.dialog(\"open\");\r\n}\r\n\r\nfunction continue_Task(){\r\n //progress_dialog.dialog( \"open\" );\r\n  start_dialog.dialog(\"open\");\r\nif(document.getElementsByClassName(\"ui-widget-overlay\")){\r\n   document.getElementsByClassName(\"ui-widget-overlay\")[0].style.opacity = \"1\";\r\n}\r\n}\r\n\r\n// test account function\r\n  $(\"#home\").dialog({\r\n      autoOpen: false,\r\n      modal: true\r\n    });\r\n  \r\n $(\"#home\").dialog({\r\n      buttons : {\r\n        \"Continue session\": function() {\r\n          // do nothing\r\n         //$(this).dialog(\"close\");\r\n          home_dialog.dialog(\"close\");\r\n          continue_Task();\r\n        },\r\n        \"♳ Control\" : function() {\r\n               localStorage.clear();\r\n               sessionStorage.clear();\r\n               localStorage.setItem(\"uid\", USER_id);\r\n               localStorage.setItem(\"userclass\", \"Control\");\r\n               gDBQuery(\'DELETE_USER_PREVIOUS_SESSION\', localStorage.getItem(\'uid\'));\r\n               home_dialog.dialog(\"close\");\r\n               launch_Task();\r\n        },  \r\n          \"♴ Generalised \" : function() {\r\n               localStorage.clear();\r\n               sessionStorage.clear();\r\n               localStorage.setItem(\"uid\", USER_id);\r\n               localStorage.setItem(\"userclass\", \"Generalised\");\r\n               gDBQuery(\'DELETE_USER_PREVIOUS_SESSION\', localStorage.getItem(\'uid\'));\r\n               home_dialog.dialog(\"close\");\r\n               launch_Task();\r\n        },          \r\n         \"♵ Personalised\" : function() {\r\n               localStorage.clear();\r\n               sessionStorage.clear();\r\n               localStorage.setItem(\"uid\", USER_id);\r\n               localStorage.setItem(\"userclass\", \"Personalised\");\r\n               gDBQuery(\'DELETE_USER_PREVIOUS_SESSION\', localStorage.getItem(\'uid\'));\r\n                home_dialog.dialog(\"close\");\r\n                launch_Task();\r\n        }\r\n\r\n      }\r\n    });\r\n\r\n// progress bar\r\n\r\n      var progressTimer,\r\n      progressbar = $( \"#progressbar\" ),\r\n      progressLabel = $( \".progress-label\" ),\r\n      dialogButtons = [{\r\n        text: \"Please wait ... \",\r\n        disabled: true,\r\n        click: \"\"\r\n      }],\r\n      dialog = $( \"#dialog\" ).dialog({\r\n        autoOpen: false,\r\n        closeOnEscape: false,\r\n        resizable: false,\r\n        modal: true,\r\n        buttons: dialogButtons,\r\n        open: function() {\r\n          progressTimer = setTimeout( progress, 2000 );\r\n        },\r\n        beforeClose: function() {\r\n           downloadButton.button( \"option\", {\r\n            disabled: false,\r\n            label: \"Start Download\"\r\n          });\r\n        }\r\n      }),\r\n      downloadButton = $( \"#downloadButton\" )\r\n        .button()\r\n        .on( \"click\", function() {\r\n          $( this ).button( \"option\", {\r\n            disabled: true,\r\n            label: \"Downloading...\"\r\n          });\r\n          dialog.dialog( \"open\" );\r\n        });\r\n \r\n    progressbar.progressbar({\r\n      value: false,\r\n      change: function() {\r\n        progressLabel.text( \"Current Progress: \" + progressbar.progressbar( \"value\" ) + \"%\" );\r\n      },\r\n      complete: function() {\r\n        progressLabel.text( \"Complete!\" );\r\n        dialog.dialog( \"option\", \"buttons\", [{\r\n          text: \"Start new attempt\",\r\n          click: closeDownload\r\n        }]);\r\n        $(\".ui-dialog button\").last().trigger( \"focus\" );\r\n      }\r\n    });\r\n \r\n    function progress() {\r\n      var val = progressbar.progressbar( \"value\" ) || 0;\r\n \r\n      progressbar.progressbar( \"value\", val + Math.floor( Math.random() * 3 ) );\r\n \r\n      if ( val <= 99 ) {\r\n        progressTimer = setTimeout( progress, 50 );\r\n      }\r\n    }\r\n \r\n    function closeDownload() {\r\n      clearTimeout( progressTimer );\r\n      dialog\r\n        .dialog( \"option\", \"buttons\", dialogButtons )\r\n        .dialog( \"close\" );\r\n      progressbar.progressbar( \"value\", false );\r\n      start_dialog.dialog(\"open\");\r\n      /*progressLabel\r\n        .text( \"Starting download...\" );\r\n      downloadButton.trigger( \"focus\" );*/\r\n    }\r\n\r\nif (window.location.href == (root_url + \"/lms/course/view.php?id=\" + task_course_id.substr(-1)) ){\r\n\r\n   if (localStorage.getItem(\"grp\") == \"AD\") {\r\n         if (localStorage.getItem(\"userclass\") === null) {\r\n                  localStorage.setItem(\"userclass\", \"Control\");\r\n         }\r\n          progress_dialog = $(\"#dialog\").dialog();\r\n          start_dialog = $(\"#start_dialog\").dialog();\r\n         $(\"#home\").html(\"You are  currently using a test account and subscribed to the <b>\"  + localStorage.getItem(\"userclass\")  + \"</b> group. How would you like to proceed?\");\r\n          home_dialog = $(\"#home\").dialog();\r\n         \r\n          $(\"#home\").dialog(\"open\");\r\n    }else{\r\n        start_dialog = $(\"#start_dialog\").dialog();\r\n        continue_Task();\r\n if(document.getElementsByClassName(\"ui-widget-overlay\")){\r\n   document.getElementsByClassName(\"ui-widget-overlay\")[0].style.opacity = \"0.95\";\r\n}\r\n    }\r\n}\r\n\r\n\r\n  if (localStorage.getItem(\"grp\") == \"CN\"){\r\n     localStorage.setItem(\"userclass\" , \"Control\");\r\n  }else if (localStorage.getItem(\"grp\") == \"GE\"){\r\n     localStorage.setItem(\"userclass\" , \"Generalised\");\r\n  }else if (localStorage.getItem(\"grp\") == \"PL\"){\r\n     localStorage.setItem(\"userclass\" , \"Personalised\");\r\n  } \r\n\r\nif (localStorage.getItem(\"grp\") != null){\r\n if ( (localStorage.getItem(\"grp\") == \"CN\"))\r\n{\r\n  if (document.getElementById(\"block-region-side-pre\")) {\r\nif ((window.location.href).includes(root_url + \"/lms/mod/book/view.php?id=20\")){\r\ndocument.getElementById(\"block-region-side-pre\").style.display = \"none\";\r\ndocument.getElementById(\"region-main\").style.width = \"100%\";\r\n}\r\n}\r\n}\r\n}\r\n\r\nif(window.location.href == root_url + \"/lms/course/view.php?id=4\"){\r\n\r\nif ( localStorage.getItem(\"userclass\")!= null){\r\nif ( localStorage.getItem(\"grp\") == \"CN\"){\r\n document.getElementById(\"module-49\").style.display = \"none\";\r\n}\r\n}\r\n}\r\n\r\nsetInterval(function() {\r\nif ( (typeof course !== \'undefined\') && (course == task_course_id )){\r\n        if (localStorage.getItem(\"ESSAY_START\") != null){\r\n             var now = new Date();\r\n             var lapsed_time =  Math.floor((now.getTime() - parseInt(localStorage.getItem(\"ESSAY_START\")))/1000);\r\n             var remaining_time = ((TASK_DURATION*60) - lapsed_time) > 0 ? ((TASK_DURATION*60) - lapsed_time) : 0;\r\n             if (remaining_time <= 0 ){\r\n                       gDBQuery(\'CHECK_USER_TIMEUP\', localStorage.getItem(\'uid\'));\r\n                       \r\n                 }\r\n            \r\n                //check for scaffolds  launch  \r\n\r\n                if (localStorage.getItem(\"userclass\") != \"Control\"){\r\n                    if ((document.getElementById(\"myToDo\") == null) || (document.getElementById(\"scaffoldlogo\") == null) || ($(\"#sortable\") == null) ){\r\n                       logData(\"SCAFFOLD_LOG\", \"Attaching_Scaffold_Using_TIMER\", localStorage.getItem(\"url\"));\r\n                        attach_Scaffold();\r\n                    }\r\n\r\n                    var curr_time = Math.floor(lapsed_time/60);\r\n                    console.log(\"Lapsed Time so far: \" + curr_time + \" | \" + curr_time + \":\"  + (lapsed_time - curr_time * 60) );\r\n                    Scaffold_Meta(curr_time);\r\n                }  \r\n                //  end scaffold code\r\n            }\r\n\r\n}\r\n        }, 10000);\r\n</script>' WHERE name = 'additionalhtmlfooter'";

              if ($conn->query($sql) === TRUE) {
                 echo "<br/> &#9989;&#160;&#160; -----> New 'additionalhtmlfooter' data in config table";
               } else {
                 echo "<br/> &#10060;&#160;&#160; -----> Error: " . $sql . "<br>" . $conn->error;
               }

            
            // generico settings

            $sql = "UPDATE moodle.mdl_config_plugins SET value = '<script>\r\nvar script = document.createElement(\'script\');\r\nscript.type = \'text/javascript\';\r\nscript.src = \'https://hs.myannotator.com/embed.js\';\r\ndocument.head.appendChild(script);\r\n</script>' WHERE name = 'template_2' AND plugin = 'filter_generico'"; 

            $qry = array($sql);

            $sql = "UPDATE moodle.mdl_config_plugins SET value = '<script>\r\ndocument.getElementsByClassName(\"list-group\")[0].getElementsByTagName(\"li\")[0].style.display = \"none\";\r\ndocument.getElementsByClassName(\"list-group\")[0].getElementsByTagName(\"li\")[1].style.display = \"none\";\r\ndocument.getElementsByClassName(\"list-group\")[0].getElementsByTagName(\"li\")[2].style.display = \"none\";\r\ndocument.getElementsByClassName(\"list-group\")[0].getElementsByTagName(\"li\")[3].style.display = \"none\";\r\ndocument.getElementsByClassName(\"list-group\")[0].getElementsByTagName(\"li\")[4].style.display = \"none\";\r\n\r\n</script>' WHERE name = 'template_3' AND plugin = 'filter_generico'"; 

            array_push($qry,$sql);

            $sql = "UPDATE moodle.mdl_config_plugins SET value = '<!-- HTTPS version -->\r\n\r\n<span id=\'userid\' style=\"display:none\">@@USER:id@@</span>\r\n<span id=\'userfirstname\' style=\"display:none\">@@USER:firstname@@</span>\r\n<span id=\'userlastname\' style=\"display:none\">@@USER:lastname@@</span>\r\n<span id=\'username\' style=\"display:none\">@@USER:username@@</span>\r\n<span id=\'userlang\' style=\"display:none\">@@USER:lang@@</span> \r\n<!--<span id=\'userlang\' style=\"display:none\">NL</span> -->\r\n\r\n<span id=\'essay\' style=\"display:none\">enabled</span>\r\n<span id=\'timer\' style=\"display:none\">enabled</span>\r\n<span id=\'planner\' style=\"display:none\">enabled</span>\r\n<span id=\'scaffolds\' style=\"display:none\">enabled</span>\r\n\r\n\r\n<div id=\"end_dialog\" title=\"Time\'s Up!\" style = \"width:450px;\">\r\n</div>\r\n\r\n\r\n<script>\r\nvar _s = document.createElement(\"script\"); \r\n_s.src = root_url + \"/flora/Timer/js/flipclock.js\"; \r\n_s.onload = function(e){\r\nvar script = document.createElement(\'script\');\r\nscript.type = \'text/javascript\';\r\nscript.src = \'/flora/FLoRAconfig.js\';\r\ndocument.body.appendChild(script); \r\n};  \r\n\r\ndocument.head.appendChild(_s); \r\n</script>' WHERE name = 'template_4' AND plugin = 'filter_generico'"; 

            array_push($qry,$sql);

            $sql = "UPDATE moodle.mdl_config_plugins SET value = '<!-- HTTPS version -->\r\n<span id=\'userid\' style=\"display:none\">@@USER:id@@</span>\r\n<span id=\'userfirstname\' style=\"display:none\">@@USER:firstname@@</span>\r\n<span id=\'userlastname\' style=\"display:none\">@@USER:lastname@@</span>\r\n<span id=\'username\' style=\"display:none\">@@USER:username@@</span>\r\n<span id=\'userlang\' style=\"display:none\">@@USER:lang@@</span>\r\n\r\n<span id=\'essay\' style=\"display:none\">enabled</span>\r\n<span id=\'timer\' style=\"display:none\">disabled</span>\r\n<span id=\'planner\' style=\"display:none\">disabled</span>\r\n\r\n<script>\r\nvar script = document.createElement(\'script\');\r\nscript.type = \'text/javascript\';\r\nscript.src = \'/flora/FLoRAconfig.js\';\r\ndocument.body.appendChild(script);\r\n</script>' WHERE name = 'template_5' AND plugin = 'filter_generico'";

            array_push($qry,$sql);

            $sql = "UPDATE moodle.mdl_config_plugins SET value = '<script>\r\ndocument.getElementsByClassName(\"list-group\")[0].getElementsByTagName(\"li\")[0].style.display = \"none\";\r\ndocument.getElementsByClassName(\"list-group\")[0].getElementsByTagName(\"li\")[1].style.display = \"none\";\r\n</script>' WHERE name = 'template_6' AND plugin = 'filter_generico'"; 

            array_push($qry,$sql);

            $sql = "UPDATE moodle.mdl_config_plugins SET value = 'hypothesis' WHERE name = 'templatekey_2' AND plugin = 'filter_generico'";
            array_push($qry,$sql); 
            $sql = "UPDATE moodle.mdl_config_plugins SET value = 'fix_navigation' WHERE name = 'templatekey_3' AND plugin = 'filter_generico'";
            array_push($qry,$sql); 
            $sql = "UPDATE moodle.mdl_config_plugins SET value = 'floramenu' WHERE name = 'templatekey_4' AND plugin = 'filter_generico'"; 
            array_push($qry,$sql);
            $sql = "UPDATE moodle.mdl_config_plugins SET value = 'fix_essaysubmitform' WHERE name = 'templatekey_5' AND plugin = 'filter_generico'"; 
            array_push($qry,$sql);
            $sql = "UPDATE moodle.mdl_config_plugins SET value = 'fix_training_navigation' WHERE name = 'templatekey_6' AND plugin = 'filter_generico'";
            array_push($qry,$sql);

            $sql = "UPDATE moodle.mdl_config_plugins SET value = 'hypothesis' WHERE name = 'templatename_2' AND plugin = 'filter_generico'"; 
            array_push($qry,$sql);
            $sql = "UPDATE moodle.mdl_config_plugins SET value = 'fix_navigation' WHERE name = 'templatename_3' AND plugin = 'filter_generico'"; 
            array_push($qry,$sql);
            $sql = "UPDATE moodle.mdl_config_plugins SET value = 'floramenu' WHERE name = 'templatename_4' AND plugin = 'filter_generico'"; 
            array_push($qry,$sql);
            $sql = "UPDATE moodle.mdl_config_plugins SET value = 'fix_essaysubmitform' WHERE name = 'templatename_5' AND plugin = 'filter_generico'"; 
            array_push($qry,$sql);
            $sql = "UPDATE moodle.mdl_config_plugins SET value = 'fix_training_navigation' WHERE name = 'templatename_6' AND plugin = 'filter_generico'"; 
            array_push($qry,$sql);

            $sql = "UPDATE moodle.mdl_config_plugins SET value = 'function getSelectionText() {\r\n    var text = \"\";\r\n    if (window.getSelection) {\r\n        text = window.getSelection().toString();\r\n    } else if (document.selection && document.selection.type != \"Control\") {\r\n        text = document.selection.createRange().text;\r\n    }\r\n    return text;\r\n}\r\n\r\nwindow.addEventListener(\'click\', function() {\r\nif(document.activeElement === document.querySelector(\"hypothesis-adder\")) {\r\n  logData(\"Annotation\", localStorage.getItem(\"uid\"),\"TextSelected\",getSelectionText());\r\n  }else if (document.activeElement.classList.contains(\'list-group-item\')){\r\n  logData(\"TABLE OF CONTENTS\", localStorage.getItem(\"uid\"),\"CLICK\",document.activeElement.innerText.trim());\r\n  }else if (document.activeElement.getAttribute(\"id\") == \"nav-drawer\"){\r\n    logData(\"TABLE OF CONTENTS\", localStorage.getItem(\"uid\"),\"CLICK\",\"\");\r\n}\r\n\r\n}, true);' WHERE name = 'templatescript_2' AND plugin = 'filter_generico'"; 
            array_push($qry,$sql);
            $sql = "UPDATE moodle.mdl_config_plugins SET value = 'if (document.querySelectorAll(\'[aria-controls=\"nav-drawer\"]\')[0].getAttribute(\'aria-expanded\') == \"false\"){\r\ndocument.querySelectorAll(\'[aria-controls=\"nav-drawer\"]\')[0].click();\r\n}' WHERE name = 'templatescript_3' AND plugin = 'filter_generico'"; 
            array_push($qry,$sql);
            $sql = "UPDATE moodle.mdl_config_plugins SET value = '// Check and Display TimeUp Notification   \r\nif (course == task_course_id){\r\n    document.getElementById(\"end_dialog\").innerHTML = \" The allocated time (45 minutes) for this session is up! The task is now closed. Please contact your <b> Instructor </b> if you have any queries about this task. <br /><br /> All your activity data has successfully been saved ...\";\r\n    $(\"#end_dialog\").dialog({\r\n      autoOpen: false,\r\n      modal: true\r\n    });\r\n    var targetUrl = root_url + \"/lms/mod/feedback/complete.php?id=18\";\r\n\r\n   $(\"#end_dialog\").dialog({\r\n      buttons : {\r\n        /*\"Save the Essay and Exit\" : function() {\r\n          window.location.href = targetUrl;\r\n        },*/\r\n        \"Go Back to Home Page\" : function() {\r\n               window.location.href = root_url + \"/lms/my/\";\r\n        }\r\n      }\r\n    });\r\n\r\nif ($(\'html\').attr(\'lang\') == \"nl\"){\r\n     $(\".ui-dialog-title\")[3].innerHTML = \"De tijd is op!\";\r\n     $(\"#end_dialog\").html(\"Wacht op verdere instructies van uw experimentator.\");\r\n   \r\n$(\"#end_dialog\").dialog({\r\n      buttons : {\r\n        \"Nee – ga naar de startpagina\" : function() {\r\n               window.location.href = root_url + \"/lms/my/\";\r\n        }\r\n      }\r\n    }); \r\n}\r\n\r\nif ($(\'html\').attr(\'lang\') == \"de\"){\r\n\r\n$(\".ui-dialog-title\")[3].innerHTML = \"Die Zeit ist um!\";\r\n$(\"#end_dialog\").html(\"Bitte warten Sie auf weitere Anweisungen Ihres Experimentators.\");\r\n\r\n $(\"#end_dialog\").dialog({\r\n      buttons : {\r\n      }\r\n    }); \r\n}\r\n\r\nif (localStorage.getItem(\"ESSAY_START\") != null){\r\n             var now = new Date();\r\n             var lapsed_time =  Math.floor((now.getTime() - parseInt(localStorage.getItem(\"ESSAY_START\")))/1000);\r\n             var remaining_time = ((TASK_DURATION*60) - lapsed_time) > 0 ? ((TASK_DURATION*60) - lapsed_time) : 0;\r\n             if (remaining_time <= 0 ){\r\n                        $(\"#end_dialog\").dialog(\"open\");\r\n                        $(\'.ui-widget-overlay\').css(\"z-index\", \"2147483640\");\r\n                        $(\"[aria-describedby=end_dialog]\")[0].style.width = \"450px\";\r\n                       gDBQuery(\'CHECK_USER_TIMEUP\', localStorage.getItem(\'uid\'));\r\n                 }\r\n}\r\n\r\n};\r\n\r\nsetInterval(function() {\r\nif (course == task_course_id){\r\n        if (localStorage.getItem(\"ESSAY_START\") != null){\r\n             var now = new Date();\r\n             var lapsed_time =  Math.floor((now.getTime() - parseInt(localStorage.getItem(\"ESSAY_START\")))/1000);\r\n             var remaining_time = ((TASK_DURATION*60) - lapsed_time) > 0 ? ((TASK_DURATION*60) - lapsed_time) : 0;\r\n             if (remaining_time <= 0 ){\r\n                       gDBQuery(\'CHECK_USER_TIMEUP\', localStorage.getItem(\'uid\'));\r\n                        $(\"#end_dialog\").dialog(\"open\");\r\n                        $(\'.ui-widget-overlay\').css(\"z-index\", \"2147483640\");\r\n                        $(\"[aria-describedby=end_dialog]\")[0].style.width = \"450px\"\r\n                 }\r\n             \r\n                //check for scaffolds  launch  \r\n\r\n                if (localStorage.getItem(\"userclass\") != \"Control\"){\r\n                    if ((document.getElementById(\"myToDo\") == null) || (document.getElementById(\"scaffoldlogo\") == null) || ($(\"#sortable\") == null) ){\r\n                        attach_Scaffold();\r\n                    }\r\n\r\n                   \r\n                }  \r\n                //  end scaffold code\r\n\r\n            }\r\n    }\r\n        }, 4000);\r\n\r\ndocument.addEventListener(\"mousemove\", function(event) {\r\n  myFunction(event);\r\n});\r\n\r\nfunction myFunction(e) {\r\n  var x = e.clientX;\r\n  var y = e.clientY;\r\n  var coor = \"Coordinates: (\" + x + \",\" + y + \")\";\r\n  \r\n  if ( typeof attach_Scaffold === \"function\")\r\n {\r\n  if ((document.getElementById(\"myToDo\") == null) || (document.getElementById(\"scaffoldlogo\") == null) || ($(\"#sortable\") == null) ){\r\n    console.log(coor);\r\n    attach_Scaffold();\r\n  }\r\n }\r\n}' WHERE name = 'templatescript_4' AND plugin = 'filter_generico'";
            array_push($qry,$sql);



            $sql = "UPDATE moodle.mdl_config_plugins SET value = '' WHERE name IN('insertcustomnodesusers','insertcustomnodesadmins', 'insertcustombottomnodesusers', 'insertcustombottomnodesadmins') AND plugin = 'local_boostnavigation'";
            array_push($qry,$sql);

            $sql = "UPDATE moodle.mdl_config_plugins SET value = '0' WHERE name IN('collapsemycoursesnode', 'collapsemycoursesnodeicon', 'collapsemycoursesnodedefault',  'collapsemycoursesnodesession', 'collapsecustomnodesusers', 'collapsecustomnodesusersicon', 'collapsecustomnodesusersdefault',  'collapsecustomnodesuserssession', 'collapsecustomnodesusersaccordion', 'collapsecustomnodesadmins', 'collapsecustomnodesadminsicon', 'collapsecustomnodesadminsdefault', 'collapsecustomnodesadminssession', 'collapsecustomnodesadminsaccordion', 'insertcoursesectionscoursenode',  'insertactivitiescoursenode', 'insertresourcescoursenode',  'collapsecoursesectionscoursenode', 'collapsecoursesectionscoursenodeicon',  'collapsecoursesectionscoursenodedefault',  'collapsecoursesectionscoursenodesession', 'collapseactivitiescoursenode',  'collapseactivitiescoursenodeicon',  'collapseactivitiescoursenodesession', 'collapsecustomcoursenodesuserssession', 'collapsecustomcoursenodesadminssession', 'collapsecustombottomnodesusers', 'collapsecustombottomnodesusersicon','collapsecustombottomnodesusersdefault', 'collapsecustombottomnodesuserssession',  'collapsecustombottomnodesusersaccordion', 'collapsecustombottomnodesadmins', 'collapsecustombottomnodesadminsicon',  'collapsecustombottomnodesadminsdefault', 'collapsecustombottomnodesadminssession','collapsecustombottomnodesadminsaccordion') AND plugin = 'local_boostnavigation'";
            array_push($qry,$sql);

            $sql = "UPDATE moodle.mdl_config_plugins SET value = '1' WHERE name IN( 'removemyhomenode',  'removehomenode', 'removecalendarnode', 'removeprivatefilesnode', 'removemycoursesnode', 'removebadgescoursenode', 'removecompetenciescoursenode', 'removegradescoursenode', 'removeparticipantscoursenode', 'collapseactivitiescoursenodedefault', 'collapsecustomcoursenodesusers', 'collapsecustomcoursenodesusersdefault', 'collapsecustomcoursenodesusersaccordion', 'collapsecustomcoursenodesadmins', 'collapsecustomcoursenodesadminsdefault', 'collapsecustomcoursenodesadminsaccordion') AND plugin = 'local_boostnavigation'";
            array_push($qry,$sql);

            $sql = "UPDATE moodle.mdl_config_plugins SET value = '2' WHERE name IN('collapsecustomcoursenodesusersicon', 'collapsecustomcoursenodesadminsicon') AND plugin = 'local_boostnavigation'"; 
            array_push($qry,$sql);

            $sql = "UPDATE moodle.mdl_config_plugins SET value = '{mlang en} Instructions{mlang}{mlang nl} Instructie {mlang} {mlang de} Anweisungen {mlang}  |\"\"\r\n    -{mlang en} General Instruction{mlang}{mlang nl} Algemene instructie{mlang} {mlang de} Allgemeine Anweisungen {mlang} |\"\"\r\n    -{mlang en} Rubric{mlang}{mlang nl} Rubric{mlang} {mlang de} Rubrik {mlang} |\"\"\r\n1:{mlang en} Artificial Intelligence in Education{mlang}{mlang de} Künstliche Intelligenz in der Bildung{mlang}{mlang nl} Artificial intelligence in het onderwijs{mlang} |\"\"\r\n-1.1{mlang en} Definition of AI{mlang}{mlang nl} Wat is AI?{mlang} {mlang de} Definition der künstlichen Intelligenz{mlang} |\"\"\r\n-1.2{mlang en} History of AI{mlang}{mlang nl} Geschiedenis van AI{mlang} {mlang de} Die Geschichte der künstlichen Intelligenz{mlang} |\"\"\r\n-1.3{mlang en} How does AI work?{mlang}{mlang nl} Hoe werkt AI?{mlang} {mlang de} Wie funktioniert künstliche Intelligenz? {mlang}  |\"\"\r\n-1.4{mlang en} Ethics of AI{mlang}{mlang nl} Ethiek van AI{mlang} {mlang de} Ethik und Risiken bei der Entwicklung künstlicher Intelligenz {mlang} |\"\"\r\n-1.5{mlang en} Supervised ML{mlang}{mlang nl} Supervised machine learning{mlang} {mlang de} Überwachtes maschinelles Lernen {mlang} |\"\"\r\n-1.6{mlang en} Unsupervised ML{mlang}{mlang nl} Unsupervised machine learning{mlang} {mlang de} Unüberwachtes maschinelles Lernen {mlang} |\"\"\r\n-1.7{mlang en} Reinforcement learning{mlang}{mlang nl} Reinforcement learning{mlang} {mlang de} Verstärkendes Lernen (Reinforcement Learning){mlang} |\"\"\r\n-1.8{mlang en} Deep Learning{mlang}{mlang nl} Deep Learning{mlang} {mlang de} Deep Learning {mlang} |\"\"\r\n2:{mlang en} Differentiation in Education{mlang}{mlang de} Differenzierung in der Grundschule{mlang}{mlang nl}Differentiatie in het onderwijs{mlang} |\"\"\r\n-2.1{mlang en} What is Differentiation?{mlang}{mlang nl} Wat is differentiatie?{mlang} {mlang de} Was bedeutet Differenzierung? {mlang} |\"\"\r\n-2.2{mlang en} Using differentiation{mlang}{mlang nl} Differentiatie toepassen in de klas{mlang}  {mlang de} Differenzierung zur Anpassung der Bildung nutzen {mlang} |\"\"\r\n-2.3{mlang en} Standards for teaching{mlang}{mlang nl} Standaarden voor onderwijzen{mlang} {mlang de} Standards für die Lehre{mlang}|\"\"\r\n3:{mlang en} Scaffolding in Education{mlang}{mlang de} Scaffolding in der Grundschule {mlang}{mlang nl} Scaffolding in het onderwijs{mlang} |\"\"\r\n-3.1{mlang en} The development of scaffolding{mlang}{mlang nl} De wortels van scaffolding{mlang} {mlang de} Entwicklung von Scaffolding {mlang} |\"\"\r\n-3.2{mlang en} What is cognitive apprenticeship?{mlang}{mlang nl} Wat is cognitive apprenticeship?{mlang} {mlang de}Was ist die kognitive Ausbildung? {mlang} |\"\"\r\n-3.3{mlang en} What is scaffolding{mlang}{mlang nl} Wat is scaffolding?{mlang} {mlang de} Was ist Scaffolding? {mlang} |\"\"\r\n-3.4{mlang en} Applications of scaffolding{mlang}{mlang nl} Toepassen van scaffolding{mlang} {mlang de} Anwendungen von Scaffolding {mlang} |\"\"\r\n-3.5{mlang en} Applications of cognitive apprenticeship{mlang}{mlang nl} Toepassen van cognitive apprenticeship{mlang} {mlang de} Anwendungen von kognitiver Lehre {mlang} |\"\"' WHERE name = 'insertcustomcoursenodesadmins' AND plugin = 'local_boostnavigation'"; 
            array_push($qry,$sql);


            $sql = "UPDATE moodle.mdl_config_plugins SET value = '{mlang en} Instructions{mlang}{mlang nl} Instructie {mlang} {mlang de} Anweisungen {mlang}  |/mod/lesson/view.php?id=8&pageid=1\r\n    -{mlang en} General Instruction{mlang}{mlang nl} Algemene instructie{mlang} {mlang de} Allgemeine Anweisungen {mlang} |/mod/lesson/view.php?id=8&pageid=1\r\n    -{mlang en} Rubric{mlang}{mlang nl} Rubric{mlang} {mlang de} Rubrik {mlang} |/mod/lesson/view.php?id=8&pageid=2\r\n1:{mlang en} Artificial Intelligence in Education{mlang}{mlang de} Künstliche Intelligenz in der Bildung{mlang}{mlang nl} Artificial intelligence in het onderwijs{mlang} |/mod/page/view.php?id=9\r\n-1.1{mlang en} Definition of AI{mlang}{mlang nl} Wat is AI?{mlang} {mlang de} Definition der künstlichen Intelligenz{mlang} |/mod/page/view.php?id=9\r\n-1.2{mlang en} History of AI{mlang}{mlang nl} Geschiedenis van AI{mlang} {mlang de} Die Geschichte der künstlichen Intelligenz{mlang} |/mod/page/view.php?id=10\r\n-1.3{mlang en} How does AI work?{mlang}{mlang nl} Hoe werkt AI?{mlang} {mlang de} Wie funktioniert künstliche Intelligenz? {mlang}  |/mod/page/view.php?id=11\r\n-1.4{mlang en} Ethics of AI{mlang}{mlang nl} Ethiek van AI{mlang} {mlang de} Ethik und Risiken bei der Entwicklung künstlicher Intelligenz {mlang} |/mod/page/view.php?id=12\r\n-1.5{mlang en} Supervised ML{mlang}{mlang nl} Supervised machine learning{mlang} {mlang de} Überwachtes maschinelles Lernen {mlang} |/mod/page/view.php?id=13\r\n-1.6{mlang en} Unsupervised ML{mlang}{mlang nl} Unsupervised machine learning{mlang} {mlang de} Unüberwachtes maschinelles Lernen {mlang} |/mod/page/view.php?id=14\r\n-1.7{mlang en} Reinforcement learning{mlang}{mlang nl} Reinforcement learning{mlang} {mlang de} Verstärkendes Lernen (Reinforcement Learning){mlang} |/mod/page/view.php?id=15\r\n-1.8{mlang en} Deep Learning{mlang}{mlang nl} Deep Learning{mlang} {mlang de} Deep Learning {mlang} |/mod/page/view.php?id=16\r\n2:{mlang en} Differentiation in Education{mlang}{mlang de} Differenzierung{mlang}{mlang nl}Differentiatie in het onderwijs{mlang} |/mod/page/view.php?id=17\r\n-2.1{mlang en} What is Differentiation?{mlang}{mlang nl} Wat is differentiatie?{mlang} {mlang de} Was bedeutet Differenzierung? {mlang} |/mod/page/view.php?id=17\r\n-2.2{mlang en} Using differentiation{mlang}{mlang nl} Differentiatie toepassen in de klas{mlang}  {mlang de} Differenzierung zur Anpassung der Bildung nutzen {mlang} |/mod/page/view.php?id=18\r\n-2.3{mlang en} Standards for teaching{mlang}{mlang nl} Standaarden voor onderwijzen{mlang} {mlang de} Standards für die Lehre{mlang}|/mod/page/view.php?id=19\r\n3:{mlang en} Scaffolding in Education{mlang}{mlang de} Scaffolding{mlang}{mlang nl} Scaffolding in het onderwijs{mlang} |/mod/page/view.php?id=20\r\n-3.1{mlang en} The development of scaffolding{mlang}{mlang nl} De wortels van scaffolding{mlang} {mlang de} Entwicklung von Scaffolding {mlang} |/mod/page/view.php?id=20\r\n-3.2{mlang en} What is cognitive apprenticeship?{mlang}{mlang nl} Wat is cognitive apprenticeship?{mlang} {mlang de} Was ist die kognitive Ausbildung? {mlang} |/mod/page/view.php?id=21\r\n-3.3{mlang en} What is scaffolding{mlang}{mlang nl} Wat is scaffolding?{mlang} {mlang de} Was ist Scaffolding? {mlang} |/mod/page/view.php?id=22\r\n-3.4{mlang en} Applications of scaffolding{mlang}{mlang nl} Toepassen van scaffolding{mlang} {mlang de} Anwendungen von Scaffolding {mlang} |/mod/page/view.php?id=23\r\n-3.5{mlang en} Applications of cognitive apprenticeship{mlang}{mlang nl} Toepassen van cognitive apprenticeship{mlang} {mlang de} Anwendungen von kognitiver Lehre {mlang} |/mod/page/view.php?id=24'  WHERE name = 'insertcustomcoursenodesusers' AND plugin = 'local_boostnavigation'"; 
            array_push($qry,$sql);
             
            $sql = "SET GLOBAL sql_mode='STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_ENGINE_SUBSTITUTION'";
            array_push($qry,$sql);


                $index = 1;
                foreach ($qry as $val) {
                
                if ($conn->query($val) === TRUE) {
                     echo "<br/> &#9989;&#160;&#160; -----> New plugin data added in mdl_config_plugins table. (Success code#: " . $index  . ")" ;
                   } else {
                     echo "<br/> &#10060;&#160;&#160; -----> Error: " . $val . "<br>" . $conn->error;
                   }
                   $index++;
                }

                $conn->close();
                echo "</div> <!-- /container -->";


               echo "<br/><br/>";
               echo "<center><button onclick='window.location.assign(\"http://\" + window.location.host + \"/lms/\");'> Go to LMS</button></center>";

            }else {
                  echo"<div class = \"container form-signin\">";
                  $msg = 'Enter MySQL User Credentials';
                  echo "</div> <!-- /container -->";
          ?>
      
      <div class = "container">
      
         <form class = "form-signin" role = "form" 
            action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); 
            ?>" method = "post">
            <h5 class = "form-signin-heading"><?php echo $msg; ?></h5>
            <input type = "text" class = "form-control" 
               name = "db_user" placeholder = "DB username" 
               required autofocus></br>
            <input type = "text" class = "form-control"
               name = "db_pass" placeholder = "DB password" required> </br>
            <input type = "text" class = "form-control"
               name = "db_host" placeholder = "DB host" required> </br>
            <button class = "btn btn-lg btn-primary btn-block" type = "submit" 
               name = "install">Install DB</button>
         </form>
  
      </div> 

      <?php
          }
      
      ?>
      
   </body>
</html>
