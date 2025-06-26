<div class="exam-box">
    <div class="exam">
        <?php if(empty($data['exams'])):?>
            <div class="no-exam-decor">
                <i class="fas fa-exclamation-circle no-exam-icon"></i>  
                <p class="no-exam-message">Hiện tại chưa có bài kiểm tra nào, chill đi :)!</p>
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
            <input type="text" id="search" placeholder="Nhập tên đề kiểm tra.">
            <button id="search-btn">Tìm kiếm</button>
        </div>  

        <div class="history-exam">
            <?php foreach($data['give_name']as $item):?>
                <div class="history-item" onclick="handleItemClick('math-test-1')">
                    <div class="exam-title">
                        <strong><?=$item['tieu_de']?></strong>
                    </div>
                    <div class="exam-class">
                        <span>Lớp: <?=$item['ten_lop']?></span>
                    </div>
                    <div class="exam-score">
                        <span>
                            <?php
                                if ($item['trang_thai'] === 'da_nop') 
                                    echo 'Điểm: ' . number_format($item['diem'] ?? 0.0, 1) . ' / 10.0';
                                else
                                    echo '<strong style="color: red;"> Bạn chưa làm</strong>';
                            ?>
                            
                        </span>
                    </div>
                </div>
            <?php endforeach;?>
        </div>
    </div>
</div>
