document.getElementById('exportButton').addEventListener('click', function() {
	var table = document.getElementById('myTable');
	var rows = table.rows;
	var data = [];

	for (var i = 0; i < rows.length; i++) {
	  var rowData = [];
	  var cells = rows[i].cells;

	  for (var j = 0; j < cells.length; j++) {
		var cell = cells[j];

		if (cell.querySelector('.dateInput')) {
		  var dateInput = cell.querySelector('.dateInput');
		  var enteredDate = dateInput.value;

		  // Format the entered date as desired (e.g., dd-mm-yyyy)
		  var formattedDate = formatDate(enteredDate);

		  rowData.push(formattedDate);
		} else {
		  rowData.push(cell.innerText);
		}
	  }

	  data.push(rowData);
	}

	// Create a workbook and worksheet
	var workbook = XLSX.utils.book_new();
	var worksheet = XLSX.utils.aoa_to_sheet(data);

	// Add the worksheet to the workbook
	XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet 1');

	// Convert the workbook to Excel buffer
	var excelBuffer = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });

	// Save the Excel buffer as a file
	saveAs(new Blob([excelBuffer], { type: 'application/octet-stream' }), 'Detail Laporan Perjalanan Dinas.xlsx');
  });

  // Function to format the date as dd-mm-yyyy
  function formatDate(date) {
	var parts = date.split('-');
	var day = parts[2];
	var month = parts[1];
	var year = parts[0];

	return day + '-' + month + '-' + year;
  }