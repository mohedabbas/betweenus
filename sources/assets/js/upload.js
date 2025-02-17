document.addEventListener('DOMContentLoaded', function() {
    const uploadButton = document.getElementById('uploadButton');
    const fileInput = document.getElementById('fileInput');
  
    if (!uploadButton || !fileInput) {
      console.error("Le bouton d'upload ou le champ de fichier est introuvable !");
      return;
    }
  
    // Récupère le token CSRF depuis la balise meta
    const metaTag = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = metaTag ? metaTag.getAttribute('content') : '';
  
    // Au clic sur le bouton, on déclenche l'ouverture du sélecteur de fichiers
    uploadButton.addEventListener('click', () => {
      fileInput.click();
    });
  
    // Lorsqu'un ou plusieurs fichiers sont sélectionnés
    fileInput.addEventListener('change', function() {
      const formData = new FormData();
  
      for (let file of fileInput.files) {
        formData.append('files[]', file);
      }
  
      // Récupère l'ID de la galerie depuis un attribut data sur l'input
      const galleryId = fileInput.getAttribute('data-gallery-id');
      formData.append('galleryId', galleryId);
      formData.append('csrf_token', csrfToken);
  
      fetch(`/gallery/upload/${galleryId}`, {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(result => {
        if(result.success) {
          window.location.reload();
        } else {
          alert(result.message || 'Erreur inconnue');
        }
      })
      .catch(error => {
        console.error('Erreur fetch:', error);
        alert("Erreur fetch: " + error.message);
      });
    });
  });
  