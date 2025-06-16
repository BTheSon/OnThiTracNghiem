
<style>
    .score-container {
    margin: 50px auto;
    max-width: 400px;
    padding: 30px;
    background-color: #fdfdfd;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    text-align: center;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.score-container h2 {
    margin-bottom: 20px;
    font-size: 24px;
    color: #2c3e50;
}

.score-label {
    font-size: 18px;
    color: #34495e;
}

.score-value {
    margin-top: 10px;
    font-size: 32px;
    font-weight: bold;
    color: #27ae60;
    background-color: #ecf0f1;
    border-radius: 8px;
    display: inline-block;
    padding: 10px 20px;
}

.exit-btn {
    margin-top: 30px;
    padding: 12px 24px;
    font-size: 16px;
    color: white;
    background-color: #3498db;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.exit-btn:hover {
    background-color: #2980b9;
}

</style>
<div class="score-container" id="score-container">
    <h2>Kết quả bài kiểm tra</h2>
    <label for="score" class="score-label">Điểm của bạn:</label>
    <p id="score-value" class="score-value">8.5</p> <!-- Điểm mẫu, sẽ thay bằng JS -->
    <button type="button" onclick="window.location.href='student/home'" class="exit-btn">Thoát</button>
</div>
