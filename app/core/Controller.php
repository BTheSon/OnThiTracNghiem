<?php
namespace App\Core;

use Exception;

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
     * layout: tên file layout
     * layoutPartials = [
     *     'sidebar' => 'hocsinh/partials/menu.php',
     *     'content' => 'hocsinh/menu.php',
     * ]
     * data: [
     *     'CSS_FILE' => [
     *         'public/css/cssDangNhap.css',
     *         'public/css/cssDangNhap2.css'
     *     ],
     *     'JS_FILE' => [
     *         'public/js/jsDangNhap.js',
     *         'public/js/jsDangNhap2.js'
     *     ],
     *     'error' => 'Cần phải nhập email và mật khẩu.'
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

    private function getContentPartial(array $partials, array $layoutPartials): string {
        if (isset($partials['content']) && !empty($partials['content'])) {
            return $partials['content'];
        }
        throw new Exception("Nội dung partial không tìm thấy hoặc rỗng: " . ($layoutPartials['content'] ?? ''));
    }

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