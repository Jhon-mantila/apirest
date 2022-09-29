<!DOCTYPE html>
<?php
require_once "Consumo.php";
if(isset($_POST['submit']))
 
{
 
$name = htmlentities(addslashes($_POST['name']));
$porcentaje = $_POST['porcentaje'];
//echo $name . "  " . $porcentaje;

$buscar = new Consumo();
$result_decode = $buscar->getConsumo($name, $porcentaje);
//echo '<pre>'; echo print_r($result_decode, true);
$total = $result_decode->total;
//echo $total; 
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    
    <title>Document</title>
</head>
<body>

<div class="container">
    <br>
    <div class="alert alert-primary" role="alert">Buscador</div>
    <div class="row">
        
        <form class="form-inline" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group mx-sm-3 mb-2">
            <input type="text" name="name" class="form-control" placeholder="Ingrese Nombre">
            </div>
            <div class="form-group mx-sm-3 mb-2">
            <input type="numeric" name="porcentaje" class="form-control" placeholder="Porcentaje">
            </div>
            <input type="submit" name="submit" class="btn btn-primary mb-2" value="Buscar"><br>
        
        </form>
        
        <?php if($total == 0){ ?>
            <div class="alert alert-warning mx-sm-5 mb-2" role="alert">No encontro registros</div>
        <?php } ?> 

    </div>
    <br><br>
    <?php if($total != 0){ ?>

    <div class="row justify-content-center">   
        

            <table id="example" class="table table-striped table-bordered table-hover" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Tipo Persona</th>
                        <th>Tipo Cargo</th>
                        <th>Departamento</th>
                        <th>Municipio</th>
                        <th>Porcentaje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($result_decode->result as $item){ ?>
                    <tr>
                            <td><?php  echo $item->nombre?></td>
                            <td><?php  echo $item->tipo_persona?></td>
                            <td><?php  echo $item->tipo_cargo?></td>
                            <td><?php  echo $item->departamento?></td>
                            <td><?php  echo $item->municipio?></td>
                            <td><?php  echo (int)$item->porcentaje . " %"?></td>
                    </tr>
                    <?php  } ?>
                </tbody>
            </table>
    </div> 

    <?php } ?> 
      

       
        
</div>    
    
     <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js" type="text/javascript"></script>
    </body>
    <script>
        $(document).ready( function () {
        $('#example').dataTable( {
            "bAutoWidth": false
        } );
        } );
    </script>
</html>