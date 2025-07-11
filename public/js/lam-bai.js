let testData = [];
let currentQuestion = 101; // Match first question ID
let timeLeft = 1800; // Default fallback
let hst_id = null;
let autoSaveInterval = 60; // Thời gian tự động lưu (giây)

// Render LaTeX with retry mechanism
function renderLatex(mediaBlock, latex) {
    if (!window.MathLive) {
        console.error('MathLive not loaded, retrying...');
        mediaBlock.innerHTML = '<div class="media-error">Đang tải công thức toán...</div>';
        setTimeout(() => renderLatex(mediaBlock, latex), 500);
        return;
    }
    try {
        const mathField = mediaBlock.querySelector('math-field');
        if (mathField) {
            mathField.value = latex;
            MathLive.renderMathInElement(mediaBlock);
            console.log(`Rendered LaTeX: ${latex}`);
        }
    } catch (error) {
        console.error('Error rendering LaTeX:', error);
        mediaBlock.innerHTML = '<div class="media-error">Lỗi hiển thị công thức toán</div>';
    }
}

// Render question
function renderQuestion() {
    const question = testData.find(q => q.id === currentQuestion);
    if (!question) {
        document.getElementById('question-title').innerText = 'Không tìm thấy câu hỏi';
        document.getElementById('options').innerHTML = '';
        document.getElementById('media-block').innerHTML = '<div class="media-error">Lỗi hiển thị câu hỏi</div>';
        return;
    }
    
    document.getElementById('question-title').innerText = question.noi_dung;
    
    // Render options
    const optionsContainer = document.getElementById('options');
    optionsContainer.innerHTML = '';
    question.dap_an.forEach((option) => {
        const optionElement = document.createElement('label');
        optionElement.className = 'option';
        if (question.answer === option.id) {
            optionElement.classList.add('selected');
        }
        optionElement.innerHTML = `
            <input type="radio" name="option" value="${option.id}" onchange="selectOption(${option.id})">
            ${option.noi_dung}
        `;
        optionsContainer.appendChild(optionElement);
    });

    // Render media block (image or MathLive formula)
    const mediaBlock = document.getElementById('media-block');
    mediaBlock.innerHTML = '';
    try {
        if (question.hinh) {
            const img = document.createElement('img');
            img.src = question.hinh;
            img.alt = 'Hình ảnh bài thi';
            img.onerror = () => {
                mediaBlock.innerHTML = '<div class="media-error">Không thể tải hình ảnh</div>';
            };
            mediaBlock.appendChild(img);
        } else if (question.cong_thuc) {
            const mathField = document.createElement('math-field');
            mathField.setAttribute('readonly', 'true');
            mathField.value = question.cong_thuc;
            mediaBlock.appendChild(mathField);
            renderLatex(mediaBlock, question.cong_thuc);
        }
    } catch (error) {
        console.error('Error rendering media:', error);
        mediaBlock.innerHTML = '<div class="media-error">Lỗi hiển thị nội dung</div>';
    }

    updateGrid();
    updateAnsweredCount();
}

// Select option
function selectOption(optionId) {
    const question = testData.find(q => q.id === currentQuestion);
    question.answer = optionId;
    renderQuestion();
}

// Render question grid
function renderGrid() {
    const grid = document.getElementById('question-grid');
    grid.innerHTML = '';
    let index = 0;
    testData.forEach(question => {
        const gridItem = document.createElement('div');
        gridItem.className = 'grid-item';
        if (question.id === currentQuestion) {
            gridItem.classList.add('active');
        }
        if (question.answer !== null) {
            gridItem.classList.add('answered');
        }
        gridItem.innerText = ++index;
        gridItem.onclick = () => {
            currentQuestion = question.id;
            renderQuestion();
        };
        grid.appendChild(gridItem);
    });
}

// Update grid
function updateGrid() {
    renderGrid();
}

// Update answered count
function updateAnsweredCount() {
    const answered = testData.filter(q => q.answer !== null).length;
    document.getElementById('answered-count').innerText = `Số câu đã chọn: ${answered}/${testData.length}`;
}

// Auto-save test data
async function autoSave() {
    if (!hst_id) {
        console.warn('Không có ID bài thi để tự động lưu');
        return;
    }

    // Prepare data for auto-save
    const questions = testData
        .filter(q => q.answer !== null)
        .reduce((acc, q) => {
            acc[q.id] = q.answer;
            return acc;
        }, {});

    const payload = {
        hst_id: hst_id,
        questions: questions
    };

    try {
        const response = await fetch(`exam/save`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        if (!response.ok) {
            throw new Error('Auto-save failed');
        }

        console.log('Tự động lưu thành công:', await response.json());
    } catch (error) {
        console.error('Lỗi khi tự động lưu:', error);
    }
}

// Timer
function startTimer() {
    const timerElement = document.getElementById('timer');
    let autoSaveCounter = autoSaveInterval; // Đếm ngược cho tự động lưu
    setInterval(() => {
        if (timeLeft <= 0) {
            timerElement.innerText = 'Hết thời gian!';
            console.log('endd');            
            submitTest();
            return;
        }
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timerElement.innerText = `Thời gian còn lại: ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
        timeLeft--;

        // Kiểm tra tự động lưu mỗi giây
        autoSaveCounter--;
        if (autoSaveCounter <= 0) {
            autoSave();
            autoSaveCounter = autoSaveInterval; // Reset đếm ngược
        }
    }, 1000);
}

// Fetch test data
async function fetchTestData() {
    try {
        const response = await fetch('exam/questions', {
            method: 'POST',
            headers: {
                'Accept': 'application/json'
            }
        });
        const result = await response.json();
        console.log('Server response:', result);
        if (result.status !== 'success') {
            throw new Error(result.message);
        }

        // Map response data to testData format
        testData = result.data.cau_hoi.map(question => ({
            id: question.id,
            noi_dung: question.noi_dung,
            hinh: question.hinh,
            cong_thuc: question.cong_thuc,
            dap_an: question.dap_an.map(da => ({
                id: da.id,
                noi_dung: da.noi_dung,
                da_dung: da.da_dung || false
            })),
            answer: null
        }));

        // Set hst_id
        hst_id = result.data.hst_id;

        // Set currentQuestion to first question ID
        if (testData.length > 0) {
            currentQuestion = testData[0].id;
        }

        // Calculate timeLeft
        timeLeft = result.data.tg_phut * 60; // Convert minutes to seconds
        console.log('timeLeft:', timeLeft); // Debug timeLeft
        if (timeLeft < 0) timeLeft = 0;

        // Initialize UI
        document.getElementById('question-title').innerText = result.data.tieu_de;
        renderQuestion();
        renderGrid();
        updateAnsweredCount();
        startTimer();
    } catch (error) {
        console.error('Error fetching test data: ', error);
        document.getElementById('question-title').innerText = 'Lỗi tải bài thi';
        document.getElementById('media-block').innerHTML = '<div class="media-error">Không thể tải dữ liệu bài thi</div>';
    }
}

// Submit test
async function submitTest() {
    if (!hst_id) {
        alert('Lỗi: Không có ID bài thi');
        return;
    }
    const cancelSubmit = !confirm("Bạn có chắc chắn muốn nộp bài?");
    if (cancelSubmit) {
        return;
    }

    // gọi lại auto save để lưu những câu hỏi cuối cùng vào bảng tạm
    autoSave();

    // Prepare data for submission
    const questions = testData
        .filter(q => q.answer !== null)
        .reduce((acc, q) => {
            acc[q.id] = q.answer;
            return acc;
        }, {});

    const payload = {
        hst_id: hst_id,
        questions: questions
    };

    try {
        const response = await fetch('exam/submit', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        if (!response.ok) {
            throw new Error('Submission failed');
        }

        const data = await response.json();
        if (data.status === "error") {
            throw new Error("Status error: " + data.message);
        }
        alert('Bài thi đã được nộp!');
        alert(data.message);
        
        if (data.status === "error") {
            return;
        }

        displayScore(data.finalPoint);
    } catch (error) {
        console.error('Error submitting test:', error);
        alert('Lỗi khi nộp bài thi');
    }
}

function displayScore(score) {
    console.log("hekio");
    document.querySelector('.container').style.display = 'none';
    document.querySelector('.score-container').style.display = 'block';
    document.getElementById('score-value').textContent = score;
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    fetchTestData();
});

window.addEventListener('unload', () => {
    navigator.sendBeacon('exam/on-crash');
});