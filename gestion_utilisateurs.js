document.addEventListener('DOMContentLoaded', function () {
    chargerUtilisateurs();
});


function chargerUtilisateurs() {
    fetch('gestion_utilisateurs.php?action=lister')
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.json();
        })
        .then(data => {
            const tbody = document.querySelector('#table-utilisateurs tbody');
            tbody.innerHTML = '';

            data.forEach(user => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${user.id_uti}</td>
                    <td>${user.nom}</td>
                    <td>${user.email}</td>
                    <td>${user.role}</td>
                    <td><button onclick="modifierUtilisateur(${user.id_uti})">Modifier</button></td>
                    <td><button onclick="supprimerUtilisateur(${user.id_uti})">Supprimer</button></td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors du chargement des utilisateurs');
        });
}

function modifierUtilisateur(id) {
    window.location.href = `modifier_utilisateur.php?id=${id}`;
}

function supprimerUtilisateur(id) {
    if(confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur ?")) {
        fetch('gestion_utilisateurs.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'supprimer', id_uti: id })
        })
            .then(response => response.text()) // d'abord lire comme texte brut !
            .then(result => {
                alert(result); // affiche exactement ce que le serveur retourne
                console.log(result);
            })
            .catch(error => {
                alert("Erreur réseau : " + error);
            });
    }
}
