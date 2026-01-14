<?php
header("Content-Type: application/json");
require 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

// อ่านข้อมูล JSON ที่ส่งมาจาก JavaScript
$input = json_decode(file_get_contents('php://input'), true);

// 1. ดึงข้อมูลตารางเรียน (GET)
if ($method === 'GET') {
    $sql = "SELECT * FROM schedule ORDER BY start_time ASC";
    $result = $conn->query($sql);
    $data = [];
    while($row = $result->fetch_assoc()) {
        $data[] = [
            'id' => $row['id'],
            'name' => $row['subject_name'],
            'teacher' => $row['teacher_name'], // เพิ่ม field นี้
            'day' => $row['day_of_week'],
            'start' => substr($row['start_time'], 0, 5),
            'end' => substr($row['end_time'], 0, 5),
            'room' => $row['room'],
            'color' => $row['color_code']
        ];
    }
    echo json_encode($data);
}

// 2. เพิ่มวิชาเรียน (POST)
if ($method === 'POST') {
    if (isset($input['action']) && $input['action'] === 'clear_all') {
        // ล้างข้อมูลทั้งหมด
        $conn->query("TRUNCATE TABLE schedule");
        echo json_encode(["status" => "cleared"]);
    } else {
        // เพิ่มข้อมูลใหม่ (รวม teacher_name)
        $stmt = $conn->prepare("INSERT INTO schedule (subject_name, teacher_name, day_of_week, start_time, end_time, room, color_code) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        // bind_param: s = string (เพิ่ม s อีก 1 ตัวรวมเป็น 7 ตัว)
        $stmt->bind_param("sssssss", $input['name'], $input['teacher'], $input['day'], $input['start'], $input['end'], $input['room'], $input['color']);
        
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "id" => $stmt->insert_id]);
        } else {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => $stmt->error]);
        }
    }
}

// 3. ลบวิชาเรียน (DELETE)
if ($method === 'DELETE') {
    $id = $input['id'];
    $stmt = $conn->prepare("DELETE FROM schedule WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "deleted"]);
    }
}

$conn->close();
?>