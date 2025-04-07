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

<?php
$cart = \Config\Services::cart(); 
$session = session();
$nombre = $session->get('nombre');
$perfil = $session->get('perfil_id');

// Inicializar variables
$id_pedido = '';
if ($session->has('id_pedido')) {
    $id_pedido = $session->get('id_pedido');
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
                    <td style="color: #ffff;"><strong id="totalCompra">$<?php echo number_format($gran_total, 2); ?></strong></td>
                </tr>
                <tr>
                    <td style="color:rgb(192, 250, 214);"><strong>Vendedor:</strong></td>
                    <td style="color: #ffff;"><?php echo $nombre ?></td>
                </tr>
                <tr>
                    <td style="color:rgb(192, 250, 214);"><strong>Nombre del Cliente:</strong></td>
                    <td>
                        <input class="selector" type="text" name="nombre_prov" placeholder="Ingrese nombre cliente" maxlength="20" required>
                    </td>
                </tr>                         
                
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
                <tr>
                    <td style="color: rgb(192, 250, 214);"><strong>Tipo de Compra:</strong></td>
                    <td>
                        <select name="tipo_compra" id="tipoCompra" class="selector">
                        <option value="Compra">Compra</option>   
                        <option value="Fiado">Fiado</option> 
                                                   
                        </select>                    
                    </td>
                </tr>                
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
                <?php echo form_hidden('total_venta', $gran_total); ?>
            </table>
            
            <section class="botones-container" style="width:65%;">
                <a class="btn" href="<?php echo base_url('CarritoList') ?>">Volver</a>
            
                <?php if ($id_pedido) { ?>
                    <a href="<?php echo base_url('cancelar_edicion/'.$id_pedido);?>" class="btn danger" onclick="return confirmarAccionPedido();">
                        Cancelar Modificación
                    </a>
                <?php } else { ?>
                    <a href="<?php echo base_url('carrito_elimina/all');?>" class="btn danger" onclick="return confirmarAccionCompra();">
                        Cancelar Pedido
                    </a>
                <?php } ?>
                <?php echo form_hidden('id_pedido', $id_pedido); ?>
                <?php echo form_submit('confirmar', 'Confirmar', "class='btn'"); ?>
            </section>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

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
        return false;
    }
    
    function confirmarAccionPedido() {
        Swal.fire({
            title: "¿Estás seguro?",
            text: "Se cancelará la modificación del pedido y quedará como estaba.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, Cancelar",
            cancelButtonText: "Cancelar"
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

        conEnvioSelect.addEventListener("change", function () {
            costoEnvioFila.style.display = conEnvioSelect.value === "Si" ? "table-row" : "none";
            if (conEnvioSelect.value !== "Si") {
                document.getElementById("costoEnvio").value = "";
            }
        });
        
        // Calcular monto en efectivo
        const pagoTransferencia = document.getElementById("pagoTransferencia");
        const pagoEfectivo = document.getElementById("pagoEfectivo");
        const granTotal = <?php echo $gran_total; ?>;
        
        function calcularMontoEfectivo() {
            const transferencia = parseFloat(pagoTransferencia.value) || 0;
            const efectivo = granTotal - transferencia;
            pagoEfectivo.value = efectivo > 0 ? efectivo.toFixed(2) : "0.00";
        }
        
        pagoTransferencia.addEventListener("input", calcularMontoEfectivo);
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
</style>