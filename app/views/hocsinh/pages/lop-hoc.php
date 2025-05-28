<div class ="lop-hoc">
    <div class="header-class">
        <a id="ten-lop"><?=$data['classes']['ten_lop']?></a>
        <a id="ten-gv"><?=$data['classes']['mo_ta']?></a> 
        <a id="ten-gv"><br>
            <b>Giáo viên:</b> 
            <?=$data['classes']['ten_gv']?>
        </a> 
        </div>
    <div class="tb-ktra"> 
        <i class="fa-duotone fa-solid fa-bullhorn"></i>
        <a id="thong-bao"> Thông báo tại đây </a>
    </div>
    <?php if (empty($data['documents'])): ?>
        <p class="no-file-msg">Giáo viên của bạn chưa gửi file ôn tập</p>
    <?php else:?>
        <!-- LẶP LẠI NẾU CÓ NHIỀU FILE -->
        <?php foreach($data['documents'] as $item):?>
            <div class="file-on-tap">
                <div class="ten-file">  <?=$item['tieu_de']?></div>
                <div class="mo-ta-file"> <?=$item['mo_ta']?></div>
                <div class="down-file" onclick="window.location.href='document/download/<?=$item['id']?>'">
                    <i class="fa-solid fa-download"></i>
                </div>
                <div class="ngay-dang">Ngày đăng: <?=$item['ngay_dang']?></div>
            </div>
        <?php endforeach;?>
    <?php endif; ?>
</div>