// Vérifier si l'utilisateur est bien administrateur
fetch('admin_verification.php')
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            // Si l'utilisateur n'est pas un admin, redirige vers la page de connexion
            window.location.href = 'connexion.html';
        }
    })
    .catch(error => console.error('Erreur : ', error));
