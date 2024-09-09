<?php
    ini_set('display_errors', '1');
    require_once "header.php";
    require_once('configDB.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FLoRA Log Analysis</title>
    
    <!--shortcut icon - generated using https://realfavicongenerator.net -->
    <script src="./DTEditor/jquery-3.0.0.js" ></script>
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.ico">
    
  <!--   <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css" />-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="./css/styles.css" type="text/css" />  
    

    <link rel="stylesheet" href="./css/bootstrap1.css" />
    <link rel="stylesheet" href="./css/jquery.dataTables.css" />
    <link rel="stylesheet" href="./css/buttons.dataTables.css" />
    <link rel="stylesheet" href="./css/select.dataTables.css" />
    <link rel="stylesheet" href="./css/responsive.dataTables.css" />

   <script src="https://cdn.jsdelivr.net/gh/minisuperfiles/MSFmultiSelect/msfmultiselect.min.js"></script>
   <link href="https://cdn.jsdelivr.net/gh/minisuperfiles/MSFmultiSelect/msfmultiselect.min.css" rel="stylesheet"/>

    <style type="text/css">

    input{
    	    padding: 5px;
    		margin: 5px;
    }
	table.tableizer-table {
		font-size: 12px;
		border: 1px solid #CCC; 
		font-family: "Arial Narrow", Helvetica, sans-serif;
	} 

	.dataTables_length{
		padding:  5px;
	}
	.tableizer-table td {
		padding: 4px;
		margin: 3px;
		border: 1px solid #CCC;
		text-align: center;
	}

	.center {
	  margin-left: auto;
	  margin-right: auto;
	}
	.tableizer-table th {
		background-color: #104E8B; 
		color: #FFF;
		font-weight: bold;
		text-align: center;
		min-width: 80px;
	}

        #scaff_result td{
	  padding : 5px;
	  text-align: center;
	  border: 1px solid black;
	  border-collapse: collapse;
	}

	#scaff_result th{
	  padding : 5px;
	  min-width: 80px;
	  text-align: center;
	  border: 1px solid black;
	  border-collapse: collapse;
	}

</style>


</head>
<body>


<style type="text/css">
	table.tableizer-table {
		font-size: 12px;
		border: 1px solid #CCC; 
		font-family: Arial, Helvetica, sans-serif;
	} 
	.tableizer-table td {
		padding: 4px;
		margin: 3px;
		border: 1px solid #CCC;
	}
	.tableizer-table th {
		background-color: #104E8B; 
		color: #FFF;
		font-weight: bold;
	}
</style>

	<section class="container center" style="width:90%;">
		<div class="row">
			<div class="col-12">
				

		        <h3> FLoRA Project - Participant Logs </h3>
		        <br />
				<form action="index.php" method="post">
			        <input type="submit" class="button" name="raw_logs" value="View Raw Logs (un-processed)" />
			        <input type="submit" class="button" name="action_logs" value="View Processed Logs (with Action Labels)" />
			        <input type="submit" class="button" name="pattern_logs" value="View Logs (with Pattern Labels)" />
			        <input type="submit" class="button" name="essay_logs" value="View Essay Data" />
				<input type="submit" class="button" name="scaffold_logs" value="View Scaffold Info" />	
		    </form>

		        <?php
		            echo "<br />";

					if(isset($_POST['raw_logs'])){
						?>

						<h4> Viewing Raw Participant Logs</h4>


						<table>
		                 <tr>
		                   <td>
		                   	Select Participant:
		                   </td>
		                   <td>
		                    <input list="searchID" name="searchByID" id="searchByID" style="padding: 5px 15px 5px 5px;">
		                     <datalist id='searchID'>
		                         <?php 
		                        $sql = mysqli_query($DBconnect, "SELECT DISTINCT(userid) FROM user_log");
		                        while ($row = $sql->fetch_assoc()){
		                          echo "<option value='" . $row['userid']  . "'>";
		                        }
		                       ?>
		                     </datalist>
		                   </td>
		                 </tr>
		               </table>

		               <br><br>

			          <table cellpadding="0" cellspacing="0" border="0" class="dataTable table table-striped" style="font-size: 12px;font-family: Arial Narrow;" id="example">
					  <script src="./logs.js" ></script>

					<?php

					}elseif(isset($_POST['action_logs'])){

						?>

						<h4> Viewing Action Logs</h4>

						<table>
		                 <tr>
		                   <td>
		                   	Select Participant:
		                   </td>
		                   <td>
		                    <input list="searchID" name="searchByID" id="searchByID" style="padding: 5px 15px 5px 5px;">
		                     <datalist id='searchID'>
		                         <?php 
		                        $sql = mysqli_query($DBconnect, "SELECT DISTINCT(userid) FROM user_log");
		                        while ($row = $sql->fetch_assoc()){
		                          echo "<option value='" . $row['userid']  . "'>";
		                        }
		                       ?>
		                     </datalist>
		                   </td>
		                 </tr>
		               </table>

		               <br><br>

			          <table cellpadding="0" cellspacing="0" border="0" class="dataTable table table-striped" style="font-size: 12px;font-family: Arial Narrow;" id="example">
					  <script src="./actions.js" ></script>
						

						<?php

					}elseif(isset($_POST['pattern_logs'])){
						?>

						<h4> Viewing Pattern Logs</h4>

						<table>
		                 <tr>
		                   <td>
		                   	Select Participant:
		                   </td>
		                   <td>
		                    <input list="searchID" name="searchByID" id="searchByID" style="padding: 5px 15px 5px 5px;">
		                     <datalist id='searchID'>
		                        <?php 
		                        $sql = mysqli_query($DBconnect, "SELECT DISTINCT(userid), uid FROM user_log");
		                        while ($row = $sql->fetch_assoc()){
		                          echo "<option data-value='" . $row['uid']  . "' value='" . $row['userid']  . "'>";
		                        }
		                       ?>
		                     </datalist>
		                     <input type="hidden" name="answer" id="answer-hidden">
		                   </td>
		                 </tr>
		               </table>

		               <br>
					  <button onclick="runLabeller();">Re-Run Pattern Labeller ?</button>
		               <br><br><br>

			          <table cellpadding="0" cellspacing="0" border="0" class="dataTable table table-striped" style="font-size: 12px;font-family: Arial Narrow;" id="example">
					  <script src="./patterns.js" ></script>

					  <script type="text/javascript">
					  	    function runLabeller(){
					  	       if (document.querySelector("#searchID option[value='" + $('#searchByID').val() + "']" ) == null)
					  	       {
									alert("The entered participant does not exist !!! ");  

					  	       }else{
							         $.ajax({
										  type: 'POST',
										  url: "https://floralearn.org/functions.php",
										  data: {source: 'flora' , func: 'UPDATE_PATTERN_LABELS_V3' , 'para1' : document.querySelector("#searchID option[value='" + $('#searchByID').val() + "']" ).getAttribute('data-value') },
										  success: function(d){
										    alert("Pattern labelling complete for user!!!"); 
										    $('#searchByID').change();
										  }
										});          
							    }
							}
					  </script>

						<?php

					}elseif(isset($_POST['scaffold_logs'])){

					 ?>

					 <h4> Scaffold Data</h4>
					


						<table>
		                 <tr>
		                   <td>
		                   	Select Participant(s):
		                   </td>
		                   <td>

		                   	 	<select id="multiselectID" name="selectedIDs[]" multiple="multiple" >
							      <?php 
			                        $sql = mysqli_query($DBconnect, "SELECT DISTINCT(userid) FROM user_log order by userid ASC");
			                        while ($row = $sql->fetch_assoc()){
			                          echo "<option value='" . $row['userid']  . "'>" . $row['userid'] . "</option>";
			                        }
			                       ?>

							    </select>
		                   </td>
		                 </tr>
		               </table>
		               <br><br>

		               <h5>Scaffolds Summary: </h5>
		               <br>
		               
		               

		               <div style="border: 1px solid lightgray; width: 950px; padding: 20px;">
		               		<div id="scaffolds_data">
		               			Select participant(s) to view Scaffolds Data.
		               		</div>

		               </div>

		               <script type="text/javascript">
		               	var scaff_select = new MSFmultiSelect(
							  document.querySelector('#multiselectID'),
							  {
							    theme: 'theme2',
							    selectAll: true,
							    searchBox: true,
							    Width: '250px',
							    minHeight: '25px',
							    margin: '0px',
							    // readOnly: true,
							    onChange:function(checked, value, instance) {
							       if (scaff_select.getData().length > 0){
							       		//console.log(scaff_select.getData());
							       		$.ajax({
										  type: 'POST',
										  url: "fetch.php",
										  data: {action: 'getScaffoldDATA' , 'qid' : scaff_select.getData() },
										  success: function(d){
										  	$('#scaffolds_data')[0].innerHTML = "";
										  	$('#scaffolds_data')[0].innerHTML = '<a href="#" onclick="download_table_as_csv(\'scaff_result\');">Download as CSV</a>';
										     $('#scaffolds_data').append(d); 
										  }
										}); 
							       }else{

										$('#scaffolds_data')[0].innerHTML = "Select participant(s) to view Scaffolds Data.";

							       }		 
							    },
							    afterSelectAll:function(checked, values, instance) {
							    	if (scaff_select.getData().length > 0){
							        	//console.log(scaff_select.getData());
							        	$.ajax({
										  type: 'POST',
										  url: "fetch.php",
										  data: {action: 'getScaffoldDATA' , 'qid' : scaff_select.getData() },
										  success: function(d){
										  	$('#scaffolds_data')[0].innerHTML = "";
										  	$('#scaffolds_data')[0].innerHTML = '<a href="#" onclick="download_table_as_csv(\'scaff_result\');">Download as CSV</a>';
										    $('#scaffolds_data').append(d); 
										  }
										}); 
							        }else{
										$('#scaffolds_data')[0].innerHTML = "Select participant(s) to view Scaffolds Data.";
							        }
							    }
							    //appendTo: '#myselect2',
							    //readOnly:true,
							    //autoHide: false
							  }
							);

		               </script>


 					 <?php
				    }elseif(isset($_POST['essay_logs'])){
						?>

						<h4> Essay Data</h4>

						<table>
		                 <tr>
		                   <td>
		                   	Select Participant:
		                   </td>
		                   <td>
		                    <input list="searchID" name="searchByID" id="searchByID" style="padding: 5px 15px 5px 5px;">
		                     <datalist id='searchID'>
		                        <?php 
		                        $sql = mysqli_query($DBconnect, "SELECT DISTINCT(userid) FROM user_log");
		                        while ($row = $sql->fetch_assoc()){
		                          echo "<option value='" . $row['userid']  . "'>";
		                        }
		                       ?>
		                     </datalist>
		                   </td>
		                 </tr>
		               </table>

		               <br><br>
		               <h5>Final Essay: </h5>
		               

		               <div style="border: 1px solid lightgray; width: 750px; padding: 20px;">
		               		<text id="essay_data">
		               			Enter participant's information to lookup essay.
		               		</text>

		               </div>
		               <script type="text/javascript">
		               	    $('#searchByID').change(function(){
							    if ($('#searchByID').val().length < 6){
							        $('#essay_data')[0].innerText = "No text found!";                
							    }else{
							        //, 'qid' : $('#searchByID').val() 

							         $.ajax({
										  type: 'POST',
										  url: "fetch.php",
										  data: {action: 'getEssayDATA' , 'qid' : $('#searchByID').val() },
										  success: function(d){
										     $('#essay_data')[0].innerText = d; 
										  }
										});            
							    }
							 });

		               </script>

		               <?php

					}else{
						echo "<h5> Please click on an option above. </h5>";
					}

		        ?>

			
		    </div>
		</div>
	</section>
	
</body>
</html>

</script>

<script src="./DTEditor/jquery-migrate-3.3.0.js" ></script>
<script src="./DTEditor/jquery.dataTables.js" ></script>
<script src="./DTEditor/dataTables.buttons.js" ></script>
<script src="./DTEditor/dataTables.select.js" ></script>
<script src="./DTEditor/bootstrap.js" ></script>
<script src="./DTEditor/dataTables.responsive.js" ></script>
<script src="./DTEditor/dataTables.altEditor.free.js" ></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>

<script type="text/javascript">
	// Quick and simple export target #table_id into a csv
	function download_table_as_csv(table_id, separator = ',') {
	    // Select rows from table_id
	    var rows = document.querySelectorAll('table#' + table_id + ' tr');
	    // Construct csv
	    var csv = [];
	    for (var i = 0; i < rows.length; i++) {
	        var row = [], cols = rows[i].querySelectorAll('td, th');
	        for (var j = 0; j < cols.length; j++) {
	            // Clean innertext to remove multiple spaces and jumpline (break csv)
	            var data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s)/gm, ' ')
	            // Escape double-quote with double-double-quote (see https://stackoverflow.com/questions/17808511/properly-escape-a-double-quote-in-csv)
	            data = data.replace(/"/g, '""');
	            // Push escaped string
	            row.push('"' + data + '"');
	        }
	        csv.push(row.join(separator));
	    }
	    var csv_string = csv.join('\n');
	    // Download it
	    var filename = 'export_' + table_id + '_' + new Date().toLocaleDateString() + '.csv';
	    var link = document.createElement('a');
	    link.style.display = 'none';
	    link.setAttribute('target', '_blank');
	    link.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv_string));
	    link.setAttribute('download', filename);
	    document.body.appendChild(link);
	    link.click();
	    document.body.removeChild(link);
	}
</script>
