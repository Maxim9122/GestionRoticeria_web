<?php $session = session();
$nombre= $session->get('nombre');
$perfil=$session->get('perfil_id');
$id=$session->get('id');?>
<section>
    <!-- Agrega esto en tu sección de estilos -->
    <style>
    /* Estilo Modal Entregar Fiado - Clases CSS */
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.6);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        padding: 15px; /* Espacio en móviles */
        box-sizing: border-box;
    }

    .modal-content {
        background: white;
        padding: 20px;
        border-radius: 10px;
        width: 100%; /* Cambiado a 100% para móviles */
        max-width: 400px; /* Máximo ancho en pantallas grandes */
    }

    /* Estilos específicos para el modal de entrega */
    .modal-entrega {
        border: 3px solid #001f3f;
        box-shadow: 0 0 15px #0074D9, 0 0 30px #2ECC40;
        animation: neon-glow 1.5s ease-in-out infinite alternate;
        background-color: #f8f9fa;
    }

    .modal-entrega-title {
        color: #001f3f;
        text-align: center;
        margin-bottom: 20px;
        font-size: 1.3rem; /* Tamaño responsive */
    }

    .modal-entrega-label {
        display: block;
        margin-bottom: 5px;
        color: #001f3f;
        font-weight: bold;
        font-size: 0.9rem; /* Tamaño responsive */
    }

    .modal-entrega-select {
        width: 100%;
        padding: 10px; /* Más grande en móviles */
        border: 1px solid #001f3f;
        border-radius: 4px;
        background-color: white;
        margin-bottom: 15px;
        font-size: 1rem; /* Tamaño legible */
    }

    .modal-entrega-input {
        width: 100%;
        padding: 10px; /* Más grande en móviles */
        border: 1px solid #001f3f;
        border-radius: 4px;
        font-size: 1rem; /* Tamaño legible */
    }

    .modal-entrega-buttons {
        margin-top: 20px;
        display: flex;
        justify-content: space-between;
        gap: 10px; /* Espacio entre botones */
    }

    .modal-entrega-btn-cancel {
        padding: 12px 15px; /* Más grande en móviles */
        background-color:rgb(139, 51, 60);
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 1rem; /* Tamaño legible */
        flex: 1; /* Ocupa espacio disponible */
    }

    .modal-entrega-btn-confirm {
        padding: 12px 15px; /* Más grande en móviles */
        background-color: #001f3f;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        box-shadow: 0 0 5px #0074D9;
        font-size: 1rem; /* Tamaño legible */
        flex: 1; /* Ocupa espacio disponible */
    }

    /* Animación para el efecto neón */
    @keyframes neon-glow {
        from {
            box-shadow: 0 0 10px #0074D9, 0 0 20px #2ECC40;
        }
        to {
            box-shadow: 0 0 15px #0074D9, 0 0 30px #2ECC40;
        }
    }

    /* Efectos hover */
    .modal-entrega-btn-cancel:hover,
    .modal-entrega-btn-confirm:hover {
        opacity: 0.9;
        transform: scale(1.02);
        transition: all 0.2s ease;
    }

    /* Media queries para dispositivos móviles */
    @media (max-width: 480px) {
        .modal-content {
            padding: 15px;
        }
        
        .modal-entrega-title {
            font-size: 1.1rem;
            margin-bottom: 15px;
        }
        
        .modal-entrega-buttons {
            flex-direction: column; /* Botones en columna en móviles muy pequeños */
        }
        
        .modal-entrega-select,
        .modal-entrega-input {
            padding: 12px; /* Más grande para tactil */
        }
    }

    @media (max-width: 360px) {
        .modal-entrega-title {
            font-size: 1rem;
        }
        
        .modal-entrega-label {
            font-size: 0.8rem;
        }
    }
</style>
    
    <!-- Mensajes temporales -->
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

    <?php
    $session = session();
    $id_cliente_seleccionado = $session->get('id_cliente') ?? '';
    $id_pedido = $session->get('id_pedido') ?? '';

    $estado = '';
    if ($session->has('estado')) {
        $estado = $session->get('estado');
    }
    ?>

    <div style="width: 100%;">
        <br>
        <h2 class="textoColor" align="center">Listado de Pedidos Pendientes</h2>
        <br>
        <div style="text-align: end;">
            <br>
            <a class="button" href="<?php echo base_url('pedidosTodos');?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-checklist" viewBox="0 0 16 16">
                    <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                    <path d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0zM7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0z"/>
                </svg>Pedidos No Entregados</a>
            <a class="button" href="<?php echo base_url('pedidosCompletados');?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-checklist" viewBox="0 0 16 16">
                    <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                    <path d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0zM7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0z"/>
                </svg>Pedidos Todos</a>
            <br><br>
            <?php $Recaudacion = 0; ?>
            <table class="table table-responsive table-hover" id="users-list">
                <thead>
                    <tr class="colorTexto2">
                        <th>Nro Pedido</th>
                        <th>Cliente</th>
                        <th>Teléfono</th>
                        <th>Vendedor</th>
                        <th>Total</th>
                        <th>Fecha Registro</th>
                        <th>Hora Reg.</th>
                        <th>Modo Compra</th>
                        <th>Estado</th>                          
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($pedidos): ?>
                        <?php foreach($pedidos as $p): ?>
                            <tr>
                                <td><?php echo $p['id']; ?></td>
                                <td><?php echo $p['nombre_cliente']; ?></td>
                                <td><?php echo $p['telefono']; ?></td>
                                <td><?php echo $p['nombre_usuario'];?></td>
                                <td>$<?php echo $p['total_venta'];?></td>
                                <td><?php echo $p['fecha'];?></td>
                                <td><?php echo $p['hora'];?></td>
                                <td><?php echo $p['modo_compra'];?></td>
                                <td><?php echo $p['estado'];?></td>

                                <td>
                                    <div class="dropdown">
                                        <span class="dropdown-toggle btn">Acciones▼</span>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="<?php echo base_url('DetalleVta/'.$p['id']); ?>">
                                                    📄 Ver Detalle
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo base_url('cancelar/'.$p['id']); ?>" class="text-danger"
                                                   onclick="mostrarConfirmacionCancelar(event, '¿Estás seguro de cancelar este pedido?', this.href);">
                                                    ❌ Cancelar
                                                </a>
                                            </li>
                                            <li>
                                                <?php if(!$id_pedido){?>
                                                <a href="<?php echo base_url('cargar_pedido/'.$p['id']); ?>">
                                                    ✏️ Modificar
                                                </a>
                                                <?php } ?>
                                            </li>
                                            <?php if ($estado == '') { ?>
                                                <?php if($p['modo_compra'] == 'Compra'){ ?> 
                                                <li>
                                                    <a class="text-success btn" href="<?php echo base_url('cobrarPedido/'.$p['id']); ?>">
                                                        ✅ Cobrar
                                                    </a>
                                                </li>
                                                <?php } else if($p['modo_compra'] == 'Fiado') { ?>
                                                <li>                
                                                    <a class="text-success btn" onclick="abrirModalEntrega(<?= $p['id']; ?>, event)">✅ Entrega</a>
                                                </li>
                                                <?php } ?>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Cuadro de confirmación de Cancelar Pedido -->
            <div id="confirm-dialog-Cancelar" class="confirm-dialog" style="display: none;">
                <div class="confirm-content btn2">
                    <p id="confirm-message-cancelar">¿Estás seguro de Cancelar el pedido??</p>
                    <div class="confirm-buttons">
                        <button id="confirm-yes" class="btn btn-yes" autofocus>Sí</button>
                        <button id="confirm-no" class="btn btn-no">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal de entrega (fuera de la sección principal) -->
<div id="modalEntrega" style="display: none;" class="modal">
    <div class="modal-content modal-entrega">
        <h3 class="modal-entrega-title">Entrega del Pedido Fiado</h3>

        <form id="formEntrega" method="post" action="<?php echo base_url('entregarFiado') ?>">
            <input type="hidden" name="id_pedido" id="idPedidoModal">

            <div>
                <label for="conEnvio" class="modal-entrega-label">¿Tiene envío?</label>
                <select name="con_envio" id="conEnvio" required class="modal-entrega-select">
                    <option value="no">No</option>
                    <option value="si">Sí</option>
                </select>
            </div>

            <div id="montoEnvioContainer" style="display: none;">
                <label for="monto_envio" class="modal-entrega-label">Monto del envío</label>
                <input type="number" step="0.01" name="monto_envio" id="montoEnvio" 
                       placeholder="Ej: 500" class="modal-entrega-input">
            </div>

            <div class="modal-entrega-buttons">
                <button type="button" onclick="cerrarModal()" class="modal-entrega-btn-cancel">Cancelar</button>
                <button type="submit" class="modal-entrega-btn-confirm">Confirmar entrega</button>                
            </div>
        </form>
    </div>
</div>
<!--Script Modal Entrega Fiado -->
<script>
function abrirModalEntrega(idPedido, event) {
    event.preventDefault(); // Previene el envío del formulario
    document.getElementById("modalEntrega").style.display = "flex";
    document.getElementById("idPedidoModal").value = idPedido;
}

function cerrarModal() {
    document.getElementById("modalEntrega").style.display = "none";
}

// Mostrar/ocultar el monto del envío
document.getElementById("conEnvio").addEventListener("change", function () {
    const valor = this.value;
    const container = document.getElementById("montoEnvioContainer");
    container.style.display = valor === "si" ? "block" : "none";
});

// Validar antes de enviar
document.getElementById("formEntrega").addEventListener("submit", function (e) {
    const conEnvio = document.getElementById("conEnvio").value;
    const monto = document.getElementById("montoEnvio").value;

    if (conEnvio === "si" && (monto === "" || parseFloat(monto) <= 0)) {
        e.preventDefault();
        alert("Por favor, ingrese un monto válido para el envío.");
    }
});
</script>

<!-- Esta parte es del cartel de confirmacion de Cancelar pedido o pedido Listo-->
<script>
function mostrarConfirmacionCancelar(event, mensaje, url) {
    event.preventDefault(); // Previene la acción por defecto del enlace
    const confirmDialog = document.getElementById('confirm-dialog-Cancelar');
    const confirmMessage = document.getElementById('confirm-message-cancelar');
    const confirmYes = document.getElementById('confirm-yes');
    const confirmNo = document.getElementById('confirm-no');

    // Muestra el cuadro de confirmación con el mensaje proporcionado
    confirmMessage.textContent = mensaje;
    confirmDialog.style.display = 'flex';

    // Si el usuario confirma, redirige a la URL
    confirmYes.onclick = function () {
        window.location.href = url;
    };

    // Si el usuario cancela, oculta el cuadro de confirmación
    confirmNo.onclick = function () {
        confirmDialog.style.display = 'none';
    };
}

function cerrarConfirmacion() {
    const dialog = document.getElementById('confirm-dialog-Cancelar');
    dialog.style.display = 'none';
}
</script>

<script src="<?php echo base_url('./assets/js/jquery-3.5.1.slim.min.js');?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('./assets/css/jquery.dataTables.min.css');?>">
<script type="text/javascript" src="<?php echo base_url('./assets/js/jquery.dataTables.min.js');?>"></script>

<!-- Para la tabla de pedido-->
<script>
    $(document).ready( function () {
        $('#users-list').DataTable( {
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros por página.",
                "zeroRecords": "Sin Resultados! No hay pedidos agendados para Hoy.",
                "info": "Mostrando la página _PAGE_ de _PAGES_",
                "infoEmpty": "No hay registros disponibles.",
                "infoFiltered": "(filtrado de _MAX_ registros totales)",
                "search": "Buscar: ",
                "paginate": {
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            }
        });
    });

    // Crear un objeto Date en UTC
    const today = new Date();

    // Ajustar la hora a la zona horaria de Argentina (UTC-3)
    const options = { timeZone: 'America/Argentina/Buenos_Aires', hour12: false };
    const formatter = new Intl.DateTimeFormat('es-AR', {
        ...options,
        year: 'numeric', month: '2-digit', day: '2-digit'
    });

    const formattedDate = formatter.format(today).split('/').reverse().join('-'); // Formato YYYY-MM-DD

    // Formatear la hora en formato HH:MM
    const formattedTime = new Intl.DateTimeFormat('es-AR', {
        ...options,
        hour: '2-digit',
        minute: '2-digit'
    }).format(today);

    // Establecer la fecha y hora actuales en los campos correspondientes
    // Establecer la fecha mínima y el valor predeterminado
    const fechaInput = document.getElementById('fecha');
    if(fechaInput) {
        fechaInput.setAttribute('min', formattedDate);
        fechaInput.setAttribute('value', formattedDate);
    }
    
    // Rescata la hora del input por medio del id y asigna la hora actual
    const horaInput = document.getElementById('hora');
    if(horaInput) {
        horaInput.value = formattedTime;
    }
</script>