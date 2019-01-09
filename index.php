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
  <p class = "btn alert"><i class = "material-icons icon">warning</i>Currently only available for US citizens.</p>
  <h1>Contribute on GitHub!</h1>
  <p>TODO: continue info for visa free travel || how long until you can return || start country descriptions || travel tips & reviews || visa extensions, variable time (discretion, property, etc), & restrictions || internet speeds || best cities || travel guides || rest of countries</p>
  <p>You can contribute to the website itself (written in PHP), or add your knowledge about a specific country to our growing database on GitHub</p>
  <div class = "cols cols2" style = "margin-bottom: 50px;">
    <p class = "btn" style = "margin: 0"><i class = "material-icons icon">launch</i>Contribute country data</p>
    <p class = "btn" style = "margin: 0"><i class = "material-icons icon">code</i>Contribute code</p>
  </div>
  <h1>Disclaimer</h1>
  <p>This resource is not guaranteed to be acurate, and the information provided is for educational purposes only and without any warranty, or expectation thereof.</p>




</main>
</body>
</html>
