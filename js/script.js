// Récupérer l'élément où la date sera affichée
const dateElement = document.getElementById('currentDate');

// Fonction pour obtenir la date actuelle et l'afficher
function afficherDate() {
    const date = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric', timeZoneName: 'short' };
    const dateText = date.toLocaleDateString('fr-FR', options);
    dateElement.textContent = "Date et heure actuelles : " + dateText;
}

// Appeler la fonction pour afficher la date actuelle lors du chargement de la page
afficherDate();




  //placer le formulaire de projet au centre
  document.getElementById("showFormButton").addEventListener("click", function() {
    var formContainer = document.getElementById("formContainer");
    formContainer.style.display = "block";
    formContainer.style.top = "80%";
    formContainer.style.left = "50%";
    formContainer.style.transform = "translate(-50%, -50%)";
});

//suppression

$(document).ready(function() {
  $('.deleteBtn').on('click', function() {
      var projectId = $(this).data('id');
      // Envoyer une requête AJAX pour supprimer le projet
      $.ajax({
          type: 'POST',
          url: 'supprimer_projet.php',
          data: { id: projectId },
          success: function(response) {
              // Supprimer la ligne du tableau si la suppression est réussie
              if (response == 'success') {
                  $('#projet_' + projectId).remove();
              } else {
                  alert('Erreur lors de la suppression du projet.');
              }
          }
      });
  });
});

function afficherFormulaireModification(projetId) {
    var modifierModal = $("#modifier-modal");
    modifierModal.show();
    modifierModal.css({
        display: block,
        
        left: "30%",
        zIndex: "100",
        transform: "translate(-50%, -50%)"

    });
    chargerDetailsProjet(projetId);
}


