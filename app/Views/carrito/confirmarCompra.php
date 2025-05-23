<?php if (session("msgEr")): ?>
    <div id="flash-message" class="flash-message danger">
        <?php echo session("msgEr"); ?>
    </div>
    <script>
        setTimeout(function() {
            document.getElementById('flash-message').style.display = 'none';
        }, 3000);
    </script>
<?php endif; ?>
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

<?php
$cart = \Config\Services::cart(); 
$session = session();
$nombre = $session->get('nombre');
$perfil = $session->get('perfil_id');
$estado = '';
if ($session->has('estado')) {
    $estado = $session->get('estado');
}

$nombre_cliente = '';
if ($session->has('nombre_cliente')) {
    $nombre_cliente = $session->get('nombre_cliente');
}
if ($session->has('nombre_cli')) {
    $nombre_cliente = $session->get('nombre_cli');
}

if ($session->has('id_cliente')) {
    $id_cliente = $session->get('id_cliente');
}

$id_pedido = '';
if ($session->has('id_pedido')) {
    $id_pedido = $session->get('id_pedido');
}

if ($session->has('total_venta')) {
    $total_venta = $session->get('total_venta');
}

$modo_compra = '';
if ($session->has('modo_compra')) {
    $modo_compra = $session->get('modo_compra');
}

$gran_total = 0;
if ($cart):
    foreach ($cart->contents() as $item):
        $gran_total = $gran_total + $item['subtotal'];
    endforeach;
endif;
?>

<div style="width:100%;" align="center">
    <div id="">
        <?php echo form_open("confirma_compra", ['class' => 'form-signin', 'role' => 'form']); ?>
        <br>
        <div align="center">
            <u><i><h2 align="center">Resumen del Pedido</h2></i></u>

            <table style="font-weight: 900;" class="tableResponsive">
                <tr>
                    <td style="color:rgb(192, 250, 214);"><strong>Total General:</strong></td>
                    <td style="color: #ffff;">
                        <strong id="totalCompra">
                    $<?php echo number_format(($gran_total > 0 ? $gran_total : $total_venta), 2, '.', ','); ?>
                        </strong>
                    </td>
                </tr>
                <tr>
                    <td style="color:rgb(192, 250, 214);"><strong>Vendedor:</strong></td>
                    <td style="color: #ffff;"><?php echo $nombre ?></td>
                </tr>
                <tr>
                    <td style="color:rgb(192, 250, 214);"><strong>Nombre del Cliente:</strong></td>
                    <td>
                        <?php if ($estado === 'Cobrando' || $estado === 'Modificando'): ?>
                            <input type="text" class="selector" name="nombre_prov" value="<?= esc($nombre_cliente ?? '') ?>" readonly>
                            <input type="hidden" name="id_cliente" value="<?= esc($id_cliente ?? '') ?>">
                        <?php else: ?>
                            <input class="selector" type="text" name="nombre_prov" id="nombreCliente" 
                                placeholder="Ingrese nombre cliente" maxlength="30" required
                                value="<?= esc($nombre_cliente ?? '') ?>" list="clientesList" autocomplete="off">

                            <input type="hidden" name="id_cliente" id="idClienteSeleccionado" value="<?= esc($id_cliente ?? '') ?>">

                            <datalist id="clientesList"></datalist>
                        <?php endif; ?>
                    </td>
                </tr>
                     
                <?php if($estado == 'Cobrando'){ ?>
                <tr>
                    <td style="color: rgb(192, 250, 214);"><strong>Monto en Transferencia:</strong></td>
                    <td>
                        <input class="selector" type="text" id="pagoTransferencia" name="pagoTransferencia" placeholder="Monto en $" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, ''); calcularMontoEfectivo();">
                    </td>
                </tr>
                
                <tr>
                    <td style="color: rgb(192, 250, 214);"><strong>Monto en Efectivo:</strong></td>
                    <td>
                        <input class="selector" type="text" id="pagoEfectivo" name="pagoEfectivo" placeholder="Monto en $" maxlength="15" readonly>
                    </td>
                </tr>
                <?php } ?>
                <tr>
                <td style="color: rgb(192, 250, 214);"><strong>Modo de Compra:</strong></td>
                <td>
                    <select name="modo_compra" id="tipoCompra" class="selector">
                        <?php if($modo_compra != ''){ ?>
                        <option value="Compra" <?= ($modo_compra == 'Compra' || empty($modo_compra)) ? 'selected' : '' ?>>Compra</option>
                        <?php } else { ?>
                        <option value="Compra" <?= ($modo_compra == 'Compra' || empty($modo_compra)) ? 'selected' : '' ?>>Compra</option>
                        <option value="Fiado" <?= ($modo_compra == 'Fiado') ? 'selected' : '' ?>>Fiado</option>
                        <?php } ?>
                    </select>                    
                </td>
                </tr>    
                <?php if($estado == 'Cobrando'){ ?>            
                <tr>
                    <td style="color: rgb(192, 250, 214);"><strong>Con Envío:</strong></td>
                    <td>
                        <select name="con_envio" id="conEnvio" class="selector">
                            <option value="No">No</option>
                            <option value="Si">Sí</option>
                        </select>
                    </td>
                </tr>
                <tr id="costoEnvioFila" style="display: none;">
                    <td style="color: rgb(192, 250, 214);"><strong>Costo de Envío:</strong></td>
                    <td>
                        <input class="selector" type="text" id="costoEnvio" name="costoEnvio" placeholder="Costo de envío en $" maxlength="15">
                    </td>
                </tr>
                <?php } ?>
                <?php echo form_hidden('total_venta', $gran_total); ?>
            </table>
            
            <section class="botones-container" style="width:65%;">                
            
                <?php if ($estado == 'Modificando') { ?>
                    <a class="btn" href="<?php echo base_url('CarritoList') ?>">Volver</a>
                    <a href="<?php echo base_url('cancelar_edicion/'.$id_pedido);?>" class="btn danger" onclick="return confirmarAccionPedido();">
                        Cancelar Modificación
                    </a>
                <?php } else if ($estado == 'Cobrando') { ?>
                    <a href="<?php echo base_url('cancelarCobro/'.$id_pedido);?>" class="btn danger" onclick="return confirmarAccionCobro();">
                        Cancelar Cobro
                    </a>
                <?php } else { ?>
                    <a class="btn" href="<?php echo base_url('CarritoList') ?>">Volver</a>
                    <a href="<?php echo base_url('carrito_elimina/all');?>" class="btn danger" onclick="return confirmarAccionCompra();">
                        Cancelar Pedido
                    </a>
                <?php }  ?>
                <?php echo form_hidden('id_pedido', $id_pedido); ?>
                <?php echo form_hidden('total_venta', ($gran_total > 0 ? $gran_total : $total_venta)); ?>
                <?php if ($estado == 'Cobrando'): ?>
                    <button type="submit" id="btnCobrar" class="btn">Cobrar</button>
                <?php else: ?>
                    <?php echo form_submit('confirmar', 'Confirmar', "class='btn' id='btnConfirmarPedido'"); ?>
                <?php endif; ?>
            </section>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('btnConfirmarPedido')?.addEventListener('click', function(e) {
    e.preventDefault();

    Swal.fire({
        title: 'Confirmar el Pedido?',        
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, Registrar',
        cancelButtonText: 'No, Cancelar',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33'
    }).then((result) => {
        if (result.isConfirmed) {
            document.querySelector("form.form-signin").submit();
        }
    });
});
</script>

<script>
// Función para el botón Cobrar
document.getElementById('btnCobrar')?.addEventListener('click', function(e) {
    e.preventDefault();
    
    Swal.fire({
        title: 'Deseas confirmar el cobro?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, Cobrado',
        cancelButtonText: 'No, Volver'
    }).then((result) => {
        if (result.isConfirmed) {
            // Envía el formulario directamente
            document.querySelector("form.form-signin").submit();
        }
    });
});

// Funciones de confirmación para otras acciones
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
    return false;
}

function confirmarAccionCobro() {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "Se cancelará el Cobro del Pedido.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, Cancelar",
        cancelButtonText: "No, Volver"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "<?php echo base_url('cancelarCobro/'.$id_pedido); ?>";
        }
    });
    return false;
}

function confirmarAccionPedido() {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "Se cancelará la modificación del pedido y quedará como estaba.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, Cancelar",
        cancelButtonText: "No, Volver"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "<?php echo base_url('cancelar_edicion/'.$id_pedido); ?>";
        }
    });
    return false;
}

document.addEventListener("DOMContentLoaded", function () {
    // Mostrar u ocultar el campo de costo de envío
    const conEnvioSelect = document.getElementById("conEnvio");
    const costoEnvioFila = document.getElementById("costoEnvioFila");

    if (conEnvioSelect && costoEnvioFila) {
        conEnvioSelect.addEventListener("change", function () {
            costoEnvioFila.style.display = conEnvioSelect.value === "Si" ? "table-row" : "none";
            if (conEnvioSelect.value !== "Si") {
                document.getElementById("costoEnvio").value = "";
            }
        });
    }
    
    // Calcular monto en efectivo (solo cuando se está cobrando)
    const pagoTransferencia = document.getElementById("pagoTransferencia");
    const pagoEfectivo = document.getElementById("pagoEfectivo");
    const granTotal = <?php echo ($gran_total > 0 ? $gran_total : $total_venta); ?>;

    if (pagoEfectivo) {
        pagoEfectivo.value = granTotal.toFixed(2); // Mostrar total inicial
    }
    
    function calcularMontoEfectivo() {
        const transferencia = parseFloat(pagoTransferencia.value) || 0;

        if (transferencia > granTotal) {
            Swal.fire({
                title: "Monto inválido",
                text: "El monto en transferencia no puede superar el total de la compra.",
                icon: "error",
                confirmButtonText: "Aceptar"
            }).then(() => {
                pagoTransferencia.value = "";
                pagoEfectivo.value = granTotal.toFixed(2);
            });
        } else {
            const efectivo = granTotal - transferencia;
            pagoEfectivo.value = efectivo.toFixed(2);
        }
    }
    
    if (pagoTransferencia) {
        pagoTransferencia.addEventListener("input", calcularMontoEfectivo);
    }

    // Manejo de autocompletado de clientes (funciona en todos los casos)
    const clientes = <?php echo isset($clientes_json) ? $clientes_json : '[]'; ?>;
    const nombreClienteInput = document.getElementById('nombreCliente');
    const clientesList = document.getElementById('clientesList');

    if (nombreClienteInput && clientesList) {
        function updateDatalist(clientes) {
            clientesList.innerHTML = '';
            clientes.forEach(cliente => {
                const option = document.createElement('option');
                option.value = cliente.nombre;
                // Agregar más datos si es necesario (teléfono, CUIL, etc.)
                if (cliente.telefono) option.dataset.telefono = cliente.telefono;
                if (cliente.cuil) option.dataset.cuil = cliente.cuil;
                option.dataset.id = cliente.id;
                clientesList.appendChild(option);
            });
        }

        // Llenar el datalist inicialmente con todos los clientes
        updateDatalist(clientes);

        // Actualizar el datalist mientras se escribe
        nombreClienteInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            // Si el campo está vacío, mostrar todos los clientes
            if (searchTerm === '') {
                updateDatalist(clientes);
                return;
            }
            
            // Filtrar clientes que coincidan con el término de búsqueda
            const filtered = clientes.filter(cliente => 
                cliente.nombre.toLowerCase().includes(searchTerm) ||
                (cliente.telefono && cliente.telefono.includes(searchTerm)) ||
                (cliente.cuil && cliente.cuil.includes(searchTerm))
            );
            
            updateDatalist(filtered.length > 0 ? filtered : clientes);
        });

        // Permitir selección con teclado
        nombreClienteInput.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                e.preventDefault();
                const options = Array.from(clientesList.querySelectorAll('option'));
                if (options.length === 0) return;
                
                const selectedIndex = options.findIndex(opt => opt.selected);
                let newIndex = 0;
                
                if (e.key === 'ArrowDown') {
                    newIndex = selectedIndex < options.length - 1 ? selectedIndex + 1 : 0;
                } else {
                    newIndex = selectedIndex > 0 ? selectedIndex - 1 : options.length - 1;
                }
                
                options.forEach((opt, index) => {
                    opt.selected = index === newIndex;
                    if (index === newIndex) {
                        nombreClienteInput.value = opt.value;
                    }
                });
            } else if (e.key === 'Enter' && nombreClienteInput.value) {
                e.preventDefault();
            }
        });

        // Mostrar sugerencias al hacer clic en el campo
        nombreClienteInput.addEventListener('focus', function() {
            if (this.value === '') {
                updateDatalist(clientes);
            }
        });
    }
});
</script>

<style>
.tableResponsive{
    width: 50%;
    text-align: center;
}
@media screen and (max-width: 768px) {
    .tableResponsive{
        width: 100%;
    }
}

.selector {
    width: 85%;
    padding: 8px;
    border: 2px solid #50fa7b;
    background-color: #282a36;
    color: #f8f8f2;
    border-radius: 5px;
    font-size: 16px;
    font-weight: bold;
}

.selector:focus {
    outline: none;
    border-color: #8be9fd;
    box-shadow: 0 0 5px #8be9fd;
}

.botones-container {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
    padding: 15px;
}

.btn {
    padding: 10px 20px;
    background-color: #50fa7b;
    color: #282a36;
    text-decoration: none;
    font-size: 16px;
    border-radius: 5px;
    transition: background 0.3s;
    text-align: center;
    display: inline-block;
}

.btn:hover {
    background-color: #8be9fd;
}

.danger {
    background-color: #ff5555;
    color: white;
}

.danger:hover {
    background-color: #ff4444;
}

@media (max-width: 600px) {
    .botones-container {
        flex-direction: column;
        align-items: center;
    }
    .btn {
        width: 100%;
    }
}


/* Estilo mejorado para el autocompletado */
input[list] {
    position: relative;
}

datalist {
    position: absolute;
    max-height: 200px;
    overflow-y: auto;
    background-color: #282a36;
    border: 1px solid #50fa7b;
    border-radius: 0 0 5px 5px;
    width: 85%;
    z-index: 1000;
}

datalist option {
    padding: 8px;
    cursor: pointer;
    color: #f8f8f2;
}

datalist option:hover {
    background-color: #44475a;
}
</style>

<script>
    // Cargar los clientes desde PHP
    const clientes = <?= $clientes_json ?>;

    // Rellenar el datalist
    const datalist = document.getElementById('clientesList');
    clientes.forEach(cliente => {
        const option = document.createElement('option');
        option.value = cliente.nombre;
        option.dataset.id = cliente.id_cliente;
        datalist.appendChild(option);
    });

    // Al seleccionar un nombre, guardar el id_cliente oculto
    document.getElementById('nombreCliente').addEventListener('input', function () {
        const inputVal = this.value;
        const match = clientes.find(c => c.nombre === inputVal);
        if (match) {
            document.getElementById('idClienteSeleccionado').value = match.id_cliente;
        } else {
            document.getElementById('idClienteSeleccionado').value = ''; // Por si no coincide
        }
    });
</script>
