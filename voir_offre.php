<?php
<script>
// 👇 Injecte le rôle depuis PHP dans JS
const role = <?= json_encode($_SESSION['role'] ?? null) ?>;

document.addEventListener("click", function (event) {
    if (event.target && event.target.classList.contains("verifier-btn")) {
        const id_ann = event.target.getAttribute("data-id");

        if (role === "pilote") {
            Swal.fire({
                title: "Vous êtes connecté en tant que pilote.",
                text: "Souhaitez-vous découvrir l'annonce et y postuler ?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Découvrir",
                cancelButtonText: "Annuler",
                confirmButtonColor: "#2368e1",
                cancelButtonColor: "#d33"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "postuler.php?id_ann=" + id_ann;
                }
            });
        } else if (role === "etudiant") {
            window.location.href = "postuler.php?id_ann=" + id_ann;
        } else {
            Swal.fire({
                icon: "warning",
                title: "Connexion requise",
                text: "Vous devez être connecté pour postuler à une offre.",
                confirmButtonText: "OK"
            });
        }
    }
});
</script>
?>
