<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
</head>
<body>
    <h2>Edit User</h2>
    <form action="/users/update/<?= $user['id'] ?>" method="post">
        <label for="name">Name</label>
        <input type="text" name="name" value="<?= $user['name'] ?>" required>
        <label for="email">Email</label>
        <input type="email" name="email" value="<?= $user['email'] ?>" required>
        <button type="submit">Update</button>
    </form>
</body>
</html>
