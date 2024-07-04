document.addEventListener("DOMContentLoaded", function(){
  var element = document.getElementById("myToast");

  var myToast = new bootstrap.Toast(element);

  btn.addEventListener("click", function(){
      myToast.show();
  });
});