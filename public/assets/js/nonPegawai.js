$(document).ready(function() {
  // Initialize Select2
  $('#pegawaiDropdown').select2({
    dropdownParent: $('#non_pegawai')
  });

  // Check if the selected value is empty
  function checkSelectedValue() {
    var selectedValue = $('#pegawaiDropdown').val();
    if (selectedValue === '') {
      $('#triggerButton').prop('disabled', false);
    } else {
      $('#triggerButton').prop('disabled', true);
    }
  }

  // Call checkSelectedValue on initialization
  checkSelectedValue();

  // Triggered when the select2 value changes
  $('#pegawaiDropdown').on('change', function() {
    checkSelectedValue();
  });

  $('#openModalButton').click(function() {
    $('#non_pegawai').modal('show');
  });

  $('#triggerButton').click(function() {
    $('#formContainer').collapse('toggle');
    $('#triggerButton').prop('disabled', true);
    $('#buttonContainer').html('<button id="showDropdownButton" class="btn btn-primary btn-sm">Tampil Daftar Non-Pegawai</button>');
    $('#pegawaiDropdown').select2('destroy'); // Destroy Select2 instance
    $('#pegawaiDropdown').hide(); // Hide the Select2 dropdown
    $('label[for="pegawaiDropdown"]').hide(); // Hide the label
  });

  // Delegate the event handling to a parent element
  $(document).on('click', '#showDropdownButton', function() {
    $('#formContainer').collapse('hide');
    $('#buttonContainer').html(
      '<button id="triggerButton" class="btn btn-primary btn-sm">Tambah Non-Pegawai</button>'
    );
    $('#pegawaiDropdown').select2({
      dropdownParent: $('#non_pegawai')
    }); // Reinitialize Select2
    $('#pegawaiDropdown').show(); // Show the Select2 dropdown
    $('label[for="pegawaiDropdown"]').show(); // Show the label

    // Bind the click event to the new trigger button
    $('#triggerButton').click(function() {
      $('#formContainer').collapse('toggle');
      $('#triggerButton').prop('disabled', true);
      $('#buttonContainer').html(
        '<button id="showDropdownButton" class="btn btn-primary btn-sm">Tampil Daftar Non-Pegawai</button>'
      );
      $('#pegawaiDropdown').select2('destroy'); // Destroy Select2 instance
      $('#pegawaiDropdown').hide(); // Hide the Select2 dropdown
      $('label[for="pegawaiDropdown"]').hide(); // Hide the label
    });
  });

  $('#nonPegawaiForm').submit(function(e) {
    e.preventDefault();
    // Handle the form submission here
    var nonPegawaiName = $('#nonPegawaiName').val();
    // Perform any necessary actions with the form data

    // Reset the form
    $('#nonPegawaiForm')[0].reset();

    // Hide the accordion and enable the trigger button
    $('#formContainer').collapse('hide');
    $('#buttonContainer').html(
      '<button id="triggerButton" class="btn btn-primary btn-sm">Tambah Non-Pegawai</button>'
    );
    $('#pegawaiDropdown').select2('open');
    $('label[for="pegawaiDropdown"]').show(); // Show the label
  });

  // Associate label click event with select dropdown
  $('label[for="pegawaiDropdown"]').on('click', function() {
    $('#pegawaiDropdown').select2('open');
  });
});
