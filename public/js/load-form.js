console.log('load-form.js loaded');
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
            var formcontainer = document.getElementById('form-container');
            formcontainer.innerHTML = html;
            formcontainer.style.display = 'block';
        })
        .catch(error => {
            console.error('Error loading form:', error);
        });
}