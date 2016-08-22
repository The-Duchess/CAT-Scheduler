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
function isLeapYear(year) {
    if ((year % 4 == 0 && year % 100 != 0) || year % 400 == 0) {
        return true;
    } else {
        return false;
    }
}

function dateIsValid(date_string) {
    var regex_array = ( /^(\d{2})\/(\d{2})\/(\d{4})$/ ).exec(date_string);
    
    if (!regex_array) { return false; }
    
    var month = parseInt(regex_array[1], 10);
    var day = parseInt(regex_array[2], 10);
    var year = parseInt(regex_array[3], 10);

    if (year < 1946) {
        return false;
    } else if (month < 0 || month > 12) {
        return false;
    } else if (day < 0 || day > 31) {
        return false;
    } else if ((month == 4 || month == 6 || month == 9 || month == 11) && day > 30) {
        return false;
    } else if (month == 2 && day > (isLeapYear(year) ? 29 : 28)) {
        return false;
    }
    
    return true;
}

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
        if (!dateIsValid($("#endDate").val())) {
            $("#endDate").datepicker("option", {
                defaultDate: defaultEnd,
                minDate: endMin
            });
            $("#endDate").prop("disabled", false);
        } else {
            $("#endDate").datepicker("option", "minDate", endMin);
        }
        //enable due date, set default date and max date
        if (!dateIsValid($("#dueDate").val())) {
            $("#dueDate").datepicker("option", {
                defaultDate: defaultDue,
                maxDate: dueMax
            });
            $("#dueDate").prop("disabled", false);
        } else {
            $("#dueDate").datepicker("option", "maxDate", dueMax);
        }
     
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

//  click handler for mentoring button
$("#mentoringButton").click( function() {
    if ($("#mentoringCheckbox").prop("checked")) {
        $(this).removeClass("btn-success");
        $("#mentoringGlyphicon").removeClass("glyphicon-ok");
        $(this).addClass("btn-warning");
        $("#mentoringGlyphicon").addClass("glyphicon-remove");
    } else {
        $(this).removeClass("btn-warning");
        $("#mentoringGlyphicon").removeClass("glyphicon-remove");
        $(this).addClass("btn-success");
        $("#mentoringGlyphicon").addClass("glyphicon-ok");
    }
    $("#mentoringCheckbox").prop("checked", function(index, oldval) { return !oldval; });
});

//  click handler for visible button
$("#visibleButton").click( function() {
    if ($("#visibleCheckbox").prop("checked")) {
        $(this).removeClass("btn-success");
        $("#visibleGlyphicon").removeClass("glyphicon-ok");
        $(this).addClass("btn-warning");
        $("#visibleGlyphicon").addClass("glyphicon-remove");
    } else {
        $(this).removeClass("btn-warning");
        $("#visibleGlyphicon").removeClass("glyphicon-remove");
        $(this).addClass("btn-success");
        $("#visibleGlyphicon").addClass("glyphicon-ok");
    }
    $("#visibleCheckbox").prop("checked", function(index, oldval) { return !oldval; });
});

//  click handler for editable button
$("#editableButton").click( function() {
    if ($("#editableCheckbox").prop("checked")) {
        $(this).removeClass("btn-success");
        $("#editableGlyphicon").removeClass("glyphicon-ok");
        $(this).addClass("btn-warning");
        $("#editableGlyphicon").addClass("glyphicon-remove");
    } else {
        $(this).removeClass("btn-warning");
        $("#editableGlyphicon").removeClass("glyphicon-remove");
        $(this).addClass("btn-success");
        $("#editableGlyphicon").addClass("glyphicon-ok");
    }
    $("#editableCheckbox").prop("checked", function(index, oldval) { return !oldval; });
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
    //  reset values
    $("#termName").val("");
    $("#startDate").val("");
    $("#endDate").val("");
    $("#dueDate").val("");
    $("#mentoringCheckbox").prop("checked", false);
    $("#visibleCheckbox").prop("checked", false);
    $("#editableCheckbox").prop("checked", false);

    //  reset datepickers
    $("#startDate").datepicker("setDate", null);
    $("#endDate").datepicker("setDate", null);
    $("#endDate").datepicker("option", {
        defaultDate: null,
        minDate: null
    });
    $("#dueDate").datepicker("setDate", null);
    $("#dueDate").datepicker("option", {
        defaultDate: null,
        maxDate: null
    });

    //  disabled inputs
    $("#endDate").prop("disabled", true);
    $("#dueDate").prop("disabled", true);
    $("#submitButton").prop("disabled", true);
    $("#autofillButton").prop("disabled", true);

    //  assign appropriate classes to input groups and displays
    $("#nameGroup").removeClass("has-success");
    $("#nameGroup").addClass("has-error");
    $("#startGroup").removeClass("has-success");
    $("#startGroup").addClass("has-error");
    $("#endGroup").removeClass("has-success");
    $("#endGroup").addClass("has-error");
    $("#dueGroup").removeClass("has-success");
    $("#dueGroup").addClass("has-error");
    $("#mentoringButton").removeClass("btn-success");
    $("#mentoringButton").addClass("btn-warning");
    $("#mentoringGlyphicon").removeClass("glyphicon-ok");
    $("#mentoringGlyphicon").addClass("glyphicon-remove");
    $("#visibleButton").removeClass("btn-success");
    $("#visibleButton").addClass("btn-warning");
    $("#visibleGlyphicon").removeClass("glyphicon-ok");
    $("#visibleGlyphicon").addClass("glyphicon-remove");
    $("#editableButton").removeClass("btn-success");
    $("#editableButton").addClass("btn-warning");
    $("#editableGlyphicon").removeClass("glyphicon-ok");
    $("#editableGlyphicon").addClass("glyphicon-remove");

    //  reset term info
    $("#termDuration").hide();
    $("#dueOffset").hide();
    $("#termInfoWarning").show();
});


/**********  Object Initialization  **********/
///*
if ($("#termName").val().length > 0) {
    $("#nameGroup").removeClass("has-error");
    $("#nameGroup").addClass("has-success");
}

if (dateIsValid($("#startDate").val())) {
    //  initialize start date
    $("#startDate").datepicker({
        defaultDate: $("#startDate").val()
    });

    //  variables
    var currentStart = $("#startDate").datepicker("getDate");
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
    if (dateIsValid($("#endDate").val())) {
        $("#endDate").datepicker({
            minDate: endMin
        });
        $("#endDate").datepicker("setDate", $("#endDate").val());
        $("#endDate").prop("disabled", false);
        $("#endGroup").removeClass("has-error");
        $("#endGroup").addClass("has-success");
    } else {
        $("#endDate").datepicker("option", {
            defaultDate: defaultEnd,
            minDate: endMin
        });
        $("#endDate").prop("disabled", false);
    }
    //enable due date, set default date and max date
    if (dateIsValid($("#dueDate").val())) {
        $("#dueDate").datepicker({
            maxDate: dueMax
        });
        $("#dueDate").datepicker("setDate", $("#dueDate").val());
        $("#dueDate").prop("disabled", false);
        $("#dueGroup").removeClass("has-error");
        $("#dueGroup").addClass("has-success");
    } else {
        $("#dueDate").datepicker("option", {
            defaultDate: defaultDue,
            maxDate: dueMax
        });
        $("#dueDate").prop("disabled", false);
    }
 
    //  update objects
    $("#startGroup").removeClass("has-error");
    $("#startGroup").addClass("has-success");
    $("#autofillButton").prop("disabled", false);
} else {
    $("#startDate").datepicker();
    $("#endDate").datepicker();
    $("#dueDate").datepicker();
}

updateInfo();
validateInput();


//  when document fully loaded
$(document).ready( function() {
    //  Fade out alert if its being shown
    if ($("#pageAlert").prop("hidden") == false) {
        $("#pageAlert").fadeTo(7000, 500).slideUp(2000, function() {
            $("#pageAlert").slideUp(2000);
        });
    }

    //  slide down term editing panels if term selected first time
    if ($("#editTermPanelSlider").hasClass("beginSlide") == true) {
        $("#editTermPanelSlider").removeClass("beginSlide");
        $("#editTermPanelSlider").slideDown(2000, function () {
            if ($("#buttonSlider").hasClass("beginSlide") == true) {
                $("#buttonSlider").removeClass("beginSlide");
                $("#buttonSlider").slideDown(500);
            }
        });
    }
    if ($("#termInfoPanelSlider").hasClass("beginSlide") == true) {
        $("#termInfoPanelSlider").removeClass("beginSlide");
        $("#termInfoPanelSlider").slideDown(2000);
    }
});
