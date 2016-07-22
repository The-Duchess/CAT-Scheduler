$(document).ready( function() {
    var startObj = $("#startDate");
    var endObj = $("#endDate");
    var dueObj = $("#dueDate");
    var statsObj = $("#stats");

    function updateStats() {
        var msecs_to_day = 1000*60*60*24;
        var startMsec = (new Date(startObj.val())).getTime();
        var endMsec = (new Date(endObj.val())).getTime();
        var dueMsec = (new Date(dueObj.val())).getTime();
        var start_to_end_weeks = Math.ceil((endMsec - startMsec) / msecs_to_day / 7);
        var due_to_start_days = Math.ceil((startMsec - dueMsec) / msecs_to_day);

        statsObj.text("Due  --- " + due_to_start_days + " days --->  Start  --- " + start_to_end_weeks + " weeks --->  End");
    }

    startObj.datepicker();
    endObj.datepicker();
    dueObj.datepicker();

    startObj.change( function() {
        if ($(this).val() != "") {
            var currentStart = $(this).datepicker("getDate");
            var defaultEnd = new Date();
            var endMin = new Date();
            var defaultDue = new Date();
            var dueMax = new Date();

            defaultEnd.setDate(currentStart.getDate() + 76);
            endMin.setDate(currentStart.getDate() + 1);
            defaultDue.setDate(currentStart.getDate() - 7);
            dueMax.setDate(currentStart.getDate() - 1);
            endObj.datepicker("option", {
                defaultDate: defaultEnd,
                minDate: endMin,
                disabled: false
            });
            endObj.val(defaultEnd.toLocaleDateString());
            dueObj.datepicker("option", {
                defaultDate: defaultDue,
                maxDate: dueMax,
                disabled: false
            });
            dueObj.val(defaultDue.toLocaleDateString());
            
            updateStats();
        }
    });

    endObj.change( function() {
        if ($(this).val() != "") { updateStats(); }
    });

    dueObj.change( function() {
        if ($(this).val() != "") { updateStats(); }
    });
});
