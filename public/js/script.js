document.addEventListener('DOMContentLoaded', () => {
  const contactForm = document.querySelector('#contact-form');
  if (!contactForm) return;   // si no existe, no hace nada

  contactForm.addEventListener('submit', (e) => {
    e.preventDefault(); // bloquea solo el de contacto
    alert('✅ Datos enviados (ejemplo)');
    // aquí puedes poner la lógica para simular envío o AJAX
  });
});

