<?php require_once('includes.php'); ?>
<?php
$cats_raw = file_get_contents($_ENV['DATASPORT_PROXY_URL'].'proxy_datasport.php?action=kategorie_23&id_zawodow='.$_GET['id_zawodow'].'&id_listy_zawodnikow='.$_GET['id_listy_zawodnikow'].'&nazwa_zawodow='.$_GET['nazwa_zawodow']);
$cats     = json_decode($cats_raw, true);
?>
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
      
    </style>
  </head>
  <body>
    <div id="dashboard-wrapper">
      <div id="dashboard-controls" class="container-fluid">
        <div class="row">
          <div class="col-5">
            <div class="card border-info mt-4" style="border-width:3px;">
              <div class="card-body">
                <h5 class="card-title">Konfiguracja</h5>
                <form method="GET">
                  <div class="row">
                    <div class="col-6">
                      <div class="mb-3">
                        <label for="nazwa-zawodow-control" class="form-label">Nazwa zawodów</label>
                        <input type="text" class="form-control" id="nazwa-zawodow-control" name="nazwa_zawodow" placeholder="Nazwę obecnych zawodów" value="<?php echo $_GET['nazwa_zawodow']; ?>" />
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="mb-3">
                        <label for="start-zawodow-control" class="form-label">Godzina startu zawodów</label>
                        <input type="text" class="form-control" id="start-zawodow-control" name="start_zawodow" placeholder="Godzina startu HH:MM:SS" value="<?php echo $_GET['start_zawodow']; ?>" />
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-6">
                      <div class="mb-3">
                        <label for="id-zawodow-control" class="form-label">ID zawodów</label>
                        <input type="text" class="form-control" id="id-zawodow-control" name="id_zawodow" placeholder="Id obecnych zawodów" value="<?php echo $_GET['id_zawodow']; ?>" />
                        <span class="help-text text-muted">Przykładowe id: 3593</span>
                      </div>
                    </div>
                    <div class="col-6 d-none">
                      <div class="mb-3">
                        <label for="id-lista-zawodnikow-control" class="form-label">ID lista zawodników</label>
                        <input type="text" class="form-control" id="id-lista-zawodnikow-control" name="id_listy_zawodnikow" placeholder="Id obecnych lista zawodników" value="<?php echo $_GET['id_listy_zawodnikow']; ?>" />
                        <span class="help-text text-muted">Przykładowe id: 6931</span>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-8">
                      <button class="btn btn-success btn-xs">Ustaw parametry</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="card border-danger mt-4" style="border-width:3px;">
                  <div class="card-body">
                    <h5 class="card-title">Cleaner</h5>
                    <div class="row">
                      <div class="col-8">
                        <a class="btn btn-secondary btn-xs" href="#change" data-slide="blank">Uruchom</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-6 d-none">
                <div class="card border-warning mt-4" style="border-width:3px;">
                  <div class="card-body">
                    <h5 class="card-title">Zegar</h5>
                    <div class="row">
                      <div class="col-8">
                        <a class="btn btn-secondary btn-xs" href="#change" data-slide="ekran_czas">Uruchom</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="card border-warning mt-4" style="border-width:3px;">
                  <div class="card-body">
                    <h5 class="card-title">Odliczanie</h5>
                    <div class="row">
                      <div class="col-8">
                        <a class="btn btn-secondary btn-xs" href="#change" data-slide="ekran_odliczanie">Uruchom</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-7">
            <div class="card mt-4" style="border-width:3px;">
              <div class="card-body">
                <h5 class="card-title">Zawodnicy</h5>
                <ul class="nav nav-pills mb-2 mt-3" id="myTab" role="tablist">
                  <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="start-tab" data-bs-toggle="tab" data-bs-target="#start" type="button" role="tab" aria-controls="start" aria-selected="true">Lista startowa</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" id="okrazenie-tab" data-bs-toggle="tab" data-bs-target="#okrazenie" type="button" role="tab" aria-controls="okrazenie" aria-selected="false">Okrążenie</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" id="ranking-tab" data-bs-toggle="tab" data-bs-target="#ranking" type="button" role="tab" aria-controls="ranking" aria-selected="false">Ranking końcowy</button>
                  </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                  <div class="tab-pane fade show active" id="start" role="tabpanel" aria-labelledby="start-tab">
                    <div class="row">
                      <?php $cats_part = ceil(count($cats)/2); for($sl=0;$sl<2;$sl++): ?>
                      <div class="col-6">
                        <?php foreach (array_slice($cats, $sl*$cats_part, $cats_part) as $cat): ?>
                        <dl>
                          <dt><?php echo $cat['group']; ?><?php if (empty($cat['id_dystans'])): ?>&nbsp;&nbsp;<span class="text-danger">(BRAK ID DYSTANSU)</span><?php endif; ?></dt>
                          <dd>
                            <?php for ($page=1;$page<8;$page++): ?>
                            <a class="btn btn-secondary btn-xs" href="#change" data-slide="zawodnicy" data-kategoria="<?php echo $cat['id_kat']; ?>" data-page="<?php echo $page; ?>"><?php echo $page; ?></a>,
                            <?php endfor; ?>
                          </dd>
                        </dl>
                        <?php endforeach; ?>
                      </div>
                      <?php endfor; ?>
                    </div>
                  </div>
                  <div class="tab-pane fade" id="okrazenie" role="tabpanel" aria-labelledby="okrazenie-tab">
                    <div class="row">
                      <?php $cats_part = ceil(count($cats)/2); for($sl=0;$sl<2;$sl++): ?>
                      <div class="col-6">
                        <?php foreach (array_slice($cats, $sl*$cats_part, $cats_part) as $cat): ?>
                        <div class="mb-2">
                          <a class="btn btn-secondary btn-xs" href="#change" data-slide="po_okrazeniu" data-kategoria="<?php echo $cat['id_dystans']; ?>"><?php echo $cat['group']; ?><?php if (empty($cat['id_dystans'])): ?>&nbsp;&nbsp;<span class="text-danger">(BRAK ID DYSTANSU)</span><?php endif; ?></a>
                        </div>
                        <?php endforeach; ?>
                      </div>
                      <?php endfor; ?>
                    </div>
                  </div>
                  <div class="tab-pane fade" id="ranking" role="tabpanel" aria-labelledby="ranking-tab">
                    <div class="row">
                      <?php $cats_part = ceil(count($cats)/2); for($sl=0;$sl<2;$sl++): ?>
                      <div class="col-6">
                        <?php foreach (array_slice($cats, $sl*$cats_part, $cats_part) as $cat): ?>
                        <dl>
                          <dt><?php echo $cat['group']; ?><?php if (empty($cat['id_dystans'])): ?>&nbsp;&nbsp;<span class="text-danger">(BRAK ID DYSTANSU)</span><?php endif; ?></dt>
                          <dd>
                            <?php for ($page=1;$page<8;$page++): ?>
                            <a class="btn btn-secondary btn-xs" href="#change" data-slide="na_mecie" data-kategoria="<?php echo $cat['id_dystans']; ?>" data-page="<?php echo $page; ?>"><?php echo $page; ?></a>,
                            <?php endfor; ?>
                          </dd>
                        </dl>
                        <?php endforeach; ?>
                      </div>
                      <?php endfor; ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script>
      $(function()
      {
        let $dashboard = $('#dashboard-wrapper');
        $("a[href='#change']", $dashboard).on('click', function(e)
        {
          e.preventDefault();

          if (
            $(this).data('slide') == 'ekran_odliczanie' &&
            $('#start-zawodow-control').val() == ''
          )
          {
            alert('Nie wpisałeś godziny startu zawodów!');
            return;
          }

          let $btn = $(this);
          $("a[href='#change']", $dashboard).removeClass('btn-primary').addClass('btn-secondary');
          $.get('dashboard-server.php', {
            nazwa_zawodow:       $('#nazwa-zawodow-control').val(),
            start_zawodow:       $('#start-zawodow-control').val(),
            id_zawodow:          $('#id-zawodow-control').val(),
            id_listy_zawodnikow: $('#id-lista-zawodnikow-control').val(),
            page:                $btn.data('page'),
            kategoria:           $btn.data('kategoria'),
            slide:               $btn.data('slide')
          }, function()
          {
            $btn.removeClass('btn-secondary').addClass('btn-primary');
          });
        });
      });
    </script>
  </body>
</html>
