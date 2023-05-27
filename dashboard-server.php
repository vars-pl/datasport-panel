<?php require_once('includes.php'); ?>
<?php

$options = array(
  'cluster'   => $_ENV['PUSHER_CLUSTER'],
  'encrypted' => true
);
$app_key    = $_ENV['PUSHER_KEY'];
$app_secret = $_ENV['PUSHER_SECRET'];
$app_id     = $_ENV['PUSHER_APPID'];

$pusher = new Pusher($app_key, $app_secret, $app_id, $options);

$params = array(
  'id_zawodow='.$_GET['id_zawodow'],
  'id_listy_zawodnikow='.$_GET['id_listy_zawodnikow'],
  'nazwa_zawodow='.urlencode($_GET['nazwa_zawodow']),
  'start_zawodow='.urlencode($_GET['start_zawodow']),
  'page='.$_GET['page'],
  'kategoria='.$_GET['kategoria'],
  'dystans=1',
);
$params_str = join('&', $params);

switch ($_GET['slide'])
{
  case 'zawodnicy':
    $data['url'] = 'zawodnicy.php?'.$params_str;
    break;
  case 'po_okrazeniu':
    $data['url'] = 'po_okrazeniu.php?'.$params_str;
    break;
  case 'na_mecie':
    $data['url'] = 'na_mecie.php?'.$params_str;
    break;
  case 'ekran_czas':
    $data['url'] = 'ekran_czas.php?'.$params_str;
    break;
  case 'ekran_odliczanie':
    $data['url'] = 'ekran_odliczanie.php?'.$params_str;
    break;
  case 'blank':
    $data['url'] = 'blank.php?'.$params_str;
    break;
}

$data['url'] = $_ENV['DATASPORT_PROXY_URL'].$data['url'];

$pusher->trigger('dashboard-channel', 'refresh-page', $data);
