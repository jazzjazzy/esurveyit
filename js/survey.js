$(document).ready(function(){
	
	if($('.title').html() == ''){
		$(".title").warning("There is no Title, you will need a Title before you can save this Survey");
	}
	
	if($('.details').html() == ''){
		$(".details").warning("There is no Description, you will need a discription before you can save this Survey");
	}
	
	if($('.question').children('li').length == 0){
		$(".warning-area").warning("There is no Question in this survey, there needs to be at least one question in this survey before it can be saved");
    }
	/*********************** CREATE Survey ***************************/
	
	 $("#add-survey").click(function(){
		 if($('#newtitle').val() == '' || $('#newdetails').val() == ''){
			 alert("Error : need to fill out the Title and Description before you can create a survey")
		 }else{
			 $('.title').html($('#newtitle').val());
			 $('.details').html($('#newdetails').val());
			 if($('.title').html() == ''){
				console.debug("no title");
			 }
			 if($('.details').html() == ''){
				console.debug("no details");
			 }
			 Boxy.get(this).hide();
		 }
	 })
	
		
	/************************Frame setting****************************/
	
	$('.close-frame').click(function(){
		
		var frame = $(this).parents('div').parents('div');
		var divLoc ="#"+frame.attr('id')+" div.inside-box";
		var height = '';
		
		switch(frame.attr('id')){
			case 'daterange' : height="230px"; break;
			case 'network' : height="290px"; break;
			default : height="360px";
		}
		
		if($(divLoc).css('height') == '14px'){
			$(divLoc).animate({
			    height: height
			  }, 1000 );
			$(divLoc+" span.close-frame img").attr("src", "images/frame-up.png")
		}else{
			$(divLoc).animate({
			    height: "14px"
			  }, 1000 );
			$(divLoc+" span.close-frame img").attr("src", "images/frame-dn.png")
		}
	});
	
	/************************Question CREATE****************************/
	
	$("#create").click(function(){
		 new Boxy("#question-create", {title: 'Create New Questions', modal : true});
	});
	
	/*$("#display").click(function(){
		$("#display div.inside-box").animate({
		    height: "330px"
		  }, 1000 );
	});
	
	$("#daterange").click(function(){
		$("#daterange div.inside-box").animate({
		    height: "230px"
		  }, 1000 );
	});
	
	$("#network").click(function(){
		$("#network div.inside-box").animate({
		    height: "330px"
		  }, 1000 );
	});*/
	/************************ADD Question*******************************/
	
	$("#add-question").click(function(){
		var question = $("#newquestion").val();
		var type = $("#questionType option:selected").val();
		var id = $(".question>li").size()+1;
		Boxy.get("#question-create").hide();
		 $.post( "ajax/survey.ajax.php", { 'action': 'append', 'id': id, 'question': question, 'type':type },function( data ) {
			 $('.question').append( data );
			 $(".question li").click(getQuestionDetails);
			 $('#newquestion').val('');
			 $('#questionType').val('');
			 if($('.question').children('li').length == 0){
				 $(".warning-area").warning("There is no Question in this survey, there needs to be at least one question in this survey before it can be saved");
			 }else{
				 $(".warning-area").html('');
			 }
		 });
		 
	});
	
	$(".remove-question").live("click" , function(){
		var id = question_id.replace("#q", "");
		$("#qid_"+id+" div.question-label").parent('li').remove();
		closeQuestionDetails();
	});
	
	
	/************************Payment SETTING****************************/	
	
	if ($('input[name=display-type]:checked').val() !== undefined) {
		$('#display-field').html($('input[name=display-type]:checked').parents("h4").text());
	}
	
	$('input[name=display-type]').click(function(){
		$('#display-field').html($('input[name=display-type]:checked').parents("h4").text());
	});
	
	/************************Date Range SETTING****************************/	
	var showstartdate = '';
	var showenddate = '';

	if ($('#startdate:checked').val() !== undefined) {
		$(".start").attr('disabled', 'disabled');
		showstartdate = "Now";
	}else{
		showstartdate = $('select[name=day].start option:selected').text()+"/"+
		$('select[name=month].start option:selected').text()+"/"+
		$('select[name=year].start option:selected').text();
	}
	
	$('#startdate').change(function(){
		if ($('#startdate:checked').val() !== undefined) {
			$(".start").attr('disabled', 'disabled');
			showstartdate = "Now";
		}else{
			$(".start").attr('disabled', '');
			showstartdate = $('select[name=day].start option:selected').text()+"/"+
			$('select[name=month].start option:selected').text()+"/"+
			$('select[name=year].start option:selected').text();
		}
		$('#daterange-field').html(showstartdate+' - '+ showenddate);
	});
	
	if ($('#enddate:checked').val() !== undefined) {
		$(".end").attr('disabled', 'disabled');
		showenddate = "Never";
	}else{
		showenddate  = $('select[name=day].end option:selected').text()+"/"+
		$('select[name=month].end option:selected').text()+"/"+
		$('select[name=year].end option:selected').text();
	}
	
	$('#enddate').change(function(){
		if ($('#enddate:checked').val() !== undefined) {
			$(".end").attr('disabled', 'disabled');
			showenddate = "Never";
		}else{
			$(".end").attr('disabled', '');
			showenddate = $('select[name=day].end option:selected').text()+"/"+
			$('select[name=month].end option:selected').text()+"/"+
			$('select[name=year].end option:selected').text();
		}
		$('#daterange-field').html(showstartdate+' - '+ showenddate);
	});
	
	$('.start').change(function(){
		showstartdate = $('select[name=day].start option:selected').text()+"/"+
		$('select[name=month].start option:selected').text()+"/"+
		$('select[name=year].start option:selected').text();
		
		if ($('#enddate:checked').val() !== undefined) {
			showenddate = "Never"
		}else{
			showenddate = $('select[name=day].end option:selected').text()+"/"+
			$('select[name=month].end option:selected').text()+"/"+
			$('select[name=year].end option:selected').text();
		}
		
		$('#daterange-field').html(showstartdate+' - '+ showenddate);
	});
	
	$('.end').change(function(){
		
		showenddate = $('select[name=day].end option:selected').text()+"/"+
		$('select[name=month].end option:selected').text()+"/"+
		$('select[name=year].end option:selected').text();
		
		if ($('#startdate:checked').val() !== undefined) {
			showstartdate = "Now"
		}else{
			showstartdate = $('select[name=day].start option:selected').text()+"/"+
			$('select[name=month].start option:selected').text()+"/"+
			$('select[name=year].start option:selected').text();
		}
		
		$('#daterange-field').html(showstartdate+' - '+ showenddate);
	});
	
	$('#daterange-field').html(showstartdate+' - '+ showenddate);
	
	/************************Network SETTING****************************/
	if ($('input[name=network]:checked').val() !== undefined) {
		switch($('input[name=network]:checked').val()){
		case 'open' : 	$('#network-field').html('Open Network');
						$(".countryip").attr('disabled', 'disabled');
						$(".startIP").attr('disabled', 'disabled');
						$(".endIP").attr('disabled', 'disabled');
						$(".CIDRIP").attr('disabled', 'disabled');
					break;
		case 'country' :$('#network-field').html($('select[name=country].countryip option:selected').text());
						$(".countryip").attr('disabled', '');
						$(".startIP").attr('disabled', 'disabled');
						$(".endIP").attr('disabled', 'disabled');
						$(".CIDRIP").attr('disabled', 'disabled');
					break;
		case 'range' :	$('#network-field').html(validIPrange('ip'));
						$(".countryip").attr('disabled', 'disabled');
						$(".startIP").attr('disabled', '');
						$(".endIP").attr('disabled', '');
						$(".CIDRIP").attr('disabled', 'disabled');
					break;
		case 'CIDR' :	$('#network-field').html(validIPrange('CIDR'));
						$(".countryip").attr('disabled', 'disabled');
						$(".startIP").attr('disabled', 'disabled');
						$(".endIP").attr('disabled', 'disabled');
						$(".CIDRIP").attr('disabled', '');
					break;
		}
	}
	
	
	
	$('input[name=network]').click(function(){
		switch($('input[name=network]:checked').val()){
			case 'open' : 	$('#network-field').html('Open Network');
							$(".countryip").attr('disabled', 'disabled');
							$(".startIP").attr('disabled', 'disabled');
							$(".endIP").attr('disabled', 'disabled');
							$(".CIDRIP").attr('disabled', 'disabled');
						break;
			case 'country' :$('#network-field').html($('select[name=country].countryip option:selected').text());
							$(".countryip").attr('disabled', '');
							$(".startIP").attr('disabled', 'disabled');
							$(".endIP").attr('disabled', 'disabled');
							$(".CIDRIP").attr('disabled', 'disabled');
						break;
			case 'range' :	$('#network-field').html(validIPrange('ip'));
							$(".countryip").attr('disabled', 'disabled');
							$(".startIP").attr('disabled', '');
							$(".endIP").attr('disabled', '');
							$(".CIDRIP").attr('disabled', 'disabled');
						break;
			case 'CIDR' :	$('#network-field').html(validIPrange('CIDR'));
							$(".countryip").attr('disabled', 'disabled');
							$(".startIP").attr('disabled', 'disabled');
							$(".endIP").attr('disabled', 'disabled');
							$(".CIDRIP").attr('disabled', '');
						break;
		}
	})
	
	$('.countryip').change(function(){
		$('#network-field').html($('select[name=country].countryip option:selected').text());
	})
	
	$('.countryip').keyup(function(){
		$('#network-field').html($('select[name=country].countryip option:selected').text());
	})

	$('.startIP').keyup(function(){
		$('#network-field').html(validIPrange('ip'));
	})
	
	$('.endIP').keyup(function(){
		$('#network-field').html(validIPrange('ip'));
	})
	
	$('.CIDRIP').keyup(function(){
		$('#network-field').html(validIPrange('CIDR'));
	})
	
	function validIPrange(type){
		
		var pattern=new RegExp("^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$");
		
		if(type == 'ip' ){
			var rangeStart = '';
			var rangeEnd = '';
			
			var startip = $('input[name=startIP1]').val()+"."+$('input[name=startIP2]').val()+"."+$('input[name=startIP3]').val()+"."+$('input[name=startIP4]').val();
			if (!pattern.test(startip)){
				rangeStart = "Invalid";
			}else{
				rangeStart = startip;
			}
			
			var endip = $('input[name=endIP1]').val()+"."+$('input[name=endIP2]').val()+"."+$('input[name=endIP3]').val()+"."+$('input[name=endIP4]').val();
			if (!pattern.test(endip)){
				rangeEnd = "Invalid";
			}else{
				rangeEnd = endip;
			}
			return rangeStart+" - "+rangeEnd;
		}else if(type == 'CIDR'){
			
			var cidr = '';
			var mask = '';
			
			var cidrip = $('input[name=CIDRIP1]').val()+"."+$('input[name=CIDRIP2]').val()+"."+$('input[name=CIDRIP3]').val()+"."+$('input[name=CIDRIP4]').val();
			if (!pattern.test(cidrip)){
				return "Invalid";
			}else{
				var int = parseInt($('input[name=CIDRIP5]').val());
				if(!isNaN(int)){
					if(int <= 32){
						mask  = int;
					}else{
						mask = "Invalid";
					}
				}
				return cidrip+"/"+mask;
			}
		}else{
			return "Invalid";
		}
	}
	
	
	/************************Question SETTING****************************/
	/**
	 *  Adds sortable list to the Question List (left questions)
	 *  jQuery.ui.sortable
	 */
	$(".question").sortable({
			axis: 'y',
			update: function(event, ui) { 
				var order = $(".question").sortable('serialize', {attribute:'id'});
				var pid = $('#pid').val(); 
				$.post("ajax/advertisement.ajax.php?action=arrange-sort&id="+pid+"&"+order);
					ui.item.unbind("click");
					ui.item.one("click", function (event) { 
	               	 	event.stopImmediatePropagation();
	               		$(this).click(getQuestionDetails);
	            	});
			},
			delay: 30
	});
	
	/**
	 *  work with above 
	 */
	$(".question").disableSelection();
	
	/**
	 * Closes the question details box 
	 */
	$(".cancel-button").live("click", closeQuestionDetails);
	
	/**
	 * Get information on the current selected list item (left questions)
	 */ 
	$(".question li").click(getQuestionDetails);
	
	/***************************************************************/	
		
		
	/************************PAGE SETTING****************************/
	
	$(".page").click(getQuestionDetails);
	
	/***************************************************************/
	

	
	
});


var closeQuestionDetails = function() {
	$("#question-box").css({ display: "none", position: "static", marginLeft: 0, marginTop: 0});
	$("#question-pointer").css({ display: "none", position: "static", marginLeft: 0, marginTop: 0});
	$('#question-content').html("Closed");
	$(".question").sortable({disabled : false});
	$("#display").animate({opacity: 1}, 40);
	$("#daterange").animate({opacity: 1}, 40);
	$("#network").animate({opacity: 1}, 40);
}

var getQuestionDetails = function() {
		$("#display").animate({opacity: 0.2}, 40);
		$("#daterange").animate({opacity: 0.2}, 40);
		$("#network").animate({opacity: 0.2}, 40);
		var pid = $('#pid').val();
		var id_val= $(this).attr('id').split("_");
		var id = id_val[1];
		var type = $(this).attr('class');
	
		if(type == 'page'){
			$.getScript("question-plugin/"+type+".question.js");
			$('#question-content').load("question-plugin/"+type+".question.html", function(){
				eval(type+'SetProp()');
			});
		}else{
			$.getScript("question-plugin/"+type+"/"+type+".question.js");
			$('#question-content').load("question-plugin/"+type+"/"+type+".question.html", function(){
				eval(type+'SetProp('+id+')');
			});
		}

		var position = $(this).position();
		var positionmMain = $(".mainContent").position();
		var qposition = $("#question-placer").position();

		var x = positionmMain.left + $(".mainContent").width();
		var temp  = position.top + 20;

		var imgHeight = ($("#question-pointer").height()/2);
		var y = temp - imgHeight;
		var y2 = temp-40;
		
		if(y2 < qposition.top){
			y2 = qposition.top;
		}
		
		$(".question").sortable({disabled : true});
			
		$("#question-box").css({ display: "block", position: "absolute", marginLeft: 0, marginTop: 0, top: (y2), left: (x+16) });
		$("#question-pointer").css({ display: "block", position: "absolute", marginLeft: 0, marginTop: 0, top: (y+8), left: (x+10) });
		$('#question-content').html("Loading....");
		
};

