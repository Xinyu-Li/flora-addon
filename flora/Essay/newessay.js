// Language support

var nl = {"essay": "Essay", "autosaved": "automatisch opgeslagen", "count": "Tellen", "words_remaining": "Resterende woorden", "words": "woorden", "submit_essay" : "Essay indienen als eindversie"};
var de = {"essay": "Aufsatz", "autosaved": "automatisch gespeichert", "count": "Anzahl", "words_remaining": "verbleibende Worte", "words": "Worte"};

// Think we need to be a SCORM module to make this work?
function findLMSAPI(win) {
  // look in this window
  if (win.hasOwnProperty("GetStudentID")) return win;

  // all done if no parent
  else if (win.parent == win) return null;

  // climb up to parent window & look there
  else return findLMSAPI(win.parent);
}

function getUserName() {
  var newName="";
  var lmsAPI = findLMSAPI(this);
  if (lmsAPI) {
    var myName = lmsAPI.GetStudentName();
    var array = myName.split(',');
    var newName = array[1];
  }
  return newName
}

$(document).ready(function() {

$( "#submit_essay" ).click(function() {
console.log("Redirect");

var lang = localStorage.getItem("moodle-lang");
var save_msg = "Essay Saved";
if (lang==="nl") { save_msg = "Essay opgeslagen"; }
if (lang==="de") { save_msg = "Aufsatz gespeichert"; }


alert(save_msg );
// temporary saving removal
//top.window.location.href="https://www.floraproject.org/moodle/mod/feedback/complete.php?id=46";

});

 $("#word_count").on('keydown', function(event) {
localStorage.setItem('essay',document.getElementById("word_count").value);
localStorage.setItem('essay_snapshot', document.getElementById("word_count").value);

    /*
      var snapshot = "";
    if (document.getElementById("word_count")){
    snapshot = document.getElementById("word_count").value;
    }*/

  var event_report = event.key;
  logData("ESSAY", new URLSearchParams(window.location.search).get('uid'),"TYPE",event_report);
  //logData("ESSAY", new URLSearchParams(window.location.search).get('uid'),"SNAPSHOT",snapshot);

  var words = 0;
  if (this.value.match(/\S+/g) != null){
    words = this.value.match(/\S+/g).length;
  }
	
	$('#display_count').text(words);
	$('#word_left').text(essayLength-words);


        
	//if (event.code == 'KeyZ' && (event.ctrlKey || event.metaKey)) { // doesn't always work & what about re-do
	//  event_report='Undo';
	//}
	const queryString = window.location.search;
	//console.log(queryString);
	const urlParams = new URLSearchParams(queryString);
	const uid = urlParams.get('uid');
	//const lang = urlParams.get('lang');
  //console.log("typing")

	//logData((new URLSearchParams(window.location.search)).get('uid'),"essay_type",event_report);
});

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);



//setInterval(function() {
let essaytextarea = document.getElementById('word_count');

    var ctrlDown = false,
        ctrlKey = 17,
        cmdKey = 91,
        vKey = 86,
        cKey = 67;


essaytextarea.addEventListener('keydown', (e) => {

localStorage.setItem('essay',document.getElementById("word_count").value);
localStorage.setItem('essay_snapshot', document.getElementById("word_count").value);

var sub_action  = "KEYDOWN";
var val = e.key;

 $(document).keydown(function(e) {
        if (e.keyCode == ctrlKey || e.keyCode == cmdKey) ctrlDown = true;
    }).keyup(function(e) {
        if (e.keyCode == ctrlKey || e.keyCode == cmdKey) ctrlDown = false;
    });

if (ctrlDown && (e.keyCode == cKey)) val  = "Ctrl+C";
if (ctrlDown && (e.keyCode == vKey)) val  = "Ctrl+V";


var snap = document.getElementById('word_count').value;
var snapshot = val.concat( " | ", snap);
//logData("ESSAY", new URLSearchParams(window.location.search).get('uid'),sub_action,snapshot);

});

essaytextarea.addEventListener('paste', (event) => {
localStorage.setItem('essay',document.getElementById("word_count").value);
localStorage.setItem('essay_snapshot', document.getElementById("word_count").value);
	var sub_action  = "PASTETEXT";
    let paste = (event.clipboardData || window.clipboardData).getData('text');
	logData("ESSAY", new URLSearchParams(window.location.search).get('uid'),sub_action,paste);
	var snap = document.getElementById('word_count').value;
	var snapshot = paste.concat( " | ", snap);
	logData("ESSAY", new URLSearchParams(window.location.search).get('uid'),"AFTER PASTE",snapshot); 
});
});

$(window).on('load', function () {

    var essval_store = localStorage.getItem('essay');
    if (essval_store===null) { essval_store=""; }
    //alert("No "+document.getElementById("word_count"));
    var txtarea=document.getElementById("word_count");
    txtarea.value=essval_store
    var words = 0;
    if (localStorage.getItem('essay') != null){ 

    	if (essval_store.match(/\S+/g) != null){
                words = essval_store.match(/\S+/g).length;
        }
    }
    $('#display_count').text(words);
    $('#word_left').text(essayLength-words);


console.log("Loading Essay Language Support Module ...");

const lang = localStorage.getItem("moodle-lang");
var replacements=null;
if (lang==="nl") { replacements=nl; }
if (lang==="de") { replacements=de; }
    if (replacements!=null) {
	     for (var key in replacements) {
	       $('.'+key).text(replacements[key]);
	     }
    }
    
});

$(document).on('blur', 'textarea[name*=\'txtScript\']', function () {
    var value = jQuery(this).val();
    localStorage.setItem('essay',value)
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    var user = urlParams.get('uid');
    logData("ESSAY", user,"SAVE",document.getElementById("word_count").value);
    // alert(value);
});

$(document).on('focus', 'textarea[name*=\'txtScript\']', function () {
    logData("ESSAY", new URLSearchParams(window.location.search).get('uid'),"FOCUS", "");
});










