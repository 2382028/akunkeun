
$(document).ready(function () {
    var t = $('.data-table').DataTable({
        columnDefs: [
            {
                searchable: false,
                orderable: false,
                targets: 0,
            },
        ],
        order: [[1, 'asc']],
    });
 
    t.on('order.dt search.dt', function () {
        let i = 1;
 
        t.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
            this.data(i++);
        });
    }).draw();
});

$(document).ready(function () {
    var t = $('.data-table-spby').DataTable({
        pageLength: 15, // Menampilkan 15 data per bagian
        lengthMenu: [15, 25, 50, 100],
        columnDefs: [
          {
            searchable: false,
            orderable: false,
            targets: '_all',
            
          },
        ],
    });
 
    t.on('order.dt search.dt', function () {
        let i = 1;
 
        t.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
            this.data(i++);
        });
    }).draw();
});