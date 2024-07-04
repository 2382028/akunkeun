window.addEventListener('DOMContentLoaded', function() {
    // Get the table and links
var table = document.getElementById('perangkatOrang');
    var links = document.querySelectorAll('a');

    // Check if at least one table cell is filled
    var filledCells = Array.from(table.getElementsByTagName('td')).filter(function(cell) {
      return cell.innerHTML.trim() !== '';
    });

    if (filledCells.length === 0) {
      // Disable all the links
      links.forEach(function(link) {
        link.addEventListener('click', function(event) {
          event.preventDefault();
          alert('Perangkat Orang tidak boleh kosong!');
        });
      });
    }
  });
