// =========================================== WORKPLACE SCRIPTS
  // Fill the table
  $.each(workplacesData, function(workplaceIndex, item) {
    var $tr = $("<tr>").append(
      $("<th scope='row'>").text(item.designation),
      $("<td class='update-workplace' contenteditable='false'>")
        .attr("data", item.id + "-description")
        .text(item.description)
    ).appendTo("#workplaces-table");
  });

// ============================================= EQUIPMENTS SCRIPTS
// Fill the table
$.each(equipmentsData, function(equipmentIndex, item) {
    var $tr = $("<tr>").append(
      $("<th scope='row' class='update-equipment' contenteditable='true'>")
        .attr("data", item.id + "-designation")
        .text(item.designation),
      $("<td class='update-equipment' contenteditable='true'>")
        .attr("data", item.id + "-type")
        .text(item.type),
      $("<td class='update-equipment' contenteditable='true'>")
        .attr("data", item.id + "-model")
        .text(item.model),
      $("<td class='update-equipment' contenteditable='true'>")
        .attr("data", item.id + "-purchase-year")
        .text(item.purchaseYear),
      $("<td class='update-equipment' contenteditable='true'>")
        .attr("data", item.id + "-value")
        .text(item.value),
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
      $("<td>"),
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