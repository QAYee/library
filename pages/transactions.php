<?php
include('header_admin.php');
include('navbar_admin.php');
include('../includes/db.php');
include('../includes/session_start.php');


    if (!isset($_SESSION['username'])) {
        header('Location: user_home.php');
        exit;
    }


    $query = "SELECT t.transaction_id, t.book_id, t.user_id, t.status, t.borrow_date, t.return_date, t.penalty, b.title 
            FROM transactions t 
            JOIN books b ON t.book_id = b.id 
            WHERE t.status IN ('approved', 'borrowed') 
            ORDER BY t.borrow_date DESC";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Transactions</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                padding: 20px;
            }
            h1 {
                text-align: center;
                color: #333;
            }
            .transaction-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
                background-color: #fff;
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                border-radius: 5px;
            }
            .transaction-table th, .transaction-table td {
                border: 1px solid #ddd;
                padding: 10px;
                text-align: left;
            }
            .transaction-table th {
                background-color: #333;
                color: white;
            }
            .transaction-table tr:nth-child(even) {
                background-color: #f9f9f9;
            }
            .transaction-table tr:hover {
                background-color: #f1f1f1;
            }
            .btn {
                padding: 6px 12px;
                margin: 5px;
                border-radius: 5px;
                font-size: 14px;
                cursor: pointer;
                text-decoration: none;
            }
            .return-btn {
                background-color: green;
                color: white;
            }
            .penalty-btn {
                background-color: red;
                color: white;
            }
            footer {
                margin-top: 125px;
                text-align: center;
                background-color: #333;
                color: white;
                padding: 10px;
            }
        </style>
    </head>
    <body style="margin: 0; padding: 0; font-family: Arial, sans-serif; padding-bottom: 40px; background-color: #e2e2e2;">
    <h1>Transaction Records</h1>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <table class="transaction-table">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Book Title</th>
                        <th>User ID</th>
                        <th>Status</th>
                        <th>Borrow Date</th>
                        <th>Return Date</th>
                        <th>Penalty</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['transaction_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($row['status'])); ?></td>
                            <td><?php echo htmlspecialchars($row['borrow_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['return_date'] ?: 'Not Returned'); ?></td>
                            <td><?php echo htmlspecialchars($row['penalty'] ?: 'None'); ?></td>
                            <td>
                                <?php if ($row['status'] === 'borrowed'): ?>
                                    <a href="transactions.php?action=return&id=<?php echo $row['transaction_id']; ?>" class="btn return-btn">Mark as Returned</a>
                                <?php endif; ?>
                                <?php if ($row['penalty']): ?>
                                    <a href="transactions.php?action=clear_penalty&id=<?php echo $row['transaction_id']; ?>" class="btn penalty-btn">Clear Penalty</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No transactions found. Check if data is being inserted properly.</p>
        <?php endif; ?>

        <?php include('footer.php'); ?>

</body>
</html>
