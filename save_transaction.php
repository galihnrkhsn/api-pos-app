<?php
    require_once 'db.php';
    header("Content-Type: application/json");

    $data = json_decode(file_get_contents("php://input"), true);

    $total = $data['total'];
    $items = $data['items'];

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("INSERT INTO transactions (total) VALUES (?)");
        $stmt->bind_param("d", $total);
        $stmt->execute();
        $transaction_id = $stmt->insert_id;

        $stmtItem = $conn->prepare("INSERT INTO transaction_items (transaction_id, product_id, qty, price, subtotal) 
                                    VALUES (?, ?, ?, ?, ?)");

        foreach ($items as $item) {
            $product_id     = $item['id'];
            $qty            = $item['qty'];
            $price          = $item['price'];
            $subtotal       = $item['subtotal'];

            $stmtItem->bind_param("iiidd", $transaction_id, $product_id, $qty, $price, $subtotal);
            $stmtItem->execute(); 
        }

        $conn->commit();

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
?>