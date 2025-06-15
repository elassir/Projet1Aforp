/**
 * Fichier: gestion_docPedago.js
 * 
 * Fonctions JavaScript pour la gestion de l'interface des documents pédagogiques
 */

/**
 * Affiche ou masque le formulaire d'ajout de document
 */
function toggleAddDocSection() {
    const section = document.getElementById('ajout-doc-pedago');
    section.style.display = section.style.display === 'none' ? 'block' : 'none';
}

/**
 * Affiche ou masque la liste des devoirs rendus pour une consigne spécifique
 * 
 * @param {number} id - ID du document pédagogique
 */
function toggleDevoirsRendus(id) {
    const devoirsContainer = document.getElementById('devoirs-container-' + id);
    const toggleButton = document.getElementById('toggle-btn-' + id);
    
    if (devoirsContainer.style.display === 'none' || !devoirsContainer.style.display) {
        devoirsContainer.style.display = 'block';
        toggleButton.textContent = 'Masquer les devoirs rendus';
    } else {
        devoirsContainer.style.display = 'none';
        toggleButton.textContent = 'Voir les devoirs rendus';
    }
}

/**
 * Filtre les documents pédagogiques par système
 * 
 * @param {number} systemeId - ID du système à filtrer
 */
function filterBySystem(systemeId) {
    const allCards = document.querySelectorAll('.doc-card');
    
    allCards.forEach(card => {
        if (systemeId === 0 || card.getAttribute('data-system') == systemeId) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
    
    // Mettre à jour le texte du bouton de filtre
    const filterBtn = document.getElementById('system-filter');
    const selectedSystem = systemeId === 0 ? 
        'Tous les systèmes' : 
        document.querySelector(`option[value="${systemeId}"]`).textContent;
    
    filterBtn.textContent = 'Système: ' + selectedSystem;
}

/**
 * Affiche ou masque le formulaire d'envoi de devoir pour une consigne spécifique
 * 
 * @param {number} id - ID de la consigne
 */
function toggleDevoirForm(id) {
    const devoirForm = document.getElementById('devoir-form-' + id);
    if (devoirForm.style.display === 'none' || !devoirForm.style.display) {
        devoirForm.style.display = 'block';
    } else {
        devoirForm.style.display = 'none';
    }
}
