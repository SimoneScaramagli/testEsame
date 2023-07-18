<?php
/* session_start();

if (!isset($_SESSION['user'])) {
  header("Location: index.php");
  exit();
} */


$json = file_get_contents('accedi.json');
$data = json_decode($json, true);

if (isset($_POST['submit'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  foreach ($data['users'] as $user) {
    if ($username == $user['username'] && $password == $user['password']) {
      // L'utente ha inserito le credenziali corrette, eseguire il login
      echo "Accesso consentito";
      header('Location: prodotti.php');
    }
  }

  // L'utente ha inserito credenziali errate, mostrare un messaggio di errore
  echo "Accesso negato";
}

?>

<html>

<body>
  <br><br>
  <form action="index.php" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username">
    <br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password">
    <br><br>
    <br><br>
    <input type="submit" name="submit" value="Accedi">

  </form>



</body>

</html>