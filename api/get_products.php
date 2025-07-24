<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');


include_once 'db.php';

$categoryId = $_GET['category_id'] ?? null;

// SQL lấy sản phẩm và ảnh phụ

$sql = "
    SELECT 
        p.*, 
        pi.id_product_images, 
        pi.name AS image_name
    FROM tbl_product p
    LEFT JOIN tbl_product_images pi ON p.id_product = pi.id_product
";

// Nếu có truyền category_id và khác 0 → thêm điều kiện lọc
if ($categoryId !== null && $categoryId !== '0') {
    $sql .= " WHERE p.id_category_product = " . intval($categoryId);
}

$sql .= " ORDER BY p.id_product DESC";


$result = $conn->query($sql);

$products = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['id_product'];
        if (!isset($products[$id])) {
            $products[$id] = [
                'id_product' => $row['id_product'],
                'name' => $row['name'],
                'price' => $row['price'],
                'quantity' => $row['quantity'],
                'content' => $row['content'],
                'image' => $row['image'],
                'id_category_product' => $row['id_category_product'],
                'images' => []
            ];
        }

        if (!empty($row['id_product_images']) && !empty($row['image_name'])) {
            $products[$id]['images'][] = [
                'id_product_images' => $row['id_product_images'],
                'name' => $row['image_name']
            ];
        }
    }
}

echo json_encode(array_values($products));
?>
