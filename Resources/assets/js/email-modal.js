$(document).ready(function() {
    $('#emailModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Botón que activó el modal

        var invoiceId = button.data('invoice-id'); // Obtener invoiceId del botón

        // Establecer el valor del invoiceId en el campo oculto del formulario
        $('#invoiceId').val(invoiceId);
    });

    // Capturar evento de clic del botón de enviar correo
    $('.btn-send-email').click(function() {
        var invoiceId = $('#invoiceId').val();
        var email = $('#email').val();

        // Establecer el valor del invoiceId en el campo oculto del formulario
        $('#invoiceId').val(invoiceId);

        // Enviar el formulario
        $('#emailForm').submit();
    });
});
