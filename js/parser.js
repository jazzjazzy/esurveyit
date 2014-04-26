$(document).ready(function(){
	
	$.getScript("question-plugin/text/text.question.js");
	$.getScript("question-plugin/textarea/textarea.question.js");
	$.getScript("question-plugin/select/select.question.js");
	$.getScript("question-plugin/array/array.question.js");
	$.getScript("question-plugin/radio/radio.question.js");
	$.getScript("question-plugin/checkbox/checkbox.question.js");
	
	var children = new  Array();
	$('#save-survey').click(function(){		
		$('.question').children('li').each(function(index, value){
			children[index] = $(this).attr('id');
		});
		
		if($('.title').text() == '' || $('.title').text() == "Undefined"){
			alert("There Survey will need a title before it can be saved");
			return false;
		}
		if($('.details').text() == '' || $('.details').text() == "Undefined"){
			alert("There requires some details about its content");
			return false;
		}
		if(children.length == 0){
			alert("There is no Question in this survey, there needs to be at least one question in this survey before it can be saved");
			return false;
		};
		
		/****************************************************************/
		//Info
		var survey_id = '"survey_id":"'+$('#survey_id').val()+'"';
		var survey_title = '"survey_title":"'+$('.title').text()+'"';
		var survey_description = '"survey_description":"'+$('.details').text()+'"';
		//color
		var mainContent_bg = '"mainContent":"'+$('.mainContent').css('background-color')+'"';
		var title_color = '"title_color":"'+$('.title').css('color')+'"';
		var details_color = '"details_color":"'+$('.details').css('color')+'"';
		var question_block_color = '"question_block":"'+$('.question-block').css('color')+'"';
		var question_label_bg = '"question_label_bg":"'+$('.question-label').css('background-color')+'"';
		var question_label_color = '"question_label_color":"'+$('.question-label').css('color')+'"';
		//fonts
		var title_font = '"title_font":"'+$(".title").css("font-size")+'"';
		var details_font = '"details_font":"'+$(".details").css("font-size")+'"';
		var question_label_font = '"question_label_font":"'+$(".question-label").css("font-size")+'"';
		var question_block_font = '"question_block_font":"'+$(".question-block").css("font-size")+'"';
		
		//display
		var survey_type = '"survey_type":"'+$('input[name=display-type]:checked').val()+'"';
		
		//daterange
		if ($('#startdate:checked').val() !== undefined) {
			var start_date = '"start_date": "NULL"';
		}else{
			var start_date  = '"start_date":"'+$('select[name=day].start option:selected').text()+"/"+$('select[name=month].start option:selected').text()+"/"+$('select[name=year].start option:selected').text()+'"';
		}
		
		if ($('#enddate:checked').val() !== undefined) {
			var end_date = '"end_date": "NULL"';
		}else{
			var end_date  = '"end_date":"'+$('select[name=day].end option:selected').text()+"/"+$('select[name=month].end option:selected').text()+"/"+$('select[name=year].end option:selected').text()+'"';
		}
		
		//network
		
		switch($('input[name=network]:checked').val()){
			case 'country' :  
					var network_type = '"network_type": "country"';
					var network_value = '"network_value": "'+$('select[name=country].countryip option:selected').val()+'"';
					break;
			case 'range' :  
					var network_type = '"network_type": "range"';
					var network_value = '"network_value": "'+$('input[name=startIP1]').val()+"."
									+$('input[name=startIP2]').val()+"."
									+$('input[name=startIP3]').val()+"."
									+$('input[name=startIP4]').val()+" - " 
									+$('input[name=endIP1]').val()+"."
									+$('input[name=endIP2]').val()+"."
									+$('input[name=endIP3]').val()+"."
									+$('input[name=endIP4]').val()+'"';
					break;
			case 'CIDR' :
					var network_type = '"network_type": "CIDR"';
					var network_value = '"network_value": "'+$('input[name=CIDRIP1]').val()+"."+$('input[name=CIDRIP2]').val()+"."+$('input[name=CIDRIP3]').val()+"."+$('input[name=CIDRIP4]').val()+"/"+$('input[name=CIDRIP5]').val()+'"';
					break;
			default : 
					var network_type = '"network_type": "open"';
					var network_value = '"network_value": "NULL"';
		}
		
		/****************************************************************/
		
		var jsonArray = new Array();
		for(var i in children)
		{
		    var type  = $("#"+children[i]).attr('class');
			jsonArray[i] = eval(type+"Gather(children[i])");
		}
		
		jsonArray.unshift('"page":{'+survey_id+','+survey_title+','+survey_description+','+mainContent_bg+', '+title_color+', '+details_color+', '+question_block_color+', '+question_label_bg+', '+question_label_color+', '+title_font+', '+details_font+', '+question_label_font+', '+question_block_font+', '+survey_type+', '+start_date+', '+end_date+', '+network_type+', '+network_value+'}');
		
		var questions = jsonArray.join(',');

		$.post("/parse.php",{"val":'{"survey":{'+questions+'}}'}, function(data){
			window.location.href = 'survey.php';
		});

	})
});




