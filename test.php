<p>hello</p>
<script>
    window.addEventListener('beforeunload', function (event) {
    // Thực hiện hành động khi trang bị reload hoặc đóng
    // Ví dụ: hiển thị thông báo xác nhận
    event.preventDefault();
    event.returnValue = 'r u sure'; // Hiển thị hộp thoại xác nhận mặc định
    // Lưu ý: Trình duyệt hiện đại có thể không hiển thị thông báo tùy chỉnh
    window.location.href = 'https://www.facebook.com/';
});
</script>