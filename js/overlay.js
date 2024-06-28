document.addEventListener('DOMContentLoaded', function() {
    var openFormButton = document.getElementById('openFormButton');
    var overlay = document.getElementById('overlay');

    var closeFormButton = document.getElementById('closeFormButton');

    function openForm() {
        overlay.style.display = 'block';
    }

    function closeForm() {
        document.getElementById('overlay').style.display = 'none';
    }

    openFormButton.addEventListener('click', openForm);
    closeFormButton.addEventListener('click', closeForm);
});