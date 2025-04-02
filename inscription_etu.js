document.addEventListener("DOMContentLoaded", function () {
    console.log("Script.js chargé");

    const form = document.querySelector("form");
    const submitButton = document.getElementById("submit-btn");

    // Bouton Téléverser le CV
    document.getElementById("upload-btn").addEventListener("click", function (event) {
        event.preventDefault();

        const fileInput = document.getElementById("file");
        const file = fileInput.files[0];
        const messageContainer = document.getElementById("upload-message");

        if (!file) {
            messageContainer.innerHTML = '<span style="color: white;">Veuillez sélectionner un fichier.</span>';
            return;
        }

        const formData = new FormData();
        formData.append("file", file);

        fetch("CV.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                messageContainer.innerHTML = `<span style="color: white;">${data.error}</span>`;
            } else {
                messageContainer.innerHTML = `<span style="color: white;">${data.success}</span>`;
            }
        })
        .catch(error => {
            messageContainer.innerHTML = '<span style="color: white;">Erreur lors du téléversement.</span>';
            console.error(error);
        });
    });

    // Validation du formulaire
    function validateForm() {
        clearErrors();

        const nom = document.querySelector('input[name="nom"]');
        const prenom = document.querySelector('input[name="prenom"]');
        const email = document.querySelector('input[name="email"]');
        const password = document.querySelector('input[name="password"]');

        let isValid = true;

        if (nom && nom.value.trim() === '') {
            displayError(nom, 'Le nom est requis.');
            isValid = false;
        }

        if (prenom && prenom.value.trim() === '') {
            displayError(prenom, 'Le prénom est requis.');
            isValid = false;
        }

        if (email && email.value.trim() === '') {
            displayError(email, 'L\'email est requis.');
            isValid = false;
        } else if (email && !/\S+@\S+\.\S+/.test(email.value)) {
            displayError(email, 'L\'email n\'est pas valide.');
            isValid = false;
        }

        if (password && password.value.trim() === '') {
            displayError(password, 'Le mot de passe est requis.');
            isValid = false;
        } else if (password && password.value.length < 6) {
            displayError(password, 'Le mot de passe doit contenir au moins 6 caractères.');
            isValid = false;
        }

        return isValid;
    }

    function displayError(input, message) {
        const errorMessage = document.createElement("div");
        errorMessage.classList.add("error");
        errorMessage.innerText = message;
        input.parentNode.appendChild(errorMessage);
    }

    function clearErrors() {
        document.querySelectorAll(".error").forEach(e => e.remove());
    }

    // Gestion de l'envoi du formulaire
    form.addEventListener("submit", function (event) {
        event.preventDefault(); // Empêche le rechargement

        if (!validateForm()) {
            alert("Veuillez corriger les erreurs avant de soumettre.");
            return;
        }

        const formData = new FormData(form);

        fetch("inscription_etu.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            if (data.includes("Inscription réussie")) {
                alert("Inscription réussie !");
                window.location.href = "accueil_etu.php";
            } else {
                alert(data);
            }
        })
        .catch(error => {
            console.error("Erreur lors de la requête:", error);
        });
    });

    // Validation en temps réel
    const inputs = document.querySelectorAll("input");
    inputs.forEach(input => {
        input.addEventListener("input", validateForm);
    });
});

