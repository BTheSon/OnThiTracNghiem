document.addEventListener('submit', function(e) {
    if (e.target.matches('#uploadForm')) {
        e.preventDefault();
        const formData = new FormData(e.target);
        console.log('Form data:', formData);
        fetch('document/upload', {
            method: 'POST',
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
            })
            .catch(err => {
                console.error('Lỗi upload:', err);
            });
    }
});

console.log("form-add-tl.js loaded");