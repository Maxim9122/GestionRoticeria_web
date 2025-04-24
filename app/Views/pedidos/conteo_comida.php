<?php $session = session();
          $nombre= $session->get('nombre');
          $perfil=$session->get('perfil_id');
          $id=$session->get('id');?>
          <?php if (session()->getFlashdata('msg')): ?>
        <div id="flash-message" class="flash-message success">
            <?= session()->getFlashdata('msg') ?>
        </div>
    <?php endif; ?>   
    <script>
        setTimeout(function() {
            document.getElementById('flash-message').style.display = 'none';
        }, 3000); // 3000 milisegundos = 3 segundos
    </script>
<section class="Fondo">
<div class="" style="width: 100%;" align="center">
<section class="contenedor-titulo">
  <strong class="titulo-vidrio">Registro de Ventas por Comida</strong>
  </section>
<!-- Variable para la recaudacion -->
<?php $TotalRecaudado = 0;?>

  <div class="estiloTurno" style="width: 70%;">
      <form method="GET" action="<?= base_url('filtrarVentasComida') ?>">
        <label for="fecha_desde" style="color:#ffff;">Desde:</label>
        <input type="date" name="fecha_desde" id="fecha_desde" value="<?= esc($filtros['fecha_desde'] ?? '') ?>">

        <label for="fecha_hasta" style="color:#ffff;">Hasta:</label>
        <input type="date" name="fecha_hasta" id="fecha_hasta" value="<?= esc($filtros['fecha_hasta'] ?? '') ?>">

        <label for="id_cliente" style="color:#ffff;">Categoria:</label>
        <select name="categoria_id" id="cliente_id">
            <option value="">Todos</option>
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?= $categoria['categoria_id'] ?>" <?= (isset($filtros['categoria_id']) && $filtros['categoria_id'] == $categoria['categoria_id']) ? 'selected' : '' ?>>
                    <?= esc($categoria['descripcion']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        

          <button type="submit" class="btn">Filtrar</button>
          
          <a class="button" href="<?php echo base_url('conteoComida');?>">
               <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-checklist" viewBox="0 0 16 16">
                <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                <path d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0zM7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0z"/>
        </svg> VER TODO</a>
       </form>
       
    </div>





  <div style="text-align: end;">
    <section><a class="button" href="<?php echo base_url('compras');?>">
               <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-checklist" viewBox="0 0 16 16">
                <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                <path d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0zM7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0z"/>
        </svg> Volver a VENTAS</a></section>
<!-- Recaudacion de Ventas (Todas o por filtro)-->
  
  <br><br>
  <?php $Recaudacion = 0; ?>
  <table class="table table-responsive table-hover" id="users-list">
       <thead>
          <tr class="colorTexto2">
             <th>Nro Categoria</th>
             <th>Nombre</th>
             <th>Cantidad Vendida</th>             
             <th>Acciones</th>
          </tr>
       </thead>
       <tbody>
          <?php if($ventas): ?>
          <?php foreach($ventas as $vta): ?>
          <tr>
             <td><?php echo $vta['categoria_id']; ?></td>
             <td><?php echo $vta['nombre_categoria']; ?></td>
             <td><?php echo $vta['total_vendidos']; ?></td>              
             
             <td class="row">               

             

              </td>
              
            </tr>
         <?php endforeach; ?>
         <?php endif; ?>
       
     </table>
     <!-- Recaudacion de Ventas (Todas o por filtro)
     <h2 class="estiloTurno textColor">Total Recaudado: $ <?php echo $TotalRecaudado ?> (No se suman las Canceladas)</h2>
     <br> -->
  </div>
</div>
</section>

<style>
  @media (max-width: 768px) { /* Aplica cambios en pantallas pequeñas */
    table td:last-child {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1px; /* Espaciado entre los botones */
        min-height: 50px; /* Ajusta la altura mínima según necesites */
    }
    
    table td:last-child a {
        width: 100%; /* Hace que los botones ocupen todo el ancho */
        text-align: center;
    }
}
</style>


          <script src="<?php echo base_url('./assets/js/jquery-3.5.1.slim.min.js');?>"></script>
          <link rel="stylesheet" type="text/css" href="<?php echo base_url('./assets/css/jquery.dataTables.min.css');?>">
          <script type="text/javascript" src="<?php echo base_url('./assets/js/jquery.dataTables.min.js');?>"></script>
<script>
    
    $(document).ready( function () {
      $('#users-list').DataTable( {
        "order": [[0, "desc"]],
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros por página.",
            "zeroRecords": "Lo sentimos! No hay resultados.",
            "info": "Mostrando la página _PAGE_ de _PAGES_",
            "infoEmpty": "No hay registros disponibles.",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "search": "Buscar: ",
            "paginate": {
              "next": "Siguiente",
              "previous": "Anterior"
            }
        }
    } );
    
  } );


    // Crear un objeto Date en UTC
    const today = new Date();

// Ajustar la hora a la zona horaria de Argentina (UTC-3)
const options = { timeZone: 'America/Argentina/Buenos_Aires', hour12: false };
const formatter = new Intl.DateTimeFormat('es-AR', {
    ...options,
    year: 'numeric', month: '2-digit', day: '2-digit'
});

const formattedDate = formatter.format(today).split('/').reverse().join('-'); // Formato YYYY-MM-DD


</script>





<br><br>