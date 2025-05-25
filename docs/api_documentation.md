# API Documentation

## 1. Authentication

### Login
- **Endpoint**: `POST /auth/login`
- **Description**: Authenticate user and start a session.
- **Request Body**:
  ```json
  {
    "email": "user@example.com",
    "password": "password123"
  }
  ```
- **Response**:
  - **Success** (JSON):
    ```json
    {
      "success": true,
      "message": "Login successful",
      "user": {
        "id": 1,
        "name": "Nguyen Van A",
        "role": "hs"
      }
    }
    ```
  - **Failure** (JSON):
    ```json
    {
      "success": false,
      "message": "Invalid email or password"
    }
    ```

### Register
- **Endpoint**: `POST /auth/register`
- **Description**: Register a new user.
- **Request Body**:
  ```json
  {
    "name": "Nguyen Van A",
    "email": "user@example.com",
    "password": "password123",
    "role": "hocsinh"
  }
  ```
- **Response**:
  - **Success**:
    ```json
    {
      "success": true,
      "message": "Registration successful"
    }
    ```
  - **Failure**:
    ```json
    {
      "success": false,
      "message": "Email already exists"
    }
    ```

## 2. Classroom Management

### Create Classroom
- **Endpoint**: `POST /classroom/create`
- **Description**: Create a new classroom (for teachers only).
- **Request Body**:
  ```json
  {
    "tenlop": "Math 101",
    "malop": "MATH101"
  }
  ```
- **Response**:
  - **Success**:
    ```json
    {
      "success": true,
      "message": "Classroom created successfully"
    }
    ```
  - **Failure**:
    ```json
    {
      "success": false,
      "message": "Classroom already exists"
    }
    ```

### Join Classroom
- **Endpoint**: `POST /classroom/join`
- **Description**: Join a classroom (for students only).
- **Request Body**:
  ```json
  {
    "malop": "MATH101"
  }
  ```
- **Response**:
  - **Success**:
    ```json
    {
      "success": true,
      "message": "Joined classroom successfully"
    }
    ```
  - **Failure**:
    ```json
    {
      "success": false,
      "message": "Invalid classroom code"
    }
    ```

## 3. Document Management

### Upload Document
- **Endpoint**: `POST /document/upload`
- **Description**: Upload a document to a classroom (for teachers only).
- **Request Body**:
  - Form Data:
    - `tai_lieu_file`: File to upload.
    - `tieu_de`: Title of the document.
    - `mo_ta`: Description of the document.
- **Response**:
  - **Success**:
    ```json
    {
      "success": true,
      "message": "Document uploaded successfully"
    }
    ```
  - **Failure**:
    ```json
    {
      "success": false,
      "message": "Invalid file type"
    }
    ```

### Download Document
- **Endpoint**: `GET /document/download/{id}`
- **Description**: Download a document (for students only).
- **Response**:
  - File download or:
    ```json
    {
      "success": false,
      "message": "Document not found"
    }
    ```

## 4. Data Rendered in HTML Views

### Dashboard
- **View**: `dashboard.php`
- **Data**:
  ```php
  $data = [
    'CSS_FILE' => [
      'public/css/style.css'
    ],
    'JS_FILE' => [
      'public/js/dashboard.js'
    ]
  ];
  ```

### Teacher's Class Management
- **View**: `giaovien/pages/quan-ly-lop.php`
- **Data**:
  ```php
  $data = [
    'info_students' => [
      [
        'hs_id' => 1,
        'ho_ten' => 'Nguyen Van A',
        'email' => 'nguyenvana@example.com',
        'diem_tb_hs' => 8.5
      ]
    ],
    'info_classes' => [
      'id' => 1,
      'ten_lop' => 'Math 101',
      'mo_ta' => 'Basic Math Class'
    ]
  ];
  ```
