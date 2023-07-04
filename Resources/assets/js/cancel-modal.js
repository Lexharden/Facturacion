$(document).ready(function() {
    $('#cancelModal').on('show.bs.modal', function(event) {
        console.log('Modal is about to be shown');

        var button = $(event.relatedTarget); // Botón que activó el modal
        console.log(button);

        var invoiceId = button.attr('data-invoice-id'); // Extrae la información del atributo data-invoice-id directamente
        console.log(invoiceId);

        // Muestra el invoiceId en el modal
        $('#invoiceIdDisplay').text(' ' + invoiceId);

        var modal = $(this);
        modal.find('#cancelInvoiceId').val(invoiceId); // Guarda el invoiceId en el formulario
    });
});
