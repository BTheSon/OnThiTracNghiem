# MindQuest
## Luồng hoạt động của MVC

1. **Người dùng nhập URL:**  
  Người dùng nhập một URL theo cấu trúc:  
  ```
  http://localhost/OnThiTracNghiem/Controller/Action/param1/param2
  ```

2. **Xử lý URL:**  
  - Lớp [`App`](d:/Source_Code/web/OnThiTracNghiem/app/core/App.php) nhận URL `$_GET['url'] = "Controller/Action/param1/param2"` từ trình duyệt.  
  - URL được phân tích để xác định:  
    - **Controller**: Tên controller cần gọi.  
    - **Action**: Phương thức trong controller sẽ được thực thi.  
    - **Parameters**: Các tham số được truyền vào phương thức.

3. **Tải Controller:**  
  - Lớp `App` sử dụng cơ chế autoload của [`Autoloader`](d:/Source_Code/web/OnThiTracNghiem/app/core/Autoloader.php) để tự động nạp file PHP tương ứng với controller.  
  - Controller được khởi tạo và phương thức (action) được gọi.
 
4. **Xử lý logic trong Controller:**  
  - Controller thực hiện các xử lý logic cần thiết.  
  - Nếu cần dữ liệu từ cơ sở dữ liệu, controller sẽ gọi model thông qua phương thức `model()`.

5. **Tương tác với Model:**  
  - Model thực hiện các truy vấn cơ sở dữ liệu và trả về dữ liệu cho controller.  
  - Dữ liệu này sẽ được xử lý hoặc chuyển tiếp đến view.

6. **Tải View:**  
  - Controller gọi phương thức `view()` để nạp file view tương ứng.  
  - Dữ liệu từ controller được truyền vào view để hiển thị.

7. **Hiển thị kết quả:**  
  - View kết hợp dữ liệu và giao diện để tạo ra trang HTML hoàn chỉnh.  
  - Kết quả được trả về trình duyệt để hiển thị cho người dùng.

8. **Kết thúc:**  
  - Sau khi hiển thị, ứng dụng kết thúc xử lý và giải phóng tài nguyên.


## Cấu trúc thư mục

- **config/**  
  Chứa các file cấu hình của dự án, bao gồm [constants.php](d:/Source_Code/web/OnThiTracNghiem/config/constants.php) dùng để định nghĩa các hằng số toàn cục.

- **app/**  
  Chứa mã nguồn chính của ứng dụng.  
   - **core/**  
      Chứa các lớp cơ sở của dự án:
      - [App.php](d:/Source_Code/web/OnThiTracNghiem/app/core/App.php): Lớp quản lý router, load controller và xử lý URL.
      - [Autoloader.php](d:/Source_Code/web/OnThiTracNghiem/app/core/Autoloader.php): Lớp tự động nạp các lớp theo namespace.
      - [Controller.php](d:/Source_Code/web/OnThiTracNghiem/app/core/Controller.php): Lớp cơ sở cho các controller, cung cấp phương thức load model và view.
      - [Model.php](d:/Source_Code/web/OnThiTracNghiem/app/core/Model.php): Lớp cơ sở cho các model, thiết lập kết nối CSDL và cung cấp các phương thức truy vấn.
  - **controllers/**  
    Chứa các controller của ứng dụng. Controller chịu trách nhiệm nhận request và gọi các phương thức tương ứng.
 
  - **models/**  
    Chứa các model tương ứng với các bảng hoặc thực thể dữ liệu trong database(là lớp chuyên kết nối với db).
  - **views/**  
    Chứa các file view của ứng dụng(các trang html).

- **public/**  
  Thư mục gốc của ứng dụng (DocumentRoot).  
  thư mục chứa các file tài nguyên như CSS, JavaScript, hình ảnh và các widget.

## Chức năng
- __Cấu trúc url cơ bản sau:__
  - _localhost/OnThiTracNgiem/Controller/Action/param1/param2_
  - __Controller__: loại controller sẽ sài (auth,subject)
  - __Action__: tên phương thức trong Controller sẽ chạy (auth,subject)
  - __param1/param2__: danh sách các tham số truyền vào

- **Xử lý URL và định tuyến:**  
  Lớp [`App`](d:/Source_Code/web/OnThiTracNghiem/app/core/App.php) sẽ phân tích URL, load controller tương ứng và gọi phương thức thích hợp dựa trên URL.

- **Tự động nạp (Autoloading):**  
  Lớp [`Autoloader`](d:/Source_Code/web/OnThiTracNghiem/app/core/Autoloader.php) đảm bảo tự động nạp các file PHP dựa trên namespace và cấu trúc thư mục.

- **Controller và View:**  
  Lớp cơ sở [`Controller`](d:/Source_Code/web/OnThiTracNghiem/app/core/Controller.php) cung cấp các phương thức `model()` và `view()` để load model và view trong quá trình xử lý request.

- **Kết nối CSDL và truy vấn:**  
  Lớp [`Model`](d:/Source_Code/web/OnThiTracNghiem/app/core/Model.php) thiết lập kết nối đến MySQL sử dụng PDO, đồng thời cung cấp các phương thức để thực hiện các truy vấn SQL.

## Hướng dẫn sử dụng

1. **Cài đặt và cấu hình:**  
   - Điều chỉnh các thông số kết nối CSDL trong file [constants.php](d:/Source_Code/web/OnThiTracNghiem/config/constants.php).
   

2. **Sử dụng các lớp cơ sở:**
   - **Controller:** Tạo file PHP trong thư mục [app/controllers](d:/Source_Code/web/OnThiTracNghiem/app/controllers). Các controller sẽ được tự động load bởi lớp [`App`](d:/Source_Code/web/OnThiTracNghiem/app/core/App.php).
   - **View:** Trong controller, sử dụng phương thức `view()` (được kế thừa từ lớp [`Controller`](d:/Source_Code/web/OnThiTracNghiem/app/core/Controller.php) để load view. Ví dụ:
     ````php
     // Trong controller của bạn
     $this->view('home', ['data' => $data]);  // tên trang (home) và mảng các tham số sẽ truyền vào trang để hiển thị
     ````
   - **Model:** Để sử dụng model, gọi phương thức `model()` từ controller để tải model tương ứng:
     ````php
     // Trong controller của bạn
     $userModel = $this->model('UserModel');
     $userData = $userModel->fetchAll("SELECT * FROM users");
     ````

4. **Quy tắc đặt tên namespace và sử dụng use:**
   - __Lí do sài:__ 
      khi dùng use thì các class sẽ tự động load mà không cần require_one nhiều lần, dễ dẫn đến lỗi xàm
   - **Đặt tên namespace:**  
     Namespace nên được đặt theo cấu trúc thư mục của dự án. Ví dụ, các lớp trong thư mục [controllers](http://_vscodecontentref_/0) có thể đặt namespace là [Controllers](http://_vscodecontentref_/1), các lớp trong [models](http://_vscodecontentref_/2) có thể sử dụng [Models](http://_vscodecontentref_/3).  
     Ví dụ:
     ````php
     <?php
     namespace App\Controllers;

     class HomeController {
         // ...existing code...
     }
     ````
   - **Sử dụng use:**  
     Để sử dụng các lớp từ namespace khác, bạn cần import chúng bằng từ khóa `use`. Điều này giúp viết code ngắn gọn và dễ quản lý hơn.
     Ví dụ, nếu bạn muốn sử dụng lớp `UserModel` có namespace [Models](http://_vscodecontentref_/4) trong controller:
     ````php
     <?php
     namespace App\Controllers;

     use App\Models\UserModel;

     class HomeController {
         public function index() {
             $userModel = new UserModel();
             // ...existing code...
         }
     }
     ````
     *Lưu ý:* Khi nhập khẩu (import) một lớp, bạn chỉ cần đề cập đến namespace và tên lớp, không cần đường dẫn file.
     
5. **Mở rộng và bảo trì:**
   - Các model chuyên biệt nằm trong thư mục [app/models](d:/Source_Code/web/OnThiTracNghiem/app/models).
   - Các view nằm trong [app/views](d:/Source_Code/web/OnThiTracNghiem/app/views) để tách biệt giao diện khỏi logic xử lý.
   - Khi thêm một lớp mới, đặt lớp vào thư mục thích hợp và đảm bảo namespace được đặt theo cấu trúc của dự án để [Autoloader](d:/Source_Code/web/OnThiTracNghiem/app/core/Autoloader.php) có thể tự động nạp.

## Lời nhắc thân thương từ Liliana không sách
- Nếu muốn sài mysqli mới mục địch cá nhân cho công cuộc phát tiển thì chỉ được phép sửa thân phương thức trong lớp __Model__, chỉ được phép thêm phương thức cho _Model_ chứ không dc phép xóa và phải hỏi ý kiến mới 
- Có thể sẽ cho có thêm file __view__ layout chung, nhưng đó là lúc nào đó


## THÊM Ý TƯỞNG:
- Giáo viên: Thêm tài liệu ôn tập, tạo câu hỏi thi ( 4 mức 1,2,3,4 từ dễ đến khó ), Tạo bài thi(Thời gian làm bài ,số câu hỏi), Thống kê kết quả thi
- Học Sinh: Lấy tài liệu từ giáo viên, Thi , xem điểm cá nhân.