document.addEventListener('DOMContentLoaded', () => {
    let termItems = document.querySelectorAll('.term-list-item');

    termItems.forEach(item => {
        item.addEventListener('click', function() {
            let term = this.getAttribute('data-term');
            selectTerm(term);
        });
    });
});

function selectTerm(term) {
    saveTermToSession(term);
    window.location.reload();
}
function saveTermToSession(term) {
    fetch('/ajaxPHP/saveTerm.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({ term: term })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            console.log('Термін успішно збережений у сесії');
        } else {
            console.error('Помилка при збереженні терміну у сесії', data.message);
        }
    });
}