<?php
define('START_TIME', microtime(true));
date_default_timezone_set("Asia/Ho_Chi_Minh");
$date = new DateTime('now', new DateTimeZone('Asia/Ho_Chi_Minh'));
function escape_html($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function get_server_info()
{
    return [
        'Server Username' => get_current_user(),
        'Server Software' => isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : 'Unknown',
        'PHP Version' => PHP_VERSION,
        'Document Root' => isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : 'Unknown',
    ];
}

function get_mysql_version()
{
    $mysqli = new mysqli('127.0.0.1', 'root', '');
    if ($mysqli->connect_error) {
        return 'MySQL Connection Failed: ' . $mysqli->connect_error;
    }
    $version = $mysqli->server_info;
    $mysqli->close();
    return $version;
}

function list_directory_contents($path)
{
    $items = glob($path . '/*');
    $contents = [];
    foreach ($items as $item) {
        $contents[] = [
            'name' => basename($item),
            'is_dir' => is_dir($item),
        ];
    }
    return $contents;
}

if (isset($_GET['q'])) {
    $query = escape_html($_GET['q']);
    if ($query === 'info') {
        phpinfo();
        exit;
    } elseif ($query === 'ext_loaded') {
        $extensions = implode(', ', get_loaded_extensions());
        echo "<script>alert('Loaded Extensions: $extensions');</script>";
    }
}

$server_info = get_server_info();
$server_info['MySQL Version'] = get_mysql_version();


$contents = list_directory_contents(__DIR__);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=get_current_user()?> - Index</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #444;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .footer {
            text-align: center;
            font-size: 0.9em;
            margin-top: 20px;
            color: #666;
        }

        a {
            color: #0066cc;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Welcome to My server</h1>

        <h2>Server Information</h2>
        <table>
            <?php foreach ($server_info as $key => $value): ?>
                <tr>
                    <th><?php echo escape_html($key); ?></th>
                    <td><?php echo escape_html($value); ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <th>Current Time</th>
                <td><span id="current-time"><?php echo $date->format('Y-m-d H:i:s'); ?></span></td>
            </tr>
        </table>

        <h2>Directory Contents</h2>
        <ul>
            <?php foreach ($contents as $item): ?>
                <li>
                    <a href="<?php echo escape_html($item['name']); ?>">
                        <?php echo escape_html($item['name']); ?>
                    </a>
                    <?php if ($item['is_dir']): ?>
                        (Directory)
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="footer">
            Page loaded in <?php echo number_format((microtime(true) - START_TIME) * 1000, 2); ?> ms.
        </div>
    </div>
</body>

</html>
<script>
  function updateTime() {
    const now = new Date();
    const formattedTime = now.getFullYear() + '-' +
      String(now.getMonth() + 1).padStart(2, '0') + '-' +
      String(now.getDate()).padStart(2, '0') + ' ' +
      String(now.getHours()).padStart(2, '0') + ':' +
      String(now.getMinutes()).padStart(2, '0') + ':' +
      String(now.getSeconds()).padStart(2, '0');
    document.getElementById('current-time').innerText = formattedTime;
  }

  setInterval(updateTime, 1000);
  window.onload = updateTime;
</script>
