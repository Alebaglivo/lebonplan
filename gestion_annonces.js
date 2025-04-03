document.addEventListener('DOMContentLoaded', function () {
    chargerAnnonces(); // ✅ Avec le "r"
});


function chargerAnnonces() {
    fetch('gestion_annonces.php?action=lister')
        .then(response => {
            if (!response.ok) {
                console.error("Erreur HTTP :", response.status);
                throw new Error('Erreur réseau');
            }
            return response.text(); // ← lire en texte brut pour voir le message d'erreur PHP
        })
        .then(raw => {
            console.log("Réponse brute reçue :", raw); // ← regarde bien la console ici
            const data = JSON.parse(raw);
            const tbody = document.querySelector('#table-annonces tbody');
            tbody.innerHTML = '';

            data.forEach(annonce => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${annonce.id_ann}</td>
                    <td>${annonce.titre}</td>
                    <td>${annonce.contenu}</td>
                    <td>${annonce.nom_entreprise}</td>
                    <td><button onclick="modifierAnnonce(${annonce.id_ann})">Modifier</button></td>
                    <td><button onclick="supprimerAnnonce(${annonce.id_ann})">Supprimer</button></td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(error => {
            console.error('Erreur fetch :', error);
            alert('Erreur lors du chargement des annonces');
        });
}
function modifierAnnonce(id) {
    window.location.href = `modifier_annonce.php?id=${id}`;
}


function supprimerAnnonce(id) {
    if(confirm("Êtes-vous sûr de vouloir supprimer cette annonce ?")) {
        fetch('gestion_annonces.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'supprimer', id_ann: id })
        })
            .then(response => response.json())
            .then(result => {
                alert(result.message || "Suppression faite");
                location.reload();
            })
            .catch(error => {
                alert("Erreur réseau : " + error);
            });
    }
}
