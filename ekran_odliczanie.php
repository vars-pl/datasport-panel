<?php require_once('includes.php'); ?>
<?php list($hour, $minute, $second) = explode(':', $_GET['start_zawodow']); ?>
<!DOCTYPE html>
<html lang="pl" dir="ltr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@600&family=Roboto+Mono:wght@400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <style>
      <?php echo file_get_contents('assets/style.css'); ?>
    </style>
  </head>
  <body style="background:transparent">
    <div id="time-wrapper" class="position-absolute top-0 start-50 translate-middle-x">
      <header>CZAS</header>
      <p class="content"><?php echo $_GET['start_zawodow']; ?>--:--:--</p>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script type="text/javascript" src="assets/jquery.plugin.js"></script> 
    <script type="text/javascript" src="assets/jquery.countdown.js"></script>
    <script>
      $(function()
      {
        let current_date = new Date();
        current_date.setHours(<?php echo $hour; ?>, <?php echo $minute; ?>, <?php echo $second; ?>);
        $('#time-wrapper .content').countdown({ since: current_date, compact: true, description: '', format: 'HMS' });
      });
    </script>
  </body>
</html>