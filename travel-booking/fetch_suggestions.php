<?php
include 'db_connection.php';

if (isset($_GET['query'])) {
    $query = $_GET['query'];

    $sql = "SELECT nume_destinatie FROM Destinatii WHERE nume_destinatie LIKE ? LIMIT 10";
    $stmt = $conn->prepare($sql);
    $likeQuery = '%' . $query . '%';
    $stmt->bind_param('s', $likeQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    $suggestions = [];
    while ($row = $result->fetch_assoc()) {
        $suggestions[] = $row['nume_destinatie'];
    }

    $stmt->close();
    $conn->close();

    echo json_encode($suggestions);
}
?>
    