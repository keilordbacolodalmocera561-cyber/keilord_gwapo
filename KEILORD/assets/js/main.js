document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', () => {
        const productId = button.dataset.productId;

        fetch('../add_to_cart.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'product_id=' + productId
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showMessage(data.message);
            } else {
                showMessage(data.message, true);
            }
        })
        .catch(err => {
            showMessage("Something went wrong", true);
        });
    });
});

function showMessage(message, isError = false) {
    const msgBox = document.createElement('div');
    msgBox.textContent = message;
    msgBox.style.position = 'fixed';
    msgBox.style.top = '20px';
    msgBox.style.right = '20px';
    msgBox.style.background = isError ? '#e63946' : '#2a9d8f';
    msgBox.style.color = 'white';
    msgBox.style.padding = '12px 20px';
    msgBox.style.borderRadius = '8px';
    msgBox.style.zIndex = 9999;
    msgBox.style.fontSize = '1em';
    msgBox.style.boxShadow = '0 4px 10px rgba(0,0,0,0.2)';
    document.body.appendChild(msgBox);

    setTimeout(() => msgBox.remove(), 2000);
}