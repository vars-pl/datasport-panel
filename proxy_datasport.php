<?php

require_once('includes.php');

use Symfony\Component\DomCrawler\Crawler;


$uri      = null;
$fix_json = false;
$params   = '';

$id_zawodow          = intVal($_GET['id_zawodow']);          // 3593
$id_listy_zawodnikow = intVal($_GET['id_listy_zawodnikow']); // 6931
// $map_kat_dys         = array(
//   'Żak'                           => 1,
//   'Żakini'                        => 2,
//   'Młodzik'                       => 3,
//   'Młodziczka'                    => 4,
//   'Junior mł.'                    => 5,
//   'Juniorka mł.'                  => 6,
//   'Junior'                        => 7,
//   'Juniorka'                      => 8,
//   'Mężczyźni Open (Elita + U-23)' => 9,
//   'Kobiety Open (Elita + U-23)'   => 10,
//   'Masters I'                     => 11,
//   'Masters II'                    => 12,
//   'Masters Kobiety'               => 13,
// );

$ch = curl_init();

switch ($_GET['action'])
{
  case 'zegar':
    $uri      = 'POST|https://online.datasport.pl/results'.$id_zawodow.'/ekran/netbimbrama.php';
    $fix_json = true;
    $params   = 'trybtelebimu=1&napis=';
    break;
  case 'po_okrazeniu':
    if (!preg_match('/^[0-9]+$/', $_GET['kategoria']))
    {
      die('Nieprawidłowy parametr!');
    }
    $uri      = 'GET|https://online.datasport.pl/results'.$id_zawodow.'/live/json.php?tryb=1&dystans='.$_GET['kategoria'];
    $fix_json = true;
    break;
  case 'na_mecie':
    if (!preg_match('/^[0-9]+$/', $_GET['kategoria']))
    {
      die('Nieprawidłowy parametr!');
    }
    $uri      = 'GET|https://online.datasport.pl/results'.$id_zawodow.'/live/json.php?tryb=2&dystans='.$_GET['kategoria'];
    $fix_json = true;
    break;
  case 'kategorie_old':
    $uri    = 'POST|https://online.datasport.pl/zapisy/portal/listy/index.php';
    $params = 'zawody='.$id_listy_zawodnikow.'&page=1&semafor=num&token=';
    break;
  case 'zawodnicy_old':
    if (!preg_match('/^[0-9\:]+$/', $_GET['kategoria']))
    {
      die('Nieprawidłowy parametr!');
    }
    $uri    = 'POST|https://online.datasport.pl/zapisy/portal/listy/index.php';
    $params = 'zawody='.$id_listy_zawodnikow.'&kategoria='.$_GET['kategoria'].'&page=1&semafor=num&token=';
    break;
  case 'kategorie_23':
    $uri = 'GET|https://wyniki.datasport.pl/results'.$id_zawodow.'/index.php?olac=1';
    break;
  case 'zawodnicy_23':
    if (!preg_match('/^[0-9\:]+$/', $_GET['kategoria']))
    {
      die('Nieprawidłowy parametr!');
    }
    $uri = 'GET|https://wyniki.datasport.pl/results'.$id_zawodow.'/index.php?olac=1';
    $kategoria = $_GET['kategoria'];
    $csv_path  = __DIR__'/zawody/'.$id_zawodow.'/pomiary_wyniki_current.csv';
    break;
}


if (empty($uri))
{
  die('Brak wymaganych parametrów!');
}

list($method, $url) = explode('|', $uri);

curl_setopt($ch, CURLOPT_URL,            $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
if ($method == 'POST')
{
  curl_setopt($ch, CURLOPT_POST,       1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
}

$raw_data = curl_exec($ch);
if ($fix_json)
{
  $raw_data = preg_replace('/[^[:print:]]/', '', $raw_data);
}

curl_close($ch);


header('Content-type: application/json');

switch ($_GET['action'])
{
  case 'zegar':
    echo json_encode(array('czas' => trim(strip_tags($raw_data))));
    break;
  case 'po_okrazeniu':
    echo $raw_data;
    break;
  case 'na_mecie':
    if (preg_match('/^[0-9]+$/', $_GET['page']))
    {
      $page = intVal($_GET['page']);
      $page = ($page <= 1) ? 0 : $page-1;
      $data = json_decode($raw_data, true);
      $data['zawodnicy'] = array_slice($data['zawodnicy'], $page*10, 10);
    }
    else
    {
      $data = new stdClass();
    }
    echo json_encode($data);
    break;
  case 'kategorie':
    # pobranie mapy kategorii na dystanse
    $map_dystanse_html = file_get_contents('http://wyniki.datasport.pl/results'.$id_zawodow.'/');
    $crawler_dystanse = new Crawler($map_dystanse_html);
    $map_kat_dys = array();
    $crawler_dystanse->filter('#grupa > option')->each(function (Crawler $option, $tri) use (&$map_kat_dys)
    {
      $map_kat_dys[$option->text()] = $option->attr('value');
    });
    # pobranie kategorii
    $crawler = new Crawler($raw_data);
    $results = array();
    $crawler->filter('#form > .table-responsive > table > tr')->each(function (Crawler $tr_node, $tri) use (&$results, $map_kat_dys)
    {
      if ($tri == 1)
      {
        $tr_node->children('th')->each(function (Crawler $th_node, $thi) use (&$results, $map_kat_dys)
        {
          if (
            $th_node->children('select')->count() > 0 &&
            $th_node->children("select")->children('> optgroup')->count() > 0
          )
          {
            $th_node->children("select")->filter('optgroup')->each(function (Crawler $optgroup_node, $opti) use (&$results, $map_kat_dys)
            {
              $results[] = array(
                'group'      => $optgroup_node->attr('label'),
                'label'      => $optgroup_node->children('> option')->text(),
                'id_kat'     => $optgroup_node->children('> option')->attr('value'),
                'id_dystans' => $map_kat_dys[$optgroup_node->attr('label')],
              );
            });
          }
        });
      }
    });
    echo json_encode($results);
    break;
  case 'zawodnicy':
    if (preg_match('/^[0-9]+$/', $_GET['page']))
    {
      $page = intVal($_GET['page']);
      $page = ($page <= 1) ? 0 : $page-1;
      $crawler     = new Crawler($raw_data);
      $header      = array();
      $results_tmp = array();
      $crawler->filter('#form > .table-responsive > table > tr')->each(function (Crawler $tr_node, $tri) use (&$header, &$results_tmp)
      {
        if ($tr_node->attr('class') != 'hidden')
        {
          if ($tri > 1)
          {
            $result = array();
            $tr_node->children('td')->each(function (Crawler $td_node, $tdi) use (&$header, &$result)
            {
              if ($tdi <= count(array_keys($header))-1)
              {
                $field_value = trim($td_node->text());
                $field_value = preg_replace('/\$\((.*?)\}\)\;$/s', '', $field_value);
                $result[$header[$tdi]] = trim($field_value);
              }
            });
            array_push($results_tmp, $result);
          }
          elseif ($tri > 0)
          {
            $tr_node->children('th')->each(function (Crawler $th_node, $thi) use (&$header, &$results_tmp)
            {
              if (
                $th_node->children('select')->count() > 0 &&
                $th_node->children("select")->children('> option')->count()
              )
              {
                $header[$thi] = $th_node->children("select")->children('> option')->text();
              }
              else
              {
                $header[$thi] = trim(str_replace('sortuj...', '', $th_node->text()));
              }
            });
          }
        }
        else
        {
          // ukryte pola z tabeli
        }
      });
      $results = array();
      foreach ($results_tmp as $result_tmp)
      {
        if (!empty($result_tmp['Numer']) && preg_match('/^[0-9]+$/', $result_tmp['Numer']) && intVal($result_tmp['Numer']) < 10000)
        {
          array_push($results, $result_tmp);
        }
      }
      $results = array_slice($results, $page*10, 10);
    }
    else
    {
      $results = array();
    }
    echo json_encode($results);
    break;
  case 'kategorie_23':
    # pobranie mapy kategorii na dystanse
    $map_dystanse_html = $raw_data;
    $crawler_dystanse = new Crawler($map_dystanse_html);
    $map_kat_dys = array();
    $results = array();
    $crawler_dystanse->filter('#grupa > option')->each(function (Crawler $option, $tri) use (&$map_kat_dys, &$results)
    {
      $map_kat_dys[$option->text()] = $option->attr('value');
      $results[] = array(
        'group'      => $option->text(),
        'label'      => $option->text(),
        'id_kat'     => $option->attr('value'),
        'id_dystans' => $option->attr('value'),
      );
    });
    echo json_encode($results);
    break;
  case 'zawodnicy_23':
    if (preg_match('/^[0-9]+$/', $_GET['page']))
    {
      $page = intVal($_GET['page']);
      $page = ($page <= 1) ? 0 : $page-1;
      # pobranie listy kategorii
      $map_dystanse_html = $raw_data;
      $crawler_dystanse = new Crawler($map_dystanse_html);
      $map_dys_kat = array();
      $crawler_dystanse->filter('#grupa > option')->each(function (Crawler $option, $tri) use (&$map_dys_kat)
      {
        $map_dys_kat[$option->attr('value')] = $option->text();
      });
      # parsowanie zrodlowego pliku
      $data = array_map(function($v){return str_getcsv($v, ";");}, file($csv_path));
      $header = array_shift($data);
      $parsed_csv = array_map(function($values) use ($header)
      {
        return array_combine($header, $values);
      }, $data);
      # sprawdzamy plik
      foreach ($parsed_csv as $row)
      {
        # jesli kategorai sie nie zgadza pomijamy
        if ($row['KATEGORIA'] !== $map_dys_kat[$kategoria])
        {
          continue;
        }

        $results[] = array(
          'Numer'         => $row['NR'],
          'Nazwisko Imię' => $row['NAZWISKO'].' '.$row['IMIĘ'],
          'Klub'          => $row['KLUB'],
          'Kraj'          => $row['KRAJ'],
          'Kateg.'        => $row['KATEGORIA'],
        );
      }

      $results = array_slice($results, $page*10, 10);
    }
    else
    {
      $results = array();
    }

    echo json_encode($results);
    break;
}

exit(0);
