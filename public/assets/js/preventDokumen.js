var links = document.getElementsByTagName("a");
    for (var i = 0; i < links.length; i++) {
      links[i].addEventListener("click", function(event) {
        event.preventDefault();
        alert("Link navigation is disabled.");
      });
    }

    // Form submit event handler
    var form = document.getElementById("myForm");
    form.addEventListener("submit", function(event) {
      event.preventDefault();

      var hasEmptyValue = false;

      // Loop through all the td elements
      var cells = document.getElementById("myTable").getElementsByTagName("td");
      for (var i = 0; i < cells.length; i++) {
        if (cells[i].textContent.trim() === "") {
          hasEmptyValue = true;
          break;
        }
      }

      // Show alert if there are empty values
      if (hasEmptyValue) {
        alert("Please fill in all values in the table.");
        return;
      }

      // Manually trigger the form submission
      form.submit();
    });