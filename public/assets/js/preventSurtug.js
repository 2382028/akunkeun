const form = document.getElementById('myForm');
const input = document.getElementById('myInput');

function showCustomWarning() {
  const customMessage = 'Nomor Surat Tugas tidak boleh kosong!';
  window.alert(customMessage);
}

const externalLinks = document.querySelectorAll('a[href^="http"], a[href^="//"]');
externalLinks.forEach(link => {
  link.addEventListener('click', (event) => {
    if (input.value.trim() === '') {
      event.preventDefault();
      showCustomWarning();
    }
  });
});

const navigateButton = document.getElementById('navigateButton');
navigateButton.addEventListener('click', (event) => {
  if (input.value.trim() === '') {
    event.preventDefault();
    showCustomWarning();
  }
});

form.addEventListener('submit', (event) => {
  if (input.value.trim() === '') {
    event.preventDefault();
    showCustomWarning();
  }
});
