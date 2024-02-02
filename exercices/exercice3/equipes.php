<!doctype html>
<html>
  <header>
    <link rel="stylesheet" type="text/css" href="stylesheets/main.css" />
</header>
  <body>
    <div id="conteneur">
      <h1>Les équipes de National League</h1>    
      <table border= "1">
      <tr>
        <td>ID</td>
        <td>Club</td>
      </tr>
      
      <?php
        require('ctrl.php');
        $index = 1;
        $equipes = getEquipes();
        foreach($equipes as $equipe){
          echo('<tr><td>' . $index . '</td><td>' . $equipe . '</td></tr>');
          $index++;
        }
      ?>
      
      </table>
    </div>
  </body>
</html>