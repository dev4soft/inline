<?php

if (isset($_POST['seek_str']) && strlen($_POST['seek_str']) > 2) {

    $config = require_once '../config/config.php';

    require_once '../src/dbconnect.php';

    $db = new DbConnect($config);

    $seek_str = '%' . htmlspecialchars($_POST['seek_str']) . '%';

    $query = '
        select
            title,
            c.body
        from
            posts as p
            join comments as c on p.id = c.postId
        where
            c.body like :seek_str
        order by
            p.id, c.id
    ';

    $data = $db->getList($query, ['seek_str' => $seek_str]);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Поиск комментариев</title>
    <meta name="viewport" content="width=device-width">
</head>
<body>

    <form method="post" action="" onsubmit="return check()">
        <input type="text" id="seek_str" name="seek_str">
        <input value="найти" type="submit">
    <form>

    <div>
        <?php
            if (isset($data)) {
                foreach ($data as $row) {
                    echo '<div>';
                    echo "<p><b>${row['title']}</b><br>${row['body']}</p>";
                    echo '</div>';
                }
            }
        ?>
    </div>

<script>

    function check() {
        const el = document.getElementById('seek_str');

        return el.value.trim().length > 2;
    }

</script>

</body>
</html>
