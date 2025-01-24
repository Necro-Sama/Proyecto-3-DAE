let dias = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"];
let horarios = [
    { id: 1, horaInicio: "08:00", horaFinal: "08:30" },
    { id: 2, horaInicio: "08:30", horaFinal: "09:00" },
    { id: 3, horaInicio: "09:00", horaFinal: "09:30" },
    { id: 4, horaInicio: "09:30", horaFinal: "10:00" },
    { id: 5, horaInicio: "10:00", horaFinal: "10:30" },
    { id: 6, horaInicio: "10:30", horaFinal: "11:00" },
    { id: 7, horaInicio: "11:00", horaFinal: "11:30" },
    { id: 8, horaInicio: "11:30", horaFinal: "12:00" },
    { id: 9, horaInicio: "12:00", horaFinal: "12:30" },
    { id: "almuerzo", horaInicio: "12:55", horaFinal: "14:30", esAlmuerzo: true },
    { id: 10, horaInicio: "14:30", horaFinal: "15:00" },
    { id: 11, horaInicio: "15:00", horaFinal: "15:30" },
    { id: 12, horaInicio: "15:30", horaFinal: "16:00" },
    { id: 13, horaInicio: "16:00", horaFinal: "16:30" },
    { id: 14, horaInicio: "16:30", horaFinal: "17:00" },
    { id: 15, horaInicio: "17:00", horaFinal: "17:30" },
    { id: 16, horaInicio: "17:30", horaFinal: "18:00" },
    { id: 17, horaInicio: "18:00", horaFinal: "18:30" },
    { id: 18, horaInicio: "18:30", horaFinal: "19:00" },
];

function agendar(dia, bloque, fecha_ini, fecha_ter) {
    fecha_ini = new Date(fecha_ini);
    fecha_ter = new Date(fecha_ter);
    
    $("#exampleModal").modal();
    $("#dia")[0].innerHTML = dias[dia - 1] + " " + fecha_ini.toLocaleString();
    $("#bloque_horario")[0].innerHTML = bloque;
    let f = fecha_ini;
    let ft = fecha_ter;
    
    let fecha_in = (new Date(f.getTime() - (f.getTimezoneOffset() * 60000))).toISOString().slice(0, 19).replace('T', ' ');
    let fecha_te = (new Date(ft.getTime() - (ft.getTimezoneOffset() * 60000))).toISOString().slice(0, 19).replace('T', ' ');
    $("#fecha_ini")[0].value = fecha_in;
    $("#fecha_ter")[0].value = fecha_te;
}

function seleccion_semana(e) {
    cargar_calendario();
}

function cargar_calendario() {
    let tiempo_servidor = new Date(document.getElementById("tiempo-servidor").innerText);

    
    // console.log("tiempo-servidor"+document.getElementById("tiempo-servidor").innerText); // ¿Muestra la fecha esperada?
    const tablaHorario = document.getElementById("tabla-horario");
    // console.log("tablaHorario"+document.getElementById("tabla-horario")); // ¿Muestra null?
    
    tablaHorario.innerHTML = ""; // Limpiar la tabla antes de cargar
    const semana = document.getElementById("semana-select").value.replace("00:00:00", "");

    const fragment = document.createDocumentFragment(); // Usar fragmentos para optimizar el DOM

    horarios.forEach((horario, index) => {
        const fila = document.createElement("tr");
        fila.className = index % 2 === 0 ? "fila1" : "fila2";

        const celdaHora = document.createElement("td");
        celdaHora.innerHTML = `<div class="p-2 display-8">${horario.id}<br>${horario.horaInicio} - ${horario.horaFinal}</div>`;
        fila.appendChild(celdaHora);

        for (let dia = 1; dia <= 5; dia++) {
            const celda = document.createElement("td");

            // Calcular tiempo de inicio del bloque
            let tInicio = `${horario.horaInicio}:00`;
            let tiempo_bloque_ini = new Date(`${semana}${tInicio}`);
            tiempo_bloque_ini = new Date(tiempo_bloque_ini.getTime() + (dia - 1) * 24 * 3600 * 1000);

            if (horario.estado === 'Reservado') {
                celda.innerHTML = `<div style="color: #ff0000;">Reservado</div>`;
            } else if (horario.esAlmuerzo || tiempo_bloque_ini < tiempo_servidor) {
                celda.innerHTML = `<div style="color: #ff0000;">(no disponible)</div>`;
            } else {
                celda.innerHTML = crearBotones(dia, horario, tiempo_bloque_ini);
            }

            fila.appendChild(celda);
        }

        fragment.appendChild(fila);
    });

    tablaHorario.appendChild(fragment); // Agregar el fragmento al DOM
}

function crearBotones(dia, horario, tiempo_bloque_ini) {
    let botonesHtml = "";
    if (tipoUsuario === "administrador" || tipoUsuario === "trabajadorsocial") {
        botonesHtml += `
            <button class="btn btn-primary" 
                onClick="bloquear(${dia}, ${horario.id}, '${tiempo_bloque_ini.toISOString()}', '${tiempo_bloque_ini.toISOString()}')">
                Bloquear
            </button>`;
    }
    if (tipoUsuario === "estudiante" || tipoUsuario === "noestudiante") {
        botonesHtml += `
            <button class="btn btn-success" 
                onClick="agendar(${dia}, ${horario.id}, '${tiempo_bloque_ini.toISOString()}', '${tiempo_bloque_ini.toISOString()}')">
                Agendar
            </button>`;
        if (reagenda) {
            botonesHtml += `
                <button class="btn btn-warning mt-1" 
                    onClick="agendar(${dia}, ${horario.id}, '${tiempo_bloque_ini.toISOString()}', '${tiempo_bloque_ini.toISOString()}')">
                    Reagendar
                </button>`;
        }
    }
    return `<div>${botonesHtml}</div>`;
}
    
$(document).ready(function() {
    cargar_calendario();
});