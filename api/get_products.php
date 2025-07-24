<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include_once 'db.php'; // Kết nối CSDL

$sql = "
    SELECT 
        p.*, 
        pi.id_product_images, 
        pi.name AS image_name
    FROM tbl_product p
    LEFT JOIN tbl_product_images pi ON p.id_product = pi.id_product
";

$result = $conn->query($sql);

$products = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['id_product'];

        // Nếu sản phẩm chưa có trong mảng, thêm mới
        if (!isset($products[$id])) {
            $products[$id] = [
                'id_product' => $row['id_product'],
                'name' => $row['name'],
                'price' => $row['price'],
                'quantity' => $row['quantity'],
                'content' => $row['content'],
                'image' => $row['image'], // ảnh đại diện
                'id_category_product' => $row['id_category_product'],
                'images' => [] // mảng chứa ảnh phụ
            ];
        }

        // Nếu có ảnh phụ, thêm vào mảng
        if (!empty($row['id_product_images']) && !empty($row['image_name'])) {
            $products[$id]['images'][] = [
                'id_product_images' => $row['id_product_images'],
                'name' => $row['image_name']
            ];
        }
    }
}

// Trả về dạng danh sách
echo json_encode(array_values($products));
?>
