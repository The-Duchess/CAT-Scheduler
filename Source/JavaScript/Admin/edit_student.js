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
    if ($("#studentUsername").val().length > 0 &&
            dateIsValid($("#joinDate").val())) {
        $("#submitButton").prop("disabled", false);
    } else {
        $("#submitButton").prop("disabled", true);
    }
}


/**********  Define Handlers  **********/
//  change handler for termName
$("#studentUsername").change( function() {
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
$("#studentUsernameClear").click( function() {
    $("#studentUsername").val("");
    $("#studentUsername").trigger("change");
});

//  change handler for start date input
$("#joinDate").change( function() {
    if (dateIsValid($(this).val())) {
        var currentJoin = $(this).datepicker("getDate");
        var defaultLeave = new Date(currentJoin);
        var leaveMin = new Date(currentJoin);
        
        //  enable leave date, set default date and min date
        if (!dateIsValid($("#leaveDate").val())) {
            $("#leaveDate").datepicker("option", {
                defaultDate: defaultLeave,
                minDate: leaveMin
            });
            $("#leaveDate").prop("disabled", false);
        } else {
            $("#leaveDate").datepicker("option", "minDate", leaveMin);
        }
        
        //  update objects
        $("#joinGroup").removeClass("has-error");
        $("#joinGroup").addClass("has-success");
    } else {
        $("#joinGroup").removeClass("has-success");
        $("#joinGroup").addClass("has-error");
    }
    validateInput();
});

//  click handler for start date clear button
$("#joinDateClear").click( function() {
    $("#joinDate").val("");
    $("#joinDate").trigger("change");
});

//  change handler for end date input
$("#leaveDate").change( function() {
    if (dateIsValid($(this).val())) {
        $("#leaveGroup").removeClass("has-warning");
        $("#leaveGroup").addClass("has-success");
    } else {
        $("#leaveGroup").removeClass("has-success");
        $("#leaveGroup").addClass("has-warning");
    }
    validateInput();
});

//  click handler for end date clear button
$("#leaveDateClear").click( function() {
    $("#leaveDate").val("");
    $("#leaveDate").trigger("change");
    
    if (dateIsValid($("#joinDate").val())) {
        var currentJoin = $("#joinDate").datepicker("getDate");
        var defaultLeave = new Date(currentJoin);

        //  calculate default dates for end and due dates
        defaultLeave.setDate(currentJoin.getDate() + 1);

        //  enable end date, set default date and min date
        $("#leaveDate").datepicker("option", {
            defaultDate: defaultLeave,
        });
    }
});

//  click handler for active button
$("#activeButton").click( function() {
    if ($("#activeCheckbox").prop("checked")) {
        $(this).removeClass("btn-success");
        $("#activeGlyphicon").removeClass("glyphicon-ok");
        $(this).addClass("btn-warning");
        $("#activeGlyphicon").addClass("glyphicon-remove");
    } else {
        $(this).removeClass("btn-warning");
        $("#activeGlyphicon").removeClass("glyphicon-remove");
        $(this).addClass("btn-success");
        $("#activeGlyphicon").addClass("glyphicon-ok");
    }
    $("#activeCheckbox").prop("checked", function(index, oldval) { return !oldval; });
});

//  click handler for reset button
$("#resetButton").click( function() {
    //  reset values
    $("#studentUsername").val("");
    $("#joinDate").val("");
    $("#leaveDate").val("");
    $("#activeCheckbox").prop("checked", false);

    //  reset datepickers
    $("#joinDate").datepicker("setDate", null);
    $("#leaveDate").datepicker("setDate", null);
    $("#leaveDate").datepicker("option", {
        defaultDate: null,
        minDate: null
    });

    //  disabled inputs
    $("#leaveDate").prop("disabled", true);
    $("#submitButton").prop("disabled", true);

    //  assign appropriate classes to input groups and displays
    $("#nameGroup").removeClass("has-success");
    $("#nameGroup").addClass("has-error");
    $("#joinGroup").removeClass("has-success");
    $("#joinGroup").addClass("has-error");
    $("#leaveGroup").removeClass("has-success");
    $("#leaveGroup").addClass("has-error");
    $("#activeButton").removeClass("btn-success");
    $("#activeButton").addClass("btn-warning");
    $("#activeGlyphicon").removeClass("glyphicon-ok");
    $("#activeGlyphicon").addClass("glyphicon-remove");
});


/**********  Object Initialization  **********/
///*
if ($("#studentUsername").val().length > 0) {
    $("#nameGroup").removeClass("has-error");
    $("#nameGroup").addClass("has-success");
}

if (dateIsValid($("#joinDate").val())) {
    //  initialize start date
    $("#joinDate").datepicker({
        defaultDate: $("#joinDate").val()
    });

    //  variables
    var currentJoin = $("#joinDate").datepicker("getDate");
    var defaultLeave = new Date(currentJoin);
    var leaveMin = new Date(currentJoin);

    //  calculate default dates for end and due dates
    defaultLeave.setDate(currentJoin.getDate() + 1);
    leaveMin.setDate(currentJoin.getDate() + 1);

    //  enable end date, set default date and min date
    if (dateIsValid($("#leaveDate").val())) {
        $("#leaveDate").datepicker({
            minDate: leaveMin
        });
        $("#leaveDate").datepicker("setDate", $("#leaveDate").val());
        $("#leaveDate").prop("disabled", false);
        $("#leaveGroup").removeClass("has-warning");
        $("#leaveGroup").addClass("has-success");
    } else {
        $("#leaveDate").datepicker();
        $("#leaveDate").datepicker("option", {
            defaultDate: defaultLeave,
            minDate: leaveMin
        });
        $("#leaveDate").prop("disabled", false);
    }
 
    //  update objects
    $("#joinGroup").removeClass("has-error");
    $("#joinGroup").addClass("has-success");
} else {
    $("#joinDate").datepicker();
    $("#leaveDate").datepicker();
}

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
    if ($("#editStudentPanelSlider").hasClass("beginSlide") == true) {
        $("#editStudentPanelSlider").removeClass("beginSlide");
        $("#editStudentPanelSlider").slideDown(2000, function () {
            if ($("#buttonSlider").hasClass("beginSlide") == true) {
                $("#buttonSlider").removeClass("beginSlide");
                $("#buttonSlider").slideDown(500);
            }
        });
    }
});
