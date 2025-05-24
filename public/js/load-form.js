function hideForm() {
    document.getElementById('formOverlay').style.display = 'none';
}

function showForm() {
    document.getElementById('formOverlay').style.display = 'flex';
}

function loadForm(url) {
    // tải html từ url bàng feactch
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(html => {
            // chèn html vào div có id là form-container
            var contentForm = document.getElementById('form-content');
            contentForm.innerHTML = html;
            showForm();
        })
        .catch(error => {
            console.error('Error loading form:', error);
        });
}

console.log('load-form.js loaded');