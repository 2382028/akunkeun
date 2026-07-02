function calculateRowResult($row) {
  var num1 = parseFloat($row.find('.num1').val()) || 0;
  var num2 = parseFloat($row.find('.num2').val()) || 0;
  var num3 = parseFloat($row.find('.num3').val()) || 0;
  var num4 = parseFloat($row.find('.num4').val()) || 0;
  var num5 = parseFloat($row.find('.num5').val()) || 0;
  var num6 = parseFloat($row.find('.num6').val()) || 0;
  var desimalNum2 = num2 / 100;
  var desimalNum3 = num3 / 100;
  var desimalNum4 = num4 / 100;
  var desimalNum5 = num5 / 100;
  var result = num1 + num2 + num3 +num4 + num5 + num6;

  console.log(num1, num2, num3, num4, num5, result);

  $row.find('.result').val(result);
  calculateTotal($row.closest('.calculationTable'));
  calculateSummaryTotal();
}

function calculateTotal($table) {
  var total = 0;

  $table.find('tbody tr').each(function() {
    var result = parseFloat($(this).find('.result').val()) || 0;
    total += result;
  });

  $table.find('.total').val(total);
}

function calculateSummaryTotal() {
$('#summaryTableBody').empty();

var grandTotal = 0;

$('.calculationTable').each(function() {
    var tableId = $(this).attr('name');
    var total = parseFloat($(this).find('.total').val()) || 0;
    grandTotal += total;

    $('#summaryTableBody').append('<tr><td>' + tableId + '</td><td>' + total + '</td></tr>');
});

$('#summaryTableBody').append('<tr><td><strong>Grand Total:</td><td><strong>' + grandTotal + '</td></tr>');
}




// Event delegation to handle dynamically added elements
$(document).on('change', '.calculationTable .num1, .calculationTable .num2, .calculationTable .num3, .calculationTable .num4, .calculationTable .num5, .calculationTable .num6', function() {
  var $row = $(this).closest('tr');
  calculateRowResult($row);
});

// Calculate totals and summary total on page load
$(document).ready(function() {
  $('.calculationTable').each(function() {
    calculateTotal($(this));
  });

  calculateSummaryTotal();
});