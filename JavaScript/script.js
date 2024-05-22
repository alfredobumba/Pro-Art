// Função para verificar se o documento está pronto
$(document).ready(function() {
    // Adiciona interatividade aos botões 'Ver mais'
    $('.btn-primary').click(function(event) {
        event.preventDefault(); // Previne o comportamento padrão do link
        var cardTitle = $(this).closest('.card-body').find('.card-title').text();
        alert('Mais informações sobre: ' + cardTitle);
    });
});

$(document).ready(function() {
    $('#googleMapsBtn').click(function(event) {
      event.preventDefault();
      alert('Você será redirecionado para o Google Maps.');
      window.open($(this).attr('href'));
    });
});

window.onload = function() {
    setTimeout(function(){
        var messageDiv = document.getElementById('successMessage');
        if (messageDiv) {
            messageDiv.style.display='none';
        }
    }, 5000);
}
