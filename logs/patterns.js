$(document).ready(function() {
  var columnDefs = [
      {
        data: "userid",
        title: "Username",
        className: "dt-body-center  dt-head-center"
      },
      {
        data: "uid",
        title: "Moodle <br />ID",
        //"visible": false,
        className: "dt-body-center  dt-head-center"
      },
      {
        data: "session_start",
        title: "Session Start <br />(ms)",
        //"visible": false,
        className: "dt-body-center  dt-head-center"
      },
      {
        data: "process_label",
        title: "Process Label",
        //"visible": false,
        className: "dt-body-center  dt-head-center"
      },
      {
        data: "process_starttime",
        title: "Process Start Time",
        //"visible": false,
        className: "dt-body-center  dt-head-center"
      },
      {
        data: "process_endtime",
        title: "Process End Time",
        //"visible": false,
        className: "dt-body-center  dt-head-center"
      } 

  ];

  var myTable;

    $('#searchByID').change(function(){
        if (document.querySelector("#searchID option[value='" + $('#searchByID').val() + "']" ) == null){
            alert("The entered participant does not exist !!! ");
            if (typeof myTable != "undefined") {
            myTable
              .column(1)
              .search($('#searchByID').val())
              .draw(); 
            } 

        }else{

            if (typeof myTable != "undefined") {
                myTable.clear().destroy();
            }

            myTable = $('#example').DataTable({
                "sPaginationType": "full_numbers",
                "aLengthMenu": [ [10, 50, 100, 200, -1], [10, 50, 100, 200, "All"] ],
                ajax: {
                    url : "fetch.php",
                    type : "POST",
                    data : {action: 'getPatternDATA', 'qid' : $('#searchByID').val() },
                    // our data is an array of objects, in the root node instead of /data node, so we need 'dataSrc' parameter
                    dataSrc : ''
                },
                columns: columnDefs,
                dom: 'Bfrltip',        // Needs button container
                select: 'single',
                responsive: true,
                altEditor: true,     // Enable altEditor
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
          });
        }
    });

});

