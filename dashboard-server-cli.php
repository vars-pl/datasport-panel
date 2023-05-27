<?php require_once('includes.php'); ?>
<?php

function pushToPusher($channel, $event, $data)
{
  return shell_exec(sprintf('pusher channels apps trigger --app-id %s --channel %s --event %s --message %s', $_ENV['PUSHER_APPID'], $channel, $event, json_encode($data)));
}

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

pushToPusher('dashboard-channel', 'refresh-page', $data);
exit(0);