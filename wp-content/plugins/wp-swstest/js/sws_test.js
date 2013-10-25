// JavaScript Document

function newalt(rid, inid, qid) {
	var alt = jQuery( "#" + inid ).val();
	
	if (alt) {

		jQuery.post(ajaxurl,
			{
				action:"add_alt",
				quetionid:qid,
				alt:escape(alt)
			},
			function(data, status){
				console.log("bonbon "+data+" "+status+" "+ajaxurl);
				
				if (status == "success") {
					
					altid = data;
					jQuery( "#" + rid ).before(
						'<tr id="'+altid+'">  \
							<td></td> \
							<td><div><input type="text" value="' + alt + '" /></div></td> \
							<td> \
								<div> \
									<button type="button" class="button deletemeta button-small" onclick="deletealt(\''+altid+'\', \''+qid+'\');">Delete</button> \
								</div> \
							</td> \
						</tr>'
					);
				}
			}
		);
		
		
	}
}

function deletealt(altid, qid) {
	
	if(altid){
		jQuery.post(ajaxurl,
			{
				action:"delete_alt",
				quetionid:qid,
				altid:altid	
			},
			function(data, status) {
				
				if (status == "success") {
	
					console.log("bonbon "+data+" "+status+" "+ajaxurl);	
					
					jQuery( "#" + altid ).remove();
				
				}
			}
		);
	}
}

function updatealt(altid, qid, altsrc) {
	
	var alt = jQuery( "#"+altsrc ).val();
	
	if(altid){
		jQuery.post(ajaxurl,
			{
				action:"update_alt",
				quetionid:qid,
				altid:altid,
				alt:escape(alt)
			},
			function(data, status) {
				
				if (status == "success") {
	
					console.log("bonbon update: "+data+" "+status+" "+ajaxurl);	
									
				}
			}
		);
	}
}

function updateq(qid, qsrc) {
	
	var q = jQuery( "#"+qsrc ).val();
	
	if(qid){
		jQuery.post(ajaxurl,
			{
				action:"update_question",
				quetionid:qid,
				q:q
			},
			function(data, status) {
				
				if (status == "success") {
	
					console.log("bonbon update q: "+data+" "+status+" "+ajaxurl);	
									
				}
			}
		);
	}
}

function addq(taid, slid, testid) {
	
	var q = jQuery( "#"+taid ).val();
	var qtype = jQuery( "#"+slid ).val();
	
	if(q){
		jQuery.post(ajaxurl,
			{
				action:"add_question",
				q:q,
				qtype:qtype,
				testid:testid
			},
			function(data, status) {
				
				if (status == "success") {
	
					console.log("bonbon update q: "+data+" "+status+" "+ajaxurl);	
					if (status == "success") {
					
						altid = data;
						jQuery( "#" + slid ).after(
							'<p style="color: green;">Added, refresh page to see.</p>'
						);
					}			
				}
			}
		);
	}
}

function updateqtype(qid, slid) {
	var qtype = jQuery( "#"+slid ).val();

	if(qtype) {
		jQuery.post(ajaxurl,
			{
				action:"update_question_type",
				quetionid:qid,
				qtype:qtype
			},
			function(data, status) {
				
				if (status == "success") {
	
					console.log("bonbon update d: "+data+" status: "+status+" "+ajaxurl+" qtype: "+qtype+" qid: "+qid);	
						
				}
			}
		);
	}
}

function deleteq(qid, tid, testid) {
	if(qid && testid){
		jQuery.post(ajaxurl,
			{
				action:"delete_question",
				quetionid:qid,
				testid:testid
			},
			function(data, status) {
				
				if (status == "success") {
	
					console.log("bonbon "+data+" "+status+" "+ajaxurl+" testid: "+testid);	
					
					jQuery( "#" + tid ).remove();
				
				}
			}
		);
	}
}