<div class="container">
    <div class="question-area">
        <div class="question-title" id="question-title"></div>
        <div class="media-block" id="media-block"></div>
        <div id="options"></div>
    </div>
    <div class="sidebar">
        <div class="timer" id="timer">Thời gian còn lại: 99:00</div>
        <div class="answered-count" id="answered-count">Số câu đã chọn: 0/0</div>
        <div class="question-grid" id="question-grid"></div>
        <button class="submit-btn" onclick="submitTest()">Nộp bài</button>
    </div>
</div>

<form class="score-form" style="margin-top: 30px; text-align: center; display: none;" id="score-form"></form>
    <h2>Kết quả bài kiểm tra</h2>
    <label for="score">Điểm của bạn:</label>
    <p type="text" id="score" name="score" readonly style="width: 60px; text-align: center;">
    </p>
    <br><br>
    <button type="button" onclick="window.location.href='student/home';" class="exit-btn">Thoát</button>
</form>
