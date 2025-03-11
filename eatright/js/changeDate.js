//Funcion que introduce como valor la fecha en la url para cambiar la fecha
const cambiaFecha = (e) => {
  location.href = "../index.php?fecha=" + e.target.value;
};
