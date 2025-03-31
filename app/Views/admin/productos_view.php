<?php $session = session();
          $nombre= $session->get('nombre');
          $perfil=$session->get('perfil_id');
          $id=$session->get('id');?>  
 <?php if($perfil == 1){  ?>

<!-- Mensajes temporales -->
<?php if(session()->getFlashdata('mensaje_stock')): ?>
    <div id="msg_stock">
        <?= session()->getFlashdata('mensaje_stock'); ?>
    </div>
<?php endif; ?>

<style>
    #msg_stock {
        position: fixed;
        top: 100px;
        left: 50%;
        transform: translateX(-50%);
        background-color: black; /* Fondo oscuro para destacar el mensaje */
        color: white;
        font-weight: bold;
        padding: 10px 20px;
        border: 3px solid #ff073a; /* Rojo flúor */
        border-radius: 5px;
        text-align: center;
        z-index: 1000;
        box-shadow: 0px 0px 10px #ff073a; /* Efecto neón */
    }

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

<script>
    setTimeout(function() {
        let msg = document.getElementById('msg_stock');
        if (msg) {
            msg.style.display = 'none';
        }
    }, 1500); // Se oculta después de 1.5 segundos
</script>
<?php if (session()->getFlashdata('msg')): ?>
        <div id="flash-message" class="flash-message success">
            <?= session()->getFlashdata('msg') ?>
        </div>
    <?php endif; ?>
    <?php if (session("msgEr")): ?>
        <div id="flash-message" class="flash-message danger">
            <?php echo session("msgEr"); ?>
        </div>
    <?php endif; ?>
    <script>
        setTimeout(function() {
            document.getElementById('flash-message').style.display = 'none';
        }, 3000); // 3000 milisegundos = 3 segundos
    </script>
<!-- Fin de los mensajes temporales -->
 
<section class="contenedor-titulo">
  <strong class="titulo-vidrio">ABM de Productos</strong>
  </section>
<div style="width: 100%; text-align: end;">
  
<br>
  <div class="dropdown2" style="margin-right: 45px;">
        <span class="dropdown-toggle2 btn">Mas Opciones▼</span>
        <ul class="dropdown-menu2">
            <li>
            <a class="btn" href="<?php echo base_url('StockBajo');?>">
                    📄 Productos Stock Bajo
                </a>
            </li>
            <li>
                <a class="btn" href="<?php echo base_url('nuevoProducto');?>">
                    📄 Crear Producto
                </a>
            </li>
            <li>
                <a class="btn" href="<?php echo base_url('eliminadosProd');?>">
                    ❌ Eliminados
                </a>
            </li>
                </ul>
    </div>




  <div class="mt-3 text">
      <!-- Variables para calcular cuanto hay en $ en mercaderia total -->
  <?php $TotalArticulos= 0; 
        $totalCU = 0;
  ?>

  <br>
  <table class="table table-responsive table-hover" id="users-list">
       <thead>
          <tr class="colorTexto2">
             <th>Nombre</th>
             <th>Precio</th>
             <th>Precio Venta</th>
             <th>Categoría</th>
             <th>Imagen</th>
             <th>Stock</th>
             <th>Acciones</th>
          </tr>
       </thead>
       <tbody>
          <?php if($productos): ?>
          <?php foreach($productos as $prod): ?>
          <tr>
             <td><?php echo $prod['nombre']; ?></td>
             <td>$<?php echo $prod['precio']; ?></td>
             <td>$<?php echo $prod['precio_vta']; ?></td>
             <?php 
             $categoria_nombre = 'Desconocida';
             foreach ($categorias as $categoria) {
                 if ($categoria['categoria_id'] == $prod['categoria_id']) {
                     $categoria_nombre = $categoria['descripcion'];
                     break;
                 }
             }
             ?>
             <td><?php echo $categoria_nombre; ?></td>
             
             <td><img class="frmImg" src="<?php echo base_url('assets/uploads/'.$prod['imagen']);?>"></td>
             
             <?php if($prod['stock'] <= $prod['stock_min']){ ?>
                <td class="text-center">
                    <span class="low-stock-ring"><?php echo $prod['stock']; ?></span>
                </td>
            <?php } else { ?>
                    <td class="text-center"><?php echo $prod['stock']; ?></td>
            <?php } ?>
             
            <td>
               <a class="btn btn-outline-warning" href="<?php echo base_url('ProductoEdit/'.$prod['id']);?>">
               ✏️ Editar</a>&nbsp;&nbsp;
                <a class="btn btn-outline-danger" href="<?php echo base_url('deleteProd/'.$prod['id']);?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                </svg> Eliminar</a>
             </td>
             <?php $totalCU = $prod['precio_vta'] * $prod['stock']; ?>
             <?php $TotalArticulos = $TotalArticulos + $totalCU; ?>
            </tr>
         <?php endforeach; ?>
         <?php endif; ?>
       
     </table>
     <h2 class="estiloTurno textColor day">Total en articulos: $ <?php echo $TotalArticulos ?></h2>
     <br>
  </div>
</div>

<script src="<?php echo base_url('./assets/js/jquery-3.5.1.slim.min.js');?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('./assets/css/jquery.dataTables.min.css');?>">
<script type="text/javascript" src="<?php echo base_url('./assets/js/jquery.dataTables.min.js');?>"></script>

<script>
    
    $(document).ready( function () {
      $('#users-list').DataTable( {
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros por página.",
            "zeroRecords": "Lo sentimos! No hay resultados.",
            "info": "Mostrando la página  _PAGE_ de _PAGES_",
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


  function formatearMiles() {
        const input = document.getElementById('pago');
        let valor = input.value.replace(/\./g, ''); // Quita los puntos
        if (valor === '') {
            input.value = '';
            return;
        }
        valor = parseFloat(valor).toLocaleString('de-DE'); // Agrega el formato de miles con puntos
        input.value = valor;
    }
</script>
<?php }else{ ?>
  <h2>Su perfil no tiene acceso a esta parte,
    Vuelva a alguna seccion de Empleado!
  </h2>
<?php }?>
<br><br>