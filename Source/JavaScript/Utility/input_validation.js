// checks if the inputted date is a leap year
// PARAMETERS:
//  year = the year to check "yyyy"
// RETURN TYPE:
//  returns a boolean value, true if the year is a leap year
//  false otherwise
function isLeapYear(year) {
    if ((year % 4 == 0 && year % 100 != 0) || year % 400 == 0) {
        return true;
    } else {
        return false;
    }
}

// checks if the inputted date is of a valid format
// PARAMETERS:
//  date_string: a string of a date
// RETURN TYPE:
//  return a boolean value, true if the year is of a valid format
//  false if it is not
function dateIsValid(date_string) {

    //use a regex to parse the string into an array
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
