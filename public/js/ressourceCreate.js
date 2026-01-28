document.addEventListener('DOMContentLoaded', function () {

    /* ================================
       1️⃣ Compteur de caractères (max 1000)
    ================================= */
    const descriptionTextarea = document.getElementById('description');
    const charCount = document.getElementById('charCount');
    const MAX_LENGTH = 1000;

    if (descriptionTextarea && charCount) {

        // Met à jour le compteur et limite le texte à 1000 caractères
        const updateCharCount = () => {
            if (descriptionTextarea.value.length > MAX_LENGTH) {
                descriptionTextarea.value =
                    descriptionTextarea.value.substring(0, MAX_LENGTH);
            }
            charCount.textContent = descriptionTextarea.value.length;
        };

        descriptionTextarea.addEventListener('input', updateCharCount);

        // Initialisation du compteur au chargement de la page
        updateCharCount();
    }


    /* ================================
       2️⃣ Vérification des permissions (responsable)
    ================================= */
    const categorySelect = document.getElementById('id_categorie');

    // La variable userRole doit être définie dans Blade :
    // <script> const userRole = "{{ auth()->user()->role }}"; </script>

    if (categorySelect && typeof userRole !== 'undefined' && userRole === 'responsable') {

        categorySelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];

            // Empêche le responsable de choisir une catégorie non autorisée
            if (
                this.value !== '' &&
                !selectedOption.textContent.includes('Votre catégorie')
            ) {
                alert('⚠️ Vous ne pouvez créer des ressources que dans vos catégories.');
                this.value = '';
            }
        });
    }

});
