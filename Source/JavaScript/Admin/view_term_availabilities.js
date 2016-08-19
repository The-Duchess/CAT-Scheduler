var blueTag ="<span class=\"btn btn-success btn-xs\">";
var greenTag ="<span class=\"btn btn-available btn-xs\">";
var highlightTag ="<span class=\"btn-highlight\">";
var endSpan = "</span>";
var emptyBlue = "<span class=\"btn-success\">" + endSpan;
var emptyGreen = "<span class=\"btn-available\">" + endSpan;
  

$(document).ready(function () {
  
  // given the inner html of a availabilities table cell, reset any student selection
  var resetSelection = function(cellHtml) {
    var coloredName;
    //check if there is a marked name we should reset
    if(cellHtml.includes("btn-highlight")){
      //get the colored name from the cell html
      coloredName = cellHtml.match(/(?:<span class="btn-highlight">)(.+?)(?:<\/span><br>)/)[1];
      coloredName = coloredName + "<br>";
      //remove the marked name from the cell html
      cellHtml = cellHtml.replace(/(<span class="btn-highlight">.+?<\/span><br>)/,"");
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
        var blueName = blueTag + name + endSpan;
        var greenName = greenTag + name + endSpan;
        if (name !== "Select a student to focus on...") {
          $("#termAvailabilities tbody").find("tr").each(function() {
            $(this).children(":not(:first)").each(function() {
              var cell = $(this).html();
              //first reset marked name in this cell if there is one
              var newCell = resetSelection(cell);
              //remove name from the font tag it is in
              newCell = newCell.replace(blueName + "<br>", emptyBlue);
              newCell = newCell.replace(greenName + "<br>", emptyGreen);

              //if the name has been removed from its font tag, place it back at the top with a red marker
              if (newCell.includes(emptyBlue)) {
                newCell = highlightTag + blueName + endSpan + "<br>" + newCell;
              } else if (newCell.includes(emptyGreen)) {
                newCell = highlightTag + greenName + endSpan + "<br>" + newCell;
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
