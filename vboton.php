<?php require_once 'vistas/parte-superior.php'; ?>
<script>
    function imprimirTicket(){
        $('#contenidoticket').html('<iframe id="frmticket" src="ticket.php"></iframe>');
        $('#frmticket').get(0).contentWindow.focus();
        $('#frmticket').get(0).contentWindow.print();
    }
</script>

<button type="button" class="btn btn-success" onclick="imprimirTicket()">Imprimir ticket</button>

<div id="contenidoticket"></div>

<?php require_once 'vistas/parte-inferior.php'; ?>