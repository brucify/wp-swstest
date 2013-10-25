update();
setTimeout(check,1000);

$(function() {
	$( "#sortable" ).sortable({
	  placeholder: "btn btn-warning btn-block",
	  helper: function(event, element) {
        return element.clone().appendTo("body");
 	  }
	});
	$( "#sortable" ).disableSelection();
});

function check(){
	var time_left = update(); 
	if (time_left >= 0) {
		document.getElementById("time_left").innerHTML = convertTime(time_left);
		setTimeout(check,1000);
	} else {
		document.getElementById("submit_all").submit();
	}
}

function convertTime(t) {
	if(t > 60) {
		return Math.round(t / 60) + " m";
	} else {
		return t + " s";	
	}
}

function update(){
	var now = new Date().getTime();
	return Math.round( ( document.getElementById("due_time").innerHTML - now ) / 1000); 
}

/*
 * ticket_id
 * test_id
 * q: question id
 * aid: chosen alternative's id
 * gid: button group's id
 * fid: form_submit id
 * sid: status id, for displaying AJAX reply
 */
function multiClicked(ticket_id, test_id, q, aid, gid, fid, sid) {
	
	var a = prepareValueMulti(gid, aid);
	var ja = JSON.stringify( a );

	// observe and submit all buttons values in one go
	submitMulti(q, ja, ticket_id, test_id, sid);
	alignValue(fid, q, a);

}

/*
 * ticket_id
 * test_id
 * q: question id
 * a: the answer
 * fid: form_submit id
 * sid: status id, for displaying AJAX reply
 */
function singleClicked ( ticket_id, test_id, q, a, fid, sid ) {
	submitSingle(q, a, ticket_id, test_id, sid);
	alignValue (fid, q, a);
}

function textSaveClicked ( ticket_id, test_id, q, aid, fid, sid, bid ) {
	$("#" + bid).button('loading');
	a = $("#" + aid).val();
	submitText(q, a, ticket_id, test_id, sid, bid);
	alignValue (fid, q, a);
}

//param: question, answer, ticket_id, test_id
function submitSingle(q, a, ticket_id, test_id, sid) {
	
	var xmlhttp;
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else {// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xmlhttp.onreadystatechange = function() {
		if ( xmlhttp.readyState == 4 && xmlhttp.status == 200 ) {

			if (xmlhttp.responseText != false) {
			
				document.getElementById(sid).style.backgroundColor = "green";
				//document.getElementById(sid).innerHTML="Auto-saved"; // change display
//				document.getElementById(sid).className="alert alert-success";

			} else {
				console.log(xmlhttp.responseText);
//				document.getElementById(sid).innerHTML="Failed to auto-save";
//				document.getElementById(sid).className="alert alert-info";

			}
		}
	}
	
	var post = "q=" + q + "&a=" + a + "&t=" + Math.round(new Date().getTime() / 1000) + "&ticket_id=" + ticket_id +"&test_id=" + test_id  + "&question_type=" + "single_choice";
	
	xmlhttp.open( "POST", "#", true );
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send( post ); 
	// send answer question, answer, timestamp 
	// q, a, t, ticket_id, test_id
}

function submitMulti(q, a, ticket_id, test_id, sid) {

	var xmlhttp;
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else {// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xmlhttp.onreadystatechange = function() {
		if ( xmlhttp.readyState == 4 && xmlhttp.status == 200 ) {
			
			if (xmlhttp.responseText != false) {
			
				document.getElementById(sid).style.backgroundColor = "green";

//				document.getElementById(sid).innerHTML="Auto-saved"; // change display
//				document.getElementById(sid).className="alert alert-success";

			} else {

				//document.getElementById(sid).innerHTML="Failed to auto-save";
//				document.getElementById(sid).className="alert alert-info";

			}
		}
	}
	//var a = JSON.stringify( prepareValueMulti(gid) );
	
	// q, a, t, ticket_id, test_id, question_type
	var post = "q=" + q + "&t=" + Math.round(new Date().getTime() / 1000) + "&ticket_id=" + ticket_id +"&test_id=" + test_id + "&question_type=" + "multi_choice"+"&a=" + a;
	

	//if(a.length > 0) { post += "&a=" + a; }
							console.log(post);

	xmlhttp.open( "POST", "#", true );
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send( post ); 
	// send answer question, answer, timestamp 
}

function submitText ( q, a, ticket_id, test_id, sid, bid ) {
	
	var xmlhttp;
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else {// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xmlhttp.onreadystatechange = function() {
		if ( xmlhttp.readyState == 4 && xmlhttp.status == 200 ) {

			if (xmlhttp.responseText != false) {
			
				document.getElementById(sid).style.backgroundColor = "green";

//				document.getElementById(sid).innerHTML="Auto-saved"; // change display
//				document.getElementById(sid).className="alert alert-success";
				$("#" + bid).button('reset');

			} else {
				console.log(xmlhttp.responseText);
//				document.getElementById(sid).innerHTML="Failed to auto-save";
//				document.getElementById(sid).className="alert alert-info";
				$("#" + bid).button('reset');

			}
		}
	}
	
	var post = "q=" + q + "&a=" + a + "&t=" + Math.round(new Date().getTime() / 1000) + "&ticket_id=" + ticket_id +"&test_id=" + test_id  + "&question_type=" + "text";
	
	xmlhttp.open( "POST", "#", true );
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send( post ); 
	// send answer question, answer, timestamp 
	// q, a, t, ticket_id, test_id
}

function alignValue (id, q, a) {
	var JSONSubmitted = JSON.parse(document.getElementById(id).value);
	
	if ( !JSONSubmitted ) {
		JSONSubmitted = new Array();
		JSONSubmitted.push( {"question_id":q, "answer":a} );
		//JSONSubmitted = [ {"question_id":q, "answer":a} ];

	
	} else {
		var f = 0;
		
		for (var i = 0; i < JSONSubmitted.length; i++) {
			if ( JSONSubmitted[i].question_id == q ) {
				JSONSubmitted[i].answer = a;
				f = 1;
			}
		}
		
		if ( f == 0 ) {
			JSONSubmitted.push( {"question_id":q, "answer":a} );
		}
	}	
	
	document.getElementById(id).value = JSON.stringify(JSONSubmitted);
	
}


function prepareValueMulti(gid, aid) {

	var buttonNodes = document.getElementById(gid).childNodes;
	
	var answers = [];
	
	for (var j=0; j < buttonNodes.length; j++) {
		
		if(buttonNodes[j].nodeName == "BUTTON") {
						
			var active = buttonNodes[j].className.indexOf("active");
			
			if (buttonNodes[j].id == aid) {
				active *= -1; 
			}
			
			if ( active > 0 ) { // if button active

				var a = buttonNodes[j].id;
				answers.push( a );
			} 
		}
		
	}

	return answers;
}


