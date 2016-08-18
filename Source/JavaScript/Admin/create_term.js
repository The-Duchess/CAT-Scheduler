/**********  JQuery Includes are Scary  **********/
$.getScript("../../JavaScript/Utility/input_validation.js");


/**********  Object Initialization  **********/
$("#startDate").datepicker();
$("#endDate").datepicker();
$("#dueDate").datepicker();


/**********  New Methods for Built in Objects  **********/
Date.prototype.toPaddedLocaleDateString = function() {
    var day = this.getDate().toString();
    var month = (this.getMonth()+1).toString();
    var year = this.getFullYear();

    return (month.length == 1 ? "0" : "") + month + "/" +
        (day.length == 1 ? "0" : "") + day + "/" +
        year;
};


/**********  Functions  **********/
//  function to validate input before enabling the submit button
function validateInput() {
    if ($("#termName").val().length > 0 &&
            dateIsValid($("#startDate").val()) &&
            dateIsValid($("#endDate").val()) &&
            dateIsValid($("#dueDate").val())) {
        $("#submitButton").prop("disabled", false);
    } else {
        $("#submitButton").prop("disabled", true);
    }
}

//  function to update term information
function updateInfo() {
    //  Check if enough data is present to calculate info
    if (dateIsValid($("#startDate").val()) && (dateIsValid($("#endDate").val()) || dateIsValid($("#dueDate").val()))) {
        //  variables and constants
        var msecs_to_day = 1000*60*60*24;
        var day_names = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        
        //  check if enough data is present to calculate the term duration
        if (dateIsValid($("#startDate").val()) && dateIsValid($("#endDate").val())) {
            //  variables and calculation for term duration
            var startDate = new Date($("#startDate").val());
            var endDate = new Date($("#endDate").val());
            var startMsec = startDate.getTime();
            var endMsec = endDate.getTime();
            var start_to_end_days = Math.ceil((endMsec - startMsec) / msecs_to_day);
            var start_to_end_weeks = Math.ceil(start_to_end_days / 7);

            //  set the info for term duration and display it
            $("#statsStartToEnd1").text(start_to_end_weeks + " weeks (" + start_to_end_days + " days)");
            $("#statsStartToEnd2").text(day_names[startDate.getDay()] + " to " + day_names[endDate.getDay()]);
            $("#termDuration").show();
        } else {
            //  not enough data, hide the info for term duration
            $("#termDuration").hide();
            $("#statsStartToEnd1").text("");
            $("#statsStartToEnd2").text("");
        }

        //  check if enough data is present to calculate the due date offset
        if (dateIsValid($("#startDate").val()) && dateIsValid($("#dueDate").val())) {
            //  variables and calculation for due date offset
            var startDate = new Date($("#startDate").val());
            var startMsec = startDate.getTime();
            var dueMsec = (new Date($("#dueDate").val())).getTime();
            var due_to_start_days = Math.ceil((startMsec - dueMsec) / msecs_to_day);

            //  set the info for the term duration and display it
            $("#statsDueToStart").text(due_to_start_days + " days before start");
            $("#dueOffset").show();
        } else {
            //  not enough data, hide the info for due date offset
            $("#dueOffset").hide();
            $("#statsDueToStart").text("");
        }
        
        //  enough data for info, hide the warning
        $("#termInfoWarning").hide();
    } else {
        //  not enough data for info, hide all info and show the warning
        $("#termDuration").hide();
        $("#dueOffset").hide();
        $("#termInfoWarning").show();
    }
}


/**********  Define Handlers  **********/
//  change handler for termName
$("#termName").change( function() {
    if ($(this).val().length > 0) {
        $("#nameGroup").removeClass("has-error");
        $("#nameGroup").addClass("has-success");
    } else {
        $("#nameGroup").removeClass("has-success");
        $("#nameGroup").addClass("has-error");
    }
    validateInput();
});

//  click handler for term name clear button
$("#termNameClear").click( function() {
    $("#termName").val("");
    $("#termName").trigger("change");
});

//  change handler for start date input
$("#startDate").change( function() {
    if (dateIsValid($(this).val())) {
        //  variables
        var currentStart = $(this).datepicker("getDate");
        var defaultEnd = new Date(currentStart);
        var endMin = new Date(currentStart);
        var defaultDue = new Date(currentStart);
        var dueMax = new Date(currentStart);

        //  calculate default dates for end and due dates
        defaultEnd.setDate(currentStart.getDate() + 1);
        endMin.setDate(currentStart.getDate() + 1);
        defaultDue.setDate(currentStart.getDate() - 1);
        dueMax.setDate(currentStart.getDate() - 1);

        //  enable end date, set default date and min date
        $("#endDate").datepicker("option", {
            defaultDate: defaultEnd,
            minDate: endMin,
            disabled: false
        });
        //enable due date, set default date and max date
        $("#dueDate").datepicker("option", {
            defaultDate: defaultDue,
            maxDate: dueMax,
            disabled: false
        });
     
        //  update objects
        $("#startGroup").removeClass("has-error");
        $("#startGroup").addClass("has-success");
        $("#autofillButton").prop("disabled", false);
    } else {
        $("#startGroup").removeClass("has-success");
        $("#startGroup").addClass("has-error");
        $("#endDate").prop("disabled", true);
        $("#dueDate").prop("disabled", true);
        $("#autofillButton").prop("disabled", true);
    }

    updateInfo();
    validateInput();
});

//  click handler for start date clear button
$("#startDateClear").click( function() {
    $("#startDate").val("");
    $("#startDate").trigger("change");
    $("#endDateClear").trigger("click");
    $("#dueDateClear").trigger("click");
});

//  change handler for end date input
$("#endDate").change( function() {
    if (dateIsValid($(this).val())) {
        $("#endGroup").removeClass("has-error");
        $("#endGroup").addClass("has-success");
    } else {
        $("#endGroup").removeClass("has-success");
        $("#endGroup").addClass("has-error");
    }
    updateInfo();
    validateInput();
});

//  click handler for end date clear button
$("#endDateClear").click( function() {
    $("#endDate").val("");
    $("#endDate").trigger("change");
    
    if (dateIsValid($("#startDate").val())) {
        var currentStart = $("#startDate").datepicker("getDate");
        var defaultEnd = new Date(currentStart);

        //  calculate default dates for end and due dates
        defaultEnd.setDate(currentStart.getDate() + 1);

        //  enable end date, set default date and min date
        $("#endDate").datepicker("option", {
            defaultDate: defaultEnd,
        });
    }
});

// change handler for due date input
$("#dueDate").change( function() {
    if (dateIsValid($(this).val())) {
        $("#dueGroup").removeClass("has-error");
        $("#dueGroup").addClass("has-success");
    } else {
        $("#dueGroup").removeClass("has-success");
        $("#dueGroup").addClass("has-error");
    }
    updateInfo();
    validateInput();
});

//  click handler for due date clear button
$("#dueDateClear").click( function() {
    $("#dueDate").val("");
    $("#dueDate").trigger("change");
    
    if (dateIsValid($("#startDate").val())) {
        var currentStart = $("#startDate").datepicker("getDate");
        var defaultDue = new Date(currentStart);

        //  calculate default dates for end and due dates
        defaultDue.setDate(currentStart.getDate() - 1);

        //  enable end date, set default date and min date
        $("#dueDate").datepicker("option", {
            defaultDate: defaultDue,
        });
    }
});

//  click handler for autofill button
$("#autofillButton").click( function() {
    //  variables
    var currentStart = $("#startDate").datepicker("getDate");
    var defaultEnd = new Date(currentStart);
    var defaultDue = new Date(currentStart);

    //  calculate default dates for end and due dates
    defaultEnd.setDate(currentStart.getDate() + 76);
    defaultDue.setDate(currentStart.getDate() - 7);

    //  enable end date, set default date and min date
    if (!dateIsValid($("#endDate").val())) {
        $("#endDate").datepicker("option", {
            defaultDate: defaultEnd
        });
        $("#endDate").val(defaultEnd.toPaddedLocaleDateString());
    }
    //enable due date, set default date and max date
    if (!dateIsValid($("#dueDate").val())) {
        $("#dueDate").datepicker("option", {
            defaultDate: defaultDue
        });
        $("#dueDate").val(defaultDue.toPaddedLocaleDateString());
    }
 
    //  update objects
    $("#endDate").trigger("change");
    $("#dueDate").trigger("change");
    updateInfo();
    validateInput();
});

//  click handler for reset button
$("#resetButton").click( function() {
    $("#endDate").prop("disabled", true);
    $("#dueDate").prop("disabled", true);
    $("#nameGroup").removeClass("has-success");
    $("#nameGroup").addClass("has-error");
    $("#startGroup").removeClass("has-success");
    $("#startGroup").addClass("has-error");
    $("#endGroup").removeClass("has-success");
    $("#endGroup").addClass("has-error");
    $("#dueGroup").removeClass("has-success");
    $("#dueGroup").addClass("has-error");
    $("#submitButton").prop("disabled", true);
    $("#autofillButton").prop("disabled", true);
    $("#statsDueToStart").text("Due Date: UNKNOWN");
    $("#termDuration").hide();
    $("#dueOffset").hide();
    $("#termInfoWarning").show();
});
