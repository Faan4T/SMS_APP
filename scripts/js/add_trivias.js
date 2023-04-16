"use strict";
function removeAnswer(obj){
    $(obj).parent().parent().remove();
}

function addAnswer(q){
    var no_of_answers = $("#no_of_ans_"+q).val();
    no_of_answers = Number(no_of_answers)+Number(1);

    answer_structure = $("#answer_structure").html();
    answer_structure = answer_structure.replace(/a-no/g, no_of_answers);
    answer_structure = answer_structure.replace(/q-no/g, q);
    $("#answers_"+q).append(answer_structure);

    $("#no_of_ans_"+q).val(no_of_answers);
}

function addQuestion(){
    answer_no = 1;
    var no_of_questions = $("#no_of_questions").val();
    no_of_questions = Number(no_of_questions)+Number(1);

    question_structure = $("#question_structure").html();
    question_structure = question_structure.replace(/a-no/g, answer_no);
    question_structure = question_structure.replace(/q-no/g, no_of_questions);
    $("#questions").append(question_structure);
    $("#no_of_questions").val(no_of_questions);


}


function slideToggleMainSection(obj,section,chkBox){
    var html = $(obj).html();
    var check = html.indexOf("fa-plus");
    if(check=="-1"){
        $(obj).html('<i class="fa fa-plus" title="Close" id="fa_plus_sign"></i>');
        $('.'+section).hide('slow');

    }else{
        $(obj).html('<i class="fa fa-minus" title="Open" id="fa_plus_sign"></i>');
        $('.'+section).show('slow');

    }
}

function switchTimeDropDown(obj){
    if($(obj).val()=='0'){
        $(obj).parent().find('.timeDropDown').css('display','none');
        $(obj).parent().find('.hoursDropDown').css('display','inline');
    }else{
        $(obj).parent().find('.timeDropDown').css('display','inline');
        $(obj).parent().find('.hoursDropDown').css('display','none');
    }
}
function slideToggleInnerSection(obj,eleMent){
    if($(obj).is(":checked")==true){
        $('.'+eleMent+'').show('slow');
    }else{
        $('.'+eleMent+'').hide('slow');
    }
}

function slideToggleBeaconSection(obj,eleMent){
    if(eleMent=='campaignBeaconCouponSection'){
        $('.campaignBeaconCouponSection').show('slow');
        $('.campaignBeaconURLSection').hide('slow');
    }else{
        $('.campaignBeaconCouponSection').hide('slow');
        $('.campaignBeaconURLSection').show('slow');
    }
}

function removeFollowUp(obj){
    if(confirm("Are you sure you want to remove this follow up?")){
        obj.closest('.delay_table').remove('slow');
    }
}
function followUpHtml(){
    var timeOption =  timeOptions;
    var html = '<table width="100%" class="delay_table">';
    html += '<tr><td colspan="2"><hr id="timeoptions"></td></tr>';
    html += '<tr><td width="25%">Select Days/Time</td><td><input type="text" class="form-control numericOnly" id="date_time" placeholder="Days delay..." name="delay_day[]" value="0" onblur="switchTimeDropDown(this)">&nbsp;<select class="form-control timeDropDown" id="timeDropDowns"  name="delay_time[]">' +timeOption+ '</select><select class="form-control hoursDropDown" id="hoursDropDown" name="delay_time_hours[]">'+options+'</select></td></tr>';
    html += '<tr><td>Message</td><td><textarea name="delay_message[]" class="form-control textCounter"></textarea><span class="showCounter"><span class="showCount"> '+$maxLength+'</span> Characters left</span></td></tr>';
    html += '<tr><td>Attach Media</td><td><input type="file" name="delay_media[]" id="file"><span class="fa fa-trash" id="fa_trash" title="Remove Message" onclick="removeFollowUp(this)"></span></td></tr></table>';
    return html;
}
function addMoreFollowUpMsg(){
    var html = followUpHtml();
    $('#followUpContainer').append('<div>'+html+'</div>');
    $('.showCounter').hide();
}
