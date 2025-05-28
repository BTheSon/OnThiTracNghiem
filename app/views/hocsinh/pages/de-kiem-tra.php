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
                        <span id="ten-bai-kt"> <?=$examItem['tieu_de'] ?> </span>
                    </div>
                    <div class="mo-ta">
                        <span id="mo-ta-kt"><?=$examItem['mo_ta'] ?></span>
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
                            <b><?=$examItem['ten_lop'] ?></b>
                        </div>
                    </div>
                </div>
                <div class="lam-bai-btn">
                    <button id="start-exam-btn">Bắt đầu làm bài</button>
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
            <div class="empty-state">
                <!-- đổ dữ liệu vào đây nếu có lịch sử làm bài kiểm tra -->
            </div>
        </div>
    </div>
</div>
