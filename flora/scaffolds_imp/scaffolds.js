var data;  
var arrClasses = [];       

var s = document.createElement("script"); 
s.src = webServerUrl + "flora/scaffolds_imp/jquery.min.js";
s.onload = function(e){ /* now that its loaded, do something */ 
      $('head').append('<link rel="stylesheet" type="text/css" href= "' + base + '/scaffolds.css">');
    $('head').append('<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">');

      /* Enabling Scaffolds from Generalized/Personalized cases */
      if (localStorage.getItem("userclass") != null) {
        var scaffoldType= localStorage.getItem("userclass");
        if ((scaffoldType == "Generalised") || (scaffoldType == "Personalised")){
            if (localStorage.getItem("scaffolds") == null){
              localStorage.removeItem("pendingScaffold");
              localStorage.removeItem("scaffoldID");
                  for (key in localStorage){
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
            } 

        var text = '';
        text +=  '<button id="scaffold_badge" style=" background-color: #fff;border: solid 1px #dbdbdb; position: fixed; font: 13px / 20px Arial; color: rgb(255, 255, 255); cursor: pointer; z-index: 9991; padding: 1px 1px 1px 1px; box-shadow: rgb(0, 0, 0) 0px 0px 1px; right: 4px; top: 89px;  border-radius: 4px; display: block; height: 28px; width: 28px;" status ="close" title="View Prompts">  <img id= "scaffoldlogo" src="https://floralearn.org/flora/scaffolds_imp/scaffold.svg" style="width: 16px;"/> </button>';
        text +=  '<div id="scaffold_injected" style="display:none; top: 330px; right: 55px; background-color: rgb(118, 118, 119); z-index: 99999; opacity: 1; position: fixed; padding: 10px; width: 295px; height: 125px;font-size: 13px; font-weight: normal; text-align: left; color: rgb(255, 255, 255); font-family: Arial, sans-serif; line-height: 20px; letter-spacing: normal; box-shadow: rgb(0, 0, 0) 0px 0px 8px; border-radius: 5px;">';
        text +=  "<div style='font: 14px / 16px Arial; text-align: right; font-weight: 900;' ><a href='#' onclick='$(\"#scaffold_injected\").hide();'>Close</a> </div>";
        text +=  '</div>';

      $('body').prepend(text);
      $("#scaffoldlogo").attr('src', base + "./scaffold.svg")

     
      var z = document.createElement("script"); 
        z.src = webServerUrl + "/flora/scaffolds_imp/jquery-ui.min.js"; 
        z.onload = function(e){ 
      };
      document.head.appendChild(z);

      var y = document.createElement("script"); 
        y.src = webServerUrl + "/flora/scaffolds_imp/scf_client.js";
        y.onload = function(e){ 
      };
      document.head.appendChild(y);
          
      $('#scaffold_badge').mousedown(function(event) {
          switch (event.which) {
              case 1:
                  if ((scaffoldType == "Generalised") || (scaffoldType == "Personalised")){
                      if (document.getElementById("myToDo").style.display == "none"){
                        show_ToDo_List();
                        LoadTaskList();
                      }else{
                        $("#ToDo-close-button").click();
                      }
                  }
                  break;
              default:
                  console.log('You have a strange Mouse!');
          }
      });

      // extract course from Moodle
      $("body[class*='course-']").removeClass(function () { // Select the element divs which has class that starts with some-class-   $("body[class*='course-']")[0].className.match(/course-\d+/)[0];
       // console.log(this)
          var className = this.className.match(/course-\d+/); //get a match to match the pattern some-class-somenumber and extract that classname
          if (className) {
              arrClasses.push(className[0]); //if it is the one then push it to array
              return className[0]; //return it for removal
          }
      });

      data = {
              uid: document.getElementById("userid").innerHTML,
              username: document.getElementById("username").innerHTML,
              userid: document.getElementById("username").innerHTML,
              userclass: localStorage.getItem("userclass"),
              userlang : localStorage.getItem("moodle-lang").toUpperCase(),
              usercourse : arrClasses[0]
        };

        localStorage.setItem("scaffolds", "enabled");

      }else{
          localStorage.setItem("scaffolds", "disabled");
      }
    }
  };  
  document.head.appendChild(s); 
