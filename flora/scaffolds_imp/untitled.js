



var scfTimer, breakPointID;
var todo_curr;

//let _modal = {}; // modal for scaffolds
var edit_mode = false; 

var scaffolds = new Array();
scaffolds.length = 0;

var idleTime = 0;
var idleInterval ;

window.onload = async function () {
    //getScaffolds();
    console.log("Listening to Breakpoint and Scaffolds")
    attach_Scaffold();



}      


async function reset() {
    clearInterval(scfTimer);
    localStorage.setItem("scfTime", 0 );
    localStorage.removeItem("pendingScaffold");
    localStorage.removeItem("endTime");

    localStorage.removeItem('essay');
    localStorage.removeItem('planner_activities');
    localStorage.removeItem('trial_planner_activities');
    
    beginSCFTimer();

    localStorage.removeItem("scaffoldID");

    for (key in localStorage) {
      if (key.substring(0,3) == 'msg') {
        localStorage.removeItem(key);
      }
      if (key.substring(0,5) == 'tasks') {
        localStorage.removeItem(key);
      }
      if (key.substring(0,8) == 'scaffold') {
        localStorage.removeItem(key);
      }
    }
    Scaffold_LogData("TASK","ESSAY_TASK_RESET", "00:00:00");
}

function show_Scaffold(scaff){
    var indx = -1;
    if (localStorage.getItem("scaffoldID") == null){
        indx = 1;
    }else{
        indx = Number(localStorage.getItem("scaffoldID")) + 1;
    }
    scaff.fired = true;
    localStorage.setItem("scaffold-"+ data.userid + "-" + data.usercourse + "-" + indx , JSON.stringify(scaff));
    localStorage.pendingScaffold = JSON.stringify(scaff);
    
    Scaffold_LogData("Scaffold","Message_Triggered", "sid:" + indx);   
}

function checkBreakPoint(data, indx ){
    
    if (localStorage.getItem("endTime") == null){
        localStorage.endTime = +new Date + 45000;
        localStorage.setItem("BreakPointDelay", 45000);
    }

    breakPointID = setInterval(function()
    {
        var remaining = localStorage.endTime - new Date;

        if(document.getElementById("modal-overlay").classList.contains("closed") == false){
            if ((Number(localStorage.getItem("BreakPointDelay") < 1100)) ){
                localStorage.setItem("BreakPointDelay", 5000);
            }
   
        }
        var BP_remaining = Number(localStorage.getItem("BreakPointDelay"));
console.log(BP_remaining + " BP : R " + remaining);
        if( (remaining > 0) && ( BP_remaining > 0) )
        {
            document.getElementById("promo-menu").style.display = "block";
            console.log( "Firing in: " + Math.min(Math.floor( remaining / 1000 ), Math.floor(BP_remaining / 1000 )) );
        } else{
            //reset();
                if(document.getElementById("modal-overlay").classList.contains("closed") == false){
                     var __modal = document.querySelector("#_modal");
                    var modalOverlay = document.querySelector("#modal-overlay");
                    __modal.classList.add("closed");
                    modalOverlay.classList.add("closed");

                }
                console.log("Time to Fire Scaffold!");
                document.getElementById("promo-menu").style.display = "none";
                clearInterval(breakPointID);
                var $boxes = $('input[name=options]:checked');
                $boxes.each( function(ind, elem){
                    var str = elem.value;
                    var i = Number(str.charAt(str.length-1));
                    $('input[name=options]')[i].click();
                  });


                display_Scaffold(data, indx);
                var _modal_ = document.querySelector("#_modal");
                var modalOverlay = document.querySelector("#modal-overlay");
                _modal_.classList.remove("closed");
                modalOverlay.classList.remove("closed");
                localStorage.setItem("scaffoldID", indx);
                localStorage.setItem("BreakPointDelay", 45000);
                localStorage.removeItem("pendingScaffold");
                localStorage.removeItem("endTime");
        }
    }, 1000);   
}




function show_ToDo_GeneralisedScaffold(){

    if(localStorage.getItem("scaffoldID") == null){
        alert ("There are no current scaffolds!");
    }else{
        if (edit_mode == true){
            display_ToDo_Scaffold(todo_curr);
        }else{
            display_ToDo_Scaffold(Number(localStorage.getItem("scaffoldID")));
        }
        edit_mode = false;
        document.getElementById("myToDo").style.display = "block";
    }    
}

function display_ToDo_Scaffold(n){

  todo_curr = n;
  if (document.getElementById("myToDo")){
    if (document.getElementById("myToDoheader")){
        
        if (n > 1){
            document.getElementById("prev-button").style.display = "block";     
        }else{
            document.getElementById("next-button").style.display = "none"; 
        }

        var m1 = localStorage.getItem("msg1-" + data.userid + "-" + data.usercourse + "-" + n);
        var m2 = localStorage.getItem("msg2-" + data.userid + "-" + data.usercourse + "-" +  n);
       

        document.getElementById("myToDomessage1").innerHTML = m1;
        document.getElementById("myToDomessage2").innerHTML = m2;
        document.getElementById("TODOscf_num").innerHTML = "| Prompt: " +  todo_curr + " of " + Number(localStorage.getItem("scaffoldID")) + " |" ;

        LoadTaskList();

        /* disabling TO do List */
                    

        if (Number(n) >= Number(localStorage.getItem("scaffoldID")) ){
            document.getElementById("next-button").style.display = "none";
           // document.getElementById("container").removeAttribute("style");
            document.getElementById("TODOscf_num").style.color = "#1d6515ba"; 
            Scaffold_LogData("Scaffold","CurrToDoList_Displayed", "sid:" + Number(n));
        }else{
            document.getElementById("next-button").style.display = "block";
           // document.getElementById("container").setAttribute("style", "pointer-events: none;opacity: 0.7;background: #CCC;");
            document.getElementById("TODOscf_num").style.color = "#7977772e";
            Scaffold_LogData("Scaffold","PrevToDoList_Displayed", "sid:" + Number(n));
        }

        if (Number(n) <= 1){
            document.getElementById("prev-button").style.display = "none";
        }else{
            document.getElementById("prev-button").style.display = "block";
        }

    }
  }
}

function attach_Scaffold(){

    $('head').append('<link rel="stylesheet" type="text/css" href= "' + base + '/scaffolds.css">');
    $('head').append('<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">');


    var text = '';
    text +=  '<div class="pause-overlay closed" id="pause-overlay"></div>';
    text +=  '<div class="modal-overlay closed" id="modal-overlay"></div>';
    text +=  '<div class="_modal closed" id="_modal">';

    text +=  '<div style="height: 45px;background: #e6e8f1;"></div>';
    text +=  '<button class="close-button" id="close-button"> X </button>';
    text +=  '<div class="modal-guts">';
    text +=  '<p id= "message1" class = "message1"> To perform well in this task, it is important to understand what this task is about.</p>';
    text +=  '<img id= "thinkico" src="https://datalogger.myannotator.com/Scaffolds/client/list.svg" style="width: 40px;height: 40px;float:left; margin-right: 15px; margin-top: -3px;"/>';
    text +=  '<p id= "message2" class = "message2"> Do I have a good overview of the task? What are my next steps?</p> ';
    text +=  '<p id = "timeup-pp" style="padding: 2px;display:block;"></p> ';   

    text +=  '<div id = "optionbox" style ="display:block;"><p id="id_options">';
    text +=  '<label><input type="checkbox" name="options" value="option0"><span id="opt_0" style ="display:none;">sun</span></label>';
    text +=  '<label><input type="checkbox" name="options" value="option1"><span id="opt_1" style ="display:none;">mon</span></label>';
    text +=  '<label><input type="checkbox" name="options" value="option2"><span id="opt_2" style ="display:none;">tue</span></label>';
    text +=  '<label><input type="checkbox" name="options" value="option3"><span id="opt_3" style ="display:none;">wed</span></label>';
    text +=  '<label><input type="checkbox" name="options" value="option4"><span id="opt_4" style ="display:none;">thu</span></label>';
    text +=  '<label><input type="checkbox" name="options" value="option5"><span id="opt_5" style ="display:none;">fri</span></label>';
    text +=  '</p> </div>';

    text +=  '<div id="timesupImg"> <img src = "https://datalogger.myannotator.com/Scaffolds/client/timesup.jpg" style = "display: block;margin-left: auto; margin-right: auto; height:150px;" /> </div>';

    //text +=  '<button class="close-button" id="close-button"> Close and continue ... </button>';
    text +=  '<button class="checklist-button" id="timeup-button" style ="display:none;" onclick=""> Proceed to next task &nbsp;&nbsp; &#8680; </button>';
    text +=  '<button class="checklist-button" id="checklist-button" style ="display:block;"> Create Checklist &nbsp;&nbsp; &#8680; </button>';
  
    //  text +=  '<button class="checklist-button" id="checklist-button"> Create Checklist &nbsp;&nbsp; &#8680; </button>';
    text +=  '</div>';
    text +=  '</div>';

    
    //<!-- Draggable DIV -->
    text += '<div id="myToDo" style="display:none;">';
    text += '<div id="myToDoheader">';
    text += '<div><button class="prev-button" id="prev-button"> < </button>';
    text += '<span id ="TODOscf_num" style="width: 165px;"> Scaffold # : ? </span>';
    text +=  '<button class="next-button" id="next-button" > > </button></div>';
    text += '</div>';
     text +=  '<img id= "myToDothinkico" src="https://datalogger.myannotator.com/Scaffolds/client/list.svg" style="width: 40px;height: 40px;display:inline;     margin-right: 10px;margin-top: 8px;margin-left: 15px;"/>';
    text += '<div style = "padding: 10px;">'
    text +=  '<p id= "myToDomessage1" class = "message1"> To perform well in this task, it is important to understand what this task is about.</p>';
    //text +=  '<img id= "myToDothinkico" src="./list.svg" style="width: 30px;height: 20px;display:inline;    margin-top: 3px;"/>';
    text +=  '<p id= "myToDomessage2" class = "message2"> Do I have a good overview of the task? What are my next steps?</p> ';
    //text +=  '<p style="padding: 5px;"></p>';
    //text +=  '<p style="text-align: left; font-size: 12px;">TO-DO List:</p> ';

    text +=  '<div id="container">';
    text +=  '<h1> My To-do List  <i class="fa fa-edit" style="float: right;line-height: 1;"> </i> <!-- <i class="fa fa-plus"> </i> --> </h1>';
    text +=  '<form id="task-list">';
    text +=  '<input type="text" placeholder="Add a To-Do" id="task">';
    text +=  '<button type="submit"></button>';
    text +=  '</form>';
    text +=  '<ol id="sortable">';
    //text +=  '<li id="task-EXAMPLE"><span><i class="fa fa-trash"></i></span>';
    //text +=  '<label>EXAMPLE</label>';
    //text +=  '</li>';
    text +=  '</ol>';
    text +=  '</div>';
    text +=  '<h2 style="color: #757474;position: absolute; bottom: 22px; font-size: 12px;font-weight: normal; margin-left: 28px;"> <text id="emptyListText"> None </text></h2>';
    text +=  '<button class="close-button" id="ToDo-close-button"> Close and continue ... </button>';
    text += '</div>'
    text += '</div>';



    text += '<div id="promo-menu" style="display: none;">';
    text += '<div id="promo-launcher" class="promo-launcher promo-launcher-active blog-promoctive promo-launcher-with-message promo-launcher-with-updated-avatar promo-launcher-with-avatar promo-launcher-with-badge promo-launcher-with-preview">';
    text += '<div onclick="Scaffold_LogData(\'Scaffold\',\'Notification_Clicked\', \'sid:\' + (Number(localStorage.getItem(\'scaffoldID\')) + 1) ); localStorage.setItem(\'endTime\',+new Date); ">';
    text += '<div class="promo-launcher-button">';
    text += '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 458 458" style="enable-background:new 0 0 458 458;" xml:space="preserve">';
    text += '<g><g><g>';
    text += '<path d="M428,41.533H30c-16.568,0-30,13.432-30,30v252c0,16.568,13.432,30,30,30h132.1l43.942,52.243 c5.7,6.777,14.103,10.69,22.959,10.69c8.856,0,17.259-3.912,22.959-10.689l43.942-52.243H428c16.569,0,30-13.432,30-30v-252 C458,54.965,444.569,41.533,428,41.533z M428,323.533H281.933L229,386.465l-52.932-62.932H30v-252h398V323.533z"/> <path d="M85.402,156.999h137c8.284,0,15-6.716,15-15s-6.716-15-15-15h-137c-8.284,0-15,6.716-15,15 S77.118,156.999,85.402,156.999z"/> <path d="M71,233.999c0,8.284,6.716,15,15,15h286c8.284,0,15-6.716,15-15s-6.716-15-15-15H86 C77.716,218.999,71,225.715,71,233.999z"/>';
    text += '</g></g></g> </svg></div>';
    text += '<div class="promo-launcher-badge">1</div></div></div></div>';

    $('body').prepend(text);
    $("#thinkico").attr('src', "https://datalogger.myannotator.com/Scaffolds/client/list.svg")

    var _modal__ = document.querySelector("#_modal");
    var modalOverlay = document.querySelector("#modal-overlay");
    var closeButton = document.querySelector("#close-button");
    var ToDocloseButton = document.querySelector("#ToDo-close-button");
    var checklistButton = document.querySelector("#checklist-button");
    var show1 = document.querySelector("#show1");
    var show2 = document.querySelector("#show2");
    var show3 = document.querySelector("#show3");
    var show4 = document.querySelector("#show4");
    var show5 = document.querySelector("#show5");


    var prevButton = document.querySelector("#prev-button");
    var nextButton = document.querySelector("#next-button");

    closeButton.addEventListener("click", function() {
	var _modal___ = document.querySelector("#_modal");
      _modal___.classList.add("closed");
      modalOverlay.classList.add("closed");
      edit_mode = false;

      Scaffold_LogData("Scaffold","Message_Closed", "sid:" + todo_curr); 
    });

    $( 'input[name=options]' ).click(function() {
        var $boxes = $('input[name=options]:checked');
     

        if ($boxes.length > 3){
            var str = this.value;
	console.log(str);
            var i = Number(str.charAt(str.length-1));
            $('input[name=options]')[i].click();
            return false;
            alert ("Please select upto 3 options to create a checklist");
        }else{
           var da = $(this).parents("label")[0].innerText;
           if (this.checked){
Scaffold_LogData("Scaffold","MessageOption_Checked", "sid:" + todo_curr + " opt: " + da);  
       }else{
Scaffold_LogData("Scaffold","MessageOption_UnChecked", "sid:" + todo_curr + " opt: " + da);  
      
       }
    }


    });


    checklistButton.addEventListener("click", function() {

      var $boxes = $('input[name=options]:checked');

      if ($boxes.length > 0){

    if (edit_mode == true){
        id = todo_curr;
    }else{
        id = Number(localStorage.getItem("scaffoldID"));
    }

   var old = localStorage.getItem("tasks-"+ data.userid + "-" + id);



          sendToLocalStorage($boxes);
        var x_modal = document.querySelector("#_modal"); 
	x_modal.classList.add("closed");
          modalOverlay.classList.add("closed");


  if (edit_mode == true){
        id = todo_curr;
    }else{
        id = Number(localStorage.getItem("scaffoldID"));
    } 

   var _new = localStorage.getItem("tasks-"+ data.userid + "-" + id);
       if (old == null){
Scaffold_LogData("Scaffold","CreateChecklist", "sid:" + todo_curr + " opts: " + _new);
}else{
Scaffold_LogData("Scaffold","CreateChecklist", "sid:" + todo_curr + " opts_old: " + old + " opts_new: " + _new);
}




          show_ToDo_GeneralisedScaffold();
      }else{
        alert ("Please select upto 3 options to create a checklist");
      }

    });

    

    ToDocloseButton.addEventListener("click", function() {
      document.getElementById("myToDo").style.display = "none";
      Scaffold_LogData("Scaffold","ToDoList_Closed", "sid:" + Number(todo_curr));
    });

    prevButton.addEventListener("click", function() {
        if (todo_curr > 1){
            display_ToDo_Scaffold(--todo_curr);
        }

    });

    nextButton.addEventListener("click", function() {
            display_ToDo_Scaffold(++todo_curr);
    });

    /* TO Function */

    $("#task-list").fadeToggle();

      $("#sortable").on("click", function(event) {
    var $thatItem = $(event.target).parents("li");
    switch (event.target.nodeName) {
      case "SPAN":

      break;
      case "I":
        if ($(event.target).hasClass('fa-check-square')){
            $(event.target).removeClass('fa-check-square');
            $(event.target).addClass('fa-square');
            $thatItem.toggleClass("completed");

            if (Number(todo_curr) >= Number(localStorage.getItem("scaffoldID"))) {
                Scaffold_LogData("Scaffold","CurrToDoListItem_UnChecked", "sid:" + Number(todo_curr) + " option:" + $thatItem[0].innerText ); 
            }else{
                    Scaffold_LogData("Scaffold","PrevToDoListItem_UnChecked", "sid:" + Number(todo_curr) + " option:" + $thatItem[0].innerText ); 
            }


        }else if ($(event.target).hasClass('fa-square')){
            $(event.target).addClass('fa-check-square');
            $(event.target).removeClass('fa-square');
            $thatItem.toggleClass("completed");
            

                  if (Number(todo_curr) >= Number(localStorage.getItem("scaffoldID")) ){
                        Scaffold_LogData("Scaffold","CurrToDoListItem_Checked", "sid:" + Number(todo_curr) + " option:" + $thatItem[0].innerText );
                }else{
                        Scaffold_LogData("Scaffold","PrevToDoListItem_Checked", "sid:" + Number(todo_curr) + " option:" + $thatItem[0].innerText );
                }


        }else if ($(event.target).hasClass('fa-external-link-alt')){
            processAction(event.target.getAttribute("s_action"));
            //alert ("Opening a link");

             if (Number(todo_curr) >= Number(localStorage.getItem("scaffoldID"))) {
                        Scaffold_LogData("Scaffold","CurrToDoListItem_ClickedLink", "sid:" + Number(todo_curr) + " option:" + $thatItem[0].innerText + " >> " + event.target.getAttribute("s_action") );
                }else{
                        Scaffold_LogData("Scaffold","PrevToDoListItem_ClickedLink", "sid:" + Number(todo_curr) + " option:" + $thatItem[0].innerText  + " >> " + event.target.getAttribute("s_action") );
                }
        }
        
        break;
      case "LABEL":

        if($thatItem.find('i').hasClass('fa-square')){
            $thatItem.find('.fa-square').addClass('fa-check-square');
            $thatItem.find('.fa-square').removeClass('fa-square');
            

            if (Number(todo_curr) >= Number(localStorage.getItem("scaffoldID"))) {
                        Scaffold_LogData("Scaffold","CurrToDoListItem_Checked", "sid:" + Number(todo_curr) + " option:" + $thatItem[0].innerText );
                }else{
                        Scaffold_LogData("Scaffold","PrevToDoListItem_Checked", "sid:" + Number(todo_curr) + " option:" + $thatItem[0].innerText );
                }


        }else if ($thatItem.find('i').hasClass('fa-check-square')){
            $thatItem.find('.fa-check-square').addClass('fa-square');
            $thatItem.find('.fa-check-square').removeClass('fa-check-square');
            if (Number(todo_curr) >= Number(localStorage.getItem("scaffoldID"))) {
                        Scaffold_LogData("Scaffold","CurrToDoListItem_UnChecked", "sid:" + Number(todo_curr) + " option:" + $thatItem[0].innerText );
                }else{
                        Scaffold_LogData("Scaffold","PrevToDoListItem_UnChecked", "sid:" + Number(todo_curr) + " option:" + $thatItem[0].innerText );
                }
        }
        $thatItem.toggleClass("completed");
        break;
    }
    setTimeout(function() {
      updateLocalStorage($("#sortable"));
    }, 500);
    event.stopPropagation();
  });

  $("#task-list").submit(function(event) {
    event.preventDefault();
    // Grabbing the text typed
    var todoText = $("#task").val();
    addListItem($("#sortable"), todoText, false);
    // Clear the text field
    $("#task").val("");
    updateLocalStorage($("#sortable"));
  });

  $(".fa-plus").click(function() {
    $("#task-list").fadeToggle();
  });


  $(".fa-edit").click(function() {

    edit_mode = true;
    if (todo_curr <= scaffolds.length ){
        if(document.getElementById("modal-overlay").classList.contains("closed") == false){
            var _modala = document.querySelector("#_modal");
            var modalOverlay = document.querySelector("#modal-overlay");
            _modala.classList.add("closed");
            modalOverlay.classList.add("closed")
        }
               var _modalb = document.querySelector("#_modal");
                var modalOverlay = document.querySelector("#modal-overlay");
                _modalb.classList.remove("closed");
                modalOverlay.classList.remove("closed");

        console.log("Time to Fire Scaffold!");
        var $boxes = $('input[name=options]:checked');
        $boxes.each( function(ind, elem){
            var str = elem.value;
            var i = Number(str.charAt(str.length-1));
            $('input[name=options]')[i].click();
        });


        var $boxes = $('input[name=options]');
        var d = JSON.parse(localStorage.getItem("scaffold-" + data.userid + "-" + data.usercourse + "-" + todo_curr )).options.split("\n") ; //.split('↵'
        if (d.toString().trim().substring(1,3) == "e:"){
            //
        }else{

        $boxes.each( function(ind, elem){

            var str = elem.value;
            var i = Number(str.charAt(str.length-1));
            if (i < d.length){
                var arr = new Array(),  txt = lnk = "";
                arr = d[i].split(',');
                txt = arr[0].trim().substring(2, arr[0].trim().length - 1);
                lnk = arr[1].trim().substring(1, arr[1].trim().length - 1);

                  var localTasks = JSON.parse(localStorage.getItem("tasks-"+ data.userid + "-" + data.usercourse + "-" +todo_curr));
                  $.each(localTasks, function(task, status) {
            
                    if (task == txt){
                        $('input[name=options]')[i].click();
                    }
                  });
                
            }

        });
       
         }

        
        $('#ToDo-close-button').click();

         if (Number(todo_curr) >= Number(localStorage.getItem("scaffoldID"))){
        Scaffold_LogData("Scaffold","CurrToDoList_Edit", "sid:" + Number(todo_curr));
    }else{
        Scaffold_LogData("Scaffold","PrevToDoList_Edit", "sid:" + Number(todo_curr));
    }

        display_Scaffold(scaffolds[todo_curr - 1 ], todo_curr);
    }else{
        alert ("An error has been encountered loading this scaffold!")
        edit_mode = false;
    }
  });


  $("#sortable").sortable({
    update: function(e, ui) {
      updateLocalStorage($(this));


          var opts = Array.from($(this)[0].childNodes);
      var Ordered_Opts = opts.map(item  => item.innerText);
            if (Number(todo_curr) >= Number(localStorage.getItem("scaffoldID"))) {
            Scaffold_LogData("Scaffold","CurrToDoList_Re-Ordered", "sid:" + Number(todo_curr) + " ReOrdered To: " + Ordered_Opts);
        }else{
            Scaffold_LogData("Scaffold","PrevToDoList_Re-Ordered", "sid:" + Number(todo_curr) + " ReOrdered To: " + Ordered_Opts);
        }

    }
  }).disableSelection();

  
}

function processAction(txt) {
    var code = txt.trim().charAt(0);
    switch (code) {
      case "u":
        var link = txt.trim().substring(2, txt.trim().length);
        //alert("Open Link");
        console.log(link);
        location.replace(link);
      break;
      case "a":
        var action = txt.trim().substring(2, txt.trim().length);
        //alert("Open Tool");
        console.log(action)
        $(action).click();
      break;

      default:
         alert ("This task has no linked components!");
    }
  }

  function addListItem($t, s, c) {
    //create a new LI
    var $li = $("<li>", {
      id: "task-" + s.replace(" ", "_")
    });
    if (c) {
      $li.addClass("completed");
        var $wrap = $("<span>").appendTo($li);
            $wrap.append($("<i>", {
              class: "fa fa-check-square"
            }));
    }else{
        var $wrap = $("<span>").appendTo($li);
            $wrap.append($("<i>", {
              class: "fa fa-square"
            }));
    }

    $li.append($("<label>").html(s));

    var d = JSON.parse(localStorage.getItem("scaffold-" + data.userid + "-" + data.usercourse + "-" + todo_curr )).options.split("\n") ; //.split('↵')

    for (var i = 0 ; i < d.length; i++){
       var arr = new Array(),  txt = ttip = uri = "";
        arr = d[i].split(',');
        txt = arr[0].trim().substring(2, arr[0].trim().length - 1);
        ttip = arr[1].trim().substring(1, arr[1].trim().length - 1);
        uri= arr[2].trim().substring(1, arr[2].trim().length - 2);


        if (txt === s){
            if (ttip.trim() != "")
                var $wrap = $("<span id ='link' data-tooltip-position='top' data-tooltip='" + ttip+ "'>").appendTo($li);
            else{
                 var $wrap = $("<span>").appendTo($li);

            }

            if (uri.trim() != ""){
                $wrap.append($("<i>", {
                    class: "fa fa-external-link-alt",
                    s_action : uri,
                    s_txt : txt
                }));
            }else{
                $wrap.append($("<i>", {
                    class: "fa fa-external-link-alt",
                    s_action : uri,
                    s_txt : txt,
                    style : 'display:none;'
                }));

            }

        break;
        }
        
    }
    $li.appendTo($t);
    $t.sortable("refresh");
  }


 function LoadTaskList() {
    console.log("Loading Task")
     document.getElementById("emptyListText").style.display = "none";
  if (typeof(Storage) !== "undefined") {
    // Code for localStorage/localStorage.
    if (localStorage.getItem("tasks-"+ data.userid + "-" + data.usercourse +"-" + todo_curr) !== "undefined") {
      var localTasks = JSON.parse(localStorage.getItem("tasks-"+ data.userid + "-" + data.usercourse + "-" +todo_curr));
      // Grab stored tasks
      $("#sortable").find("li").remove();
      $.each(localTasks, function(task, status) {
        addListItem($("#sortable"), task, status);
      });
    }

    if (localStorage.getItem("tasks-" + data.userid + "-" + data.usercourse + "-" + todo_curr) == null){
        document.getElementById("emptyListText").style.display = "block";
    }

  } else {
    // Sorry! No Web Storage support..
  }
 }

   function updateLocalStorage($list) {
    var tasks = {};
    $list.find("li").each(function(ind, elem) {
      var task = $(elem).text().trim();
      var completed = $(elem).hasClass("completed");
      tasks[task] = completed;
      if (typeof(Storage) !== "undefined") {
        localStorage.setItem("tasks-"+ data.userid + "-" + data.usercourse + "-" + todo_curr , JSON.stringify(tasks));
      }
    });
  }

  function sendToLocalStorage($boxes) {
    var tasks = {};
    var id;
    if (edit_mode == true){
        id = todo_curr;
    }else{
        id = Number(localStorage.getItem("scaffoldID"));
    }


    $boxes.each( function(ind, elem){
        // Do stuff here with this
        var str = elem.value;
        var i = Number(str.charAt(str.length-1));
        var d = JSON.parse(localStorage.getItem("scaffold-" + data.userid + "-" + data.usercourse + "-" + id )).options.split("\n") ; //.split('↵')
        var arr = new Array(),  txt = lnk = "";
        arr = d[i].split(',');
        txt = arr[0].trim().substring(2, arr[0].trim().length - 1);
        lnk = arr[1].trim().substring(1, arr[1].trim().length - 1);

        var task = txt.toString();
        tasks[task] = false;
        

        if (typeof(Storage) !== "undefined") {
            localStorage.setItem("tasks-"+ data.userid + "-" + data.usercourse + "-" + id , JSON.stringify(tasks));
        }
    });

  }

/* Added by Shaveen to monitor interaction Statistics */

  // function postAjax(url, data, success) {
  //   let params = typeof data == 'string' ? data : Object.keys(data).map(
  //           function(k){ return encodeURIComponent(k) + '=' + encodeURIComponent(data[k]) }
  //       ).join('&');

  //   var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
  //   xhr.open('POST', url);
  //   xhr.onreadystatechange = function() {
  //       if (xhr.readyState>3 && xhr.status==200) { success(xhr.responseText); }
  //   };
  //   xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
  //   xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  //   xhr.send(params);
  //   return xhr;
  // }

  // function getScaffolds(){
  //     //var server_url = 'https://datalogger.myannotator.com/get_scaffolds.php';
  //     var server_url = 'http://localhost:8888/Scaffolds/get_scaffolds.php';
  //    // var server_url = 'https://cors-anywhere.herokuapp.com/https://datalogger.myannotator.com/get_scaffolds.php';
  //      postAjax(server_url, 'source=' + 'LMS&userid=' + data.userid + '&usercourse=' + data.usercourse + '&usergroup=' + data.usergrp + '&userclass=' + data.userclass + '&userlang=' + data.userlang + '&data=' + JSON.stringify(data), function (data) { console.log(data); scaffolds = JSON.parse(data); console.log(scaffolds);  }); 
  //       beginSCFTimer();
  // }




 



// monitor blur and idle time.
document.addEventListener("visibilitychange", function() {

  if (document.visibilityState === 'visible') {
   // console.log("Im am back")
    idleTime = 0;
  } else {
   // console.log("I m away");
    endSCFTimer();
    //idleTime = 30; // allow 30 seconds
  }

//endSCFTimer();
    
    //idleTime = idleTime + 1;
}, false);





    //Zero the idle timer on mouse movement.
    $(this).mousemove(function (e) {
        idleTime = 0;
    });
    $(this).keypress(function (e) {
        idleTime = 0;
    });


function timerIncrement() {
    idleTime = idleTime + 1;
    if (idleTime > 60) { // 1minute
       // endSCFTimer();

    }
}


function setWithExpiry(key, value, ttl) {
    const now = new Date()

    // `item` is an object which contains the original value
    // as well as the time when it's supposed to expire
    const item = {
        value: value,
        expiry: now.getTime() + ttl,
    }
    localStorage.setItem(key, JSON.stringify(item))
}

function getWithExpiry(key) {
    const itemStr = localStorage.getItem(key)
    // if the item doesn't exist, return null
    if (!itemStr) {
        return null
    }
    const item = JSON.parse(itemStr)
    const now = new Date()
    // compare the expiry time of the item with the current time
    if (now.getTime() > item.expiry) {
        // If the item is expired, delete the item from storage
        // and return null
        localStorage.removeItem(key)
        return null
    }
    return item.value
}


function SendScaffoldLog(action, subaction) {

    console.log(action + " " + subaction);

}











    
