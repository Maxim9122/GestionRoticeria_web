<?php $session = session();
          $nombre= $session->get('nombre');
          $perfil=$session->get('perfil_id');
          $id=$session->get('id');
          $estado = '';
if ($session->has('estado')) {
    $estado = $session->get('estado');
}?>
          
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
    <script>
        setTimeout(function() {
            document.getElementById('flash-message').style.display = 'none';
        }, 3000); // 3000 milisegundos = 3 segundos
    </script>

<?php if (session("msgEr")): ?>
    <div id="flash-message-Error" class="flash-message danger">
        <?php echo session("msgEr"); ?>
        <button class="close-btn" onclick="cerrarMensaje()">×</button>
    </div>
<?php endif; ?>
<script>
function cerrarMensaje() {
    document.getElementById("flash-message-Error").style.display = "none";
}
</script>


<!-- Fin de los mensajes temporales -->
<br>
<div class="" style="width: 100%;">
  <div class="">
  <h2 class="textoColor" align="center">Listado de Productos</h2>
  <br>
<?php if($estado == '' || $estado == 'Modificando'){ ?>
  <section class="buscador">
  
  <form id="product_form" action="<?php echo base_url('Carrito_agrega'); ?>" method="post">
  <button type="submit" class="success" style="display: none;">Agregar por Codigo de Barra</button>
  <br>
    <div style="position: relative; display: inline-block;">
        <input oninput="this.value = this.value.replace(/\D/g, '')" type="text" id="product_input" placeholder="Agregar producto por codigo de barra..." autocomplete="off" required onfocus="this.value=''" />
        <input type="hidden" id="cantidad" name="cantidad">
        <select id="product_select" name="product_id" required size="3">
            <option class="separador">Seleccione un Producto!</option>
            <?php if ($productos): ?>
                
                <?php foreach ($productos as $prod): ?>
                    <?php if ($prod['stock'] != 0) { ?>
                        <option class="product-option" 
                                value="<?php echo $prod['id']; ?>" 
                                data-nombre="<?php echo $prod['nombre']; ?>" 
                                data-precio="<?php echo $prod['precio_vta']; ?>" 
                                data-stock="<?php echo $prod['stock']; ?>">  <!-- Agregamos data-stock -->
                            <?php echo $prod['codigo_barra']; ?>
                        </option>
                    <?php } ?>
                <?php endforeach; ?>

                
            <?php endif; ?>
        </select>
        <input type="hidden" name="nombre" id="nombre">
        <input type="hidden" name="precio_vta" id="precio_vta">
        <input type="hidden" name="id" id="product_id">
        <input type="hidden" name="stock" id="producto_stock">
    </div>
</form>

    </section>
 <?php } ?> 
  <table class="" id="users-list">
   <thead>
      <tr class="colorTexto2">
         <th>Nombre</th>
         <th>Precio Venta</th>
         <th class="ocultar-en-movil">Categoría</th>
         <th>Imagen</th>
         <th>Stock</th>
         <th>Cantidad</th>
         <th>Acciones</th>
      </tr>
   </thead>
   <tbody>
      <?php if($productos): ?>
      <?php foreach($productos as $prod): ?>
      <tr>
         <td><?php echo $prod['nombre']; ?></td>
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
         <td class="ocultar-en-movil"><?php echo $categoria_nombre; ?></td>
         <td><img class="frmImg" src="<?php echo base_url('assets/uploads/'.$prod['imagen']);?>"></td>
         
         <?php if($prod['stock'] <= $prod['stock_min']){ ?>
            <td class="text-center">
                <span class="low-stock-ring"><?php echo $prod['stock']; ?></span>
            </td>
         <?php } else { ?>
                <td class="text-center"><?php echo $prod['stock']; ?></td>
         <?php } ?>

         <td>
            <!-- Campo para ingresar la cantidad -->
            <input oninput="this.value = this.value.replace(/\D/g, ''); if (this.value < 1) this.value = 1;" type="number" id="cantidad_<?php echo $prod['id']; ?>" min="1" max="<?php echo $prod['stock']; ?>" value="1" class="input-cantidad">
         </td>

         <td>
            <?php if($prod['stock'] <= 0){ ?>
               <button class="btn danger" disabled>Sin Stock</button>
            <?php } else if ($session && ($perfil == 2 || $perfil == 1) && $estado == '' || $estado == 'Modificando') { ?>
               
               <!-- Formulario para agregar al carrito -->
               <?php echo form_open('Carrito_agrega', ['class' => 'form-carrito']); ?>
               <?php echo form_hidden('id', $prod['id']); ?>
               <?php echo form_hidden('nombre', $prod['nombre']); ?>
               <?php echo form_hidden('precio_vta', $prod['precio_vta']); ?>
               
               <input type="hidden" name="cantidad" id="inputCantidad_<?php echo $prod['id']; ?>" value="1">
               
               <button type="submit" class="btn btn-agregar" data-id="<?php echo $prod['id']; ?>">Agregar</button>
               <?php echo form_close(); ?>

            <?php } else {?>
               <h4>Primero Termine de Cobrar el Pedido</h4>
            <?php } ?>
         </td>
         
      </tr>
      <?php endforeach; ?>
      <?php endif; ?>
   </tbody>
</table>
     <br>
  </div>
</div>


<!-- Modal de Confirmación -->
<div id="confirmModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center;">
    <div style="color:white; background: black; padding: 20px; border-radius: 10px; text-align: center;">
        <h2 id="modal_product_name"></h2>
        <br>
        <p>Stock Disponible: <span id="modal_product_stock"></span></p>
        <br>
        <label for="modal_quantity" style="color:white;">Cantidad:</label>
        <input type="number" id="modal_quantity" min="1" required maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
        <br><br>
        <button id="confirm_add" class="btn">Agregar</button>
        <button id="cancel_add" class="btn">Cancelar</button>
    </div>
</div>


<script>
//Script para manejo de stock en la tabla con boton Agregar
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".form-carrito").forEach(form => {
        form.addEventListener("submit", function(event) {
            let productId = form.querySelector(".btn-agregar").getAttribute("data-id");
            let cantidadInput = document.getElementById("cantidad_" + productId);
            let cantidadHidden = document.getElementById("inputCantidad_" + productId);
            let stockMax = parseInt(cantidadInput.getAttribute("max"));

            // Verifica que la cantidad no sea mayor al stock
            if (parseInt(cantidadInput.value) > stockMax) {
                alert("No puedes agregar más de " + stockMax + " unidades.");
                cantidadInput.value = stockMax; // Ajusta la cantidad al máximo permitido
                cantidadHidden.value = stockMax;
                event.preventDefault(); // Evita que se envíe el formulario
                return;
            }

            // Actualiza el input hidden antes de enviar
            cantidadHidden.value = cantidadInput.value;
        });
    });
});

</script>

<script src="<?php echo base_url('./assets/js/jquery-3.5.1.slim.min.js');?>"></script>
<script src="<?php echo base_url('./assets/js/jquery-ui.js');?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('./assets/css/jquery.dataTables.min.css');?>">
<script type="text/javascript" src="<?php echo base_url('./assets/js/jquery.dataTables.min.js');?>"></script>

<script>
    
    $(document).ready(function () {
    // Inicializar DataTables
    $('#users-list').DataTable({

        "stateSave": true, // Habilitar el guardado del estado

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
        },
        initComplete: function () {
            // Cambiar el texto del placeholder en el input de búsqueda
            $('#users-list_filter input').attr('placeholder', 'Nombre, Categoría, etc...');

            // Enfocar el campo de búsqueda de la tabla después de inicializar DataTables
            $('#users-list_filter input').focus();
        }
    });

    // Deshabilitar autofocus en el campo de código de barras
    document.getElementById('product_input').removeAttribute('autofocus');
});

</script>
<style>
  
/* Mover el buscador a la derecha */
.dataTables_filter {
        display: flex;
        justify-content: flex-end;
        width: 100%;
    }

    /* Mover el selector de "registros por página" a la derecha */
    .dataTables_length {
        text-align: right;
        width: 100%;
    }

    .dataTables_length select {
        display: inline-block;
        margin: 0 auto;
    }

    /* Hacer el campo de búsqueda más largo y ancho */
    .dataTables_filter input {
        width: 300px; /* Ajusta el tamaño según sea necesario */
        height: 55px; /* Ajusta la altura si lo deseas */
        font-size: 24px; /* Tamaño de la fuente */
        padding: 5px 10px; /* Añadir espacio dentro del campo */
        border-radius: 5px; /* Bordes redondeados */
        border: 1px solid #ccc; /* Borde gris claro */
    }

    /* Cambiar el color y hacer más nítida la letra del placeholder */
    .dataTables_filter input::placeholder {
        color: white; /* Cambiar a blanco */
        opacity: 1; /* Asegura que el color del placeholder no sea opaco */
        font-weight: bold; /* Hacer el texto más nítido */
    }

</style>
<script>
const input = document.getElementById('product_input');
const select = document.getElementById('product_select');
const form = document.getElementById('product_form');

// Elementos del modal
const modal = document.getElementById('confirmModal');
const modalProductName = document.getElementById('modal_product_name');
const modalProductStock = document.getElementById('modal_product_stock');
const modalQuantity = document.getElementById('modal_quantity');
const confirmAdd = document.getElementById('confirm_add');
const cancelAdd = document.getElementById('cancel_add');

let selectedProduct = {}; // Almacena temporalmente los datos del producto

// Detectar cuando se ingresa un código de barras completo (con Enter)
input.addEventListener('keypress', function(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        buscarProductoPorCodigo(input.value.trim());
    }
});

// Función para buscar el producto por código de barras
function buscarProductoPorCodigo(codigo) {
    const options = select.options;
    let productoEncontrado = false;

    for (let i = 1; i < options.length; i++) { // Saltar la primera opción ("Seleccione un Producto!")
        if (options[i].text.trim() === codigo) {
            productoEncontrado = true;
            select.selectedIndex = i;

            selectedProduct = {
                id: options[i].value,
                nombre: options[i].getAttribute('data-nombre'),
                precio: options[i].getAttribute('data-precio'),
                stock: parseInt(options[i].getAttribute('data-stock'))
            };

            // Mostrar el modal con la información del producto
            modalProductName.textContent = selectedProduct.nombre;
            modalProductStock.textContent = selectedProduct.stock;
            modalQuantity.value = 1; // Iniciar cantidad en 1
            modal.style.display = 'flex';
            modalQuantity.focus(); // Enfocar el input de cantidad

            break; // Salimos del bucle ya que encontramos el producto
        }
    }

    if (!productoEncontrado) {
        alert('Producto no encontrado. Verifica el código de barras.');
        input.value = ''; // Limpiar input para un nuevo intento
    }
}

// Confirmar la adición del producto al carrito
confirmAdd.addEventListener('click', function() {
    const cantidad = parseInt(modalQuantity.value);

    if (cantidad > 0 && cantidad <= selectedProduct.stock) {
        // Asignar los valores al formulario
        document.getElementById('nombre').value = selectedProduct.nombre;
        document.getElementById('precio_vta').value = selectedProduct.precio;
        document.getElementById('product_id').value = selectedProduct.id;
        document.getElementById('cantidad').value = cantidad; // Guardar cantidad seleccionada en el campo correcto

        modal.style.display = 'none'; // Ocultar el modal
        form.submit(); // Enviar formulario
    } else {
        alert('Cantidad inválida o insuficiente.');
    }
});

// Cancelar la operación y cerrar el modal
cancelAdd.addEventListener('click', function() {
    modal.style.display = 'none'; // Ocultar modal
    input.value = ''; // Limpiar el input para volver a escanear
    input.focus();
});

// Cerrar modal con tecla ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        modal.style.display = 'none';
        input.value = '';
        input.focus();
    } else if (event.key === 'Enter' && modal.style.display === 'flex') {
        confirmAdd.click(); // Simular clic en "Agregar"
    }
});

// Enfocar el input al cargar la página
document.addEventListener("DOMContentLoaded", function() {
    input.focus();
});

</script>

<br><br>