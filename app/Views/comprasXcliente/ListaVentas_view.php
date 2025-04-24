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
  <strong class="titulo-vidrio">Registro de Ventas</strong>
  </section>
<!-- Variable para la recaudacion -->
<?php $TotalRecaudado = 0;?>

  <div class="estiloTurno" style="width: 70%;">
      <form method="GET" action="<?= base_url('Carrito_controller/filtrarVentas') ?>">
        <label for="fecha_desde" style="color:#ffff;">Desde:</label>
        <input type="date" name="fecha_desde" id="fecha_desde" value="<?= esc($filtros['fecha_desde'] ?? '') ?>">
        <input type="time" name="hora_desde" id="hora_desde" value="<?= esc($filtros['hora_desde'] ?? '') ?>">

        <label for="fecha_hasta" style="color:#ffff;">Hasta:</label>
        <input type="date" name="fecha_hasta" id="fecha_hasta" value="<?= esc($filtros['fecha_hasta'] ?? '') ?>">
        <input type="time" name="hora_hasta" id="hora_hasta" value="<?= esc($filtros['hora_hasta'] ?? '') ?>">
        <br>
        <label for="estado" style="color:#ffff;">Modo Compra:</label>
        <select name="modo_compra" id="estado">
            <option value="">Todos</option>
            <option value="Fiado" <?= ($filtros['modo_compra'] ?? '') == 'Fiado' ? 'selected' : '' ?>>Fiado</option>
            <option value="Compra" <?= ($filtros['modo_compra'] ?? '') == 'Compra' ? 'selected' : '' ?>>Compra</option>
        </select>
        <br>
        <label for="estado" style="color:#ffff;">Estado:</label>
        <select name="estado" id="estado">
            <option value="">Todos</option>
            <option value="Entregado" <?= ($filtros['estado'] ?? '') == 'Entregado' ? 'selected' : '' ?>>Entregado</option>
            <option value="Cobrado" <?= ($filtros['estado'] ?? '') == 'Cobrado' ? 'selected' : '' ?>>Cobrado</option>
        </select>
        <br><br>
        <label for="id_cliente" style="color:#ffff;">Cliente:</label>
        <select name="id_cliente" id="cliente_id">
            <option value="">Todos</option>
            <?php foreach ($clientes as $cliente): ?>
                <option value="<?= $cliente['id_cliente'] ?>" <?= (isset($filtros['id_cliente']) && $filtros['id_cliente'] == $cliente['id_cliente']) ? 'selected' : '' ?>>
                    <?= esc($cliente['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>

          <button type="submit" class="btn">Filtrar</button>
          <a class="button" href="<?php echo base_url('compras');?>">
               <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-checklist" viewBox="0 0 16 16">
                <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                <path d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0zM7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0z"/>
        </svg> VENTAS TODAS</a>
       </form>
       
    </div>

    <br>

  <div style="text-align: end;">  
    <section><a class="btn" href="<?php echo base_url('conteoComida');?>">
               <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-checklist" viewBox="0 0 16 16">
                <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                <path d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0zM7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0z"/>
        </svg> VENTA POR COMIDA</a></section>
<!-- Recaudacion de Ventas (Todas o por filtro)-->
  
  <br><br>
  <?php $Recaudacion = 0; ?>
  <table class="table table-responsive table-hover" id="users-list">
       <thead>
          <tr class="colorTexto2">
             <th>Nro Pedido</th>
             <th>Cliente</th>
             <th>Vendedor</th> 
             <th>Modo Compra</th>           
             <th>ESTADO</th>
             <th>Total Venta</th>
             <th>Fecha</th>
             <th>Hora</th>
             <th>Tipo Pago</th>
             <th>Acciones</th>
          </tr>
       </thead>
       <tbody>
          <?php if($ventas): ?>
          <?php foreach($ventas as $vta): ?>
          <tr>
             <td><?php echo $vta['id']; ?></td>
             <td><?php echo $vta['nombre_cliente']; ?></td>
             <td><?php echo $vta['nombre_vendedor']; ?></td> 
             <td><?php echo $vta['modo_compra']; ?></td>           
             <td><?php echo $vta['estado']; ?></td>
             <td>$<?php echo $vta['total_venta']; ?></td>
             <td><?php echo $vta['fecha'];?></td>
             <td><?php echo $vta['hora']; ?></td>
             <td><?php echo $vta['tipo_pago']; ?></td>
             
             <td class="row">               

             <div class="dropdown">
              <span class="dropdown-toggle btn">Acciones▼</span>
               <ul class="dropdown-menu">
               <li>
                <a class="btnDesplegable" style="color:#ffff; background:#3c3d3c; border-radius:10px;" href="<?php echo base_url('DetalleVta/'.$vta['id']);?>">
                    Ver Detalle
                </a>
            </li>
            <li>
                <?php if($vta['estado'] == 'Facturada'){?>
                    <a class="btnDesplegable" style="color:#ffff; background:#3c3d3c; border-radius:10px; padding:8px;" href="<?php echo base_url('generarTicketFacturaC/'.$vta['id']); ?>">
                        Imp.Factura
                    </a>
                <?php  } if($vta['estado'] == 'Cobrado' || $vta['estado'] == 'Entregado'){  ?>
                    <a class="btnDesplegable" style="color:#ffff; background:#3c3d3c; border-radius:10px;  padding:8px;" href="<?php echo base_url('generarTicketCliente/'.$vta['id']); ?>">
                        Imp.Ticket
                    </a>
                <?php } if ($vta['estado'] == 'Entregado') {  ?>
                    
                    <form action="<?= base_url('ventas/marcarComoCobrado') ?>" method="post" onsubmit="return confirm('¿Marcar como Cobrado?');">
                        <input type="hidden" name="id_venta" value="<?= $vta['id'] ?>">
                        <button type="submit" class="btn btnDesplegable" 
                            style="color:#fff; background:#3c3d3c; border-radius:10px; padding:8px; width:30px; height:30px; margin-top:10px;">
                            Cobrar
                        </button>

                    </form>
                    
                <?php } ?> 

            </li>                                  
                    </ul>
                </div>

              </td>
              <?php if($vta['estado'] != 'Error_factura' && $vta['estado'] != 'Cancelado'){?>
              <?php $TotalRecaudado = $TotalRecaudado + $vta['total_venta']; ?>
              <?php } ?> 
            </tr>
         <?php endforeach; ?>
         <?php endif; ?>
       
     </table>
     <!-- Recaudacion de Ventas (Todas o por filtro)-->
     <h2 class="estiloTurno textColor">Total Recaudado: $ <?php echo $TotalRecaudado ?> (No se suman las Canceladas)</h2>
     <br>
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