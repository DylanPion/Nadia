// Code permettant de choisir de faire un versement en une ou deux fois dans le formulaire de création d'une fiche société

// Sélectionner tous les boutons radio
const radioOnePayment = document.getElementById("OnePayment");
const radioTwoPayment = document.getElementById("TwoPayment");
const paymentTwo = document.querySelector(".paymentTwo");

radioOnePayment.addEventListener("click", () => {
  paymentTwo.style.display = "none";
});

radioTwoPayment.addEventListener("click", () => {
  paymentTwo.style.display = "block";
});

// Validation suppresion d'une ligne

document.addEventListener("DOMContentLoaded", function () {
  const deleteButtons = document.querySelectorAll(".delete");
  for (var i = 0; i < deleteButtons.length; i++) {
    deleteButtons[i].addEventListener("click", function (e) {
      if (!confirm("Êtes-vous sûr de vouloir supprimer cette ligne?")) {
        e.preventDefault();
      }
    });
  }
});

// CRÉATION DU FILTRE RECHERCHE

// On commence par récupérer notre input searhc
const search = document.querySelector(".search");
// On récupère la valeur entrée dans l'input lorsqu'on tape sur le clavier
search.addEventListener("keyup", (e) => {
  let keyword = e.target.value;
  // dans la suite la recherche se fera en majuscule partout pour pas qu'il n'y ai de différence entre majuscule et minuscule
  keyword = keyword.toUpperCase();
  // On récupère la table ainsi que les lignes tr enfant de table
  let table = document.querySelector(".table-content");
  let all_tr = table.getElementsByTagName("tr");

  // On fait une boucle ou on récupère le premier et le deuxième td enfant de tr.
  for (let i = 0; i < all_tr.length; i++) {
    var convention_name = all_tr[i].getElementsByTagName("td")[0];
    var association_name = all_tr[i].getElementsByTagName("td")[1];
    var company_name = all_tr[i].getElementsByTagName("td")[2];
    if (convention_name && association_name && company_name) {
      let convention_value =
        convention_name.textContent || convention_name.innerText;
      let association_value =
        association_name.textContent || association_name.innerText; // extrait les valeurs de association name
      let company_value = company_name.textContent || company_name.innerText;
      convention_value = convention_value.toUpperCase();
      association_value = association_value.toUpperCase(); // transforme en majuscule la valeur de association name
      company_value = company_value.toUpperCase();

      if (
        convention_value.indexOf(keyword) > -1 ||
        association_value.indexOf(keyword) > -1 ||
        company_value.indexOf(keyword) > -1 //vérifie si que keyword est retrouvé dans association_value ou company_value sinon return false
      ) {
        all_tr[i].style.display = ""; // affiche
      } else {
        all_tr[i].style.display = "none"; // cache
      }
    }
  }
});

/* Note : Les noms Association Company et Convention correspondent aux noms des trois première colonnes mais la recherche marche sur tout le site donc les noms sont abstrait la recherche marche sur les trois première colonnes de chaque table du site c'est ce qu'il faut retenir. */
