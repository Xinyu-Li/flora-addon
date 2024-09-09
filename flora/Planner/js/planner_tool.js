/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// example making a sizeable div
// https://medium.com/the-z/making-a-resizable-div-in-js-is-not-easy-as-you-think-bda19a1bc53d
// example drag and drop:
// https://www.w3schools.com/html/html5_draganddrop.asp

var dragged; // planner tool item element being dragged
var dragtarget; // drag target element (being dragged to)
var draggedFromOriginalContainer; // original container location for currently dragged item

var numMinutes = TASK_DURATION;
// ugly trick to prevent having to duplicate all timecolumn:nth-child(5n+1) CSS selectors 
// and just remove top, bottom and right attributes for last child element 
// Although there are offcourse numMinutes+1 Labels starting counting from 0.
var factor = (numMinutes > 60) ? 2 : 1;
var numEntries = Math.ceil(numMinutes/factor) + 1 ; 
var ActivitiesNL = ["Orientatie", "Doornemen Artificial Intelligence", "Doornemen scaffolding", "Doornemen differentiatie", "Schrijven essay", "[Vrije keuze]"]
var ActivitiesDE = ["Orientierung", "KI überprüfen", "Scaffolding überprüfen", "Differenzierung überprüfen", "Aufsatz schreiben", "[freie Wahl]"]
var ActivitiesEN = ["Orientation", "Review AI", "Review scaffolding", "Review differentiation", "Write essay", "[Free choice]"]

//window.onload = function () {
function init_plannertool(uid,lang) {

    // diamond squares on time axis
    var d1 = createElement("DIV", "class", "row");
    d1.style["margin-top"] = "15px";
    for (let n = 0; n < numEntries; n++) {
        var d2 = createElement("DIV", "class", "timecolumn");
        var d3 = createElement("DIV", "class", "tick bottom-right");
        d2.appendChild(d3);
        d1.appendChild(d2);
    }
    document.getElementById("plannertooltoptimeaxis").appendChild(d1);

    // target entries for dropping activity planner tool items
    var d1 = createElement("DIV", "class", "row", "id","targetrow");
    d1.style["margin-bottom"] = "15px";
    for (let n = 0; n < numEntries; n++) {
        var d2 = createElement("DIV", "class", "dropzone column", "data-starttime", n);
        d1.appendChild(d2);
    }
    document.getElementById("containerplannertooltarget").appendChild(d1);

    // create the 8 source activity planner tool items
    var d1 = createElement("DIV", "class", "row");
    d1.style["margin-top"] = "30px";
    if (lang==='de'){
    Activities = ActivitiesDE;}
    if (lang==='en'){
	Activities = ActivitiesEN;}
    if (lang==='nl'){
	Activities = ActivitiesNL;}
    if (lang==='zh-cn'){
    Activities = ActivitiesEN;}
    for (let n = 0; n < Activities.length; n++) {
        var item;
        let d2 = createElement("DIV", "class", "sourcedropzone column");
        // create/append planner tool activity draggable item with default 5 minute duration   
        if (n===(Activities.length-1))
            // the last one is editable (name can be customized by user)
            item = createPlannerToolItem(Activities[n], true);
        else
            item = createPlannerToolItem(Activities[n], false);
            
        d2.appendChild(item); // d2=container for activity planner tool item
        d1.appendChild(d2); // row with containers for actiivty planner tool items 
    }
    document.getElementById("containerplannertoolsource").appendChild(d1);

    // diamond squares on time axis
    var d1 = createElement("DIV", "class", "row");
    for (let n = 0; n < numEntries; n++) {
        var d2 = createElement("DIV", "class", "timecolumn");
        var d3 = createElement("DIV", "class", "tick bottom-right");
        d2.appendChild(d3);
        d1.appendChild(d2);
    }
    document.getElementById("plannertoolbottomtimeaxis").appendChild(d1);

    // 5 minute text labels
    var d1 = createElement("DIV", "class", "row");
    for (let n = 0; n < (numEntries * factor); n++) {
        var d2 = createElement("DIV", "class", "timecolumn");
        d2.style["border-style"] = "none";
        if (!n || !(n % (5* factor)))
            d2.innerHTML = n.toString();
        d1.appendChild(d2);
    }
    document.getElementById("plannertoolbottomtimeaxis").appendChild(d1);

    makeResizableDiv('.resizable', document);
};

function createPlannerToolItem(label, isAdjustable=false) {
    let container = document.getElementById("containerplannertooltarget");
    let fiveMinuteWidth = Math.ceil(6 * (container.clientWidth / numEntries));
    
    var item = createElement("DIV", "class", "resizable", "style", "background-color: rgb(52, 94, 126)", "data-duration", 6);
    var sp = createElement("SPAN","contentEditable",isAdjustable.toString(), "class","activitytext");        
    var tn = document.createTextNode(label,"color","white");
    sp.appendChild(tn);            
    item.appendChild(sp);
    item.style.width = fiveMinuteWidth + "px";
    let d5 = createElement("DIV", "class", "resizers");
    let d6 = createElement("DIV", "class", "resizer bottom-right");
    let d7 = createElement("DIV", "class", "stripe-90 planneritempicker", "draggable", true);
    d5.appendChild(d6);
    d5.appendChild(d7);
    item.appendChild(d5);
    return item;
}

function makeResizableDiv(divClassName, fromElement) {
    // find all elements with "resizable" class
    const elements = fromElement.querySelectorAll(divClassName);
    for (let n = 0; n < elements.length; n++) {
        const element = elements[n]; // element is planner tool activity item
        // for this particular div (or planner tool item), find all resizer elements
        const resizers = element.querySelectorAll(divClassName + ' .resizer');
        // define the necessary event listeners to allow resizing them
        for (let i = 0; i < resizers.length; i++) {
            const currentResizer = resizers[i];
            currentResizer.addEventListener('mousedown', function (e) {
                e.preventDefault();
                window.addEventListener('mousemove', resize);
                window.addEventListener('mouseup', stopResize);
            });

            function resize(e) {
                if (currentResizer.classList.contains('bottom-right')) {
                    let canResize = true;
                    // we need the element to calculate number of pixelsper minute entry
                    let container = document.getElementById("containerplannertooltarget");

                    // get all already planned activities start times and duration
                    let plannedActivities = getPlannedActivities();
                    let startTime = element.parentElement.getAttribute("data-starttime");
                    if (startTime !== null) {
                        startTime = Number(startTime);
                        for (n = 0; n < plannedActivities.length; n++) {
                            //let currentElemWidth = Number(element.style.width.split("px")[0]); // NB: this is the OLD value, not the new resized one!
                            let currentElemWidth = e.pageX - element.getBoundingClientRect().left;
                            let widthMinutes = Math.floor(currentElemWidth / (container.clientWidth / numEntries));
                            if ((startTime < plannedActivities[n].startTime
                                    && (startTime + widthMinutes) > plannedActivities[n].startTime)
                                    || (startTime + widthMinutes) > numMinutes) {
                                canResize = false;
                                break;
                            }
                            if (canResize) {
                                // update changed duration for this planner tool activity item
                                element.setAttribute("data-duration", widthMinutes.toString());
                            }
                        }
                    }
                    if (canResize) {
                        let oneMinuteWidthPixels = Math.floor(container.clientWidth / numEntries);
                        let currentElemWidth = e.pageX - element.getBoundingClientRect().left;
                        // rounding to integer minute width values
                        element.style.width = Math.max(oneMinuteWidthPixels,
                                Math.floor(currentElemWidth / (container.clientWidth / numEntries)) * (container.clientWidth / numEntries)) + 'px';
                        //element.style.width = e.pageX - element.getBoundingClientRect().left + 'px';
                    }
                }
            }

            function stopResize() {
                window.removeEventListener('mousemove', resize);
            }
        }
    }
}




/* events fired on the draggable target */
document.addEventListener("drag", function (event) {

}, false);

// event.target applies to dragged item
document.addEventListener("dragstart", function (event) {
    event.dataTransfer.setData('text', 'anything'); // fix for Mozilla to make it work ...???
	
    // store a ref. on the dragged elem
    dragged = event.target; // // NB --> dragged.parentElement.parentElement is the planner tool activity item being dragged
    // minimize its size to allow dropping over current occupied time window
    dragged.parentElement.parentElement.style.width = "0px";

    if (event.target.parentElement.parentElement.parentElement.classList.contains("sourcedropzone")) {       
        // remember original container location
        draggedFromOriginalContainer = dragged.parentElement.parentElement.parentElement;
    } else {
        draggedFromOriginalContainer = undefined; // i.e., do not copy activity planner tool item
    }
}, false);

// Restore a set of planned activities for this user
// <div class="resizable" style="background-color: rgb(52, 94, 126); width: 80px;" data-duration="5"><span contenteditable="false" class="activitytext">Review AI</span><div class="resizers"><div class="resizer bottom-right"></div><div class="stripe-90 planneritempicker" draggable="true"></div></div></div>
function setPlannedActivities(activities) {
    if (activities) {
	let container = document.getElementById("containerplannertooltarget");
	let oneMinuteWidth = Math.ceil((container.clientWidth / numEntries));
	//alert("set planned activities: "+activities);
	jact = JSON.parse(activities);
	//var cont = document.getElementById("containerplannertooltarget");
	var cont = document.getElementById("targetrow");
	for(var i = 0; i < jact.length; i++) {
	    var planobject = jact[i];
	    var st = parseInt(planobject.startTime);
	    var dur = parseInt(planobject.duration);
	    var parent = cont.children[st];
	    if (!parent) {
		alert("children of "+cont+": "+cont.children.length+" (not "+st+").");
	    } else {
		//var nevent = createPlannerToolItem(planobject.text,planobject.editabletext);
		var nevent = createPlannerToolItem(planobject.text,planobject.editabletext);
		//var nevent = createElement("DIV", "resizable", "tick bottom-right");
		nevent.setAttribute("data-duration",dur);
		//makeResizableDiv('.resizable', nevent);
		//alert("Adding children of "+cont+": "+cont.children.length+" (pos "+st+"; duration"+dur+").");
		nevent.setAttribute("style","background-color: rgb(52, 94, 126); width: "+(oneMinuteWidth * dur).toString()+"px;");
		parent.appendChild(nevent);
	    }
	}
	makeResizableDiv('.resizable', document);
    }

}

function getPlannedActivities() {
    // get all elements in the dropping planner tool time axis area
    var plannedActivities = [];
    // get all already dropped planner tool elements
    const items = document.getElementById("containerplannertooltarget").getElementsByClassName("resizable");
    // get time line positions of all already dropped planner tool elements
    let plannerItem = undefined;
    for (let n = 0; n < items.length; n++) {

        let p = items[n];
        plannerItem = p;
	// find child element with text
	var textitem = p.getElementsByClassName("activitytext")[0];
        // find parent element 	
        let container = undefined; // element that contains a dropped planner tool activity item
        while (p.parentElement !== null) {
            p = p.parentElement;
            let currentTargetElement = dragged === undefined ? dragged : dragged.parentElement.parentElement;
            if (p.classList.contains("dropzone") && plannerItem !== currentTargetElement) {
                // item found, must be the div containing the dropped planner tool item
                //p.style.background = "yellow";
                // get attribute data value from target element that indicates position on time axis in minutes
                container = p; // this is the element that contains a dropped planner tool activity item
                var timeInfoPlannedActivity = new Object();
                timeInfoPlannedActivity.startTime = Number(container.getAttribute("data-starttime"));
                timeInfoPlannedActivity.duration = Number(plannerItem.getAttribute("data-duration"));
		if (textitem) {
		    timeInfoPlannedActivity.editabletext = textitem.getAttribute("contenteditable");
		    timeInfoPlannedActivity.text = textitem.textContent;
		}
		plannedActivities.push(timeInfoPlannedActivity);
                break;
            }
        }
    }
    return plannedActivities;
}


// event.target applies to dragged item
document.addEventListener("dragend", function (event) {
    // event.target (=dragged): // NB --> event.target.parentElement.parentElement is the planner tool activity item being dragged

    if (dragtarget.classList.contains("sourcedropzone")) {
        // delete dragged activity planner tool item
        if (dragged !== undefined) {
            // mynode: set it to the parent of the activity planner tool item  
            var myNode = dragged.parentElement.parentElement.parentElement;
            var fc = myNode.firstChild;
            while (fc) {
                myNode.removeChild(fc);
                fc = myNode.firstChild;
            }
            // copy activity planner item to make it available again for an extra planned activity
            // duplicate this activity planner tool item
            var plannerItemElement = dragged.parentElement.parentElement;
            var activiyTextElement = plannerItemElement.getElementsByClassName("activitytext");
            let item = createPlannerToolItem(activiyTextElement[0].textContent, activiyTextElement[0].isContentEditable);
            draggedFromOriginalContainer.appendChild(item);
            makeResizableDiv('.resizable', draggedFromOriginalContainer);
            
        } else {
            dragged = undefined;
        }
        return;
    }
    if (dragtarget.classList.contains("dropzone")) {
        // check if available time window fits the time length of the dragged planner tool (activity) item

        // get all already planned activities start times and duration
        let plannedActivities = getPlannedActivities();

        // get attribute data value from target element that indicates position on time axis in minutes
        var newActivity = new Object();
        newActivity.duration = Number(dragged.parentElement.parentElement.getAttribute("data-duration"));
        newActivity.startTime = Number(dragtarget.getAttribute("data-starttime"));

        // we need the element to calculate number of pixelsper minute entry
        let container = document.getElementById("containerplannertooltarget");
        // first set width to orignal before possible forced resizing
        let widthMinutes = Number(dragged.parentElement.parentElement.getAttribute("data-duration"));
        let widthPixels = Math.ceil(widthMinutes * container.clientWidth / numEntries);
        dragged.parentElement.parentElement.style.width = widthPixels.toString() + "px";

        // now that we have the timing information of possible other dropped planner tool elements,
        // find out if enough space is available to drop the new planner tool element
        if (plannedActivities.length > 0) {
            for (let n = 0; n < plannedActivities.length; n++) {
                let pa = plannedActivities[n];
                let srcstart = newActivity.startTime;
                let srcend = newActivity.startTime + newActivity.duration;
                let dststart = pa.startTime;
                let dstend = pa.startTime + pa.duration;

                if (srcstart >= dststart && srcstart < dstend) {
                    // occupied by another activity, not allowed (also not possible by CSS)
                }
                if (srcend >= dststart && srcstart < dststart) {
                    // overlapping next activity, not allowed  
                    // set srcend to dststart by reducing width
                    let widthMinutes = dststart - srcstart;
                    let widthPixels = Math.ceil((dststart - srcstart) * container.clientWidth / numEntries);
                    // check if it wasn't already reduced further by another activity element (can only make it smaller)
                    if (widthMinutes < Number(dragged.parentElement.parentElement.getAttribute("data-duration"))) {
                        dragged.parentElement.parentElement.style.width = widthPixels.toString() + "px";
                        dragged.parentElement.parentElement.setAttribute("data-duration", widthMinutes.toString());
                    }
                }

            }
        }

    }
    dragged = undefined; // make sure it won't interfer with the resizing event
}, false);

/* events fired on the drop targets */
// event.target applies to item being dragged over
document.addEventListener("dragover", function (event) {
    // prevent default to allow drop
    event.preventDefault();
}, false);

// event.target applies to item being dragged over
document.addEventListener("dragenter", function (event) {
    // highlight potential drop target when the draggable element enters it
    // event.target: the element being dragged over as a potential dropping point
    if (event.target.classList.contains("dropzone")) {
        event.target.style.background = "purple";
        dragtarget = event.target;
    }
    if (event.target.classList.contains("sourcedropzone")) {
        dragtarget = event.target;
    }
}, false);

// event.target applies to item being dragged over
document.addEventListener("dragleave", function (event) {
    // reset background of potential drop target when the draggable element leaves it
    if (event.target.classList.contains("dropzone")) {
        event.target.style.background = "";
    }

}, false);

// event.target applies to item being dragged to
document.addEventListener("drop", function (event) {
    // prevent default action (open as link for some elements)
    event.preventDefault();
    // move dragged elem to the selected drop target
    if (event.target.classList.contains("dropzone")) {
        //event.target.style.background = "";
//    event.target.appendChild( dragged );
        var plannerItemElement = dragged.parentElement.parentElement;
        //dragged.parentNode.removeChild( dragged );
        // delete planner tool element
//    while (dragged.parentNode.childElementCount > 0)
//        dragged.parentNode.childNodes[0].remove();
        event.target.appendChild(plannerItemElement);
        document.getElementById("targetrow").style.height = "100px";
        var bodyStyles = document.body.style;
        //bodyStyles.setProperty("--my-column-height","0px");
    }    
    if (draggedFromOriginalContainer !== undefined) {    
        // copy activity planner item to make it available again for an extra planned activity
        // duplicate this activity planner tool item
        var plannerItemElement = dragged.parentElement.parentElement;
        var activiyTextElement = plannerItemElement.getElementsByClassName("activitytext");
        let item = createPlannerToolItem(activiyTextElement[0].textContent, activiyTextElement[0].isContentEditable);
        draggedFromOriginalContainer.appendChild(item);
        makeResizableDiv('.resizable', draggedFromOriginalContainer);
    }
}, false);

