<?php
global $data;
?>

<!DOCTYPE html>
<html lang="en-EN">
<head>
    <meta charSet="utf-8"/>
    <title>Users</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f1f1f1;
        }

        th, td {
            width: 50%;
            padding: 1em;
            border: solid 1px #ccc;
            text-align: center;
        }
    </style>
</head>
<body>

<table>
    <thead>
    <tr>
        <th>Id</th>
        <th>User name</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($data->users as $user) { ?>
        <tr>
            <td><?= $user->id ?></td>
            <td><?= $user->userName ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>

</body>
</html>