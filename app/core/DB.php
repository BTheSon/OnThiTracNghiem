<?php
namespace App\Core;

use PDO;
use PDOException;   
use PDOStatement;
use RuntimeException;

class DB
{
    private static ?DB $instance = null;
    private PDO $connection;
    /**
     * Khởi tạo đối tượng DB, tự động kết nối đến cơ sở dữ liệu.
     * Hàm này là private để đảm bảo sử dụng Singleton pattern.
     */
    private function __construct()
    {
        $this->connect();
    }

    /**
     * Lấy instance duy nhất của lớp DB (Singleton pattern).
     *
     * @return DB Đối tượng DB duy nhất.
     */
    
    public static function getInstance(): DB
    {
        if (self::$instance === null) {
            self::$instance = new DB();
        }
        return self::$instance;
    }
    /**
     * Kết nối đến cơ sở dữ liệu sử dụng PDO.
     * Thiết lập các thuộc tính cho PDO như chế độ lỗi và kiểu fetch mặc định.
     * Nếu kết nối thất bại sẽ dừng chương trình và hiển thị thông báo lỗi.
     *
     * @return void
     */
    
    private function connect(): void {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $this->connection = new PDO($dsn, DB_USER, DB_PASSWORD);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    /**
     * Thực thi câu lệnh SQL với các tham số truyền vào (nếu có).
     *
     * @param string $sql Câu lệnh SQL cần thực thi.
     * @param array $params Mảng các tham số truyền vào cho câu lệnh SQL.
     * @return PDOStatement Đối tượng PDOStatement sau khi thực thi.
     */
    
    public function query(string $sql, array $params = []): PDOStatement {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new RuntimeException('SQL Error! : ' . $e->getMessage());
        }
    }

    /**
     * Thực thi câu lệnh SQL và trả về một dòng kết quả đầu tiên.
     *
     * @param string $sql Câu lệnh SQL cần thực thi.
     * @param array $params Mảng các tham số truyền vào cho câu lệnh SQL.
     * @return mixed Một dòng dữ liệu hoặc false nếu không có dữ liệu.
     */
    public function fetch(string $sql, array $params = []): mixed {
        return $this->query($sql, $params)->fetch();
    }

    /**
     * Thực thi câu lệnh SQL và trả về tất cả các dòng kết quả.
     *
     * @param string $sql Câu lệnh SQL cần thực thi.
     * @param array $params Mảng các tham số truyền vào cho câu lệnh SQL.
     * @return array Mảng các dòng dữ liệu.
     */
    public function fetchAll(string $sql, array $params = []): array {
        return $this->query($sql, $params)->fetchAll();
    }

    /**
     * Thực thi câu lệnh SQL (INSERT, UPDATE, DELETE) và trả về số dòng bị ảnh hưởng.
     *
     * @param string $sql Câu lệnh SQL cần thực thi.
     * @param array $params Mảng các tham số truyền vào cho câu lệnh SQL.
     * @return int Số dòng bị ảnh hưởng bởi câu lệnh SQL.
     */
    public function execute(string $sql, array $params = []): int {
        return $this->query($sql, $params)->rowCount();
    }
}