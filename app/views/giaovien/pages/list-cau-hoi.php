<?php echo'<script>console.log('.json_encode($data).')</script>';?>

<h1 question-title>Danh sách câu hỏi</h1>
<!-- lặp từng câu hỏi -->
<?php foreach($data['cau_hoi_list'] as $cauhoi):?>
    <div class="question-container">
        <div class="question" data-question-id="<?=$cauhoi['id']?>" onclick="toggleAnswers(this)"><?=$cauhoi['noi_dung'];?></div>
        <div class="answers">
            
            <!-- lặp từng đáp án lưu trong câu hỏi theo key là id của đáp án -->
            <ul>

            </ul>
            <?php
            $dapAn = $data['dap_an_list'][$cauhoi['id']];
            foreach($dapAn as $dapAnItem):
            ?>
                <li class="answer" 
                    data-answer-id="<?=$dapAnItem['id']?>" 
                    data-correct="<?=$dapAnItem['da_dung'] ? 'true' : 'false'?>">
                    <?=$dapAnItem['noi_dung']?>
                </li>
            <?php endforeach;?>
        </div>
    </div>
<?php endforeach;?>
<script>
    // Tự động thêm số thứ tự cho các câu hỏi
    document.querySelectorAll('.question').forEach((question, index) => {
        question.textContent = `${index + 1}\). ${question.textContent}`;
    });

    function toggleAnswers(element) {
        const answers = element.nextElementSibling;
        answers.classList.toggle('show');
    }
</script>