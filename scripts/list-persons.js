//************************************ PERSONS SCRIPTS
// Fill the table person
$.each(personsData, function(personIndex, item) {
    var $tr = $("<tr>").append(
      $("<th scope='row' class='update-person' contenteditable='true'>")
        .attr("data", item.id + "-name")
        .text(item.name),
      $("<th scope='row' class='update-person' contenteditable='true'>")
        .attr("data", item.id + "-lastname")
        .text(item.lastname),
      $("<td class='update-person' contenteditable='true'>")
        .attr("data", item.id + "-phone")
        .text(item.phone),
      $("<td class='update-person' contenteditable='true'>")
        .attr("data", item.id + "-email")
        .text(item.email),
      $("<td class='update-person' contenteditable='true'>")
        .attr("data", item.id + "-description")
        .text(item.description)
    ).appendTo("#persons-table");
  });

  // Add new person row
  $("#persons-table-label").click(function() {
    var $tr = $("<tr>").append(
      $("<td class='new-person' contenteditable='true'>")
        .text("Name"),
      $("<td>"),
      $("<td>"),
      $("<td>"),
      $("<td>")
    ).appendTo("#persons-table");
  });

  // Update the person
  $("#persons-table").on("keypress", ".update-person", function(event) {
    if (event.keyCode !== 13) {
      return;
    }

    this.blur();
    var value = $(this).text();
    var parameters = $(this).attr("data");
    $.ajax({
      url: "index.php",
      type: "post",
      data: {
        parameters: parameters,
        value: value,
        type: "person"
      },
      error: function() {
        alert("Name is invalid!");
      },
      success: function(){
        alert("New person set!");
      }
    });
  });

  // Create new person
  $("#persons-table").on("keypress", ".new-person", function(event) {
    if (event.keyCode !== 13) {
      return;
    }

    this.blur();
    var parameters = $(this).text();
    $.ajax({
      url: "index.php",
      type: "post",
      data: {
        parameters: parameters,
        type: "person"
      },
      error: function() {
        alert("Name is invalid!");
      },
      success: function(){
        alert("New person set!");
        location.reload();
      }
    });
  });