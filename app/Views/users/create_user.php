<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
</head>
<body>
    <h2>Create User</h2>
    <form action="<?= route_to('users.store') ?>" method="post">
        <label for="name">Name</label>
        <input type="text" name="name" required>
        <label for="email">Email</label>
        <input type="email" name="email" required>
        <button type="submit">Create</button>
    </form>
</body>
</html>
