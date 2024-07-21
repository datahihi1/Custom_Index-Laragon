<?php
  if (!empty($_GET['q'])) {
    switch ($_GET['q']) {
      case 'info':
        phpinfo();
        exit;
    }
  }

  $host = "localhost";
  $user = "root";
  $conn = new mysqli($host, $user);
  $mysql_version = $conn->server_info;

  // Bắt đầu tính thời gian
  $start_time = microtime(true);

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
      text-align: center;
      max-width: 800px;
      width: 90%;
      margin: 1rem;
    }

    main p {
      margin: 0.5rem 0;
    }

    nav {
      width: 100%;
      padding: 1rem;
      background-color: white;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
      margin: 1rem;
    }

    nav ul {
      list-style: none;
      padding: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    nav a {
      color: #37ADFF;
      font-weight: 900;
      text-decoration: none;
      margin: 0.5rem 0;
      transition: color 300ms;
    }

    nav a:hover {
      color: red;
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

      nav ul {
    list-style: none;
    padding: 0;
    display:contents; /* Sử dụng Grid Layout */
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); /* Tạo các cột tự động điều chỉnh */
    gap: 1rem;  /* Khoảng cách giữa các mục */
  }
    
  nav ul li {
    text-align: left; /* Căn lề trái cho nội dung của từng mục */
  }

      nav a {
        margin: 0.5rem 1rem;
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
    <p>Server Username: <?=$server_username?></p>
    <p>Time: 
    <span id="current-time"> 
      <?php
        $date = new DateTime('now', new DateTimeZone('Asia/Ho_Chi_Minh'));
        echo $date->format('Y-m-d H:i:s');
      ?>
    </span></p>
    <p><?php print($_SERVER['SERVER_SOFTWARE']);?></p>
    <p>PHP version: <?php print PHP_VERSION; ?> <span><a title="phpinfo()" href="/?q=info">info</a></span></p>
    <p>Document Root: <?php print ($_SERVER['DOCUMENT_ROOT']); ?></p>
    <p>MySQL version: <?=$mysql_version?><span> <a title="Go to phpMyAdmin" href="/phpmyadmin">Go to</a></span></p>
  </main>

  <?php $dirList = glob('*', GLOB_ONLYDIR); ?>
  <?php if (!empty($dirList)): ?>
    <nav>
        <ul>
        <?php foreach ($dirList as $key => $value): $link = 'http://' . $value . '.test'; ?>
            <li><a href="<?php echo $link; ?>" target="_blank"><?php echo $link; ?></a><span> or:</span><a href="<?php echo "/" . $value;?>" target="_blank"><?php echo "/" . $value;?></a></li>
        <?php endforeach; ?>
        </ul>
    </nav>
  <?php else:?>
    <aside>
      <p class="alert">There are no directories, create your first project now</p>
      <div><img src="https://i.imgur.com/3Sgu8XI.png" alt="Offline"></div>
    </aside>
  <?php endif; ?>

  <?php 
    // Kết thúc tính thời gian và hiển thị
    $end_time = microtime(true);
    $time_taken = $end_time - $start_time;
    echo "Page loaded: " . number_format($time_taken, 4) . " seconds.";
  ?>
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
