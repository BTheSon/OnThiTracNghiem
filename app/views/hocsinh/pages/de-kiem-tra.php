<div class="exam-box">
    <div class="exam">
        <?php if(empty($data['exams'])):?>
            <div class="no-exam-decor">
                <i class="fas fa-exclamation-circle no-exam-icon"></i>  
                <p class="no-exam-message">Không có bài kiểm tra nào được tìm thấy. Vui lòng quay lại sau!</p>
            </div>
        <?php else:?>
            <?php foreach($data['exams'] as $examItem):?>
                <div class="exam-header">
                    <div class="tieu-de">
                        <span id="ten-bai-kt">Bài kiểm tra: <?=$examItem['tieu_de'] ?> </span>
                    </div>
                    <div class="mo-ta">
                        <span id="mo-ta-kt">Mô tả: <?=$examItem['mo_ta'] ?></span>
                    </div>
                </div>
                
                <div class="exam-info">
                    <div class="info-item">
                        <div class="info-label">
                            Thời gian làm bài: 
                            <i><?=$examItem['tg_phut'] ?></i> 
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            Tên lớp:  
                            <b> <?=$examItem['ten_lop'] ?></b>
                        </div>
                    </div>
                </div>
                <div class="lam-bai-btn">
                    <button id="start-exam-btn" onclick="window.location.href = 'exam/on-exam/<?=$examItem['de_thi_id'] ?>'">Bắt đầu làm bài</button>
                </div>
            <?php endforeach;?>
        <?php endif;?>
    </div>

    <!-- Tìm kiếm đề kiểm tra -->
    <div class="search-section">
        <div class="search-title">Tìm kiếm đề kiểm tra</div>
        <div class="search-box">
            <input type="text" id="search" placeholder="Nhập tên đề kiểm tra, môn học hoặc lớp...">
            <button id="search-btn">Tìm kiếm</button>
        </div>  

        <div class="history-exam">
            <div class="history-item" onclick="handleItemClick('math-test-1')">
                <div class="exam-title">
                    <strong>Bài kiểm tra Toán học - Đề số 1</strong>
                </div>
                <div class="exam-class">
                    <span>Lớp 10A1</span>
                </div>
                <div class="exam-teacher">
                    <span>Thầy Nguyễn Văn A</span>
                </div>
                <div class="exam-score">
                    <span>8.5/10</span>
                </div>
            </div>
            <div class="history-item" onclick="handleItemClick('physics-test-2')">
                <div class="exam-title">
                    <strong>Bài kiểm tra Vật lý - Đề số 2</strong>
                </div>
                <div class="exam-class">
                    <span>Lớp 10B1</span>
                </div>
                <div class="exam-teacher">
                    <span>Cô Trần Thị B</span>
                </div>
                <div class="exam-score">
                    <span>bạn chưa làm</span>
                </div>
            </div>
            <!-- Additional dummy items can be added here -->
        </div>
    </div>
</div>
