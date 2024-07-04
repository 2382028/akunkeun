if (localStorage.getItem('tableData')) {
    localStorage.removeItem('tableData');
}

var tableData = {
    thead: [],
    tbody: [],
    selectedColumns: [0, 1, 2, 6, 7, 8]
};

var theadRow = document.getElementById('calculationTable1').querySelector('thead tr');
var theadCells = Array.from(theadRow.cells);
theadCells.forEach(function(cell, index) {
    if (tableData.selectedColumns.includes(index)) {
        tableData.thead.push(cell.innerHTML);
    }
});

var tableRows = document.querySelectorAll('#calculationTable1 tbody tr');
tableRows.forEach(function(row) {
    var rowData = [];
    var rowCells = Array.from(row.cells);
    rowCells.forEach(function(cell, index) {
        if (tableData.selectedColumns.includes(index)) {
            rowData.push(cell.innerHTML);
        }
    });

    tableData.tbody.push(rowData);
});

localStorage.setItem('tableData', JSON.stringify(tableData));

