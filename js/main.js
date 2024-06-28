function toggleEditMode() {
    var balanceElement = document.getElementById('balance');
    balanceElement.contentEditable = true;
    if (balanceElement.isContentEditable) {
        balanceElement.focus();
    }
}

function saveEditedBalance(newBalance, account_id) {
    var balanceElement = document.getElementById('balance');
    balanceElement.contentEditable = false;
    if (newBalance !== null && isValidBalance(newBalance)) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/ajaxPHP/updateBalance.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    alert(response.message);
                } else {
                    console.error('Сталася помилка під час оновлення балансу: ' + xhr.status);
                }
            }
        };


        var params = 'newBalance=' + encodeURIComponent(newBalance) + '&accountId=' + encodeURIComponent(account_id);

        xhr.send(params);
    } else {
        alert("Please enter a valid number not exceeding 9 digits.");
    }
}
function isValidBalance(value) {
    const regex = /^[0-9]{1,9}$/;
    return regex.test(value);
}