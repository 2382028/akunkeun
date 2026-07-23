document.addEventListener("DOMContentLoaded", function(){
    var toastElList = [].slice.call(document.querySelectorAll('.toast'));
    toastElList.map(function(toastEl) {
        toastEl.classList.remove('show');
        var myToast = new bootstrap.Toast(toastEl, {
            autohide: true,
            delay: 3000
        });
        myToast.show();
        return myToast;
    });
});