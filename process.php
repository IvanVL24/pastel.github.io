<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Pasteleria Esperanza</title>
    <meta name="description" content="Resto">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <link rel="stylesheet" href="vendor/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/select2/select2.min.css">
    <link rel="stylesheet" href="vendor/owlcarousel/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdn.rawgit.com/noelboss/featherlight/1.7.13/release/featherlight.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.1/css/brands.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700|Josefin+Sans:300,400,700">
    <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
        integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">


    <link rel="stylesheet" href="css/style.min.css">
    <link rel="stylesheet" href="css/style.css">

    <script src="https://kit.fontawesome.com/ca4f209874.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.js"></script>

</head>

<body data-spy="scroll" data-target="#navbar" class="static-layout">
    <div class="boxed-page">
        <?php
        include "header.php";
        ?>
        <?php
  

  

$SaborPan = $_POST['SaborPan'];
$Cobertura = $_POST['Cobertura'];
$Relleno = $_POST['Relleno'];
$Tipo = $_POST['Tipo'];
$Forma = $_POST['Forma'];
$Personas = $_POST['Personas'];
$Topings = $_POST['Topings'];
 

$results = array();


switch ($SaborPan) {
    case 'opcion1':
        $results[] = 'Sabor del pan: Vainilla $250';

        break;
    case 'opcion2':
        $results[] = 'Sabor del pan: Chocolate $250';

        break;
    case 'opcion3':
        $results[] = 'Sabor del pan: Flan $250';

        break;
    default:
        $results[] = 'Sabor del pan: No seleccionado';
}

switch ($Cobertura) {
    case 'opcionA':
        $results[] = 'Cobertura: Chocolate $55';
        
        break;
    case 'opcionB':
        $results[] = 'Cobertura: Fresa $55';
        
        break;
    case 'opcionC':
        $results[] = 'Cobertura: Cafe $55';
        
        break;
    default:
        $results[] = 'Cobertura: No seleccionado';
}

switch ($Relleno) {
    case 'opcionX':
        $results[] = 'Relleno del pastel: Chocolate $250';

        break;
    case 'opcionY':
        $results[] = 'Relleno del pastel: Frutos Rojos $250';

        break;
    case 'opcionZ':
        $results[] = 'Relleno del pastel: Leche $250';

        break;
    default:
        $results[] = 'Relleno del pastel: No seleccionado';
}

switch ($Tipo) {
    case 'opcion1':
        $results[] = 'Tipo de pastel: 3 leches $450';
        
        break;
    case 'opcion2':
        $results[] = 'Tipo de pastel: Mil Hojas $450';
        
        break;
    case 'opcion3':
        $results[] = 'Tipo de pastel: Chocoflan $450';
        
        break;
    default:
        $results[] = 'Tipo de pastel: No seleccionado';
}

switch ($Forma) {
    case 'opcionA':
        $results[] = 'Forma del pastel: Circular';

        break;
    case 'opcionB':
        $results[] = 'Forma del pastel: Rectangular';

        break;
    case 'opcionC':
        $results[] = 'Forma del pastel: Cuadrado';

        break;
    default:
        $results[] = 'Forma del pastel: No seleccionado';
}

switch ($Personas) {
    case 'opcionX':
        $results[] = 'Para: 50 Personas $350';

        break;
    case 'opcionY':
        $results[] = 'Para: 250 Personas $450';
        
        break;
    case 'opcionZ':
        $results[] = 'Para: 150 Personas $550';
         
        break;
    default:
        $results[] = 'Para: No seleccionado';
}

switch ($Topings) {
    case 'opcion1':
        $results[] = 'Topings: Frutos rojos $78';
        
        break;
    case 'opcion2':
        $results[] = 'Topings: Chispas de chocolate $65';
      
        break;
    case 'opcion3':
        $results[] = 'Topings: Chocolate en trozo $120';
        
        break;
    case 'opcion4':
        $results[] = 'Topings: Cereales $85';
         
        break;
    default:
        $results[] = 'Topings: No seleccionado';
}
  

?>
<style>
  .container {
    margin: 0 auto;
    width: 80%; 
    text-align: center; 
  }
</style>

<div class="container">
  <h2>Tu pastel personalizado</h2>
  <ul>
    <?php foreach ($results as $result) { ?>
      <li><?= $result ?></li>
    <?php } ?>
  </ul>
</div>
<center>
<form action="Creacion.php" method="post">
    <input type="submit" value="Confirmar Pedido">
</form>
    </center>






