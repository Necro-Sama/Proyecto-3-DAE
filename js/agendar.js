let dias = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"];
const horarios = [
    { id: 1, rango: "08:00-08:45" },
    { id: 2, rango: "08:45-09:30" },
    { id: 3, rango: "09:40-10:25" },
    { id: 4, rango: "10:25-11:10" },
    { id: 5, rango: "11:20-12:05" },
    { id: 6, rango: "12:05-12:50" },
    { id: "almuerzo1", rango: "13:00-13:45", esAlmuerzo: true },
    { id: "almuerzo2", rango: "13:45-14:30", esAlmuerzo: true },
    { id: 7, rango: "14:45-15:30" },
    { id: 8, rango: "15:30-16:15" },
    { id: 9, rango: "16:20-17:05" },
    { id: 10, rango: "17:05-17:50" },
    { id: 11, rango: "17:55-18:40" },
    { id: 12, rango: "18:40-19:25" },
];

function agendar(dia, bloque) {
    $("#exampleModal").modal();
    $("#dia")[0].value = dias[dia - 1];
    $("#bloque_horario")[0].value = bloque;
    $("#semana")[0].value = $("#semana-select")[0].value.replace("00:00:00", "");
}

function seleccion_semana(e) {
    cargar_calendario();
}

function cargar_calendario() {
    let tiempo_servidor = new Date(document.getElementById("tiempo-servidor").innerHTML);
    console.log(tiempo_servidor);
    
    const tablaHorario = document.getElementById("tabla-horario");
    tablaHorario.innerHTML = "";
    const semana = document.getElementById("semana-select").value.replace("00:00:00", "");

    horarios.forEach((horario, index) => {
        const fila = document.createElement("tr");
        fila.className = index % 2 === 0 ? "fila1" : "fila2";

        const celdaHora = document.createElement("td");
        celdaHora.innerHTML = `<div class="p-2 display-8">${horario.id}<br>${horario.rango}</div>`;
        fila.appendChild(celdaHora);

        for (let dia = 1; dia <= 5; dia++) {
            const celda = document.createElement("td");
            let t = horario.rango.split("-")[1].trim() + ":00";
            let tiempo_bloque = new Date(semana + t);
            tiempo_bloque = new Date(tiempo_bloque.getTime() + (dia-1) * 24 * 3600 * 1000);
            console.log(tiempo_bloque);
            
            if (horario.esAlmuerzo || tiempo_bloque < tiempo_servidor) {
                celda.innerHTML = `<div style='color: #ff0000;'>(no disponible)</div>`;
            } else {
                celda.innerHTML = `
                  <div>
                      <button class="btn" onClick="agendar(${dia}, ${horario.id})">Agendar</button>
                  </div>
                `;
            }
            fila.appendChild(celda);
        }
        tablaHorario.appendChild(fila);
    });
}

window.onload = (e) => {
    cargar_calendario();
};
