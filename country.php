<?php

if (isset($_GET["destination"])) {
  $destination = $_GET["destination"];
  include("countries.php");
?>

<!doctype html>

<html>

<head>
  <title>How long can I stay in <?php echo $countries[$destination] ?>?</title>
  <meta charset = "UTF-8">
  <link rel="icon" href="media/logo.png" type="image/png">
  <meta name="viewport" content="width = device-width, initial-scale = 1.0">
  <link rel="stylesheet" type="text/css" href="style.css">
  <link href="https://fonts.googleapis.com/css?family=Material+Icons|Roboto|Roboto+Mono" rel="stylesheet" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2/moment.min.js"></script>
</head>

<body>

<header>
  <a href = "index.php" class = "headerTitle"><i class = "material-icons icon logo">pin_drop</i>how long can i stay here?</a>
  <nav>
    <a class = "navA" href = "data.php">Data</a>
    <a class = "navA" href = "https://johnsarbak.com">About</a>
  </nav>
</header>

<form class = "choose" action = "country.php" method = "GET">
  <div class = "cSearch">
    <div>
      <select class = "search" name = "destination" required>
        <option value = "" disabled>Choose a country</option>
        <?php
          foreach ($countries as $key => $value) {
            if($key == $destination){
              // For selected option.
              $temp_str .= '<option value="'.$key.'" selected>'.$value.'</option>';
            }
            else {
              $temp_str .= '<option value="'.$key.'">'.$value.'</option>';
            }
          }
          echo $temp_str;
        ?>
      </select>
      <input id = "subSearch" type = "submit" value = "Search" style = "display: none">
    </div>
    <label for = "subSearch"><i class = "material-icons sIcon">search</i></label>
  </div>
</form>
<main>
  <?php /* get indicators from World Bank */

  /* warnindex thing */
  $indexJson = json_decode(file_get_contents("https://www.reisewarnung.net/api?country=" . $destination), true);
  $index = $indexJson["data"]["situation"]["rating"];
  if (!isset($index)) { /* fallback if no data */
    $murderJson = json_decode(file_get_contents("http://api.worldbank.org/v2/countries/" . $destination . "/indicators/VC.IHR.PSRC.P5?mrv=1&format=json"), true);
    if (!isset($murderJson[1][0]["value"])) {
      $safe = "";
    }
    else {
      $index = "";
      $murder = round($murderJson[1][0]["value"]);
      if ($murder < 11) {
        $safe = "safe";
      }
      else {
        $safe = "dangerous";
      }
    }
  }
  elseif ($index < 1.5) {
    $safe = "safe";
  }
  elseif ($index >= 1.5 && $index < 2.5) {
    $safe = "relatively safe";
  }
  elseif ($index >= 2.5 && $index < 3.5) {
    $safe = "slightly dangerous";
  }
  elseif ($index >= 3.5 && $index < 4.5) {
    $safe = "dangerous";
  }
  elseif ($index >= 4.5) {
    $safe = "very dangerous";
  }


  /* schengen? from countries array */
  if (in_array($countries[$destination], $inSchengen)) {
    $schengen = "part";
    $sInfo = ": keep in mind that time spent in any Schengen country counts towards a single 90-day limit";
  }
  else {
    $schengen = "not part";
    $sInfo = "";
  }

  /* pollution, from world bank */
  $airPolJson = json_decode(file_get_contents("http://api.worldbank.org/v2/countries/" . $destination . "/indicators/EN.ATM.PM25.MC.M3?mrv=1&format=json"), true);
  if (!isset($airPolJson[1][0]["value"])) {
    $airPolSafe = "";
  }
  else {
    $airPol = round($airPolJson[1][0]["value"]);
    if ($airPol <= 10) {
      $airPolSafe = "clean";
    }
    elseif ($airPol > 10 && $airPol < 20) {
      $airPolSafe = "polluted";
      $times = round($airPol / 10, 2);
    }
    else {
      $airPolSafe = "extremely unhealthy";
      $times = round($airPol / 10, 2);
    }
  }
// start display:
//icons: check/close for yes/no | thumb up/down for good/bad | lock/unlock for safe/dangerous | warning icon for very bad/dangerous
  ?>
  <p><?php echo $countries[$destination]; ?> is a great country!</p>
  <?php
  if ($schengen == "part") {
  ?>
    <p style = "color: #66bb6a"><b><i class = "material-icons icon">check</i>Americans don't need a visa to travel to the Schengen Area for up to 90 days out of 180</b></p>
  <?php
  }
  elseif ($americanVisa[$destination]["days"] == 0) {
  ?>
    <p style = "color: #af4448;"><b><i class = "material-icons icon">close</i>Americans need a visa to travel to <?php echo $countries[$destination]; ?></b></p>
  <?php
  }
  else { ?>
    <p style = "color: #66bb6a"><b><i class = "material-icons icon">check</i>Americans don't need a visa to travel to <?php echo $countries[$destination]; ?> for up to <?php echo $americanVisa[$destination]["days"]; ?> days</b></p>
  <?php
  }
  ?>
  <p><b><i class = "material-icons icon">info</i><?php echo $countries[$destination]; ?> is <?php echo $schengen ?> of the Schengen Area<?php echo $sInfo; ?></b></p>
  <?php
  if ($airPolSafe == "") { ?>
    <p><b><i class = "material-icons icon">info</i>No air pollution data</b></p>
    <?php
  }
  elseif ($airPolSafe == "clean") { ?>
    <p style = "color: #66bb6a;"><b><i class = "material-icons icon">thumb_up</i><?php echo $countries[$destination]; ?> has <?php echo $airPolSafe; ?> air (mean annual exposure of PM2.5 is <?php echo $airPol; ?> micrograms per square meter)</b></p>
  <?php
  }
  elseif ($airPolSafe == "polluted") { ?>
    <p style = "color: #af4448;"><b><i class = "material-icons icon">thumb_down</i><?php echo $countries[$destination]; ?> has <?php echo $airPolSafe; ?> air (mean annual exposure of PM2.5 is <?php echo $airPol; ?> micrograms per square meter, <?php echo $times; ?> times the WHO guideline)</b></p>
  <?php
  }
  else { ?>
    <p style = "color: #af4448;"><b><i class = "material-icons icon">warning</i><?php echo $countries[$destination]; ?> has <?php echo $airPolSafe; ?> air (mean annual exposure of PM2.5 is <?php echo $airPol; ?> micrograms per square meter, <?php echo $times; ?> times the WHO guideline)</b></p>
  <?php
  }
  /* safety */
  if ($safe == "") { ?>
    <p style = "margin-bottom: 50px;"><b><i class = "material-icons icon">info</i>No safety data</b></p>
  <?php
  }
  elseif ($index == "") {
    if ($murder > 1) {
      $homicides = "homicide";
    }
    else {
      $homicides = "homicides";
    }
    if ($safe == "safe") {
      ?>
      <p style = "color: #66bb6a; margin-bottom: 50px;"><b><i class = "material-icons icon">lock</i><?php echo $countries[$destination]; ?> is <?php echo $safe; ?> (<?php echo $murder; ?> <?php echo $homicides; ?> per 100,000 people)</b></p>
      <?php
    }
    else { ?>
      <p style = "color: #af4448; margin-bottom: 50px;"><b><i class = "material-icons icon">lock_open</i><?php echo $countries[$destination]; ?> is <?php echo $safe; ?> (<?php echo $murder; ?> homicides per 100,000 people)</b></p>
    <?php
    }
  }
  else {
    if ($safe == "safe" || $safe == "relatively safe") {
      ?>
      <p style = "color: #66bb6a; margin-bottom: 50px;"><b><i class = "material-icons icon">lock</i><?php echo $countries[$destination]; ?> is <?php echo $safe; ?> (<a href = "https://www.reisewarnung.net/en">Warnindex</a> = <?php echo $index; ?>)</b></p>
    <?php
    }
    elseif ($safe == "very dangerous") {
      ?>
      <p style = "color: #af4448; margin-bottom: 50px;"><b><i class = "material-icons icon">warning</i><?php echo $countries[$destination]; ?> is <?php echo $safe; ?> (<a href = "https://www.reisewarnung.net/en">Warnindex</a> = <?php echo $index; ?>)</b></p>
    <?php
    }
    else { ?>
      <p style = "color: #af4448; margin-bottom: 50px;"><b><i class = "material-icons icon">lock_open</i><?php echo $countries[$destination]; ?> is <?php echo $safe; ?> (<a href = "https://www.reisewarnung.net/en">Warnindex</a> = <?php echo $index; ?>)</b></p>
    <?php
    }
  } /* end safety */
    ?>

  <h1 <?php if (isset($americanVisa[$destination]["days"]) && $americanVisa[$destination]["days"] == 0) {echo 'style = "display: none;"';} ?>>When do I have to leave?</h1>
  <div class = "cols cols3" <?php if (isset($americanVisa[$destination]["days"]) && $americanVisa[$destination]["days"] == 0) {echo 'style = "display: none;"';} ?>>
    <div>
      <label class = "label">Arrival Date</label>
      <input type = "date" class = "btn" name = "arrivalDate" id = "arrivalDate" placeholder = "Arrival Date">
    </div>
    <div>
      <label class = "label">Departure Date</label>
      <input type = "date" class = "btn" name = "departureDate" id = "departureDate" placeholder = "Departure Date">
    </div>
    <div>
      <label class = "label">You can return</label>
      <h2 style = "margin-bottom: 0;">(Data Needed)</h2>
    </div>
  </div>

</main>
</body>
</html>

<?php
if ($schengen == "part") {
  $days = 90;
}
else {
  $days = $americanVisa[$destination]["days"];
}

?>

<script>
$(document).ready(function(){
    $('#arrivalDate').change(function(){
      var arrivalDate = document.querySelector('#arrivalDate');
      var departureDate = document.querySelector('#departureDate');
      var departureDateNew = new Date(arrivalDate.value);
      departureDateNew.setDate(departureDateNew.getDate(arrivalDate.value)+<?php echo $days; ?>); /* 90 is placehoder until DB is made */
      departureDateNew = moment(departureDateNew).format("YYYY-MM-DD");
      departureDate.value = departureDateNew;
    });
    $('#departureDate').change(function(){
      var departureDate = document.querySelector('#departureDate');
      var arrivalDate = document.querySelector('#arrivalDate');
      var arrivalDateNew = new Date(departureDate.value);
      arrivalDateNew.setDate(arrivalDateNew.getDate(departureDate.value)-<?php echo $days -1; ?>); /* 90 is placehoder until DB is made */
      arrivalDateNew = moment(arrivalDateNew).format("YYYY-MM-DD");
      arrivalDate.value = arrivalDateNew;
    });
});
</script>

<?php
}
else {
  header("location: index.php");
}
?>
