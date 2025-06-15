// Se selecciona el botón del menú hamburguesa
const menuToggle = document.querySelector('.menu-toggle');

// Se selecciona la barra lateral
const sidebar = document.querySelector('.sidebar');

// Se realiza una acción al hacer clic en el botón del menú
menuToggle.addEventListener('click', () => {
  // Añade o quita la clase 'active' para mostrar/ocultar la barra lateral
  sidebar.classList.toggle('active');
});