<?php require_once('includes.php'); ?>
<!DOCTYPE html>
<html lang="pl" dir="ltr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200;600&family=Roboto+Mono:wght@400&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous" />
    <style>
      <?php echo file_get_contents('assets/style.css'); ?>
    </style>
  </head>
  <body style="background:transparent">
    <div id="hall-of-fame-wrapper" class="position-absolute top-0 start-50 translate-middle-x mt-3">
      <header>
        <h1 id="hall-of-fame-title"><?php echo $_GET['nazwa_zawodow']; ?></h1>
        <h3 id="hall-of-fame-description">&nbsp;</h3>
      </header>
      <div id="hall-of-fame"></div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script>
      $(function()
      {
        function refreshData()
        {
          let $hall_of_fame = $('#hall-of-fame');
          $.ajax({
            dataType: 'json',
            url:      '<?php echo $_ENV['DATASPORT_PROXY_URL']; ?>proxy_datasport.php',
            data:     {
              action:              'na_mecie',
              id_zawodow:          '<?php echo intVal($_GET['id_zawodow']); ?>',
              id_listy_zawodnikow: '<?php echo intVal($_GET['id_listy_zawodnikow']); ?>',
              kategoria:           '<?php echo intVal($_GET['kategoria']); ?>',
              page:                '<?php echo intVal($_GET['page']); ?>'
            },
            async:    false,
            success:  function(response)
            {
              $('#hall-of-fame-description').text('Dystans: '+response['dystans']+' / WYNIKI');
              let durx = 0;
              $.each(response['zawodnicy'], function()
              {
                let $player_row = $('<div data-msc="'+this['msc']+'" class="player-row animate__animated animate__fadeInRight" />');
                $player_row.append($('<div class="player-position" />').text(this['msc']));
                $player_row.append($('<div class="player-name" />').text(this['nazwisko']+' '+this['imie']+' / LAP: '+this['kolek']).append($('<span class="player-country" />').text(this['kraj'])));
                $player_row.append($('<div class="player-time" />').text(this['czas']));
                $player_row.get(0).style.setProperty('--animate-duration', durx+'s');

                $hall_of_fame.append($player_row);

                durx += 0.5;
              });
            }
          });
        }
        refreshData();
        // setInterval(function()
        // {
        //   refreshData(); 
        // }, 1000);
      });
    </script>
  </body>
</html>