//Funcion que calcula el total de kcal segun la porcion del usuario y la porcion del alimento
const calculaKcal = (p_user, porcion) => {
  p_user = p_user.target.value;
  calc = (p_user * kcal) / porcion;
  document.getElementById("t_kcal").value = calc;
};
//Funcion que muestra los datos de el alimento para que pueda registrarse a la comida
const muestraAlimentos = (vals) => {
  fecha = new Date().toISOString().split("T")[0];
  porcion = vals["porcion"];
  kcal = vals["kcal"];
  code =
    "<form action='./pages/registra_comida.php' method='POST'><p>" +
    vals["nombre"] +
    " (" +
    vals["marca"] +
    ")</p><p>Porcion (gr o ml): <input onchange='calculaKcal(event,porcion,kcal)' value='0' type='number' step='.01' id='nporcion' name='uporcion'/></p>";
  code +=
    "<p>Numero total de calorias: <input readonly value='0' type='number' step='.01' id='t_kcal' name='total_kcal'/></p>";
  code +=
    "<p>Comida: <select name='comidas'><option value='desayuno' selected>Desayuno</option><option value='almuerzo'>Almuerzo</option><option value='comida'>Comida</option><option value='merienda'>Merienda</option><option value='cena'>Cena</option></select></p>";
  code += "<p>Fecha: <input type='date' value=" + fecha + " name='fecha'/></p>";
  code += "<input type='hidden' name='id_alimen' value='" + vals["id"] + "'/>";
  code += "<input type='submit' name='send_food' value='Insertar comida'/>";
  document.getElementById("c2").innerHTML = code;
};
//Funcion que hace una peticion para obtener la lista de los alimentos segun la busqueda que queramos hacer
const listAlimentos = (container) => {
  let nAlimento = document.getElementById("n_alimento").value;
  let marca = document.getElementById("a_marca").value;
  let checkFavoritos = document.getElementById("check-fav");
  let checkFavs = checkFavoritos.checked;
  let content = document.getElementById(container);
  let url = "../utils/add_comida.php";
  let formData = new FormData();
  let checkFav = checkFavs ? "v" : "f";
  formData.append("n_alimento", nAlimento);
  formData.append("a_marca", marca);
  formData.append("check_fav", checkFav);
  fetch(url, { method: "POST", body: formData })
    .then((response) => response.json())
    .then((data) => {
      content.innerHTML = data;
    })
    .catch((err) => console.log(err));
};
//Recibe un formulario para cambiar el tamaño de la porcion del alimento que va a comer, a traves de una peticion
const changeAlimento = (id, e, idComida) => {
  e.preventDefault();
  let content = document.getElementById("container2");
  let url = "../utils/editar_comida.php";
  content.style.display = "flex";
  let formData = new FormData();
  formData.append("id_alimento", id);
  formData.append("id_comida", idComida);
  fetch(url, { method: "POST", body: formData })
    .then((response) => response.json())
    .then((data) => {
      content.innerHTML = "<div id='c2'>" + data + "</div>";
      window.location.href = "#c2";
    })
    .catch((err) => console.log(err));
};
//Formulario para buscar los alimentos que el usuario quiera ingerir
const addComida = () => {
  code =
    "<h1>Añadir alimento</h1>" +
    '<div id="contain1">' +
    "<h3>Busqueda de los alimentos:</h3>" +
    '<form action="">' +
    '<p>Favoritos: <input type="checkbox" id="check-fav" onclick="listAlimentos(\'contain2\')" value="okfav"></p>' +
    '<input type="text" name="n_alimento" id="n_alimento" onchange="listAlimentos(\'contain2\')"/>' +
    '<select name="a_marca" id="a_marca" onchange="listAlimentos(\'contain2\')">' +
    '<option value="hacendado" selected>Hacendado</option>' +
    '<option value="aldi">Aldi</option>' +
    '<option value="alcampo">Alcampo</option>' +
    '<option value="dia">DIA</option>' +
    '<option value="carrefour">Carrefour</option>' +
    "</select>" +
    "</form></div><div id='contain2'></div>";
  document.getElementById("container2").style.display = "flex";
  document.getElementById("container2").innerHTML = code;
};
//Funcion que calcula los valores segun la porcion introducida por el usuario
const calculaValores = (vals) => {
  let p_u = document.getElementById("p_u").value;
  let p_a = vals[0];
  let valores = vals.slice(1);
  let newVals = valores.map((val) => {
    return (p_u * val) / p_a;
  });
  console.log(newVals);
  document.getElementById("v_kcal").value = newVals[0];
  document.getElementById("v_grasas").value = newVals[1];
  document.getElementById("v_gsatu").value = newVals[2];
  document.getElementById("v_carbos").value = newVals[3];
  document.getElementById("v_azucar").value = newVals[4];
  document.getElementById("v_prote").value = newVals[5];
  document.getElementById("v_sal").value = newVals[6];
};
//Muestra la lista de alimentos que puedo añadir a favoritos
const listFavorites = (container) => {
  let nAlimento = document.getElementById("n_alimento").value;
  let marca = document.getElementById("a_marca").value;
  let content = document.getElementById(container);
  let url = "../utils/add_favoritos.php";
  let formData = new FormData();
  formData.append("n_alimento", nAlimento);
  formData.append("a_marca", marca);
  fetch(url, { method: "POST", body: formData })
    .then((response) => response.json())
    .then((data) => {
      content.innerHTML = data;
    })
    .catch((err) => console.log(err));
};
//Registra un alimento a favoritos y lo muestra
const addFavorites = (id) => {
  let content = document.getElementById("f-content");
  let url = "../utils/registra_favoritos.php";
  let formData = new FormData();
  formData.append("id_alimento", id.id);
  fetch(url, { method: "POST", body: formData })
    .then((response) => response.json())
    .then((data) => {
      content.innerHTML = data;
      listFavorites("con1");
      topTenFavorites();
    })
    .catch((err) => console.log(err));
};
//Elimina un alimento de mis favoritos
const deleteFavorites = (id_alimento) => {
  let content = document.getElementById("f-content");
  let url = "../utils/eliminar_favorito.php";
  let formData = new FormData();
  formData.append("id_alimento", id_alimento);
  fetch(url, { method: "POST", body: formData })
    .then((response) => response.json())
    .then((data) => {
      content.innerHTML = data;
      listFavorites("con1");
      topTenFavorites();
    })
    .catch((err) => console.log(err));
};
//Muestra los alimentos que estan en mis favoritos con un parametro de busqueda por nombre o marca
const buscarFavoritos = () => {
  let content = document.getElementById("f-content");
  let url = "../utils/buscar_favoritos.php";
  let nAlimento = document.getElementById("f_alimento").value;
  let marca = document.getElementById("f_marca").value;
  let formData = new FormData();
  formData.append("name_ali", nAlimento);
  formData.append("marca", marca);
  fetch(url, { method: "POST", body: formData })
    .then((response) => response.json())
    .then((data) => {
      content.innerHTML = data;
    })
    .catch((err) => console.log(err));
};
//Funcion que muestra los alimentos que el usuario no ha valorado
const noValorados = (con) => {
  let nAlimento = document.getElementById("n_alimento").value;
  let marca = document.getElementById("a_marca").value;
  let url = "../utils/list_no_valorados.php";
  let content = document.getElementById(con);
  let formData = new FormData();
  formData.append("n_alimen", nAlimento);
  formData.append("marca", marca);
  fetch(url, { method: "POST", body: formData })
    .then((r) => r.json())
    .then((data) => {
      content.innerHTML = data;
    })
    .catch((err) => console.log(err));
};
//Formulario para añadir una valoracion
const addValoracion = (id_alimento, name) => {
  let content = document.getElementById("v-content");
  code =
    "<h3>" +
    name +
    "</h3><p>Valoracion: <input id='new-valor' onchange='compruebaVal(this)' type='number' value=0 min='0' max='10'/></p><p><button onclick='addVal(" +
    id_alimento +
    ")'>Añadir valoracion</button></p><a onclick=\"valorados('v-content')\" href='#my-vals'>Volver</a>";
  content.innerHTML = code;
};
//Añade una valoracion
const addVal = (id_alimento) => {
  let newVal = document.getElementById("new-valor").value;
  let url = "../utils/add_valoracion.php";
  if (!(newVal >= 0 && newVal <= 10)) {
    newVal.value = 0;
    alert("El minimo de valoracion es 0 y el maximo 10.");
  } else {
    let formData = new FormData();
    formData.append("id_alimen", id_alimento);
    formData.append("puntuacion", newVal);
    fetch(url, { method: "POST", body: formData })
      .then((r) => r.json())
      .then((data) => {
        if (data != 0) {
          alert("La puntuacion se ha añadido correctamente.");
        } else {
          alert("Ha ocurrido un error al añadir la puntuacion.");
        }
        valorados("v-content");
        noValorados("con1");
        topTenValoraciones();
      })
      .catch((err) => console.log(err));
  }
};
//Formulario para editar una valoracion
const changeValoracion = (id_alimento, name, val = 0) => {
  let content = document.getElementById("v-content");
  code =
    "<h3>" +
    name +
    "</h3><p>Valoracion: <input id='new-valor' onchange='compruebaVal(this)' type='number' value=" +
    val +
    " min='0' max='10'/></p><p><button onclick='changeVal(" +
    id_alimento +
    ")'>Editar valoracion</button></p><a onclick=\"valorados('v-content')\" href='#my-vals'>Volver</a>";
  content.innerHTML = code;
};

//Cambia una valoracion
const changeVal = (id_alimento) => {
  let newVal = document.getElementById("new-valor").value;
  let url = "../utils/change_valoracion.php";
  if (!(newVal >= 0 && newVal <= 10)) {
    newVal.value = 0;
    alert("El minimo de valoracion es 0 y el maximo 10.");
  } else {
    let formData = new FormData();
    formData.append("id_alimen", id_alimento);
    formData.append("puntuacion", newVal);
    fetch(url, { method: "POST", body: formData })
      .then((r) => r.json())
      .then((data) => {
        if (data != 0) {
          alert("La puntuacion se ha cambiado correctamente.");
        } else {
          alert("Ha ocurrido un error al cambiar la puntuacion.");
        }
        valorados("v-content");
        noValorados("con1");
        topTenValoraciones();
      })
      .catch((err) => console.log(err));
  }
};
//Muestra los alimentos valorados por el usuario
const valorados = (con) => {
  let nombre_val = document.getElementById("v_alimento").value;
  let marca_val = document.getElementById("v_marca").value;
  let content = document.getElementById(con);
  let url = "../utils/list_valorados.php";
  let formData = new FormData();
  formData.append("n_alimen", nombre_val);
  formData.append("marca", marca_val);
  fetch(url, { method: "POST", body: formData })
    .then((r) => r.json())
    .then((data) => {
      content.innerHTML = data;
    })
    .catch((err) => console.log(err));
};
//Funcion que comprueba si el valor esta entre 0 y 10
const compruebaVal = (e) => {
  let val = e.value;
  if (!(val >= 0 && val <= 10)) {
    e.value = 0;
    alert("El minimo de valoracion es 0 y el maximo 10.");
  }
};
//Elimina una valoracion
const eliminarValoracion = (id_alimento) => {
  let url = "../utils/eliminar_valoracion.php";
  let formData = new FormData();
  formData.append("id_alimen", id_alimento);
  fetch(url, { method: "POST", body: formData })
    .then((r) => r.json())
    .then((data) => {
      if (data != 0) {
        alert("La valoracion  se ha eliminado correctamente.");
      } else {
        alert("Ha ocurrido un error al intentar borrar la valoracion.");
      }
      valorados("v-content");
      noValorados("con1");
      topTenValoraciones();
    })
    .catch((err) => console.log(err));
};

//Top 10 favoritos que trae la informacion del top10 cada vez que se llama a la funcion
const topTenFavorites = () => {
  let container = document.getElementById("list-toptenfavorites");
  let canva = document.getElementById("topten-pie");
  let url = "../utils/top_ten_favorites.php";
  fetch(url)
    .then((r) => r.json())
    .then((data) => {
      container.innerHTML = data[0];
      canva.remove();
      document.getElementById("favorites").innerHTML +=
        "<canvas id='topten-pie'></canvas>";
      graficaPie(data[1], data[2], "topten-pie");
    });
};

//Top 10 valoraciones que trae la informacion del top10 cada vez que se llama a la funcion
const topTenValoraciones = () => {
  let container = document.getElementById("list-toptenvaloraciones");
  let canva = document.getElementById("topten-pie");
  let url = "../utils/top_ten_valoraciones.php";
  fetch(url)
    .then((r) => r.json())
    .then((data) => {
      container.innerHTML = data[0];
      canva.remove();
      document.getElementById("valoraciones").innerHTML +=
        "<canvas id='topten-pie'></canvas>";
      graficaPie(data[1], data[2], "topten-pie");
    });
};

//Muestra un grafico segun los datos que se le pasen
const graficaPie = (alimentos, datos, canva) => {
  let can = document.getElementById(canva);
  const colores = [
    "#FF6384",
    "#36A2EB",
    "#FFCE56",
    "#4BC0C0",
    "#9966FF",
    "#FF9F40",
    "#FFD700",
    "#00CED1",
    "#FF00FF",
    "#008000",
  ];
  let myChart = new Chart(can, {
    type: "pie",
    data: {
      labels: alimentos,
      datasets: [
        {
          label: "Favoritos",
          data: datos,
          backgroundColor: colores.slice(0, datos.length),
        },
      ],
    },
  });
};
