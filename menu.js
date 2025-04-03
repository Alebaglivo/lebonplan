
// Cibler le bouton hamburger et le menu de gauche
const menuToggle = document.getElementById('menu-toggle');
const mainMenu = document.getElementById('main-menu');

// Ajouter un événement au clic pour afficher/masquer le menu
menuToggle.addEventListener('click', function() {
  mainMenu.classList.toggle('active'); // Ajouter ou retirer la classe 'active' pour ouvrir/fermer
});

document.addEventListener('contextmenu', e => e.preventDefault());
document.addEventListener('keydown', e => {
  if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && e.key === 'I')) {
    e.preventDefault();
  }
});
document.addEventListener("keydown", function (e) {
    if (e.ctrlKey && e.shiftKey && (e.key === "I" || e.key === "i" || e.key === "C" || e.key === "c")) {
        e.preventDefault();
        alert("Cette action est désactivée sur ce site.");
    }
});
document.addEventListener("contextmenu", function (e) {
    e.preventDefault();
});
