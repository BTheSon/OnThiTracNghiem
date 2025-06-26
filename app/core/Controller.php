<?php
namespace App\Core;

use Exception;

/**
 * Controller class
 * Cơ sở cho các controller trong ứng dụng.
 * Cung cấp các phương thức để tải model và view.
 */
class Controller
{
    // tải model sử dụng autoloading
    protected function model(string $model): Model {
        $modelClass = 'App\\Models\\' . $model;
        if (class_exists($modelClass)) {
            return new $modelClass;
        } else {
            throw new Exception("Model class not found: " . $modelClass);
        }
    }
    
    /**
     * @param string $layout: tên file layout, ví dụ: 'layouts/main_layout.php'
     * @param array $layoutPartials: mảng các phần của layout, ví dụ:
     * [
     *     'sidebar' => 'partials/sidebar.php',
     *     'content' => 'partials/content.php',
     * ]
     * @param array $data: dữ liệu để truyền vào view, ví dụ:
     * [
     *     'title' => 'Trang chủ',
     *     'CSS_FILE' => ['public/css/style.css'],
     *     'JS_FILE' => ['public/js/script.js']
     * ]
     */
    protected function view(string $layout, array $layoutPartials = [], array $data = []): void {
        // Kiểm tra nếu $layout rỗng
        if (empty($layout)) {
            if (isset($layoutPartials['content'])) {
                $contentPath = BASE_VIEWS_DIR . '/' . $layoutPartials['content'];
                $contentPath = $this->normalizePath($contentPath);
                $data['css_file'] = $this->buildCssLinks($data);
                $data['js_file'] = $this->buildJsLinks($data);
                if (file_exists($contentPath)) {
                    echo $this->getHtml($contentPath, $data);
                    return;
                }
            }
            // Nếu không có layout và content, hiển thị thông báo
            echo "<!-- Không tìm thấy layout hoặc nội dung -->";
            return;
        }

        $layoutPath = $this->getLayoutPath($layout);

        // Nếu layout không tồn tại, hiển thị trực tiếp partial content (nếu có)
        if (!file_exists($layoutPath)) {
            if (isset($layoutPartials['content'])) {
                $contentPath = BASE_VIEWS_DIR . '/' . $layoutPartials['content'];
                $contentPath = $this->normalizePath($contentPath);
                if (file_exists($contentPath)) {
                    echo $this->getHtml($contentPath, $data);
                    return;
                }
            }
            // Nếu không có content hoặc content không tồn tại, hiển thị thông báo mặc định
            echo "<!-- Không tìm thấy layout hoặc nội dung -->";
            return;
        }

        // Nếu không có layoutPartials, hiển thị layout trực tiếp
        if (empty($layoutPartials)) {
            require $layoutPath;
            return;
        }

        $partials = $this->loadPartials($layoutPartials, $data);
        $data['css_file'] = $this->buildCssLinks($data);
        $data['js_file'] = $this->buildJsLinks($data);

        $content = $this->getContentPartial($partials, $layoutPartials);

        extract($partials);
        extract(['content' => $content]); // Đảm bảo biến $content luôn tồn tại

        require $layoutPath;
    }

    //========= ======
    // Các hàm helper
    //============ ===
    private function getLayoutPath(string $layout): string {
        $layoutPath = BASE_VIEWS_DIR . '/' . $layout;
        return $this->normalizePath($layoutPath);
    }
    /** 
     * cách hoạt động:
     * - Duyệt qua mảng $layoutPartials
     * - Với mỗi phần tử, kiểm tra xem file partial có tồn tại không
     * - Nếu tồn tại, gọi hàm getHtml để lấy nội dung HTML của partial
     * - Nếu không tồn tại, gán giá trị rỗng cho phần tử tương ứng
     */
    private function loadPartials(array $layoutPartials, array $data): array {
        $partials = [];
        foreach ($layoutPartials as $key => $partialFile) {
            $partialPath = BASE_VIEWS_DIR . '/' . $partialFile;
            $partialPath = $this->normalizePath($partialPath);
            if (file_exists($partialPath)) {
                $partials[$key] = $this->getHtml($partialPath, $data);
            } else {
                $partials[$key] = '';
            }
        }
        return $partials;
    }

    /**
     * cách hoạt động:
     * - Kiểm tra xem mảng $data có chứa khóa 'CSS_FILE' không
     * - Nếu có, kiểm tra xem giá trị của khóa này là một mảng hay không
     * - Nếu là mảng, duyệt qua từng phần tử và tạo thẻ <link> cho mỗi file CSS
     * - Trả về chuỗi chứa tất cả các thẻ <link> đã được tạo
     * - Nếu không có khóa 'CSS_FILE' hoặc giá trị không phải là mảng, trả về chuỗi rỗng
     */
    private function buildCssLinks(array $data): string {
        if (isset($data['CSS_FILE']) && is_array($data['CSS_FILE'])) {
            $cssLinks = [];
            foreach ($data['CSS_FILE'] as $cssFile) {
                $cssLinks[] = '<link rel="stylesheet" href="' . htmlspecialchars($cssFile) . '">';
            }
            return implode("\n", $cssLinks);
        }
        return '';
    }

    /**
     * cách hoạt động:
     * - Kiểm tra xem mảng $data có chứa khóa 'JS_FILE' không
     * - Nếu có, kiểm tra xem giá trị của khóa này là một mảng hay không
     * - Nếu là mảng, duyệt qua từng phần tử và tạo thẻ <script> cho mỗi file JS
     * - Trả về chuỗi chứa tất cả các thẻ <script> đã được tạo
     * - Nếu không có khóa 'JS_FILE' hoặc giá trị không phải là mảng, trả về chuỗi rỗng
     */
    private function buildJsLinks(array $data): string {
        if (isset($data['JS_FILE']) && is_array($data['JS_FILE'])) {
            $jsLinks = [];
            foreach ($data['JS_FILE'] as $jsFile) {
                $jsLinks[] = '<script src="' . htmlspecialchars($jsFile) . '"></script>';
            }
            return implode("\n", $jsLinks);
        }
        return '';
    }

    /**
     * Lấy nội dung của partial 'content' từ mảng $partials.
     * Nếu không tìm thấy hoặc nội dung rỗng, ném ra ngoại lệ.
     *
     * @param array $partials Mảng chứa các partials đã được tải.
     * @param array $layoutPartials Mảng chứa các phần của layout.
     * @return string Nội dung của partial 'content'.
     * @throws Exception Nếu không tìm thấy nội dung 'content'.
     */
    private function getContentPartial(array $partials, array $layoutPartials): string {
        if (isset($partials['content']) && !empty($partials['content'])) {
            return $partials['content'];
        }
        throw new Exception("Nội dung partial không tìm thấy hoặc rỗng: " . ($layoutPartials['content'] ?? ''));
    }

    /**
     * Lấy nội dung HTML từ file view.
     * Sử dụng output buffering để lấy nội dung mà không in ra trực tiếp.
     *
     * @param string $view Đường dẫn đến file view.
     * @param array $data Dữ liệu để truyền vào view.
     * @return string Nội dung HTML của view.
     */
    private function getHtml($view, $data = []): string {
        ob_start();
        require $view;
        return ob_get_clean();
    }

    // chuẩn hóa đường dẫn
    private function normalizePath($path): string {
        return str_replace(['\\', '//'], '/', $path);
    }
}