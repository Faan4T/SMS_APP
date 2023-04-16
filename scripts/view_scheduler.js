"use strict";

!function($) {
    var CalendarApp = function() {
        this.$body = $("body")
        this.$modal = $('#event-modal'),
            this.$event = ('#external-events div.external-event'),
            this.$calendar = $('#calendar'),
            this.$saveCategoryBtn = $('.save-category'),
            this.$categoryForm = $('#add-category form'),
            this.$extEvents = $('#external-events'),
            this.$calendarObj = null
    };

    CalendarApp.prototype.onDrop = function (eventObj, date) {
        var $this = this;
        var originalEventObject = eventObj.data('eventObject');
        var $categoryClass = eventObj.attr('data-class');
        var copiedEventObject = $.extend({}, originalEventObject);
        copiedEventObject.start = date;
        if ($categoryClass)
            copiedEventObject['className'] = [$categoryClass];
        $this.$calendar.fullCalendar('renderEvent', copiedEventObject, true);
        if ($('#drop-remove').is(':checked')) {
            eventObj.remove();
        }
    },

        CalendarApp.prototype.onEventClick =  function (calEvent, jsEvent, view) {
            var $this = this;
            $("#list_group_number").removeAttr("multiple");
            $this.$modal.find('.modal-title').html("Edit "+calEvent.title+" Event");
            $this.$modal.find('input[name=scheduler_id]').val(calEvent.id);
            $this.$modal.find('input[name=title]').val(calEvent.title);
            $this.$modal.find('select[name=group_id]').val(calEvent.group_id);
            $this.$modal.find('textarea[name=message]').val(calEvent.message);
            $this.$modal.find('input[name=date]').val(calEvent.start.format("YYYY-MM-DD"));
            $this.$modal.find('select[name=time]').val(calEvent.start.format("HH:mm"));
            if(calEvent.attach_mobile_device==1){
                $this.$modal.find('input[name=attach_mobile_device]').attr("checked",true);
            }
            if(calEvent.send_immediate==1){
                $this.$modal.find('input[name=send_immediate]').attr("checked",true);
            }
            $this.$modal.find('input[name=cmd]').val("update_scheduler");
            if(calEvent.phone_number.includes(",")){
                $("#list_group_number").attr("multiple","multiple");
            }
            if(calEvent.media!=""){
                $this.$modal.find('div[id=media_area]').html("<img src='"+calEvent.media+"' width='100px' height='100px' >");
                $this.$modal.find('input[name=hidden_media]').val(calEvent.media);
            }

            getGroupNumbers(calEvent.group_id,calEvent.phone_number);
            $this.$modal.modal({
                backdrop: 'static'
            });

            return false;

            var form = $("<form></form>");
            form.append("<label>Change event name</label>");
            form.append("<div class='input-group'><input class='form-control' type=text value='" + calEvent.title + "' /><span class='input-group-btn'><button type='submit' class='btn btn-success waves-effect waves-light'><i class='fa fa-check'></i> Save</button></span></div>");
            $this.$modal.modal({
                backdrop: 'static'
            });
            $this.$modal.find('.delete-event').show().end().find('.save-event').hide().end().find('.modal-body').empty().prepend(form).end().find('.delete-event').unbind('click').click(function () {
                $this.$calendarObj.fullCalendar('removeEvents', function (ev) {
                    return (ev._id == calEvent._id);
                });
                $this.$modal.modal('hide');
            });
            $this.$modal.find('form').on('submit', function () {
                calEvent.title = form.find("input[type=text]").val();
                $this.$calendarObj.fullCalendar('updateEvent', calEvent);
                $this.$modal.modal('hide');
                return false;
            });
        },


        CalendarApp.prototype.onSelect = function (start, end, allDay) {
            var $this = this;
            $("#list_group_number").removeAttr("multiple");
            $("#list_group_number").html("");

            $this.$modal.find('.modal-title').html("Add New Event");
            $this.$modal.find('input[name=scheduler_id]').val("0");
            $this.$modal.find('input[name=title]').val("");
            $this.$modal.find('select[name=group_id]').val("");
            $this.$modal.find('select[name=phone_number]').val("");
            $this.$modal.find('textarea[name=message]').val("");
            $this.$modal.find('input[name=date]').val("");
            $this.$modal.find('select[name=time]').val("");
            $this.$modal.find('input[name=attach_mobile_device]').attr("checked",false);
            $this.$modal.find('input[name=send_immediate]').attr("checked",false);
            $this.$modal.find('input[name=cmd]').val("save_scheduler");

            $this.$modal.find('div[id=media_area]').html("");
            $this.$modal.find('input[name=hidden_media]').val("");
            $this.$modal.find('input[name=media]').val("");

            $this.$modal.modal({
                backdrop: 'static'
            });

            return false;


            var form = $("<form></form>");
            form.append("<div class='row'></div>");
            form.find(".row")
                .append("<div class='col-md-6'><div class='form-group'><label class='control-label'>Event Name</label><input class='form-control' placeholder='Insert Event Name' type='text' name='title'/></div></div>")
                .append("<div class='col-md-6'><div class='form-group'><label class='control-label'>Category</label><select class='form-control' name='category'></select></div></div>")
                .find("select[name='category']")
                .append("<option value='bg-danger'>Danger</option>")
                .append("<option value='bg-success'>Success</option>")
                .append("<option value='bg-purple'>Purple</option>")
                .append("<option value='bg-primary'>Primary</option>")
                .append("<option value='bg-pink'>Pink</option>")
                .append("<option value='bg-info'>Info</option>")
                .append("<option value='bg-inverse'>Inverse</option>")
                .append("<option value='bg-orange'>Orange</option>")
                .append("<option value='bg-brown'>Brown</option>")
                .append("<option value='bg-teal'>Teal</option>")
                .append("<option value='bg-warning'>Warning</option></div></div>");
            $this.$modal.find('.delete-event').hide().end().find('.save-event').show().end().find('.modal-body').empty().prepend(form).end().find('.save-event').unbind('click').click(function () {
                form.submit();
            });
            $this.$modal.find('form').on('submit', function () {
                var title = form.find("input[name='title']").val();
                var beginning = form.find("input[name='beginning']").val();
                var ending = form.find("input[name='ending']").val();
                var categoryClass = form.find("select[name='category'] option:checked").val();
                if (title !== null && title.length != 0) {
                    $this.$calendarObj.fullCalendar('renderEvent', {
                        title: title,
                        start:start,
                        end: end,
                        allDay: false,
                        className: categoryClass
                    }, true);
                    $this.$modal.modal('hide');
                }
                else{
                    alert('You have to give a title to your event');
                }
                return false;

            });
            $this.$calendarObj.fullCalendar('unselect');
        },
        CalendarApp.prototype.enableDrag = function() {
            $(this.$event).each(function () {
                var eventObject = {
                    title: $.trim($(this).text())
                };
                $(this).data('eventObject', eventObject);
                $(this).draggable({
                    zIndex: 999,
                    revert: true,
                    revertDuration: 0
                });
            });
        }
    CalendarApp.prototype.init = function() {
        this.enableDrag();
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();
        var form = '';
        var today = new Date($.now());

        var defaultEvents = events;

        var $this = this;
        $this.$calendarObj = $this.$calendar.fullCalendar({
            slotDuration: '00:15:00',
            minTime: '08:00:00',
            maxTime: '19:00:00',
            defaultView: 'month',
            handleWindowResize: true,
            height: $(window).height() - 200,
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            events: defaultEvents,
            editable: true,
            droppable: true,
            eventLimit: true,
            selectable: true,
            drop: function(date) { $this.onDrop($(this), date); },
            select: function (start, end, allDay) { $this.onSelect(start, end, allDay); },
            eventClick: function(calEvent, jsEvent, view) { $this.onEventClick(calEvent, jsEvent, view); }

        });

        this.$saveCategoryBtn.on('click', function(){
            var categoryName = $this.$categoryForm.find("input[name='category-name']").val();
            var categoryColor = $this.$categoryForm.find("select[name='category-color']").val();
            if (categoryName !== null && categoryName.length != 0) {
                $this.$extEvents.append('<div class="relative external-event bg-' + categoryColor + '" data-class="bg-' + categoryColor + '"><i class="mdi mdi-checkbox-blank-circle m-r-10 vertical-middle"></i>' + categoryName + '</div>')
                $this.enableDrag();
            }

        });
    },
        $.CalendarApp = new CalendarApp, $.CalendarApp.Constructor = CalendarApp

}(window.jQuery),

    function($) {
        "use strict";
        $.CalendarApp.init()
    }(window.jQuery);

function getGroupNumbers(groupID,numberID){
    var Qry = 'cmd=get_group_numbers&group_id='+groupID+'&numberID='+numberID;
    $.post('server.php',Qry,function(r){
        $('#list_group_number').html(r);
    });
}
function deleteScheduler(id,img){
    if(confirm("Are you sure you want to delete this schduler?")){
        window.location = 'server.php?cmd=delete_scheduler&id='+id;
    }
}