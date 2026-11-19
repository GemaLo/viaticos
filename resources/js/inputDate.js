import 'bootstrap/dist/js/bootstrap.bundle.min.js';

document.addEventListener('DOMContentLoaded', function () {
    const inputsFecha = document.querySelectorAll('.fecha');
    const hoy = new Date();
    const yyyy = hoy.getFullYear();
    const mm = String(hoy.getMonth() + 1).padStart(2, '0');
    const dd = String(hoy.getDate()).padStart(2, '0');
    const fechaHoy = `${yyyy}-${mm}-${dd}`;
  
    inputsFecha.forEach(input => {
      input.max = fechaHoy;
  
      // Solo asigna la fecha de hoy si el campo está vacío
      if (!input.value) {
        input.value = fechaHoy;
      }
    });
  });
  
