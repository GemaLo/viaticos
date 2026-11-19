import 'bootstrap/dist/js/bootstrap.bundle.min.js';

document.addEventListener("DOMContentLoaded", function () {
  function toggleButton() {
        // Seleccionamos todos los checkboxes
        const checkboxes = document.querySelectorAll('.checkboxes');
        
        // Verificamos si al menos uno está seleccionado
        const isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
        
        // Habilitamos o deshabilitamos el botón de descarga
        document.getElementById('btnDescarga').disabled = !isChecked;
    }

    function SeleccionarTodos(){
      if (event) event.preventDefault(); 
         // Obtiene todos los checkboxes con la clase SelectArchivos
        const checkboxes = document.querySelectorAll('.checkboxes');
        // devulve un true o false
        const allCheked = Array.from(checkboxes).every(checkbox => checkbox.checked); 

        checkboxes.forEach(checkbox => checkbox.checked = !allCheked);
        toggleButton();


    }
    
  setTimeout(function() {
    const alerta = document.getElementById("alerta");
    if (alerta) {
      alerta.classList.remove("show"); // Oculta visualmente
      alerta.classList.add("fade"); // Agrega animación de desvanecimiento
      setTimeout(() => alerta.remove(), 1000); // Elimina el elemento del DOM
    }
  }, 5000); // 5000 milisegundos = 5 segundos
});
