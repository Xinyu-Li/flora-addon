$(document).ready(function() {
  var columnDefs = [
      {
        data: "id",
        title: "Id",
        "visible": false,
        "searchable": false,
        className: "dt-body-center  dt-head-center"
      },
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
        data: "block",
        title: "Block",
        className: "dt-body-center  dt-head-center"
      },
      {
        data: "block_desc",
        title: "Block <br /> Desc",
        className: "dt-body-center  dt-head-center"
      },
      {
        data: "time_lapsed",
        title: "Time Lapsed <br />(ms)",
        className: "dt-body-center  dt-head-center"
      },
      {
        data: "clock",
        title: "Clock",
        className: "dt-body-center  dt-head-center"
      },
      {
        data: "url",
        title: "Page URL",
        width: "50px",
        className: "dt-body-center  dt-head-center"
      },
      {
        data: "action",
        title: "Event",
        className: "dt-body-center  dt-head-center"
      },
      {
        data: "sub_action",
        title: "Sub-Event",
        className: "dt-body-center  dt-head-center"
      },
      {
        data: "value",
        title: "Value",
        width: "50px",
        className: "dt-body-center  dt-head-center"
      },
      {
        data: "action_label",
        title: "Action label",
        className: "dt-body-center  dt-head-center"
      }

  ];

  var myTable;


    $('#searchByID').change(function(){

       if (document.querySelector("#searchID option[value='" + $('#searchByID').val() + "']" ) == null)
        {
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
                    data : {action: 'getRawDATA', 'qid' : $('#searchByID').val() },
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

