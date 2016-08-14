
  

$(document).ready(function () {
  
  // given the inner html of a availabilities table cell, reset any student selection
  var resetSelection = function(cellHtml) {
    var redMarker = "<font color=\"red\">-</font>";
    var emptyBlue = "<font color=\"blue\"></font>";
    var emptyGreen = "<font color=\"green\"></font>";
    var coloredName = "nothing";
    if(cellHtml.includes(redMarker)){
      coloredName = cellHtml.match(/(?:<font color="red">-<\/font>)(.+?<br>)/)[1];
      cellHtml = cellHtml.replace(/(<font color="red">-<\/font>.+?<br>)/,"");
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
        if (name !== "Select a student to focus on...") {
          $("#termAvailabilities tbody").find("tr").each(function() {
            $(this).children(":not(:first)").each(function() {
              var cell = $(this).html();
              //console.log("current: "+ cell);
              //console.log("found: " + resetSelection(cell));
              var newCell = resetSelection(cell);
              if(newCell !== cell) {
                console.log("CHANGES V --- V --- V --- V");
              }
              console.log("Before: " + cell);
              console.log("After: " + newCell);
              var redMarker = "<font color=\"red\">-</font>";
              var blueName = "<font color=\"blue\">"+name+"</font><br>";
              var greenName = "<font color=\"green\">"+name+"</font><br>";
              var emptyBlue = "<font color=\"blue\"></font>";
              var emptyGreen = "<font color=\"green\"></font>";
              newCell = newCell.replace(blueName, emptyBlue);
              newCell = newCell.replace(greenName, emptyGreen);
              
              //console.log("current: "+ cell);
              //console.log("checking for: " + blueName);
              
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