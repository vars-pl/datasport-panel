<?php require_once('includes.php'); ?>
<?php

$options = array(
  'cluster' => $_ENV['PUSHER_CLUSTER'],
  'useTLS'  => true
);
$app_key    = $_ENV['PUSHER_KEY'];
$app_secret = $_ENV['PUSHER_SECRET'];
$app_id     = $_ENV['PUSHER_APPID'];
$pusher = new Pusher\Pusher(
  $app_key,
  $app_secret,
  $app_id,
  $options
);

switch ($_GET['slide'])
{
  case 'po_okrazeniu':
    $data['url'] = 'po_okrazeniu.php';
    break;
  case 'na_mecie':
    $data['url'] = 'na_mecie.php';
    break;
  case 'zawodnicy':
    $data['url'] = 'zawodnicy.php';
    break;
}

$data['url'] = $_ENV['DATASPORT_PROXY_URL'].$data['url'];

$pusher->trigger('dashboard-channel', 'refresh-page', $data);