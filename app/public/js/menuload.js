
function loadPage(url) {
    fetch(url)
        .then(res => {
            if (!res.ok) throw new Error('Không thể tải trang!');
            return res.text();
        })
        .then(html => {
            document.getElementById('main-content').innerHTML = html;
        })
        .catch(err => {
            document.getElementById('main-content').innerHTML = `<p style="color:red;">${err.message}</p>`;
        });
}

