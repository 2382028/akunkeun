  $(document).ready(function() {
      $('.calculationTable').each(function() {
          var table = $(this);
          var selectElements = table.find('.mySelect');
          
          selectElements.each(function() {
              var selectElement = $(this);
              var inputElement = selectElement.closest('tr').find('.num1');
              initializeSelect2(selectElement, inputElement);
          });
      });

      function initializeSelect2(selectElement, inputElement) {
          selectElement.select2({
              templateSelection: formatOption
          });

          selectElement.on('change', function() {
              var selectedOption = $(this).find('option:selected');
              var selectedValue = parseFloat(selectedOption.attr('data-label')) || parseFloat(selectedOption.val());
              inputElement.val(selectedValue);
          });
      }

      function formatOption(option) {
          if (!option.id) {
              return option.text;
          }
          
          return option.text;
      }
  });
