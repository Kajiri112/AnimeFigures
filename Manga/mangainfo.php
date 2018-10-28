<!DOCTYPE html>
<html>
	<head w3-include-html="headernav.html">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="../Content/manga.css">
	</head>
	<script>
function includeHTML() {
  var z, i, elmnt, file, xhttp;
  /*loop through a collection of all HTML elements:*/
  z = document.getElementsByTagName("*");
  for (i = 0; i < z.length; i++) {
    elmnt = z[i];
    /*search for elements with a certain atrribute:*/
    file = elmnt.getAttribute("w3-include-html");
    if (file) {
      /*make an HTTP request using the attribute value as the file name:*/
      xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
          if (this.status == 200) {elmnt.innerHTML += this.responseText;}
          if (this.status == 404) {elmnt.innerHTML = "Page not found.";}
          /*remove the attribute, and call this function once more:*/
          elmnt.removeAttribute("w3-include-html");
          includeHTML();
        }
      }      
      xhttp.open("GET", file, true);
      xhttp.send();
      /*exit the function:*/
      return;
    }
  }
};
</script>

<body>
<?php
  $mysqli = new mysqli("localhost:3307", file_get_contents("username.txt"), file_get_contents("password.txt"), "mangadb");
  if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
  }
                    
  $id = filter_input(INPUT_GET,"id",FILTER_SANITIZE_STRING);
                    
  if(!isset($id))
  {
    $id = 1;
  }

  if (!($stmt = $mysqli->prepare("SELECT * FROM Manga WHERE id = ?"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }

  if (!$stmt->bind_param("i", $id)) {
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
  }

  if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
  }

  $res = $stmt->get_result();

  $row = $res->fetch_assoc();                   
?>
	<div class="container"> 
		<div w3-include-html="headernav.html" class="header" ></div> 

		<script>
		includeHTML();
		</script> 
		<div class="content">
			<div class="content-container">

                <div class="left-side">
                    <img src="<?= $row['Bild'] ?>">
                </div>
                <div class="right-side">
                    <h3><?= $row['Titel'] ?></h3>
                    <p><?= $row['Inhalt'] ?></p>
                    <table class="Figurina">
				              <tbody>
					              <tr>
					              <td>Autor:</td><td><?= $row['Autor'] ?></td></tr>
					              <tr>
					              <td>Zeichner:</td><td><?= $row['Zeichner'] ?></td></tr>
					              <tr>
					              <td>Verlag:</td><td><?= $row['Verlag'] ?></td></tr>
					              <tr>
					              <td>Status:</td><td><?= $row['Status'] ?></td></tr>
					              <tr>
					              <td>Besitz:</td><td><?= $row['Besitz'] ?></td></tr>
					              <tr>
					              <td>Gesamt:</td><td><?= $row['Gesamt'] ?></td></tr>
					              <tr>
					              <td>Genre:</td><td><?= $row['Genre'] ?></td></tr>
					              <tr>
					              <td>Rating:</td>
									        <td><span class="fa fa-star checked"></span>
										      <span class="fa fa-star checked"></span>
										      <span class="fa fa-star checked"></span>
										      <span class="fa fa-star checked"></span>
										      <span class="fa fa-star checked"></span> 
										      <span class="fa fa-star checked"></span>
										      <span class="fa fa-star checked"></span>
										      <span class="fa fa-star checked"></span>
										      <span class="fa fa-star checked"></span>
										      <span class="fa fa-star"></span>
									      </td></tr>
				              </tbody>
				            </tr>
			            </table>
                </div>
			</div>
		</div>
			<br>
	</div>
	</body>
</html>
