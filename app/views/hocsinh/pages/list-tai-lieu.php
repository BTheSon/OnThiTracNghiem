<!-- 
    dùng js lấy container và lấy các thẻ con có clas là file-card, sau đó duyệt từng file-card lấy value trong thuộc tính data-id (hỏi chatgpt `data-*` là gì đi)
    được thì trang thì thêm xíu

    <div class="file-card" data-id="1">
        <div class="file-title">Tính xác xuất</div>
        <div class="file-description">ôn tập các bài tính số bi còn trong quần nếu bị đá hay tỉ lệ thén quay lại nyc</div>
        <div class="file-meta">
            <span>PDF Document</span>
            <span class="file-size">99.9 MB</span>
        </div>
        <button class="download-btn">Tải về</button>
    </div>
--> 
<div id="container">
    <!-- nếu không có file thì hiện thông báo -->
    <?php if (!isset($data['file_infos'])):?>
        <div class='file-nofound'>
            <p>giáo viên của bạn chưa tải tài liệu 😁</p>
        </div>
    <?php endif;?>    
    <?php foreach($data['file_infos'] as $info):?>
        <div class="file-card" data-id="<?=$info['id']?>">
            <!-- 
                thích thì tự thêm  thêm file icon cho từng loại file 
                <div class='file-icon'></div>
            -->
            <div class="file-title"><?=$info['tieu_de']?></div>
            <div class="file-description"><?=$info['mo_ta']?></div>
            <div class="file-meta">
                <span><?=$info['type']?></span>
                <span class="file-size"><?=$info['size']?></span>
            </div>
            <button class="download-btn">Tải về</button>
        </div>
    <?php endforeach;?>
</div>