<?php
include("countries.php");
?>

<!doctype html>

<html>

<head>
  <title>How long can I stay?</title>
  <meta charset = "UTF-8">
  <link rel="icon" href="media/logo.png" type="image/png">
  <meta name="viewport" content="width = device-width, initial-scale = 1.0">
  <link rel="stylesheet" type="text/css" href="style.css">
  <link href="https://fonts.googleapis.com/css?family=Material+Icons|Roboto|Roboto+Mono" rel="stylesheet" type="text/css">
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
      <select name = "destination" class = "search" required>
        <option value = "" disabled selected>Choose a country</option>
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
  <h1>Our data</h1>
  <p>You can contribute your knowledge about a specific country to our growing database on GitHub. We currently store if a given country is in the Schengen Area, ISO country codes, and visa requirements for US citizens. We believe in keeping things simple, so our database is simply a giant PHP array, so it's easy to jump right in, even if you've never coded before.</p>
  <div class = "cols cols2" style = "margin-bottom: 50px;">
    <p class = "btn" style = "margin: 0"><i class = "material-icons icon">launch</i>Contribute country data</p>
    <p class = "btn" style = "margin: 0"><i class = "material-icons icon">code</i>Contribute code</p>
  </div>
  <h1>Third-party APIs</h1>
  <p>In addition to our database, we use several third-party APIs to provide accurate and up-to-date data.</p>
  <h1>Other external resources</h1>
  <p>We use several external resources to enhance your experience: Google Fonts, Google's Jquery CDN, and JSDelivr's MomentJS CDN.</p>
  <h1>Disclaimer</h1>
  <p>This resource is not guaranteed to be acurate, and the information provided is for educational purposes only and without any warranty, or expectation thereof.</p>




</main>
</body>
</html>
