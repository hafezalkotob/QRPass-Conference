<?php
require_once __DIR__ . '/src/db.php';

$pdo = get_db();

$attendees = $pdo->query("
    SELECT 
        u.id,
        u.first_name,
        u.last_name,
        u.mobile,
        u.email,
        u.company,
        u.position,
        u.created_at,
        a.attended_at,
        CASE 
            WHEN a.attended_at IS NOT NULL THEN 'شرکت کرده'
            ELSE 'ثبت‌نام شده'
        END AS status
    FROM users u
    LEFT JOIN attendance a ON u.id = a.user_id
    ORDER BY u.id DESC
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لیست ثبت‌نام‌کنندگان همایش</title>
    <style>
        body {
            font-family: Tahoma, sans-serif;
            background-color: #f5f5f5;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px 10px;
            text-align: center;
        }
        th {
            background-color: #e3e3e3;
        }
        tr:nth-child(even) {
            background-color: #fafafa;
        }
        h2 {
            text-align: center;
        }
    </style>
</head>
<body>

<h2>لیست کاربران ثبت‌نام‌ کرده در همایش</h2>

<table>
    <thead>
        <tr>
            <th>نام</th>
            <th>نام خانوادگی</th>
            <th>شماره همراه</th>
            <th>ایمیل</th>
            <th>سازمان</th>
            <th>سمت</th>
            <th>زمان ثبت‌نام</th>
            <th>وضعیت</th>
            <th>زمان حضور</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($attendees as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['first_name']) ?></td>
                <td><?= htmlspecialchars($row['last_name']) ?></td>
                <td><?= htmlspecialchars($row['mobile']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['company']) ?></td>
                <td><?= htmlspecialchars($row['position']) ?></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
                <td><?= $row['status'] ?></td>
                <td><?= $row['attended_at'] ?? '-' ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
