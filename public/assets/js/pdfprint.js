 // Generate Table and PDF Convert Button
 function showSecondButton() {
    var firstButton = document.getElementById("generateTable");
    var secondButton = document.getElementById("secondButton");
    firstButton.style.display = "none";
    secondButton.style.display = "block";
}

// Generate to PDF
var tableData = JSON.parse(localStorage.getItem('tableData'));
localStorage.removeItem('tableData');

var generateTable = document.getElementById('generateTable');
generateTable.addEventListener('click', function() {

    tableData.thead.push('Tanda Tangan');
    tableData.tbody.forEach(function(rowData) {
        rowData.push('');
    });

var theadRow = document.createElement('tr');
tableData.thead.forEach(function(header) {
    var th = document.createElement('th');
    th.innerHTML = header;
    theadRow.appendChild(th);
});

document.getElementById('calculationTable1').querySelector('thead').appendChild(theadRow);

var tableBody = document.getElementById('calculationTable1').querySelector('tbody');

tableData.tbody.forEach(function(rowData) {
    var row = tableBody.insertRow();
    rowData.forEach(function(cellData) {
        var cell = row.insertCell();
        cell.innerHTML = cellData;
    });
});
});