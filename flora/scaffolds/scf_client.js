var _events;
var todo_curr;
var edit_mode = false;

let LOG_SERVER = "https://floraproject.org/flora/log_to_db.php"
let date = new Date();

let dataLOG = {
  source : "Scaffold",
  url: window.location.href,
  action : "eg. Page",
  sub_action: "eg. View",
  value: "value or json",
  timestamp: "DD/MM/YYYY HH:MM:SS",
  logServer: LOG_SERVER
};

window.onload = async function () {
    ScaffoldLog("Scaffold","Scaffold Page Load", localStorage.getItem("url")); 
    console.log("Listening to Breakpoint and Scaffolds");
    if (localStorage.getItem("scaffolds") == "enabled"){
        if(document.getElementsByClassName("annotator-frame-button")[2]) {
            document.getElementsByClassName("annotator-frame-button")[2].style.display = "none";
            document.getElementById("scaffold_badge").style.top ="88px";  
        }
        document.getElementById("scaffold_badge").style.display = "block";

        attach_Scaffold();
        console.log("Attached Scaffold Successfully");
      

    // Make the TODO List DIV element draggable:
    dragElement(document.getElementById("myToDo"));

    function dragElement(elmnt) {
      var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
      if (document.getElementById(elmnt.id + "header")) {
        // if present, the header is where you move the DIV from:
        document.getElementById(elmnt.id + "header").onmousedown = dragMouseDown;
      } else {
        // otherwise, move the DIV from anywhere inside the DIV:
        elmnt.onmousedown = dragMouseDown;
      }

      function dragMouseDown(e) {
        e = e || window.event;
        e.preventDefault();
        // get the mouse cursor position at startup:
        pos3 = e.clientX;
        pos4 = e.clientY;
        document.onmouseup = closeDragElement;
        // call a function whenever the cursor moves:
        document.onmousemove = elementDrag;
      }

      function elementDrag(e) {
        e = e || window.event;
        e.preventDefault();
        // calculate the new cursor position:
        pos1 = pos3 - e.clientX;
        pos2 = pos4 - e.clientY;
        pos3 = e.clientX;
        pos4 = e.clientY;
        // set the element's new position:
        elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
        elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
      }

      function closeDragElement() {
        // stop moving when mouse button is released:
        document.onmouseup = null;
        document.onmousemove = null;
      }
    } 


    }


    if(document.getElementsByClassName("annotator-frame-button")[2]) {
        document.getElementsByClassName("annotator-frame-button")[2].style.display = "none";
        document.getElementById("scaffold_badge").style.top ="88px";
        document.getElementById("scaffold_badge").style.display = "block";
         ScaffoldLog("Scaffold","Icon Embeded", localStorage.getItem("url")); 
    }else{
        document.getElementById("scaffold_badge").style.display = "block";
    }




};

function Scaffold_Meta(elapsed_time){
    if ($("#sortable").length < 1){
        attach_Scaffold();
    }else if (localStorage.getItem("pendingScaffold") != null){
            if (localStorage.getItem("Trigger_URL") != window.location.href){
	      localStorage.endTime = +new Date + 7500;
              localStorage.setItem("BreakPointDelay", 7500);
              ScaffoldLog("Scaffold","Pending_Scaffold_Identified", JSON.parse(localStorage.getItem("pendingScaffold")));
              checkBreakPoint(JSON.parse(localStorage.getItem("pendingScaffold")));
		    //trigger_Scaffold(JSON.parse(localStorage.getItem("pendingScaffold")));
            }
    }else{
   var scaffold_schdule = [999, 2,7,16,21,35]; // limit to 5 only.
    var block = scaffold_schdule.indexOf(elapsed_time) % 6;
    if(scaffold_schdule.includes(elapsed_time) ){
         ScaffoldLog("Scaffold","Scaffold META", elapsed_time); 
        if (localStorage.getItem("Scaff_at_" + block) == null){

        ScaffoldLog("Scaffold","Condition Met for Scaffold", "block: " + block);
		console.log("Scaffold - Condition Met for Scaffold: " + block);
		console.log("Scaffold - Sending Request  fo Scaffold: " + block);

            	var func = "";
            	if (localStorage.getItem("userclass") == "Generalised"){
                	func =  "GET_GENERALISED_SCAFFOLDS";
            	}else if (localStorage.getItem("userclass") == "Personalised"){

        			if (localStorage.getItem("PatternUpdate_at_" + block) == null){
        				//gDBQuery("UPDATE_PATTERN_LABELS_V3", localStorage.getItem("uid"));
        				localStorage.setItem("PatternUpdate_at_" + block, "done") ;
        			}
                        	func = "GET_PERSONALISED_SCAFFOLDS";
                }

            setTimeout(function(){ 
                ScaffoldLog("Scaffold","Scaffold_Requested", "time at: " + elapsed_time );
                ScaffoldLog("Scaffold","ScaffoldRequested_block_" + block, "time at: " + elapsed_time);
                sPostAjax('https://floralearn.org/functions.php', 'source=' + 'flora' + '&func=' + func +  '&para1=' + localStorage.getItem("uid") + '&para2='  + block + '&para3=' + localStorage.getItem("moodle-lang"), function (_res) {console.log(_res);    localStorage.setItem("Scaff_at_" + block, "promoted") ; ScaffoldLog("Scaffold","Scaffold_Received", _res); ScaffoldLog("Scaffold","ScaffoldReceived_block_" + block, "time at: " + elapsed_time); promoteScaffold(_res);    });


            }, 100);


        }
    }

  }
}

function promoteScaffold(sc){
        ScaffoldLog("Scaffold","Scaffold_Promoted", sc);

        //var  events = new EventSource('https://scaffold.floralearn.org/events/' + data.uid);
        //events.onmessage = (event) => 
    {
        //console.log("Retrieving Data on: " + 'https://scaffold.floralearn.org/events/' + data.uid)
        const parsedData = JSON.parse(sc)[0];
        console.log(parsedData);
            if (parsedData.type == "breakpoint"){
                localStorage.setItem("BreakPointDelay", 0);
            }else if (parsedData.type == "scaffold"){
                var showScaffold = (parsedData.option1_enabled == 1) || (parsedData.option2_enabled == 1) || (parsedData.option3_enabled == 1) || (parsedData.option4_enabled == 1) ;
                if (showScaffold){
                    parsedData.skipped = false;
                    trigger_Scaffold(parsedData);
                }else{
                    Scaffold_LogData("Scaffold","Scaffold_Skipped", parsedData.scaffold_number); 
                    parsedData.skipped = true;
                    ScaffoldLog("Scaffold","Scaffold_Skipped", sc);
                   // localStorage.setItem("scaffold-"+ data.userid + "-" + data.usercourse + "-" + parsedData.scaffold_number, JSON.stringify(scaff));
                   // localStorage.setItem("scaffoldID", parsedData.scaffold_number);
                } 
            }else if (parsedData.type == "endtask"){
                // show_TimesUp(); 
            }
                
            // }else if (){
            //     show_TimesUp(); 
            // }
        };
}

function checkBreakPoint(scaff){
    if (localStorage.getItem("endTime") == null){
        localStorage.endTime = +new Date + 45000;
        localStorage.setItem("BreakPointDelay", 45000);
    }

    var breakPointID = setInterval(function()
    {
        var remaining = localStorage.endTime - new Date;
        
        if(document.getElementById("modal-overlay").classList.contains("closed") == false){
            if ((Number(localStorage.getItem("BreakPointDelay") < 2000)) ){
                localStorage.setItem("BreakPointDelay", 10000);
            }
        }
        var BP_remaining = Number(localStorage.getItem("BreakPointDelay"));
        if( (remaining > 0) && ( BP_remaining > 0)){
            document.getElementById("promo-menu").style.display = "block";
            console.log( "Firing Scaffold in: " + Math.min(Math.floor( remaining / 1000 ), Math.floor(BP_remaining / 1000 )) );
        }else{
            todo_curr = Number(localStorage.getItem("scaffoldID"));
            show_Scaffold(scaff); 
            localStorage.setItem("activeScaffold", todo_curr);
            clearInterval(breakPointID); 
        }

    }, 1000);
}

function show_TimesUp(){
    var _tu_ = document.querySelector("#_timesUp");
    var modalOverlay = document.querySelector("#modal-overlay");
    _tu_.classList.remove("closed");
    modalOverlay.classList.remove("closed");
    Scaffold_LogData("Scaffold","TimeUP_Displayed", "display");
}


function show_Scaffold(scf){
    var index = todo_curr; // Number(localStorage.getItem("scaffoldID")); // scf.scaffold_number;
    prepare_Scaffold(scf);

    if(document.getElementById("modal-overlay").classList.contains("closed") == false){
        var __modal = document.querySelector("#_modal");
        var modalOverlay = document.querySelector("#modal-overlay");
        __modal.classList.add("closed");
        modalOverlay.classList.add("closed");
    }
    document.getElementById("promo-menu").style.display = "none";

    var _modal_ = document.querySelector("#_modal");
    var modalOverlay = document.querySelector("#modal-overlay");
    _modal_.classList.remove("closed");
    modalOverlay.classList.remove("closed");
    
    //localStorage.removeItem("pendingScaffold");
    //localStorage.removeItem("endTime");
   // localStorage.removeItem("Trigger_URL");

    Scaffold_LogData("Scaffold","Message_Displayed", "sid:" + index);
    ScaffoldLog("Scaffold","Message_Displayed", localStorage.getItem("url")); 
    //localStorage.setItem("Message_Displayed_" + scf.scaffold_number , "true"
	//
    
	localStorage.removeItem("endTime");
    localStorage.removeItem("Trigger_URL");
    localStorage.removeItem("pendingScaffold");

    todo_curr = index;
}

function trigger_Scaffold(scaff){
    
    // enable notification
    var indx; //= scaff.scaffold_number;
    if (localStorage.getItem("scaffoldID") == null){
        localStorage.setItem("activeScaffold", 0);
        indx = 1;
    }else{

        indx =  Number(localStorage.getItem("scaffoldID"));
        if (localStorage.getItem("pendingScaffold") == null){
            indx = Number(localStorage.getItem("scaffoldID")) + 1;
        }
     }

    localStorage.setItem("scaffold-"+ data.userid + "-" + data.usercourse + "-" + indx , JSON.stringify(scaff));
    localStorage.pendingScaffold = JSON.stringify(scaff);
    Scaffold_LogData("Scaffold","Message_Triggered", "sid:" + indx + " serve_id:" + scaff.id ); 
    ScaffoldLog("Scaffold","Scaffold_Triggered", "sid:" + indx + " serve_id:" + scaff.id);
    todo_curr = indx;
    checkBreakPoint(scaff);

    localStorage.setItem("scaffoldID", indx);
    //show_Scaffold(scaff, indx);
    localStorage.setItem("Trigger_URL", window.location.href);

    
        var $boxes = $('input[name=options]:checked');
            $boxes.each( function(ind, elem){
                var str = elem.value;
                var i = Number(str.charAt(str.length-1)) - 1;
                $('input[name=options]')[i].click();
        });

}


function prepare_Scaffold(scf){
  ScaffoldLog("Scaffold","Scaffold_Prepare", scf);
  if (document.getElementById("modal-overlay")){
    if (document.getElementById("_modal")){
        var indx = Number(localStorage.getItem("scaffoldID")); // scf.scaffold_number;
	    //document.getElementById("scaffold_title").innerHTML = ""; //scf.title;
        document.getElementById("message1").innerHTML = scf.content1;
        document.getElementById("message2").innerHTML = scf.content2;
	    localStorage.setItem("title-"+ data.userid + "-" + data.usercourse + "-" +  indx , scf.title);
        localStorage.setItem("msg1-"+ data.userid + "-" + data.usercourse + "-" +  indx , scf.content1);
        localStorage.setItem("msg2-"+ data.userid + "-" + data.usercourse + "-" +  indx , scf.content2);

        document.getElementById("timeup-pp").style.display = "block";
        document.getElementById("optionbox").style.display = "block";
        document.getElementById("checklist-button").style.display = "block";

        if (scf.language == "de"){
            document.getElementById("checklist-button").innerHTML = "Checkliste erstellen &nbsp;&nbsp; &#8680; ";
            document.getElementById("ToDo-close-button").innerHTML = "Schließen und fortfahren";
        }else if (scf.language == "nl"){
            document.getElementById("ToDo-close-button").innerHTML = "Sluiten en doorgaan";
            document.getElementById("checklist-button").innerHTML = "Maak checklist &nbsp;&nbsp; &#8680; "; 
        }else{
            document.getElementById("ToDo-close-button").innerHTML = "Close and continue";
            document.getElementById("checklist-button").innerHTML = "Create Checklist &nbsp;&nbsp; &#8680; ";
        } 



        document.getElementById("opt_" + 1).style.display = "block";
        document.getElementById("opt_" + 1).innerHTML = scf.option1_text;
        //console.log(scf)

        for (var i = 1; i < 4; i++){
            document.getElementById("opt_" + i).style.display = "block";
        }


        if (scf.option1_short.trim() != ""){
            document.getElementById("opt_" + 1).setAttribute("data-tooltip", scf.option1_short);
            document.getElementById("opt_" + 1).setAttribute("data-tooltip-position", "top");
        }
        if ((data.userclass == "Personalised") && ( Number(scf.option1_enabled) != 1)){
            //document.getElementById("opt_" + 1).style.textDecoration = "line-through";
            // document.getElementById("opt_" + 1).style.pointerEvents = "none";
            // document.getElementById("opt_" + 1).style.color = "#d0caca";
            // document.getElementById("opt_" + 1).style.backgroundColor = "#f1eded";
            document.getElementById("opt_" + 1).style.display = "none";
        }

        document.getElementById("opt_" + 2).style.display = "block";
        document.getElementById("opt_" + 2).innerHTML = scf.option2_text;
        if (scf.option1_short.trim() != ""){
            document.getElementById("opt_" + 2).setAttribute("data-tooltip", scf.option2_short);
            document.getElementById("opt_" + 2).setAttribute("data-tooltip-position", "top");
        }
        if ((data.userclass == "Personalised") && (Number(scf.option2_enabled) != 1)){
           // document.getElementById("opt_" + 2).style.textDecoration = "line-through";
            // document.getElementById("opt_" + 2).style.pointerEvents = "none";
            // document.getElementById("opt_" + 2).style.color = "#d0caca";
            // document.getElementById("opt_" + 2).style.backgroundColor = "#f1eded";
            document.getElementById("opt_" + 2).style.display = "none";
        }

        document.getElementById("opt_" + 3).style.display = "block";
        document.getElementById("opt_" + 3).innerHTML = scf.option3_text;
        if (scf.option1_short.trim() != ""){
            document.getElementById("opt_" + 3).setAttribute("data-tooltip", scf.option3_short);
            document.getElementById("opt_" + 3).setAttribute("data-tooltip-position", "top");
        }
        if ((data.userclass == "Personalised") && (Number(scf.option3_enabled) != 1)){
           // document.getElementById("opt_" + 3).style.textDecoration = "line-through";
            // document.getElementById("opt_" + 3).style.pointerEvents = "none";
            // document.getElementById("opt_" + 3).style.color = "#d0caca";
            // document.getElementById("opt_" + 3).style.backgroundColor = "#f1eded";
            document.getElementById("opt_" + 3).style.display = "none";
        }

       document.getElementById("opt_" + 4).style.display = "block";
        document.getElementById("opt_" + 4).innerHTML = scf.option4_text;
        if (scf.option1_short.trim() != ""){
            document.getElementById("opt_" + 4).setAttribute("data-tooltip", scf.option4_short);
            document.getElementById("opt_" + 4).setAttribute("data-tooltip-position", "top");
        }
        if ((data.userclass == "Personalised") && (Number(scf.option4_enabled) != 1)){
            //document.getElementById("opt_" + 4).style.textDecoration = "line-through";
            // document.getElementById("opt_" + 4).style.pointerEvents = "none";
            // document.getElementById("opt_" + 4).style.color = "#d0caca";
            // document.getElementById("opt_" + 4).style.backgroundColor = "#f1eded";
            document.getElementById("opt_" + 4).style.display = "none";
        }

    }
  }
}

function show_ToDo_List(){
    if((localStorage.getItem("activeScaffold") == null)  || (localStorage.getItem("activeScaffold") == "0")){
        if (data.userlang == 'DE'){
            alert ("Zurzeit gibt es keine Prompts.");
        }else{
            alert ("There are no current scaffolds!");
        }
    }else{
        //todo_curr = Number(localStorage.getItem("scaffoldID"));
        if (edit_mode == true){
            display_ToDo_List(todo_curr);
        }else{
            display_ToDo_List(Number(localStorage.getItem("activeScaffold")));
        }
        edit_mode = false;
        document.getElementById("myToDo").style.display = "block";
    }  
    ScaffoldLog("Scaffold","Show TODO List", todo_curr);  
}

function display_ToDo_List(n){
    todo_curr = n;
    if (document.getElementById("myToDo")){
        if (document.getElementById("myToDoheader")){
            if (n > 1){
                document.getElementById("prev-button").style.display = "block";     
            }else{
                document.getElementById("prev-button").style.display = "none"; 
            }
 	    var title = localStorage.getItem("title-" + data.userid + "-" + data.usercourse + "-" +  n);
            var m1 = localStorage.getItem("msg1-" + data.userid + "-" + data.usercourse + "-" +  n);
            var m2 = localStorage.getItem("msg2-" + data.userid + "-" + data.usercourse + "-" +  n);
       
	    document.getElementById("myToDomessage1").innerHTML = m1;
            document.getElementById("myToDomessage2").innerHTML = m2;
            document.getElementById("TODOscf_num").innerHTML = "  | Prompt :  " +  todo_curr +  " |" ;   // "  | " +  title +  " |";

            if (data.userlang == 'DE'){
               document.getElementById("to-do").innerHTML = "Checkliste";
               document.getElementById("ToDo-close-button").innerHTML = "Schließen und fortfahren";
            }else if((data.userlang == 'NL')){
               document.getElementById("to-do").innerHTML = "To-do lijst";
               document.getElementById("ToDo-close-button").innerHTML = "Sluiten en doorgaan";
            }else{
               document.getElementById("to-do").innerHTML = "To-Do List";
               document.getElementById("ToDo-close-button").innerHTML = "Close and continue";
            }
            LoadTaskList();

        /* disabling TO do List */
            if (Number(n) >= Number(localStorage.getItem("activeScaffold")) ){
                document.getElementById("next-button").style.display = "none";
                // document.getElementById("container").removeAttribute("style");
                document.getElementById("TODOscf_num").style.color = "#1d6515ba"; 
                Scaffold_LogData("Scaffold","CurrToDoList_Displayed", "sid:" + Number(n));
            }else{
                document.getElementById("next-button").style.display = "block";
                // document.getElementById("container").setAttribute("style", "pointer-events: none;opacity: 0.7;background: #CCC;");
                document.getElementById("TODOscf_num").style.color = "#797777d6";
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
    ScaffoldLog("Scaffold","Attaching Scaffold", localStorage.getItem("url"));  
      
    var text = '<meta charset="utf-8">';
     var text = '';
            //text +=  '<div class="pause-overlay closed" id="pause-overlay"></div>';
    text +=  '<div class="modal-overlay closed" id="modal-overlay"></div>';
    text +=  '<div class="_modal closed" id="_timesUp">';
    text +=  '<div style="height: 45px;background: #e6e8f1;"></div>';
    text +=  '<button class="close-button" id="TU-close-button"> X </button>';
    text +=  '<p id= "messageTimesUP" class = "message1" style="padding:20px;"> Time\'s Up!</p>';
    text +=  '<div id="timesupImg"> <img id = "srctimesupImg"  src = "https://datalogger.myannotator.com/Scaffolds/client/timesup.jpg" style = "display: block;margin-left: auto; margin-right: auto; height:150px;" /> </div>';
    text +=  '</div>';

    text +=  '<div class="_modal closed" id="_modal">';

    text +=  '<div style="height: 45px;background: #e6e8f1;"></div>';
    text +=  '<button class="close-button" id="close-button"> X </button>';
    text +=  '<div class="modal-guts">';
    text +=  '<p id= "message1" class = "message1"> M1</p>';
    text +=  '<img id= "thinkico" src="https://datalogger.myannotator.com/Scaffolds/client/list.svg" style="width: 40px;height: 40px;float:left; margin-right: 15px; margin-top: -3px;"/>';
    text +=  '<p id= "message2" class = "message2"> M2</p> ';
    text +=  '<p id = "timeup-pp" style="padding: 2px;display:none;"></p> ';   

    text +=  '<div id = "optionbox" style ="display:none;"><p id="id_options">';
    text +=  '<label><input type="checkbox" name="options" value="option1"><span id="opt_1" style ="display:none;">sun</span></label>';
    text +=  '<label><input type="checkbox" name="options" value="option2"><span id="opt_2" style ="display:none;">mon</span></label>';
    text +=  '<label><input type="checkbox" name="options" value="option3"><span id="opt_3" style ="display:none;">tue</span></label>';
    text +=  '<label><input type="checkbox" name="options" value="option4"><span id="opt_4" style ="display:none;">wed</span></label>';
    text +=  '</p> </div>';

    //text +=  '<div id="timesupImg"> <img src = "https://datalogger.myannotator.com/Scaffolds/client/timesup.jpg" style = "display: block;margin-left: auto; margin-right: auto; height:150px;" /> </div>';

    //text +=  '<button class="close-button" id="close-button"> Close and continue ... </button>';
    //text +=  '<button class="checklist-button" id="timeup-button" style ="display:none;" onclick=""> Proceed to next task &nbsp;&nbsp; &#8680; </button>';
    text +=  '<button class="checklist-button" id="checklist-button" style ="display:none;"> Create Checklist &nbsp;&nbsp; &#8680; </button>';
          
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
    text +=  '<h1> <b id = "to-do">My To-do List </b><i class="fa fa-edit" style="float: right;line-height: 1;"> </i> <!-- <i class="fa fa-plus"> </i> --> </h1>';
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
    text += '<div onclick="Scaffold_LogData(\'Scaffold\',\'Notification_Clicked\', \'sid:\' + (Number(localStorage.getItem(\'scaffoldID\'))) ); localStorage.setItem(\'endTime\',+new Date); ">';
    text += '<div class="promo-launcher-button">';
    text += '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 458 458" style="enable-background:new 0 0 458 458;" xml:space="preserve">';
    text += '<g><g><g>';
    text += '<path d="M428,41.533H30c-16.568,0-30,13.432-30,30v252c0,16.568,13.432,30,30,30h132.1l43.942,52.243 c5.7,6.777,14.103,10.69,22.959,10.69c8.856,0,17.259-3.912,22.959-10.689l43.942-52.243H428c16.569,0,30-13.432,30-30v-252 C458,54.965,444.569,41.533,428,41.533z M428,323.533H281.933L229,386.465l-52.932-62.932H30v-252h398V323.533z"/> <path d="M85.402,156.999h137c8.284,0,15-6.716,15-15s-6.716-15-15-15h-137c-8.284,0-15,6.716-15,15 S77.118,156.999,85.402,156.999z"/> <path d="M71,233.999c0,8.284,6.716,15,15,15h286c8.284,0,15-6.716,15-15s-6.716-15-15-15H86 C77.716,218.999,71,225.715,71,233.999z"/>';
    text += '</g></g></g> </svg></div>';
    text += '<div class="promo-launcher-badge">1</div></div></div></div>';

    $('body').prepend(text);
    $("#thinkico").attr('src', "https://datalogger.myannotator.com/Scaffolds/client/list.svg");

    // language support
    if (data.userlang == 'DE'){
        document.getElementById("messageTimesUP").innerHTML = "Die Zeit ist um!";
        document.getElementById("checklist-button").innerHTML = "Checkliste erstellen &nbsp;&nbsp; â‡¨  ";
        document.getElementById("ToDo-close-button").innerHTML = "Schließen und fortfahren";        
    }else if((data.userlang == 'NL')){
        document.getElementById("messageTimesUP").innerHTML = "De tijd is om!";
        document.getElementById("ToDo-close-button").innerHTML = "Sluiten en doorgaan";
        document.getElementById("checklist-button").innerHTML = "Maak checklist &nbsp;&nbsp; â‡¨  ";
    }else{
        document.getElementById("messageTimesUP").innerHTML = "Time's Up!";
        document.getElementById("ToDo-close-button").innerHTML = "Close and continue";
        document.getElementById("checklist-button").innerHTML = "Create Checklist &nbsp;&nbsp; â‡¨  ";
    }

    var mod = document.querySelector("#_modal");
    var modOverlay = document.querySelector("#modal-overlay");
    var closeButton = document.querySelector("#close-button");
    var ToDocloseButton = document.querySelector("#ToDo-close-button");
    var checklistButton = document.querySelector("#checklist-button");
    var prevButton = document.querySelector("#prev-button");
    var nextButton = document.querySelector("#next-button");
    var TUmod = document.querySelector("#_timesUp");
    var TUcloseButton = document.querySelector("#TU-close-button");

    TUcloseButton.addEventListener("click", function() {
        TUmod.classList.add("closed");
        modOverlay.classList.add("closed");
        Scaffold_LogData("Scaffold","TimeUP_Closed", "closed"); 
    });

    closeButton.addEventListener("click", function() {
        mod.classList.add("closed");
        modOverlay.classList.add("closed");
        Scaffold_LogData("Scaffold","Message_Closed", "sid:" + todo_curr); 
        edit_mode = false;
    });

    $('input[name=options]' ).click(function() {
        var $boxes = $('input[name=options]:checked');       
        if ($boxes.length > 3){
            var str = this.value;
            var i = Number(str.charAt(str.length-1)) - 1;
            $('input[name=options]')[i].click();
            return false;
            if (data.userlang == 'DE'){
                alert ("Wühlen Sie maximal 3 Optionen aus.");
            }else if (data.userlang == 'NL'){
                alert ("Selecteer maximaal 3 opties.");
            }else{
                alert ("Please select upto 3 options to create a checklist");
            }
        }else{
           var da = $(this).parents("label")[0].innerText;
           var str = this.value;
           var i = Number(str.charAt(str.length-1));
           if (this.checked){
                Scaffold_LogData("Scaffold","MessageOption_Checked", "sid:" + todo_curr + " opt: " + i + " - " + da);  
            }else{
                Scaffold_LogData("Scaffold","MessageOption_UnChecked", "sid:" + todo_curr + " opt: " + i + " - " + da);     
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
        var modalOverlay = document.querySelector("#modal-overlay"); 
            x_modal.classList.add("closed");
            modalOverlay.classList.add("closed");

        var _new = localStorage.getItem("tasks-"+ data.userid + "-" + id);
        if (old == null){
            Scaffold_LogData("Scaffold","CreateChecklist", "sid:" + todo_curr + " opts: " + _new);
            ScaffoldLog("Scaffold","CreateChecklist", "sid:" + todo_curr + " opts: " + _new);
        }else{
            Scaffold_LogData("Scaffold","CreateChecklist", "sid:" + todo_curr + " opts_old: " + old + " opts_new: " + _new);
            ScaffoldLog("Scaffold","CreateChecklist", "sid:" + todo_curr + " opts_old: " + old + " opts_new: " + _new);
        }


        show_ToDo_List();
      } else {
            if (data.userlang == 'DE'){
                alert ("Wählen Sie maximal 3 Optionen aus.");
            }else if (data.userlang == 'NL'){
                alert ("Selecteer maximaal 3 opties.");
            }else{
                alert ("Please select upto 3 options to create a checklist");
            }
      }
    });


    ToDocloseButton.addEventListener("click", function() {
      document.getElementById("myToDo").style.display = "none";
      Scaffold_LogData("Scaffold","ToDoList_Closed", "sid:" + Number(todo_curr));
     });

    prevButton.addEventListener("click", function() {
        if (todo_curr > 1){
            Scaffold_LogData("Scaffold","ToDoList_ScaffoldClosed", "sid:" + Number(todo_curr));
            display_ToDo_List(--todo_curr);
        }
    });

    nextButton.addEventListener("click", function() {
            Scaffold_LogData("Scaffold","ToDoList_ScaffoldClosed", "sid:" + Number(todo_curr));
            display_ToDo_List(++todo_curr);
    });

    /* TO DO List Function */

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
                    
                    if (Number(todo_curr) >= Number(localStorage.getItem("scaffoldID"))) {
                        Scaffold_LogData("Scaffold","CurrToDoListItem_ClickedLink", "sid:" + Number(todo_curr) + " option:" + $thatItem[0].innerText + " >> " + event.target.getAttribute("s_action") );
                    }else{
                        Scaffold_LogData("Scaffold","PrevToDoListItem_ClickedLink", "sid:" + Number(todo_curr) + " option:" + $thatItem[0].innerText  + " >> " + event.target.getAttribute("s_action") );
                     }
                     processAction(event.target.getAttribute("s_action"));
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
    if (true){
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

            console.log("Time to Show Scaffold Again!");
            var $boxes = $('input[name=options]:checked');
            $boxes.each( function(ind, elem){
                var str = elem.value;
                var i = Number(str.charAt(str.length-1)) - 1;
                $('input[name=options]')[i].click();
            });

            var d = JSON.parse(localStorage.getItem("scaffold-" + data.userid + "-"  + data.usercourse + "-"  + todo_curr )) ;

            var $boxes = $('input[name=options]'); 

                $boxes.each( function(ind, elem){
                    var str = elem.value;
                    var i = Number(str.charAt(str.length-1));

                       var txt = lnk = "";
            
                        if (i == 1){
                            txt = d.option1_text;
                            lnk = d.option1_link;
                        }else if (i == 2){
                            txt = d.option2_text;
                            lnk = d.option2_link;
                        }else if (i == 3){
                            txt = d.option3_text;
                            lnk = d.option3_link;
                        }else if (i == 4){
                            txt = d.option4_text;
                            lnk = d.option4_link;
                        }
        //console.log(txt + " = " + " : " + i)
                        var localTasks = JSON.parse(localStorage.getItem("tasks-"+ data.userid + "-" +todo_curr));

                        $.each(localTasks, function(task, status) {
                            
                            if (task == txt){
                                $('input[name=options]')[i - 1].click();
                            }
                        });
                });
        
            $('#ToDo-close-button').click();
            if (Number(todo_curr) >= Number(localStorage.getItem("scaffoldID"))){
                Scaffold_LogData("Scaffold","CurrToDoList_Edit", "sid:" + Number(todo_curr));
            }else{
                Scaffold_LogData("Scaffold","PrevToDoList_Edit", "sid:" + Number(todo_curr));
            }
            show_Scaffold(JSON.parse(localStorage.getItem("scaffold-"+ data.userid + "-" + data.usercourse + "-" + todo_curr)));
        }else{
            alert ("An error has been encountered loading this scaffold!")
            edit_mode = false;
        }
    });

    if($("#sortable").length > 0){
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

    if (localStorage.getItem("scaffolds") == "enabled"){
    if(document.getElementsByClassName("annotator-frame-button")[2]) {
            document.getElementsByClassName("annotator-frame-button")[2].style.display = "none";
            document.getElementById("scaffold_badge").style.top ="88px";  
        }
    }

    ScaffoldLog("Scaffold","Finished Attaching Scaffold", localStorage.getItem("url"));  
}

    function processAction(txt) {
        var code = txt.trim().charAt(0);
        console.log(txt);
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
            //console.log(action)
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
      

var localTasks = JSON.parse(localStorage.getItem("scaffold-" + data.userid + "-"  + data.usercourse + "-"  + todo_curr )) ;
var txt = ttip = uri = "";
$.each(localTasks, function(key, value) {
    if (value === s){
        arr = key.split('text');
        txt = value;
        ttip = localTasks[arr[0] + "short"];
        uri = localTasks[arr[0] + "link"];
    }
});



            if (txt === s){
                if (ttip.trim() != ""){
                    var $wrap = $("<span id ='link' data-tooltip-position='top' data-tooltip='" + ttip+ "'>").appendTo($li);
                }else{
                     var $wrap = $("<span>").appendTo($li);
                }

                if (uri != ""){
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



                            
            }
    
        $li.appendTo($t);
        //$t.sortable("refresh"); //S.S October 10
        $t.sortable({ refresh: $t})
    }


    function LoadTaskList() {
        console.log("Loading TO-DO Task List")
        document.getElementById("emptyListText").style.display = "none";
        if (typeof(Storage) !== "undefined") {
            // Code for localStorage/localStorage.
            if (localStorage.getItem("tasks-"+ data.userid + "-" + todo_curr) !== "undefined") {
              var localTasks = JSON.parse(localStorage.getItem("tasks-"+ data.userid + "-" +todo_curr));
              // Grab stored tasks
              $("#sortable").find("li").remove();
              $.each(localTasks, function(task, status) {
                addListItem($("#sortable"), task, status);
              });
            }

            if (localStorage.getItem("tasks-" + data.userid + "-" + todo_curr) == null){
                if (data.userlang == 'DE'){
                    document.getElementById("emptyListText").innerHTML = "Keiner";
                }else if((data.userlang == 'NL')){
                    document.getElementById("emptyListText").innerHTML = "Geen";
                }else{
                    document.getElementById("emptyListText").innerHTML = "None";
                }
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
            localStorage.setItem("tasks-"+ data.userid + "-" + todo_curr , JSON.stringify(tasks));
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
            var d = JSON.parse(localStorage.getItem("scaffold-" + data.userid + "-"  + data.usercourse + "-"  + id )) ;

            var txt = lnk = "";
            
            if (i == 1){
                txt = d.option1_text;
                lnk = d.option1_link;
            }else if (i == 2){
                txt = d.option2_text;
                lnk = d.option2_link;
            }else if (i == 3){
                txt = d.option3_text;
                lnk = d.option3_link;
            }else if (i == 4){
                txt = d.option4_text;
                lnk = d.option4_link;
            }

            var task = txt.toString();
            tasks[task] = false;
            if (typeof(Storage) !== "undefined") {
                localStorage.setItem("tasks-"+ data.userid + "-" + id , JSON.stringify(tasks));
            }
        });

    }

function Scaffold_LogData (action, sub_action, value){
    logData(action, localStorage.getItem("uid"),sub_action, value);
}


function sPostAjax(url, data, success) {
    let params = typeof data == 'string' ? data : Object.keys(data).map(
            function(k){ return encodeURIComponent(k) + '=' + encodeURIComponent(data[k]) }
        ).join('&');

    var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
    xhr.open('POST', url);
    xhr.onreadystatechange = function() {
        if (xhr.readyState>3 && xhr.status==200) { success(xhr.responseText); }
    };
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(params);
    return xhr;
  }


function ScaffoldLog(src,type, value){
    // set values as per the parameters
    var user = localStorage.getItem("uid");

    if (localStorage.getItem("u_course") != "course-2"){
      return;
    }

    dataLOG.action = "SCAFFOLD_LOG"; //src;
    dataLOG.sub_action = type;
    dataLOG.value = value;
    
    let now = new Date();
    dataLOG.url = localStorage.getItem("url");
    
    dataLOG.timestamp = now.toLocaleString();
    dataLOG.session_start = localStorage.getItem("ESSAY_START");
    dataLOG.time_lapsed =  Math.floor( (now.getTime() - parseInt(localStorage.getItem("ESSAY_START"))));
 
    if(document.getElementById("userid")){
          dataLOG.uid = document.getElementById("userid").innerHTML;
          dataLOG.username = document.getElementById("username").innerHTML;
    }else{
        if(localStorage.getItem("uid") != null){
          dataLOG.uid = localStorage.getItem("uid");
          dataLOG.username = localStorage.getItem("username");
        }
    }

    if(document.getElementById("logServer")){
        dataLOG.logServer = document.getElementById("logServer").innerHTML;
    }

    if (dataLOG.uid !== ""){
      
        var dt = dataLOG;
        dt.username = localStorage.getItem("username");
        dt.essay = localStorage.getItem("essay");
        postAjax('https://floralearn.org/log_to_db.php', 'source=' + 'flora&action=' + dt.action +  '&uid=' + dt.uid + '&logServer=' + dt.logServer + '&sub_action=' + dt.sub_action + '&session_start=' + dt.session_start + '&time_lapsed=' + dt.time_lapsed +  '&timestamp=' + dt.timestamp + '&essay=' + encodeURIComponent(dt.essay) + '&userid=' + dt.username + '&value=' + encodeURIComponent(dt.value) + '&data=' + encodeURIComponent(JSON.stringify(dt)) + '&url=' + encodeURIComponent(dt.url), function (_dt) { console.log(_dt); }); 
    }
}


// hack to fix late loading of scaffold icon.
document.onmousemove = function(){
    if (localStorage.getItem("scaffolds") == "enabled"){
        if(document.getElementsByClassName("annotator-frame-button")[2]) {
            if (document.getElementById("scaffold_badge").style.top != "88px"){
                document.getElementsByClassName("annotator-frame-button")[2].style.display = "none";
                document.getElementById("scaffold_badge").style.top ="88px"; 
            } 
        }
    }
};


