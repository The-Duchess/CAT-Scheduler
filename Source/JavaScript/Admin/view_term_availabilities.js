var redMarker = "<font color=\"red\">-</font>";
var emptyBlue = "<font color=\"blue\"></font>";
var emptyGreen = "<font color=\"green\"></font>";
  

$(document).ready(function () {
  
  // given the inner html of a availabilities table cell, reset any student selection
  var resetSelection = function(cellHtml) {
    
    var coloredName;
    //check if there is a marked name we should reset
    if(cellHtml.includes(redMarker)){
      //get the colored name from the cell html
      coloredName = cellHtml.match(/(?:<font color="red">-<\/font>)(.+?<br>)/)[1];
      //remove the marked name from the cell html
      cellHtml = cellHtml.replace(/(<font color="red">-<\/font>.+?<br>)/,"");
      //place the colored name back where it used to be
      if(cellHtml.includes(emptyBlue)) {
        cellHtml = cellHtml.replace(emptyBlue, coloredName);
      } else if(cellHtml.includes(emptyGreen)) {
        cellHtml = cellHtml.replace(emptyGreen, coloredName);
      }
    }
    return cellHtml;
  };
  
  
    $('#studentsForm').on('submit', function(e) {
        e.preventDefault();
        var name = $(this).children().first().children(":selected").text();
        var blueName = "<font color=\"blue\">"+name+"</font><br>";
        var greenName = "<font color=\"green\">"+name+"</font><br>";
        if (name !== "Select a student to focus on...") {
          $("#termAvailabilities tbody").find("tr").each(function() {
            $(this).children(":not(:first)").each(function() {
              var cell = $(this).html();
              //first reset marked name in this cell if there is one
              var newCell = resetSelection(cell);
              
              //remove name from the font tag it is in
              newCell = newCell.replace(blueName, emptyBlue);
              newCell = newCell.replace(greenName, emptyGreen);
              
              //if the name has been removed from its font tag, place it back at the top with a red marker
              if (newCell.includes(emptyBlue)) {
                newCell = redMarker + blueName + newCell;
              } else if (newCell.includes(emptyGreen)) {
                newCell = redMarker + greenName + newCell;
              }
              $(this).html(newCell);
            });
          });
        }
        
    });
    
    $('#studentReset').on('click', function(e) {
      $("#termAvailabilities tbody").find("tr").each(function() {
        $(this).children(":not(:first)").each(function() {
          var cell = $(this).html();
          var newCell = resetSelection(cell);
          $(this).html(newCell);
        });
      });
    });
    
});