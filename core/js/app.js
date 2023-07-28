// Récupération de tous les éléments avec une classe donnée
var divs = document.getElementsByClassName('card');

// Fonction pour ajouter ou supprimer la classe CSS
function toggleCssClass() {
  if (this.lastElementChild.classList.contains('active')) {
    this.lastElementChild.classList.remove('active');
    this.lastElementChild.classList.add('unactive');
  } else {
    this.lastElementChild.classList.add('active');
    this.lastElementChild.classList.remove('unactive');
  }
}

// Ajout d'un gestionnaire d'événement au clic sur chaque élément
for (var i = 0; i < divs.length; i++) {
    divs[i].addEventListener('click', toggleCssClass);
}
