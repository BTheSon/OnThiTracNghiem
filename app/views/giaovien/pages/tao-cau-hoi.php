<div class="quiz-form-wrapper">
        <div class="quiz-header">
            <h1 class="quiz-form-title">
                <i class="fas fa-question-circle"></i>
                Tạo Câu Hỏi Trắc Nghiệm
            </h1>
        </div>
        
        <div class="quiz-form-body">
            <form method="post" id="addQuestionForm">
                <!-- Thông tin cơ bản -->
                <div class="quiz-step">
                    <div class="quiz-step-title">
                        <i class="fas fa-info-circle"></i>
                        Thông tin cơ bản
                    </div>
                    
                    <div class="quiz-grid">
                        <div>
                            <label for="difficultyLevel" class="quiz-label">
                                <i class="fas fa-signal"></i> Độ khó
                            </label>
                            <select name="do_kho" id="difficultyLevel" class="quiz-select" required>
                                <option value="">Chọn mức độ</option>
                                <option value="1">Mức 1 - Dễ</option>
                                <option value="2">Mức 2 - Trung bình</option>
                                <option value="3">Mức 3 - Khó</option>
                                <option value="4">Mức 4 - Rất khó</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Nội dung câu hỏi -->
                <div class="quiz-step">
                    <div class="quiz-step-title">
                        <i class="fas fa-edit"></i>
                        Nội dung câu hỏi
                    </div>
                    
                    <label for="questionContent" class="quiz-label">
                        <i class="fas fa-question"></i> Câu hỏi
                    </label>
                    <textarea name="noi_dung" id="questionContent" class="quiz-textarea" required 
                              placeholder="Nhập nội dung câu hỏi..."></textarea>
                    <div class="quiz-help-text">Viết câu hỏi rõ ràng và dễ hiểu</div>
                </div>

                <!-- Các đáp án -->
                <div class="quiz-step">
                    <div class="quiz-step-title">
                        <i class="fas fa-list"></i>
                        Các lựa chọn
                    </div>
                    
                    <div class="quiz-answers-grid">
                        <div>
                            <label for="answerOption1" class="quiz-label">
                                <i class="fas fa-circle"></i> Đáp án A
                            </label>
                            <input type="text" name="dap_an_1" id="answerOption1" class="quiz-text-input" required 
                                   placeholder="Đáp án A...">
                        </div>
                        <div>
                            <label for="answerOption2" class="quiz-label">
                                <i class="fas fa-circle"></i> Đáp án B
                            </label>
                            <input type="text" name="dap_an_2" id="answerOption2" class="quiz-text-input" required 
                                   placeholder="Đáp án B...">
                        </div>
                        <div>
                            <label for="answerOption3" class="quiz-label">
                                <i class="fas fa-circle"></i> Đáp án C
                            </label>
                            <input type="text" name="dap_an_3" id="answerOption3" class="quiz-text-input" required 
                                   placeholder="Đáp án C...">
                        </div>
                    </div>
                </div>

                <!-- Đáp án đúng -->
                <div class="quiz-step">
                    <div class="quiz-step-title">
                        <i class="fas fa-check-circle"></i>
                        Đáp án chính xác
                    </div>
                    
                    <div class="quiz-correct-answer">
                        <label for="correctAnswer" class="quiz-label">
                            <i class="fas fa-star"></i> Đáp án đúng
                        </label>
                        <input type="text" name="dap_an_4" id="correctAnswer" class="quiz-text-input" required 
                               placeholder="Nhập đáp án đúng...">
                        <div class="quiz-help-text">Đây là đáp án chính xác cho câu hỏi</div>
                    </div>
                </div>

                <!-- Nút submit -->
                <button type="submit" class="quiz-submit-btn">
                    <i class="fas fa-plus-circle"></i>
                    Tạo Câu Hỏi
                </button>
            </form>
        </div>
    </div>