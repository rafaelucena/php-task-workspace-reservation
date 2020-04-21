//************************************ PERSONS SCRIPTS
// Fill the table person
$.each(personsData, function(personIndex, item) {
    var $tr = $("<tr>").append(
      $("<th scope='row'>").text(item.fullname),
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
        .text("Lasname, Name"),
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

// =========================================== WORKPLACE SCRIPTS
  // Fill the table
  $.each(workplacesData, function(workplaceIndex, item) {
    var $tr = $("<tr>").append(
      $("<th scope='row' class='update-workplace' contenteditable='true'>")
        .attr("data", item.id + "-designation")
        .text(item.designation),
      $("<td class='update-workplace' contenteditable='true'>")
        .attr("data", item.id + "-description")
        .text(item.description)
    ).appendTo("#workplaces-table");
  });

  // Add new workplace row
  $("#workplaces-table-label").click(function() {
    var $tr = $("<tr>").append(
      $("<td class='new-workplace' contenteditable='true'>")
        .text("A51"),
      $("<td>")
    ).appendTo("#workplaces-table");
  });

  // Update the workplace
  $("#workplaces-table").on("keypress", ".update-workplace", function(event) {
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
        type: "workplace"
      },
      error: function() {
        alert("Name is invalid!");
      },
      success: function(){
        alert("New workplace set!");
        location.reload();
      }
    });
  });

  // Create new workplace
  $("#workplaces-table").on("keypress", ".new-workplace", function(event) {
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
        type: "workplace"
      },
      error: function() {
        alert("Name is invalid!");
      },
      success: function(){
        alert("New workplace set!");
        location.reload();
      }
    });
  });

// ============================================= EQUIPMENTS SCRIPTS
// Fill the table
$.each(equipmentsData, function(equipmentIndex, item) {
    var $tr = $("<tr>").append(
      $("<th scope='row'>").text(item.designation),
      $("<td class='update-equipment' contenteditable='true'>")
        .attr("data", item.id + "-type")
        .text(item.type),
      $("<td class='update-equipment' contenteditable='true'>")
        .attr("data", item.id + "-description")
        .text(item.description),
      $("<td class='update-equipment' contenteditable='true'>")
        .attr("data", item.id + "-workplace")
        .text(item.workplace)
    ).appendTo("#equipments-table");
  });

  // Add new equipment row
  $("#equipments-table-label").click(function() {
    var $tr = $("<tr>").append(
      $("<td class='new-equipment' contenteditable='true'>")
        .text("Nokia 3220"),
      $("<td>"),
      $("<td>"),
      $("<td>")
    ).appendTo("#equipments-table");
  });

  // Update the equipment
  $("#equipments-table").on("keypress", ".update-equipment", function(event) {
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
        type: "equipment"
      },
      error: function() {
        alert("Equipment is invalid!");
      },
      success: function(){
        alert("New equipment set!");
        location.reload();
      }
    });
  });

  // Create new equipment
  $("#equipments-table").on("keypress", ".new-equipment", function(event) {
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
        type: "equipment"
      },
      error: function() {
        alert("Equipment is invalid!");
      },
      success: function(){
        alert("New equipment set!");
        location.reload();
      }
    });
  });

// =================================== SCHEDULE SCRIPTS
// Fill table
$.each(tableData, function(tableIndex, list) {
    $.each(list, function(listIndex, item) {
      var personData = item.id + "-" + tableIndex;
      var $tr = $("<tr>").append(
        $("<th scope='row'>").text(item.workplace),
        $("<td>").text(item.equipment),
        $("<td>").text(tableIndex),
        $("<td class='schedule-person' contenteditable='true'>").attr("data", personData).text(item.person)
      ).appendTo("#schedule-table-" + tableIndex.replace(/-/g, ""));
    });
  });

  // Edit person on the schedule table
  $(".schedule-table").on("keypress", ".schedule-person", function(event) {
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
        type: "schedule"
      },
      error: function() {
        alert("Name is invalid!");
      },
      success: function(){
        alert("New schedule set!");
      }
    });
  });