<?php $cart = \Config\Services::cart(); ?>

<!-- Mensajes temporales -->
<?php if (session()->getFlashdata('msg')): ?>
    <div id="flash-message-success" class="flash-message success">
        <?= session()->getFlashdata('msg') ?>
    </div>
<?php endif; ?>
<?php if (session("msgEr")): ?>
    <div id="flash-message-danger" class="flash-message danger">
        <?= session("msgEr"); ?>
    </div>
<?php endif; ?>

<script>
    // Ocultar mensaje de éxito después de 3 segundos
    setTimeout(function() {
        const successMessage = document.getElementById('flash-message-success');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 3000);

    // Ocultar mensaje de error después de 3 segundos
    setTimeout(function() {
        const errorMessage = document.getElementById('flash-message-danger');
        if (errorMessage) {
            errorMessage.style.display = 'none';
        }
    }, 3000);
</script>

<!-- Fin de los mensajes temporales -->
<br>

<style>

    /* Estilos generales de la tabla del carrito */
.tabla-carrito {
    width: 100%;
    border-collapse: collapse;
    font-size: 16px;
}

.tabla-carrito th,
.tabla-carrito td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: center;
}

.tabla-carrito thead {
    background-color: #f4f4f4;
    font-weight: bold;
}

/* Ajustar tabla en pantallas pequeñas */
@media screen and (max-width: 600px) {
    .tabla-carrito {
        font-size: 14px; /* Reducir tamaño de fuente */
    }
    
    .ocultar-en-movil {
        display: none; /* Ocultar columnas innecesarias */
    }

    .tabla-carrito th, 
    .tabla-carrito td {
        padding: 6px; /* Reducir espacio interno */
    }
}

</style>




<?php

$id_pedido = '';
// Añadido para el tipo de compra
$session = session();

if (!empty($session)) {
    $id_pedido = $session->get('id_pedido');
}
//print_r($id_pedido);
//exit;
?>

<div class="compados" style="width:100%;">

<div class="" >
        <div class = "">
            <u><i><h2>Productos En Carrito</h2></i></u>
        </div>
        <br>
        <div class="sinProductos" style="color:#ffff; " align="center" >
            <h2>
            <?php  
            // Si el carrito está vacio, mostrar el siguiente mensaje
            if (empty($carrito))
            {
                echo 'No hay productos agregados Todavia.!';                
            }
            ?>
            </h2>
        </div>
   

<?php
// Asegúrate de definir $gran_total antes de este script
$gran_total = isset($gran_total) ? $gran_total : 0; // Si $gran_total no está definido, usa 0 como valor
?>

        <table class="texto-negrita">

            <?php // Todos los items de carrito en "$cart".
            if ($carrito):
            ?>
                <tr class=" colorTexto2"  >
                    <td>ID</td>
                    <td>Nombre</td>
                    <td class="ocultar-en-movil">Precio</td>
                    <td>Cantidad</td>
                    <td>Subtotal</td>
                    <td>Eliminar?</td>
                </tr>
                
            <?php // Crea un formulario php y manda los valores a carrito_controller/actualiza carrito
            echo form_open('carrito/procesarCarrito', ['id' => 'carrito_form']); // Deja vacío para enviar al mismo controlador
                $gastos = 0;
                $i = 1;

                foreach ($carrito as $item):
                    echo form_hidden('cart[' . $item['id'] . '][id]', $item['id']);
                    echo form_hidden('cart[' . $item['id'] . '][rowid]', $item['rowid']);
                    echo form_hidden('cart[' . $item['id'] . '][name]', $item['name']);
                    echo form_hidden('cart[' . $item['id'] . '][price]', $item['price']);
                    echo form_hidden('cart[' . $item['id'] . '][qty]', $item['qty']);
            ?>
                    <tr style="color: black;" >
                        
                        <td  class="separador" style="color: #ffff;">
                            <?php echo $i++; ?>
                        </td>
                        <td class="separador" style="color: #ffff;">
                            <?php echo $item['name']; ?>
                        </td>
                        <td class="separador ocultar-en-movil"  style="color: #ffff;">
                        $ARS <?php  echo number_format($item['price'], 2);?>
                        </td>
                        
                        <td class="separador" style="color: #ffff;">
                        <?php 
                            if ($item['id'] < 10000) {
                                echo form_input([
                                    'name' => 'cart[' . $item['id'] . '][qty]',
                                    'value' => $item['qty'],
                                    'type' => 'number',
                                    'min' => '1',
                                    'maxlength' => '3',
                                    'size' => '1',
                                    'style' => 'text-align: right; width: 50px;',
                                    'oninput' => "this.value = this.value.replace(/[^0-9]/g, '')"
                                ]);?>
                                <span class="stock-disponible"> (Disponibles: <?php echo  $item['options']['stock']; ?>) </span>
                            <?php } else {
                                echo number_format($item['qty']);
                            }
                            ?>
                        </td>
                        
                            <?php $gran_total = $gran_total + $item['subtotal']; ?>
                        <td class="separador" style="color: #ffff;">
                        $ARS <?php echo number_format($item['subtotal'], 2) ?>
                        </td>
                        <td class="imagenCarrito separador" style="color: #ffff;">
                            <?php // Imagen para Eliminar Item
                                $path = '<img src= '. base_url('assets/img/icons/basura3.png') . ' width="10px" height="10px">';
                                echo anchor('carrito_elimina/'. $item['rowid'], $path);
                            ?>
                            
                        </td>
                        
                    </tr>
                    
                <?php
                endforeach;
                ?>
                
                <tr>
                    <td>
                        
                        
                    </td>
                    
                    <td colspan="5" align="right">
                        <br>
                        <h4 class="totalVenta">Total: $
                            
                            <?php //Gran Total
                            echo number_format($gran_total, 2);
                            ?>
                            
                        </h4>

                        <h4></h4>
                        <br>
                        <input type="hidden" id="accion" name="accion" value=""> <!-- Este campo controlará a qué función se envía -->

                        <!-- Cancelar edicion de pedido -->
                        <?php if ($id_pedido) { ?>
                            <a href="<?php echo base_url('cancelar_edicion/'.$id_pedido);?>" class="danger" onclick="return confirmarAccionPedido();">
                                Cancelar Modificación
                            </a>
                            <?php } else {?>
                            <!-- Borrar carrito usa mensaje de confirmacion -->
                            <a href="<?php echo base_url('carrito_elimina/all');?>" class="danger" onclick="return confirmarAccionCompra();">
                                        Borrar Todo
                            </a>
                            <?php  } ?>

                        <!-- Submit boton. Actualiza los datos en el carrito -->
                        <button type="submit" class="success" onclick="setAccion('actualizar')">
                            Actualizar Importes
                        </button>
                        

                            <br><br>
                        <!-- " Confirmar orden envia a carrito_controller/muestra_compra  -->
                        <a href="javascript:void(0);" class="success" onclick="setAccion('confirmar')">Confirmar Compra</a>

                        
                    </td>
                </tr>
                <?php echo form_close();
            endif; ?>
        </table>
    </div>
</div>

<script>
    function setAccion(accion) {
    // Asignamos la acción al campo oculto
    document.getElementById('accion').value = accion;

    // Enviamos el formulario
    document.getElementById('carrito_form').submit();
}

</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmarAccionCompra() {
        Swal.fire({
            title: "¿Estás seguro?",
            text: "Esto eliminará todos los productos del carrito.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, Eliminar Todo",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?php echo base_url('carrito_elimina/all'); ?>";
            }
        });
        return false; // Evita que el enlace siga su curso normal
    }

    
    function confirmarAccionPedido() {
        Swal.fire({
            title: "¿Estás seguro?",
            text: "Se cancelara la modificacion del pedido y quedara como estaba.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, Cancelar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?php echo base_url('cancelar_edicion/'.$id_pedido); ?>";
            }
        });
        return false; // Evita que el enlace siga su curso normal
    }

</script>

<br>