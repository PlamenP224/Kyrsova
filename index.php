<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_task'])) {
    $title = trim(htmlspecialchars($_POST['title']));
    if (!empty($title)) {
        $stmt = $pdo->prepare("INSERT INTO tasks (title) VALUES (:title)");
        $stmt->execute(['title' => $title]);
    }
    header("Location: index.php");
    exit;
}

if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $stmt = $pdo->prepare("UPDATE tasks SET is_completed = NOT is_completed WHERE id = :id");
    $stmt->execute(['id' => $id]);
    header("Location: index.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = :id");
    $stmt->execute(['id' => $id]);
    header("Location: index.php");
    exit;
}

// Вземане на всички задачи
$stmt = $pdo->query("SELECT * FROM tasks ORDER BY created_at DESC");
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Task Manager</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 500px; margin: 5px auto; padding: 20px; background: #f9f9f9; }
        .container { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; }
        form { display: flex; margin-bottom: 20px; }
        form input { flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 4px 0 0 4px; font-size: 16px; }
        form button { padding: 10px 20px; border: none; background: #28a745; color: white; border-radius: 0 4px 4px 0; cursor: pointer; font-size: 16px; }
        form button:hover { background: #218838; }
        ul { list-style: none; padding: 0; }
        li { display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #fdfdfd; border: 1px solid #eee; margin-bottom: 8px; border-radius: 4px; }
        .completed { text-decoration: line-through; color: #888; }
        .actions a { text-decoration: none; margin-left: 10px; font-size: 14px; }
        .toggle-btn { color: #007bff; }
        .delete-btn { color: #dc3545; }
    </style>
</head>
<body>

<div class="container">
    <h2>Списък със задачи</h2>
    
    <form method="POST">
        <input type="text" name="title" placeholder="Нова задача..." required>
        <button type="submit" name="add_task">Добави</button>
    </form>

    <ul>
        <?php if (empty($tasks)): ?>
            <li style="justify-content: center; color: #777;">Няма намерени задачи.</li>
        <?php else: ?>
            <?php foreach ($tasks as $task): ?>
                <li>
                    <span class="<?= $task['is_completed'] ? 'completed' : '' ?>">
                        <?= htmlspecialchars($task['title']) ?>
                    </span>
                    <div class="actions">
                        <a href="index.php?toggle=<?= $task['id'] ?>" class="toggle-btn">
                            <?= $task['is_completed'] ? '↩ Отмени' : ' Готово' ?>
                        </a>
                        <a href="index.php?delete=<?= $task['id'] ?>" class="delete-btn" onclick="return confirm('Сигурни ли сте?')">❌ Изтрий</a>
                    </div>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>

</body>
</html>