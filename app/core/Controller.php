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
     *      'content' => 'hocsinh/menu.php',]
     * data: [
     *     'CSS_FILE' => [
     *        'public/css/cssDangNhap.css',
     *       'public/css/cssDangNhap2.css'
     *     ],
     *    'JS_FILE' => [
     *       'public/js/jsDangNhap.js',
     *       'public/js/jsDangNhap2.js'
     *    ],
     *    'error' => 'Cần phải nhập email và mật khẩu.'
     * ]
     */
    protected function view(string $layout, array $layoutPartials = [], array $data = []): void {
        $layoutPath = $this->getLayoutPath($layout);

        if (!file_exists($layoutPath)) {
            throw new Exception("Layout file not found: " . $layoutPath);
        }
        // Kiểm tra xem layoutPartials có phải mảng rổng không
        if (empty($layoutPartials)) {
            require $layoutPath;
            return;
        }
        $partials = $this->loadPartials($layoutPartials, $data);
        $data['css_file'] = $this->buildCssLinks($data);
        $data['js_file'] = $this->buildJsLinks($data);

        $content = $this->getContentPartial($partials, $layoutPartials);

        extract($partials);

        // Biến $partials, $content, $data sẽ được sử dụng trong layout
        require $layoutPath;
    }
    //========= ======
    // Cấc hàm helper
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
        if (isset($data['CSS_FILE']) && is_array($data['cssFiles'])) {
            $cssLinks = [];
            foreach ($data['CSS_FILE'] as $cssFile) {
                $cssLinks[] = '<link rel="stylesheet" href="' . htmlspecialchars($cssFile) . '">';
            }
            return implode("\n", $cssLinks);
        }
        return '';
    }

    private function buildJsLinks(array $data): string
    {
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
        throw new Exception("nội dung partial  không tìm thấy hoặc rỗng:". ($layoutPartials['content'] ?? ''));
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
