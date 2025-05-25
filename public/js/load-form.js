function hideForm() {
    document.getElementById('formOverlay').style.display = 'none';
}

function showForm() {
    document.getElementById('formOverlay').style.display = 'flex';
}
/**
 * 
 * @param {string} url : api trả về form html
 * @param {string} scpriptUrl : đường dẫn file js xử lí form html đó
 */
function loadForm(url, scpriptUrl) {
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
            // tải script từ file js có tên là form-add-tl.js
            var script = document.createElement('script');
            script.src = scpriptUrl;
            document.body.appendChild(script);
            showForm();
        })
        .catch(error => {
            console.error('Error loading form:', error);
        });
}

console.log('load-form.js loaded');
