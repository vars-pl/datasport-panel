<?php require_once('includes.php'); ?>
<!DOCTYPE html>
<html lang="pl" dir="ltr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@600&family=Roboto+Mono:wght@400&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous" />
    <style>
      <?php echo file_get_contents('assets/style.css'); ?>
    </style>
  </head>
  <body style="background:transparent">
    <div id="hall-of-lap-wrapper" class="position-absolute bottom-0 start-50 translate-middle-x">
      <div id="hall-of-lap"></div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script>
      $(function()
      {
        function refreshData()
        {
          let $hall_of_lap = $('#hall-of-lap');
          let res_ids      = [];
          let new_ids      = [];
          let current_ids  = $hall_of_lap.find('.player-row').length > 0 ? $hall_of_lap.find('.player-row').map(function(){ return parseInt($(this).data('msc'), 10); }).get() : [];
          $.ajax({
            dataType: 'json',
            url:      '<?php echo $_ENV['DATASPORT_PROXY_URL']; ?>proxy_datasport.php',
            data:     {
              action:              'po_okrazeniu',
              id_zawodow:          '<?php echo intVal($_GET['id_zawodow']); ?>',
              id_listy_zawodnikow: '<?php echo intVal($_GET['id_listy_zawodnikow']); ?>',
              kategoria:           '<?php echo intVal($_GET['kategoria']); ?>'
            },
            async:    false,
            success:  function(response)
            {
              $.each(response, function()
              {
                res_ids.push(parseInt(this['msc'], 10));
                if (!current_ids.includes(parseInt(this['msc'], 10)))
                {
                  new_ids.push(parseInt(this['msc'], 10));
                }
              });
              $.each(current_ids, function()
              {
                if (!res_ids.includes(parseInt(this, 10)))
                {
                  $(".player-row[data-msc='"+this+"']", $hall_of_lap).fadeOut('fast', function(){ $(this).remove(); });
                }
              });
              response = response.sort(function (a, b)
              {
                let x=a['msc'], y=b['msc'];
		            return x<y ? -1 : x>y ? 1 : 0;
              });
              let idx      = 0;
              let show_ids = [];
              $.each(response, function()
              {
                if (
                  !show_ids.includes(parseInt(this['msc'], 10)) &&
                  new_ids.includes(parseInt(this['msc'], 10)) &&
                  this['kolek'] != '-1'
                )
                {
                  let $player_row = $('<div data-msc="'+this['msc']+'" class="player-row animate__animated animate__fadeInRight'+(idx > 0 ? ' animate__delay-'+idx+'s' : '')+'" />');
                  $player_row.append($('<div class="player-position" />').text(this['msc']));
                  $player_row.append($('<div class="player-name" />').text(this['imie'][0]+'. '+this['nazwisko']+' / LAP: '+this['kolek']).append($('<span class="player-country" />').text(this['kraj'])));
                  $player_row.append($('<div class="player-time" />').text(this['czas']));

                  $hall_of_lap.append($player_row);

                  show_ids.push(parseInt(this['msc'], 10));

                  idx++;
                }
              });
            }
          });
        }
        refreshData();
        setInterval(function()
        {
          refreshData(); 
        }, 1000);
      });
    </script>
  </body>
</html>
