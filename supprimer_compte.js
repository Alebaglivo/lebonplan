
function confirmerSuppression() {
    if (confirm("Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.")) {
        window.location.href = "supprimer_compte.php";
    }
}

