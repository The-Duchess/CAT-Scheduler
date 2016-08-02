function isLeapYear(year) {
    if ((year % 4 == 0 && year % 100 != 0) || year % 400 == 0) {
        return true;
    } else {
        return false;
    }
}

function dateIsValid(date_string) {
    var regex_array = ( /^(\d{2})\/(\d{2})\/(\d{4})$/ ).match(date_string);
    
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
