var inputFields = document.getElementsByClassName("prevent-submit");
for (var i = 0; i < inputFields.length; i++) {
  inputFields[i].addEventListener("keydown", function(event) {
    if (event.key === "Enter") {
      event.preventDefault(); // Prevent form submission
      submitForm(); // Manually trigger form submission
    }
  });
}

