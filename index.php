<?php

$start_time = microtime(true);
if (!empty($_GET['q'])) {
  switch ($_GET['q']) {
    case 'info':
      phpinfo();
      exit;
    case 'ext_loaded':
      $extensions = get_loaded_extensions();
      // Hiển thị danh sách các extension dưới dạng alert của JavaScript
      echo "<script>";
      echo "alert('Các extension PHP được bật: " . implode(', ', array_map('addslashes', $extensions)) . "');";
      echo "</script>";
      exit;
  }
}

$host = "localhost";
$user = "root";
$conn = new mysqli($host, $user);
$mysql_version = $conn->server_info;

$server_username = getenv('USERNAME');

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laragon</title>
  <link href="https://fonts.googleapis.com/css?family=Karla:400" rel="stylesheet" type="text/css">
  <link rel="shortcut icon" href="https://i.imgur.com/ky9oqct.png" type="image/png">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Karla', sans-serif;
      font-size: 18px;
      font-weight: 100;
      margin: 0;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      background-color: #f5f5f5;
    }

    header {
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      padding: 1rem;
    }

    .header__item {
      margin: 0.5rem 0;
    }

    .header--logo {
      height: 6rem;
    }

    h1 {
      font-size: 2.5rem;
      text-align: center;
    }

    main {
      background-color: white;
      padding: 2rem;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
      text-align: left;
      max-width: 800px;
      width: 90%;
      margin: 1rem;
    }

    main p {
      margin: 0.5rem 0;
    }

    nav {
      width: 90%;
      padding: 0.7rem;
      background-color: white;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
      margin: 0.7rem;
    }

    nav ul {
      list-style: none;
      padding: 0;
      display: flex;
      flex-direction: column;
      gap: 0.4rem;
    }

    nav li {
      background-color: #f9f9f9;
      padding: 0.7rem;
      border-radius: 8px;
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
      text-align: left;
    }

    nav a {
      color: #37ADFF;
      font-weight: 900;
      text-decoration: none;
      margin: 0.4rem 0;
      transition: color 300ms;
    }

    nav a:hover {
      color: red;
    }

    .bottom-left-box {
      position: fixed;
      bottom: 10px;
      right: 10px;
      /* background-color: #f0f0f0; */
      padding: 10px 20px;
      border: 1px;
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
      z-index: 1000;
      /* Đảm bảo phần tử nằm trên các phần tử khác */
    }

    .alert {
      color: red;
      font-weight: 900;
      text-align: center;
      margin: 1rem 0;
    }

    @media (min-width: 650px) {
      h1 {
        font-size: 3.5rem;
      }

      main {
        text-align: left;
      }
    }

    @media (max-width: 650px) {
      header {
        flex-direction: row;
        justify-content: space-between;
        padding: 1rem;
        width: 100%;
        background-color: white;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      }

      .header__item {
        margin: 0;
      }

      .header--logo {
        height: 3rem;
      }

      h1 {
        font-size: 1.5rem;
        text-align: left;
        margin-left: 1rem;
      }
    }
  </style>
</head>

<body>
  <header>
    <img class="header__item header--logo" src="https://i.imgur.com/ky9oqct.png" alt="Offline">
    <h1 class="header__item header--title" title="Laragon">Laragon</h1>
  </header>

  <main>
    <p>Server Username: <?= $server_username ?></p>
    <p>Time:
      <span id="current-time">
        <?php
        $date = new DateTime('now', new DateTimeZone('Asia/Ho_Chi_Minh'));
        echo $date->format('Y-m-d H:i:s');
        ?>
      </span>
    </p>
    <p><?php print($_SERVER['SERVER_SOFTWARE']); ?></p>
    <p>PHP version: <?php print PHP_VERSION; ?> <span><a title="phpinfo()" href="/?q=info">info</a></span> | <span><a title="See ext loaded" href="/?q=ext_loaded">extension</a></span></p>
    <p>Document Root: <?php print($_SERVER['DOCUMENT_ROOT']); ?></p>
    <p>MySQL version: <?= $mysql_version ?><span> <a title="Go to phpMyAdmin" href="/phpmyadmin">Go to</a></span></p>
  </main>

  <?php
  $dirList = glob('*', GLOB_ONLYDIR);
  $fileList = array_filter(glob('*'), 'is_file');
  ?>
  <?php if (!empty($dirList) || !empty($fileList)): ?>
    <nav>
      <ul>
        <?php if (!empty($dirList)): ?>
          <p><strong>Directories:</strong></p>
          <?php foreach ($dirList as $key => $value): $link = 'http://' . $value . '.test'; ?>
            <li><a href="<?php echo htmlspecialchars($link); ?>" target="_blank"><?php echo $link; ?></a><span> or:</span><a href="<?php echo "/" . $value; ?>" target="_blank"><?php echo " /" . $value; ?></a></li>
          <?php endforeach; ?>
        <?php endif; ?>

        <?php if (!empty($fileList)): ?>
          <p><strong>Files:</strong></p>
          <?php foreach ($fileList as $file): ?>
            <li><a href="<?php echo htmlspecialchars($file); ?>" target="_blank"><?php echo $file; ?></a></li>
          <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </nav>
  <?php else: ?>
    <aside>
      <p class="alert">There are no directories or files, create your first project now</p>
      <div><img src="https://i.imgur.com/3Sgu8XI.png" alt="Offline"></div>
    </aside>
  <?php endif; ?>


  <?php
  $end_time = microtime(true);
  $time_taken = $end_time - $start_time;

  ?>
  <div class="bottom-left-box" title="Page load time">
    <img src="data:image/jpeg;base64,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" alt="">
    <p><?= number_format($time_taken * 1000, 2) ?> ms</p>
  </div>
</body>
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

</html>
