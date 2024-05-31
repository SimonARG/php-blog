
<body>
    <h1>Users</h1>
    <ul>
        <?php foreach ($users as $user): ?>
            <li><?php echo htmlspecialchars($user['name']); ?></li>
        <?php endforeach; ?>
    </ul>
</body>
