// Please edit this configuration to meet your requirements
/* ==========  Beginning of Configurations  ========== */

var webServerUrl = window.location.protocol + "//" + window.location.host + "/"; 
var task_course_id = localStorage.getItem('task_course_id');
var training_task_id = localStorage.getItem('training_task_id');
/* ==========  End of Configurations  ========== */


var base =  webServerUrl + '/flora/scaffolds/'; // location folder for scaffolds
var headID = document.getElementsByTagName('head')[0];
var link = document.createElement('link');

link.type = 'text/css';
link.rel = 'stylesheet';
headID.appendChild(link);
link.href = '/flora/css/style.css';
var sc = document.createElement("script");
sc.setAttribute("src", "/flora/js/jquery.min.js");
sc.setAttribute("type", "text/javascript");
document.head.appendChild(sc);
var arrClasses = [];

var js = document.createElement("script");
js.setAttribute("src", "/flora/js/jstorage.min.js");
js.setAttribute("type", "text/javascript");
document.head.appendChild(js);


let LOG = {
  source : "flora",
  url: window.location.href,
  uid: "-99",
  username: "FullName",
  action : "eg. Page",
  sub_action: "eg. View",
  value: "value or json",
  session_start: "null",
  timestamp: "DATE TIME",
  logServer: "URL_OF_LOGSERVER"
};

var  course;
var  udata;

$("body[class*='course-']").removeClass(function () { // Select the element divs which has class that starts with some-class-   $("body[class*='course-']")[0].className.match(/course-\d+/)[0];

        var className = this.className.match(/course-\d+/); //get a match to match the pattern some-class-somenumber and extract that classname
        if (className) {
            arrClasses.push(className[0]); //if it is the one then push it to array
            course = arrClasses[0];
        return "xyzy";
           // return className[0]; //return it for removal
        }
      });

if (course == task_course_id){
localStorage.setItem('TASK_DURATION', localStorage.getItem('mainTask'));
}else if (course == training_task_id){
localStorage.setItem('TASK_DURATION', localStorage.getItem('trainingTask'));
}

var TASK_DURATION = parseInt(localStorage.getItem('TASK_DURATION')); //60

var essayLength = parseInt(localStorage.getItem('essayLen')) ; //400;
var essayWidth="75%";
var plannerWidth="75%";
var clockWidth= "540px";

var now = new Date();

function createCORSRequest(method, url) {
    var xhr = new XMLHttpRequest();
    if ("withCredentials" in xhr) {
      xhr.open(method, url, true);
      xhr.setRequestHeader('Content-Type', 'application/json');
    } else if (typeof XDomainRequest != "undefined") {
      xhr = new XDomainRequest();
      xhr.open(method, url);

    } else {
      // Otherwise, CORS is not supported by the browser.
      xhr = null;

    }
    return xhr;
  }

function essayOpenNav(uid,language,url) {
    var baseurl="/flora/Essay/newessay.html";
    if (url) { baseurl=url; }
    if (document.getElementById("myEssay").style.width === essayWidth) {
        essayCloseNav(uid);
    } else {
        if (document.getElementById("myPlan")){
        if (document.getElementById("myPlan").style.width === plannerWidth) {
            plannerCloseNav(uid);
        }
        }
        logData("ESSAY",uid,"OPEN","")
        document.getElementById("essayframe").src=baseurl+"?uid="+uid+"&lang="+language;
        document.getElementById("myEssay").style.width = essayWidth;
        document.getElementById("myEssay").style.overflow="hidden";
    }
}

function essayCloseNav(uid) {
    logData("ESSAY",uid,"CLOSE","")
    document.getElementById("myEssay").style.width = "0%";
}

function clockOpenNav(uid,minutes,url) {

    var baseurl="/flora/Timer/inmins.html";
    if (url) { baseurl=url; }
    if (document.getElementById("myClock")) {

     if (document.getElementById("myClock").style.width !== clockWidth){
        if (document.getElementById("myPlan").style.width === plannerWidth) {
            plannerCloseNav(uid);
        }
        logData("TIMER",uid,"OPEN","")
        document.getElementById("clockframe").src=baseurl+"?uid="+uid+"&mins="+minutes;
        document.getElementById("myClock").style.width = clockWidth;
      }
    }
 
}
function clockCloseNav(uid) {
    logData("TIMER", uid,"CLOSE","")
    document.getElementById("myClock").style.width = "0%";
}

function plannerOpenNav(uid,language,url) {
    var baseurl="/flora/Planner/planner_tool.html";
    if (url) { baseurl=url; }
    if (document.getElementById("myPlan").style.width === plannerWidth) {
        plannerCloseNav(uid);
    } else {
        if (document.getElementById("myEssay").style.width === essayWidth) {
            essayCloseNav(uid);
        }
        logData("PLANNER",uid,"OPEN","")
        document.getElementById("planframe").src=baseurl+"?uid="+uid+"&lang="+language;
        document.getElementById("myPlan").style.width = plannerWidth;
        document.getElementById("planframe").focus();
    }
}

function plannerCloseNav(uid) {
    logData("PLANNER", uid,"CLOSE","")
    document.getElementById("myPlan").style.width = "0%";
}


var qinput = document.querySelector('input[name="q"]');
if (qinput){
qinput.addEventListener('keyup', function(event){
 if (event.keyCode === 13) {
      logData("CONTENT" , udata.uid, "SEARCH" , qinput.value );
 }
});
}

window.addEventListener('load', (event) => {

   udata = {
          uid: document.getElementById("userid") ? document.getElementById("userid").innerHTML : "",
          username: document.getElementById("username") ? document.getElementById("username").innerHTML : "",
          userclass: document.getElementById("userclass") ? document.getElementById("userclass").innerHTML : localStorage.getItem("userclass"),
          userlang : document.getElementById("userlang") ? document.getElementById("userlang").innerHTML.toUpperCase() : "",
          planner : document.getElementById("planner") ? document.getElementById("planner").innerHTML : "",
          essay : document.getElementById("essay") ? document.getElementById("essay").innerHTML : "",
          scaffolds : document.getElementById("scaffolds") ? document.getElementById("scaffolds").innerHTML : "",
          timer : document.getElementById("timer") ?document.getElementById("timer").innerHTML : ""
   };

 udata.courseid = course;

    if(document.getElementById("username")){
      localStorage.setItem("uid", udata.uid);
      localStorage.setItem("username", udata.username);
      localStorage.setItem("u_course", udata.courseid);
      localStorage.setItem("url",window.location.href);
    if (localStorage.getItem("ESSAY_START") != null){
      logData("PAGE" , udata.username, "LOAD" , window.location.href);
    }
 
    }

var el = document.createElement("div");
var text = '';
if (udata.essay == "enabled"){
  gDBQuery('GET_USER_PREVIOUS_ESSAY', localStorage.getItem('uid'));
  text += '<div id="myEssay" class="essayOverlay"  style="overflow: hidden; "><a href="javascript:void(0)" class="closebtn" onclick="essayCloseNav(' + udata.uid + ')">&times;</a><div class="essay-overlay-content"> <h3 class="essay_tool">Essay Writing Tool </h3><iframe id="essayframe" width="80%" height="475px" src="/flora/Essay/newessay.html"></iframe></div></div>';
  text +=  '<button id="myEssayTool" class="leessay" onclick="essayOpenNav(' + udata.uid + ',\'' + udata.userlang+ '\')" style=" background-color: #fff;border: solid 1px #dbdbdb; position: fixed; font: 13px / 20px Arial; color: rgb(255, 255, 255); cursor: pointer; z-index: 9991; padding: 1px 1px 1px 1px; box-shadow: rgb(0, 0, 0) 0px 0px 1px; right: 4px; top: 125px;  border-radius: 4px; display: block; height: 28px; width: 28px;"  title="Write Essay">  <img id= "essaylogo" src="/flora/img/essay.svg" style="width: 20px;"/> </button>';
}

if (udata.timer == "enabled"){
    gDBQuery('GET_USER_PREVIOUS_SESSION', localStorage.getItem('uid'));
    text +=  '<button id="myTimer" class="letime" onclick="if (document.getElementById(\'myClock\').style.width !== \'' + clockWidth + '\') {clockOpenNav(' + udata.uid + ',' + TASK_DURATION + '); setTimeout(function () { clockCloseNav(' + udata.uid  + ');} , 3000); } " style=" background-color: #fff;border: solid 1px #dbdbdb; position: fixed; font: 13px / 20px Arial; color: rgb(255, 255, 255); cursor: pointer; z-index: 9991; padding: 1px 1px 1px 1px; box-shadow: rgb(0, 0, 0) 0px 0px 1px; right: 4px; top: 165px;  border-radius: 4px; display: block; height: 28px; width: 28px;"  title="Show Remaining Time">  <img id= "timerlogo" src="/flora/img/timer.svg" style="width: 20px;"/> </button>';
    text +=  '<div id="myClock" class="clockOverlay" style ="padding-left: 0px; width:0%;"><div class="clock-overlay-content"><iframe width="500px" height="170px" id="clockframe" src="/flora/Timer/inmins.html?uid='+ udata.uid + '&mins=' + TASK_DURATION +' "></iframe></div></div>'; 
}


if (udata.planner == "enabled"){
  text += '<div id="myPlan" class="planOverlay"><a href="javascript:void(0)" class="closebtn" onclick="plannerCloseNav(' + udata.uid + ')">&times;</a><div class="plan-overlay-content"> <h3 class="planner_tool">Planner Tool </h3><iframe width="750px" height="340px" id="planframe" src="/flora/Planner/planner_tool.html"></iframe></div></div>';
  text +=  '<button id="myPlan" onclick="plannerOpenNav(' + udata.uid + ',\'' + udata.userlang+ '\')" class="leplan"  style=" background-color: #fff;border: solid 1px #dbdbdb; position: fixed; font: 13px / 20px Arial; color: rgb(255, 255, 255); cursor: pointer; z-index: 9991; padding: 1px 1px 1px 1px; box-shadow: rgb(0, 0, 0) 0px 0px 1px; right: 4px; top: 205px;  border-radius: 4px; display: block; height: 28px; width: 28px;" status ="close" title="Show Planner">  <img id= "planlogo" src="/flora/img/plan.svg" style="width: 20px;"/> </button>';
}
el.innerHTML = text;
if (document.body){
  document.body.prepend(el);
}

    if (localStorage.getItem("moodle-lang") == 'nl'){
        $('.essay_tool').text("Essay-tool");
        $('.planner_tool').text("Planner-tool");
    }

    if (localStorage.getItem("moodle-lang") == 'de'){
        $('.essay_tool').text("Aufsatz");
        $('.planner_tool').text("Planer"); 
    }

if ((udata.scaffolds == "enabled") && (course == task_course_id)){

    if (scaffold_option = "IMPROVED"){
        var script = document.createElement('script');
        script.type = 'text/javascript';
        base =  webServerUrl + 'flora/scaffolds_imp/';
        script.src = base + './scaffolds.js';
        document.body.append(script);
    }else{
        var script = document.createElement('script');
        script.type = 'text/javascript';
        base =  webServerUrl + 'flora/scaffolds/';
        script.src = base + './scaffolds.js';
        document.body.append(script);
    }
}


$("#id_textarea_39").on('keydown', function(event) {
//localStorage.setItem('essay',document.getElementById("id_textarea_39").value);
//localStorage.setItem('essay_snapshot', document.getElementById("id_textarea_39").value);
  var event_report = event.key;
  logData("ESSAY", localStorage.getItem("uid"),"TYPE",event_report);

});

$("#id_textarea_39").on('input', function(event) {
localStorage.setItem('essay',document.getElementById("id_textarea_39").value);
localStorage.setItem('essay_snapshot', document.getElementById("id_textarea_39").value);
//console.log("input");

});
  });

function postAjax(url, data, success) {
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

function logData(src, user, type, value){
    // set values as per the parameters

    if (localStorage.getItem("u_course") != task_course_id){
      return;
    }

    LOG.action = src;
    LOG.sub_action = type;
    LOG.value = value;
    let now = new Date();
    LOG.url = localStorage.getItem("url");
    //LOG.timestamp = sec2time(remaining_time);
    LOG.timestamp = now.toLocaleString('EN');
    LOG.session_start = localStorage.getItem("ESSAY_START");
    LOG.time_lapsed =  Math.floor( (now.getTime() - parseInt(localStorage.getItem("ESSAY_START"))));
 
    if(document.getElementById("userid")){
          LOG.uid = document.getElementById("userid").innerHTML;
          LOG.username = document.getElementById("username").innerHTML;
    }else{
        if(localStorage.getItem("uid") != null){
          LOG.uid = localStorage.getItem("uid");
          LOG.username = localStorage.getItem("username");
        }
    }
    if(document.getElementById("logServer")){
        LOG.logServer = document.getElementById("logServer").innerHTML;
    }
    if (LOG.uid !== ""){
        var dt = LOG;
        dt.username = localStorage.getItem("username");
        dt.essay = localStorage.getItem("essay");
        //console.log(dt);
        postAjax( webServerUrl + 'log_to_db.php', 'source=' + 'flora&action=' + dt.action +  '&uid=' + dt.uid + '&logServer=' + dt.logServer + '&sub_action=' + dt.sub_action + '&session_start=' + dt.session_start + '&time_lapsed=' + dt.time_lapsed +  '&timestamp=' + dt.timestamp + '&essay=' + encodeURIComponent(dt.essay) + '&userid=' + dt.username + '&value=' + encodeURIComponent(dt.value) + '&data=' + encodeURIComponent(JSON.stringify(dt)) + '&url=' + encodeURIComponent(dt.url), function (_dt) { console.log(); }); 

      }else{
        // console.log("Data for 'anonymous' user will not be logged in DB");
      }
    // checking breakpoint
    if ((src == "TIMER") || (src == "PLANNER")) {
       localStorage.setItem("BreakPointDelay", 0);
    }
  }

function setTimer(d){
    console.log("Userclass: " + localStorage.getItem("userclass"));
    if (d.length > 0){
        console.log("Existing Session");
        localStorage["ESSAY_START"] = parseInt(d[0].session_start);
    }else{
        console.log("New Session");
        var now = new Date();
        localStorage.setItem("ESSAY_START", now.getTime());
        localStorage.setItem("essay", "");
        localStorage.setItem("essay_snap", "");
        localStorage.setItem("planner_activities", "");
        logData("TIMER", localStorage.getItem("uid"),"ESSAY_TASK_START","00:00:00");
        setTimeout(function(){ logData("PAGE" , localStorage.getItem("uid"), "LOAD" , window.location.href); }, 1000);
    }
    console.log("Course#: " + course);
    if (course == training_task_id){
        var lapsed_time =  Math.floor((new Date().getTime() - parseInt(localStorage.getItem("ESSAY_START")))/1000);
        if (lapsed_time > (20 * 60)){
            var now = new Date();
            localStorage.setItem("ESSAY_START", now.getTime());
        }
    }

}

function setEssay(d){
  if (d.length > 0){
    // Disabled synchronization of essay for now
    //localStorage.setItem("essay", d[0].essay_snapshot);
    if (localStorage.getItem("essay")== null){
        localStorage.setItem("essay", d[0].essay_snapshot); 
    }
    localStorage.setItem("essay_snap", d[0].essay_snapshot);
    console.log("Previous Essay exists");
  }else{
    console.log("No Previous Essay");
    localStorage.setItem("essay", "");
    localStorage.setItem("essay_snap", "");
  }
}

function EndSession(d){
    if (d.length == 1){
    console.log("Ending Session");
        if (localStorage.getItem("ESSAY_START") != null){
            logData("TIMER", localStorage.getItem("uid"),"ESSAY_TASK_END","01:20:00");
        }
    }else{
        //console.log("Session has previously ended");
    }

}







  
