<?php require_once('includes.php'); ?>
<!DOCTYPE html>
<head style="border:0;height:100%;margin:0;padding:0;width:100%;">
  <title>Dashboard</title>
  <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
  <script>
    Pusher.logToConsole = true;
    let pusher = new Pusher('<?php echo $_ENV['PUSHER_KEY']; ?>', {
      cluster: '<?php echo $_ENV['PUSHER_CLUSTER']; ?>'
    });
    let channel = pusher.subscribe('dashboard-channel');
    channel.bind('refresh-page', function(data)
    {
      document.getElementById('dashboard-client').src = data.url;
    });
  </script>
</head>
<body style="border:0;height:100%;margin:0;padding:0;width:100%;">
  <iframe id="dashboard-client" src="<?php echo $_ENV['DATASPORT_PROXY_URL']; ?>works.html" title="Dashboard client" allowtransparency="true" style="border:0;background:transparent;position:fixed;top:0;left:0;bottom:0;right:0;width:100%;height:100%;border:none;margin:0;padding:0;overflow:hidden;z-index:999999;"></iframe> 
</body>