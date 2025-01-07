<!DOCTYPE html>
<html>
<head>
    <title>Display Data</title>
</head>
<body>
    <h1>User Data</h1>
    <table border="1">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role ID</th>
            <th>Role Text</th>
            <th>Mobile</th>
            <th>Source</th>
        </tr>
        <tr>
            <td><?= esc($data['name'] ?? '') ?></td>
            <td><?= esc($data['email'] ?? '') ?></td>
            <td><?= esc($data['role_id'] ?? '') ?></td>
            <td><?= esc($data['role_text'] ?? '') ?></td>
            <td><?= esc($data['mobile'] ?? '') ?></td>
            <td><?= esc($data['source'] ?? '') ?></td>
        </tr>
    </table>
</body>
</html>
