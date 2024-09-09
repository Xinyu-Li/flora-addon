/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var experiment;
var subjectNr;

function getExperimentDurationSeconds(curdate) {
    var curDate;
    if (arguments.length < 1)
         curDate = new Date();
    else curDate = curdate;
    return (curDate.getTime() - experiment.startTime)/1000;   
}

function makeEditableAndHighlight(colour) {
    var range, sel = window.getSelection();
    if (sel.rangeCount && sel.getRangeAt) {
        range = sel.getRangeAt(0);
    }
    document.designMode = "on";
    if (range) {
        sel.removeAllRanges();
        sel.addRange(range);
    }
    // Use HiliteColor since some browsers apply BackColor to the whole block
    if (!document.execCommand("HiliteColor", false, colour)) {
        document.execCommand("BackColor", false, colour);
    }
    document.designMode = "off";
}

function highlight(colour) {
    var range;
    if (window.getSelection) {
        // IE9 and non-IE
        try {
            if (!document.execCommand("BackColor", false, colour)) {
                makeEditableAndHighlight(colour);
            }
        } catch (ex) {
            makeEditableAndHighlight(colour);
        }
    } else if (document.selection && document.selection.createRange) {
        // IE <= 8 case
        range = document.selection.createRange();
        range.execCommand("BackColor", false, colour);
    }
}

function highlightSelection(event) {
    event = event || window.event;
	 
    var text = window.getSelection().toString();
    if (text.length === 0) return;
    
    // highlight selected text and tag with temporarily index because the user 
    // can still cancel the highlight creation
    let color = "red";
    let nr = 0;
    highlight(color);
    for (let r=0; r<window.getSelection().rangeCount; r++) {
        let range = window.getSelection().getRangeAt(r);
        if (range.commonAncestorContainer===range.startContainer && 
                range.commonAncestorContainer===range.endContainer && 
                range.startContainer===range.endContainer) {
            nr++;
            let element = createElement("SPAN", "id","highlight-temporarily_" + nr.toString());
            range.surroundContents(element);
            // trick: the surroundContents approach creates element as a child of
            // the range object. This means in the end we have two created <TAG> nodes
            // from which the parental one will remain after deleting corresponding highlight.
            // remove color tag in parent node created by execCommand
            element.parentElement.style["background-color"] = "";
            // add color tag in new node to keep it colored red
            element.style["background-color"] = color;
        }else
        {
            let ancestor = range.commonAncestorContainer;
			if (ancestor.tagName === "SPAN") {
				nr++;
				ancestor.setAttribute("id","highlight-temporarily_" + nr.toString());                
			}
			let nodes = ancestor.querySelectorAll("SPAN");
			for (let n=0; n<nodes.length; n++) {
				let node = nodes[n];
				let id = node.getAttribute("id");
				if (node.style["background-color"] === color && 
						(id === "" || id === undefined || id === null)) {
//                if (id === "" || id === undefined || id === null) {
					nr++;
					node.setAttribute("id","highlight-temporarily_" + nr.toString());
				}
			}
        }
    }

    var x = event.screenX;
    var y = event.screenY;
    var left = x +  "px";
    // todo: change constant (40) into DOM/element derived number (though will probably be ok since vertical layout organization will not change)
    var top =  y-40 +  "px";
    
    var display_context = document.getElementById("popuphighlightnote");

    display_context.style.left = left;
    display_context.style.top =  top;
    display_context.style.display = "block";
    
    // set data-selection attribute of this element to grant access to this information
    // when user creates a highlight or a note
    display_context["data-selection"] = text;
//    highlightSelectionElement = event.target;
}


//function popupHighlightNote(message, origin, data) {     
//    // message: "##px,##px" with x and y mouse click coordinates
//    // origin: not used
//    // data: the selected text
//    var display_context = document.getElementById("popuphighlightnote");
//
//    var x,y;
//    [x,y] = message.split(","); // NB: already includes "px"
//    display_context.style.left = x;
//    display_context.style.top =  y;
//    display_context.style.display = "block";
//    
//    // set data-selection attribute of this element to grant access to this information
//    // when user creates a highlight or a note
//    display_context["data-selection"] = data;
//}

//function hidePopupHighlightNoteMenuFromIframe() {
//    // NB: DOM elements need to be serialized first before sending!
//    if (window.parent.postMessage) {
//        window.parent.postMessage({
//            "func": "handleOutsideClick",
//            "message": "",
//            "origin": ""
//        }, "http://" + window.location.hostname + ":" + window.location.port);
//    }
//}

function searchText(name, element) {
    if (name.length <= 0)
        return null;

// TODO: if search pattern is surrounded by a semi-colon, colon or something else it is currently ignored: fix it!

// old googled rubbish code: do not modify html text containing element tags that will be evaluated for the search pattern too!    
//    var innerHTML = element.innerHTML;
//    var index = innerHTML.toLowerCase().indexOf(pattern);
//    while (index >= 0) {
//        innerHTML = innerHTML.substring(0, index) + "<mark>" + innerHTML.substring(index, index + name.length) + "</mark>" + innerHTML.substring(index + name.length, innerHTML.length);
//        var startIndex = index + name.length + ("<mark></mark").length;
//        var index = innerHTML.substring(startIndex,innerHTML.length).toLowerCase().indexOf(pattern); 
//        if (index >= 0) 
//            index = index + startIndex; // add length of first ignored part
//    }
//    element.innerHTML = innerHTML;       

//    var pattern = new RegExp("\\b(" + name + ")\\b", "gi"); //  var searchTerm = [pattern]; //["teh", "adn", "btu", "adress", "youre", "msitakes"];
    var pattern = new RegExp(name, "g"); //  var searchTerm = [pattern]; //["teh", "adn", "btu", "adress", "youre", "msitakes"];
    var nodes = grep(element, pattern);
//    console.log(nodes);
    return nodes;
}

function removeHighlightNoteText(textElement, identifier) {

    if (document.getElementById("createhighlight").style.display === "block" ||
            document.getElementById("createnote").style.display === "block")
        // clicking on background should not remove temporarily styling if in a
        //  process of defining a note or highlight
        return;
    
    var n=0;
    while (true) {
        n++;
        var id = identifier + "_" + n.toString(); 
        // removes all styling for this element
        // todo: only remove <span> attribute
        var nodeElement = textElement.getElementById(id);
        if (nodeElement) {
            nodeElement.style["background-color"] = "";
            nodeElement.setAttribute("id","");
        } else {
            break;
        }        
    }  

    // remove cancelled highlighted/selected text and fix missing color styling (because
    // formerly inherited from a now missing <span> parent)
    var nodes = textElement.getElementsByTagName("span");
    for (let n=0; n<nodes.length; n++) {
        let node = nodes[n];                
        let id = node.getAttribute("id");
        // check if an extra <span> element surrounded the text containing <span> 
        // child element with empty id and style color=red
        if (node.style["background-color"] === "red" && 
                (id === undefined || id === "" || id === null)) {
            node.style["background-color"] = "";
        }
        // check if a highlight <span> child element inherits its color styling from
        // a parent <span> element, which might now be missing.
        if (typeof(id) === "string") {
            if (id.substring(0,"highlightsequence".length) === "highlightsequence") {
                node.style["background-color"] = "orange";
            }
            if (id.substring(0,"notesequence".length) === "notesequence") {
                node.style["background-color"] = "green";
            }
        }
    }    
}

function containsOldSelection(textElement) {
   var nodes = textElement.getElementsByTagName("span");
    for (let n=0; n<nodes.length; n++) {
        let node = nodes[n];                
        // check style color=red
        if (node.style["background-color"] === "red")
			return true;
	}
	return false;
}

function cleanupEmptySpanElements(doc)    {    
    var spans = doc.querySelectorAll("span");
    for (let s=0; s<spans.length;s++) {
        var node = spans[s];
        let id = node.id;
        if (node.style.cssText === "" &&
                (id === "" || id === null || id === undefined)) {
            var parent = node.parentNode;
            while( node.firstChild ) {
                parent.insertBefore(  node.firstChild, node );
            }
             parent.removeChild( node );
        }
    }
}

function cleanupMarkElements(doc)    {    
    var spans = doc.querySelectorAll("mark");
    for (let s=0; s<spans.length;s++) {
        var node = spans[s];
        var parent = node.parentNode;
        while( node.firstChild ) {
            parent.insertBefore( node.firstChild, node );
        }
         parent.removeChild( node );
    }
}


//function combineTextNodes(node, prevText) {
//    if (node.nextSibling && node.nextSibling.nodeType == 3) {
//        nodesToDelete.push(node.nextSibling);
//        return combineTextNodes(node.nextSibling, prevText + node.nodeValue);
//    } else {
//        return prevText + node.nodeValue;
//    }
//}
//
//for (i = 0; i < nodes.length; i++) {
//    if (nodes[i].nodeType == 3) {
//        nodes[i].nodeValue = combineTextNodes(nodes[i], '');
//    }
//}
//
//for (i = 0; i < nodesToDelete.length; i++) {
//    console.log(nodesToDelete[i]);
//    nodesToDelete[i].remove();
//}

//function removeHighlightNoteText(textElement, identifier) {
//    // removes all styling for this element
//    //var nodes = textElement.getElementsByName(identifier);
//    var nodes = textElement.getElementsByTagName("span");
//    
//    // since nodeLists are a LIVE representation of the DOM, the changes made in 
//    // the loop immediately change te DOM and nodes will change!! By removing SPAN
//    // tag elements, it seems text nodes merge and so will the number of elements in nodes!!
//    //while (nodes.length > 0) {
//    for (let n=0; n<nodes.length; n++) {
//        let node = nodes[n];
//        if (node.getAttribute("name")===identifier) {
//            node.style["background-color"] = "";
//            node.setAttribute("name","");
//        // update nodelist to capture live changes
//        //nodes = textElement.getElementsByName(identifier);
//        }
//    }
//}

function highlightSelectedText(color, identifier) { //"orange","highlightsequence" + newHighlight.sequenceNumber.toString());
    // make temporarily highlight "SPAN" tags permanent
//    let nodes = document.getElementById("assignmentframe").querySelectorAll("SPAN");
//    var nr=0;
//    for (let n=0; n<nodes.length; n++) {
//        let node = nodes[n];
//        if (node.getAttribute("name") === "highlight-temporarily") {
//            node.style["background-color"] = color; 
//            node.setAttribute("name","");
//            nr++;
//            var id = identifier + "_" + nr.toString(); 
//            node.setAttribute("id",id);
//        }
//    }      
    var nr=0;
    while (true) {
        nr++;
        var id = "highlight-temporarily_" + nr.toString(); 
        // removes all styling for this element
        // todo: only remove <span> attribute
        var nodeElement = document.getElementById(id);
        if (nodeElement) {
            //nodeElement.style.cssText = "";
            nodeElement.style["background-color"] = color;
            nodeElement.setAttribute("id",identifier + "_" + nr.toString());
        } else {
            break;
        }        
    }    
    
}

function highlightSearchText(name, nodes, color, identifier=undefined) {
    if (nodes===null || nodes.length === 0) return;
    var counter = 0;
    if (Object.prototype.toString.call(nodes) === "[object Text]")
        nodes = [nodes];
    for (var i = 0; i < nodes.length; i++) {
        var node = nodes[i];
        var text = node.textContent;
        
        // first create a list of indices in original text with start index/indices of searched item
        var indices = [];
        var pos = text.indexOf(name);
        while (pos !== -1) {
          indices.push(pos);
          pos = text.indexOf(name, pos + name.length);
        }        
        // keep original text and based on that insert all "MARK" markup elements
        if (indices.length > 0) {
            var sibling = node;
            for (var n=0; n<indices.length; n++) {
                var index = indices[n];
                if (n===0) {
                    // change current text node to the first text fragment before highlighted text
                    node.textContent = text.substring(0, index);
                }
                if(arguments.length > 2) {
                    // create marked text node element 
                    var m = createElement("SPAN","style","background-color: " + color, "id",identifier+"_"+counter.toString());
                    counter += 1;
                } else {
                    // create marked text node element 
                    var m = createElement("MARK");
                }
                m.appendChild(document.createTextNode(text.substring(index, index + name.length))); 
                // insert before a possible next node (appends if it doesn't exist)
                sibling = node.parentNode.insertBefore(m, sibling.nextSibling);
                // insert remainder text
                if (n < indices.length-1)
                    var endIndex = indices[n+1];
                else 
                    var endIndex = text.length;
                sibling = node.parentNode.insertBefore(document.createTextNode(text.substring(index + name.length, endIndex)), sibling.nextSibling);
            }
        }
    }
}

//function highlightText(name, nodes) {
//    if (nodes===null) return;
//    
//    for (var i = 0; i < nodes.length; i++) {
//        var node = nodes[i];
//        var text = node.textContent;
//        var index = 0;
//        while (index >= 0) {
//            var index = text.toLowerCase().indexOf(name, index);
//            if (index >= 0) { // should always be true ....
//                // change current text node to the first text fragment before highlighted text
//                node.textContent = text.substring(0, index);
//                // create marked text node element 
//                var m = createElement("MARK");
//                m.appendChild(document.createTextNode(text.substring(index, index + name.length))); 
//                // insert before a possible next node (appends if it doesn't exist)
//                node.parentNode.insertBefore(m, node.nextSibling);
//                // insert remainder text
//                node.parentNode.insertBefore(document.createTextNode(text.substring(index + name.length, text.length)), node.nextSibling.nextSibling);
//                // next search from right after current 
//                index = index + name.length;
//                // update modified content
//                text = node.parentNode.textContent;
//            }
//        }
//    }
//}

function eachNode(rootNode, callback) {
    if (!callback) {
        var nodes = [];
        eachNode(rootNode, function (node) {
            nodes.push(node);
        });
        return nodes;
    }

    if (false === callback(rootNode))
        return false;

    if (rootNode.hasChildNodes()) {
        var nodes = rootNode.childNodes;
        for (var i = 0, l = nodes.length; i < l; ++i)
            if (false === eachNode(nodes[i], callback))
                return;
    }
}

function grep(parentNode, pattern) {
    var matches = [];
    var endScan = false;

    eachNode(parentNode, function (node) {
        if (endScan)
            return false;

        // Ignore anything which isn't a text node
        if (node.nodeType !== Node.TEXT_NODE)
            return;

        if ("string" === typeof pattern) {
            if (-1 !== node.textContent.indexOf(pattern))
                matches.push(node);
        } else if (pattern.test(node.textContent)) {
            if (!pattern.global) {
                endScan = true;
                matches = node;
            } else
                matches.push(node);
        }
    });

    return matches;
}

function logText(text, destination) {
    text = getMyTime() + " - " + text;
	if (destination==="local") {
		sendLog(text, "local");
		sendLog(text, "tobii");
	} else {
		sendLog(text, "local");
		sendLog(text, "tobii");
	}
}

function getMyTime(datum) {
    var d;
    if (arguments.length === 0) {
        d = new Date();
    } else
        d = datum;
    return addZero(d.getHours()) + ":" + addZero(d.getMinutes()) + ":" + addZero(d.getSeconds()) + "." + addZero(d.getMilliseconds(), 3);
}

// NOTE: KEEP IN MIND MESSAGES MIGHT NOT BE SAVED TO DISK IN SENT FOLLOWING ORDER!!!!
function sendLog(text, destination) {
    var xmlhttp = new XMLHttpRequest();
//    xmlhttp.onreadystatechange = function () {
//        if (this.readyState === 4 && this.status === 200) {
//            //wsLog("Server acknowledge, number of bytes saved to output: " + this.responseText,"info");
//        }
//    };
    if (destination==="tobii") {
        xmlhttp.open("GET", "php/save_tobii_log.php?q=" + JSON.stringify(text), true);
    }
    else {
        xmlhttp.open("GET", "php/save_local_log.php?filename=output_" + subjectNr + ".txt&q=" + JSON.stringify(text), true);    
    }
    xmlhttp.send();
}

function readHtmlFile(filename) {
    if (filename.length === 0) {
        return [null, null];
    } else {
        var xmlhttp;
        xmlhttp = new XMLHttpRequest();
        xmlhttp.open("GET", filename, false); // synchronous call
        xmlhttp.send();
        
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            var parser = new DOMParser();
            var dom = parser.parseFromString(xmlhttp.responseText, "text/html");
            return [xmlhttp.responseText, dom];
        } else {
            return [null, null];
        }        
    }
}
    
// for printing time hours/minutes with prepended zeros for number < 10
function addZero(i, len) {
    if (arguments.length < 2)
        len = 2;

    var prefix = "";
    if (len === 2) {
        if (i < 10)
            prefix = "0";
    }
    if (len === 3) {
        if (i < 100)
            prefix = "0";
        if (i < 10)
            prefix = "00";
    }
    return prefix + i;
}

function getTimeString(d) {
    var datum;
    if (arguments.length < 1) {
        datum = new Date();
    } else
        datum = d;
    var dayPart = datum.getHours() < 12 ? "AM" : "PM";
    return addZero(datum.getHours()) + ":" + addZero(datum.getMinutes()) + " " + dayPart;
}

function createElement(e) {
    // Create DOM element of type e, additional pairs of input arguments define possible attributes
    var element = document.createElement(e);
    // Add pairs of attribute type and value, first argument is: name of e (lement)
    for (var i = 1; i < arguments.length - 1; i += 2) {
        element.setAttribute(arguments[i], arguments[i + 1]);
    }
    return element;
}

function clearSelection() {
  if (document.getSelection) { // for all new browsers (IE9+, Chrome, Firefox)
	document.getSelection().removeAllRanges();
	document.getSelection().addRange(document.createRange());
	console.log("document.getSelection");
  } else if (window.getSelection) { // equals with the document.getSelection (MSDN info)
	if (window.getSelection().removeAllRanges) { // for all new browsers (IE9+, Chrome, Firefox)
	  window.getSelection().removeAllRanges();
	  window.getSelection().addRange(document.createRange());
	  console.log("window.getSelection.removeAllRanges");
	} else if (window.getSelection().empty) { // Chrome supports this as well
	  window.getSelection().empty();
	  console.log("window.getSelection.empty");
	}
  } else if (document.selection) { // IE8-
	document.selection.empty();
	console.log("document.selection.empty");
  }
}

//function countLines(el) {
//   var divHeight = el.offsetHeight;
//   var lineHeight = parseInt(el.style.lineHeight);
//   var lines = divHeight / lineHeight;
//}