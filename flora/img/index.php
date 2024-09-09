<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>
function includeHTML() {
  var z, i, elmnt, file, xhttp;
  /*loop through a collection of all HTML elements:*/
  z = document.getElementsByTagName("*");
  for (i = 0; i < z.length; i++) {
    elmnt = z[i];
    /*search for elements with a certain atrribute:*/
    file = elmnt.getAttribute("w3-include-html");
    if (file) {
      /*make an HTTP request using the attribute value as the file name:*/
      xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
          if (this.status == 200) {elmnt.innerHTML = this.responseText;}
          if (this.status == 404) {elmnt.innerHTML = "Page not found.";}
          /*remove the attribute, and call this function once more:*/
          elmnt.removeAttribute("w3-include-html");
          includeHTML();
        }
      }      
      xhttp.open("GET", file, true);
      xhttp.send();
      /*exit the function:*/
      return;
    }
  }
};
</script>


  <?php 
  
  require_once('header.php');
  require_once('configDB.php'); 

  ?>
  <title>FLoRA Tools</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="/tools/style.css" rel="stylesheet">

    <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet"/>

    <!-- scripts -->

    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script src="https://cdn.datatables.net/r/dt/dt-1.10.9/datatables.min.js"></script>

    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>


    
</head>

<body>

<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#home">Home</a></li>
    <li><a data-toggle="tab" href="#menu1">Manage Scaffolds</a></li>
    <li><a data-toggle="tab" href="#menu2">View Userlogs</a></li>
    <li><a data-toggle="tab" href="#menu3">View User Actions</a></li>
    <li><a data-toggle="tab" href="#menu4">View User Patterns</a></li>
    <li><a data-toggle="tab" href="#menu5">View User Essay</a></li>
    <li><a data-toggle="tab" href="#menu6">Visualising Action/Patterns</a></li>
    <li><a data-toggle="tab" href="#menu7">Bulk Download</a></li>
</ul>

  <div class="tab-content">
    <div id="home" class="tab-pane fade in active container">
      <h3>Welcome</h3>
        
        <img src="./img/home-icon.png" alt="home" class="center"  />
        <p class="center">
        Through this interface, you would be able to quickly access the data from the recent FLoRA experiments.
        This is a work-in-progress site. More features would be made available in the due course.
        </p>
        <br />
        <p class="center"> Project URL: <a href="https://floraproject.org/">https://floraproject.org</a> 
        </p>

    </div>
    <div id="menu1" class="tab-pane fade">
      <div class="container">
            <div class="header"><h3>Select Scaffold Language</h3></div>
            <select id='scaffoldLang'>
              <option value='-'>-- Select Language --</option>
                       <?php 
                        $sql = mysqli_query($DBconnect, "SELECT DISTINCT(language) FROM scaffolds");
                        while ($row = $sql->fetch_assoc()){
                        echo "<option value='" . $row['language']  . "'>" . strtoupper($row['language']) . "</option>";
                        }
                       ?>
            </select>

            <div id="scaffolds" style = "background: #fff6f6; margin: 30px 0px 30px 0px; padding: 20px;"></div>

            <script type="text/javascript">
              $('#scaffoldLang').on('change', function() {
                  if ( $(this).val() != '-' ){
                     // Do the AJAX request for scaffold here, like:
                     $.get("getScaffoldsByLang.php?lang="+$(this).val().toUpperCase(), function( data ) {
                          //Set the retrieved scaffolds:
                          $('#scaffolds')[0].innerHTML = data;
                      });
                  }else{
                    $('#scaffolds')[0].innerHTML = "";      
                  }
              });

            </script>
      </div>
    </div>
    <div id="menu2" class="tab-pane fade">
        <div class="container">
        <h3>Select User</h3>


        <!-- Custom Filter -->
              <table>
                 <tr>
                   <td>
                    <input list="searchID" name="searchByID" id="searchByID" style="padding: 5px 15px 5px 5px;">
                     <datalist id='searchID'>
                        <?php 
                        $sql = mysqli_query($DBconnect, "SELECT username FROM study_data");
                        while ($row = $sql->fetch_assoc()){
                          echo "<option value='" . $row['username']  . "'>";
                        }
                       ?>
                     </datalist>
                   </td>
                 </tr>
               </table>

               <br /><br />

                <table id="userlog-grid" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>UserID</th>
                        <th>Block</th>
                        <th>UID</th>
                        <th>Time Lapsed (ms)</th>
                        <th>Clock</th>
                        <th>URL</th>
                        <th>Log Event</th>
                        <th>Log Sub-Event</th>
                        <th>Value</th>
                        <th>ACTION Label</th>
                        <th>PROCESS Label</th>
                        <th>PROCESS Start_Time</th>
                        <th>PROCESS End_Time</th>
                        <th>PROCESS Duration (sec)</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                       <th>ID</th>
                        <th>UserID</th>
                        <th>Block</th>
                        <th>UID</th>
                        <th>Time Lapsed (ms)</th>
                        <th>Clock</th>
                        <th>URL</th>
                        <th>Log Event</th>
                        <th>Log Sub-Event</th>
                        <th>Value</th>
                        <th>ACTION Label</th>
                        <th>PROCESS Label</th>
                        <th>PROCESS Start_Time</th>
                        <th>PROCESS End_Time</th>
                        <th>PROCESS Duration (sec)</th>
                    </tr>
                </tfoot>
                </table>
        

        <script>
          var dataTable;
          $(document).ready(function () {

                $('#userlog-grid tfoot th').each( function () {
                var title = $(this).text();
                $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
                } );
                   
                    dataTable = $('#userlog-grid').DataTable({
                        dom: 'lBfrtip',
                        "autoWidth": true,
                        "destroy": true,
                        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                        "lengthMenu": [ [5, 10, 25, 50, 100, 500000], [5, 10, 25, 50, 100, 'All'] ],
                        "processing": true,
                        "serverSide": true,
                        "ajax": {
                            url: "/tools/response.php", // json datasource
                            data: {action: 'getUSERLOG', qid : $('#searchByID').val() },
                            type: 'post',  // method  , by default get
                        },
                         initComplete: function (settings, json) {


                         
                          console.log(json);
                          // Apply the search
                          this.api().columns().every( function () {
                              var that = this;
               
                              $( 'input', this.footer() ).on( 'keyup change clear', function () {
                                  if ( that.search() !== this.value ) {
                                      that
                                          .search( this.value )
                                          .draw();
                                  }
                              } );
                          } );
                          

                            // var i = 0;
                            // this.api().columns().every( function () {
                            //     var column = this;
                            //     var select = $('<select id = "filt_' + (i++) + '"><option value="">All</option></select>')
                            //         .appendTo( $(column.footer()).empty() )
                            //         .on( 'change', function () {
                            //             var val = $.fn.dataTable.util.escapeRegex(
                            //                 $(this).val()
                            //             );
                 
                            //             column
                            //                 .search( val )
                            //                 .draw();

                            //             if ($(this)[1].id == "filt_1"){
                            //                 // if (val == ""){
                            //                 //   $('#searchByID').prop("selectedIndex", 0);
                            //                 // }else{
                            //                 //   $('#searchByID  option[value="' + val + '"]').prop('selected', true);
                            //                 // }
                            //             }
                            //         } );
                 
                            //     column.data().unique().sort().each( function ( d, j ) {
                            //         select.append( '<option value="'+d+'">'+d+'</option>' )
                            //     } );
                            // } );
                        }
                        
                    });

                   $('#searchByID').change(function(){
                        if ($('#searchByID').val().length < 10){
                          dataTable
                            .column(1)
                            .search("@#@#")
                            .draw();
                         // $('#filt_1').prop("selectedIndex", 0);
                          //$('#filt_0  option[value=""]').prop('selected', true);
                        }else{
                          dataTable
                            .column(1)
                            .search($('#searchByID').val())
                            .draw();
                         // $('#filt_1  option[value="' + $('#searchByID').val() + '"]').prop('selected', true);
                        }
                    });

             });
          </script>

          <div w3-include-html=""></div>
        </div>
        

    </div>
    <div id="menu3" class="tab-pane fade">
      <div class="container">
        <h3>Select User</h3>
          <div w3-include-html=""></div>
        </div>
    </div>
    <div id="menu4" class="tab-pane fade">
      <h3>Select User</h3>
        <div w3-include-html=""></div>
    </div>
    <div id="menu5" class="tab-pane fade">

      <div class="container">
            <div class="header"><h3>Enter User ID</h3></div>
            <input type="text" id="essayUID" name="essayUID">
         

            <div id="essayText" style = "background: #fff6f6; margin: 30px 0px 30px 0px; padding: 20px;"></div>

            <script type="text/javascript">
              $('#essayUID').on('keyup', function() {
                  if ( $(this).val() != '' ){
                     // Do the AJAX request for scaffold here, like:
                     $.get("getEssayByUID.php?uid="+$(this).val().toUpperCase(), function( data ) {
                          //Set the retrieved scaffolds:
                          //$('input[name=country]').val(data);
                          $('#essayText')[0].innerHTML = data;
                      });
                  }else{
                    $('#essayText')[0].innerHTML = "";      
                  }
              });

            </script>
      </div>
      


      <div w3-include-html=""></div>


    </div>
    <div id="menu6" class="tab-pane fade">
      <h3>Visualising Students SRL Behaviour</h3>
        <div w3-include-html=""></div>
    </div>

    <div id="menu7" class="tab-pane fade">
      <h3>Bulk Data Download </h3>
        <div w3-include-html="bulk-actions.php"></div>
    </div>

  </div> 

<script>
    includeHTML();
</script>

</body>
</html>