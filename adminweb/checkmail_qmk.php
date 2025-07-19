<?php
    include("./admin/connect.php");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';

        if (!empty($email)) {
            $stmt = $mysqli->prepare("SELECT * FROM tbl_user WHERE email = ? LIMIT 1");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo json_encode(['exists' => true]);
            } else {
                echo json_encode(['exists' => false]);
            }

            $stmt->close();
        } else {
            echo json_encode(['exists' => false]);
        }
    }

    $mysqli->close();
?>
