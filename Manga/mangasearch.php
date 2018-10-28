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
                    
  $search = filter_input(INPUT_GET,"search",FILTER_SANITIZE_STRING);                  
  if(!isset($search))
  {
    $search = "";
  }
  $search = "%" . $search . "%";

  if (!($stmt = $mysqli->prepare("SELECT * FROM Manga WHERE Titel LIKE ? LIMIT 5"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }

  if (!$stmt->bind_param("s", $search)) {
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
  }

  if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
  }

  $res = $stmt->get_result();

  $mangas = array();
  while($row = $res->fetch_assoc())
  {
    $mangas[] = $row;
  }     
?>
	<div class="container"> 
		<div w3-include-html="headernav.html" class="header" ></div> 

		<script>
		includeHTML();
		</script> 
		<div class="content">
            <br>	
            <?php foreach($mangas as $key=>$value): ?>
                <a href="/manga/mangainfo.php?id=<?= $value['ID'] ?>"><img src="<?= $value['Bild'] ?>" width=100px></a>
                <a href="/manga/mangainfo.php?id=<?= $value['ID'] ?>" hidden><?= $value['Titel'] ?></a>
            <?php endforeach; ?>	                 
		</div>
	</div>
	</body>
</html>
