let dias = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"];
const horarios = [
    { id: 1, rango: "08:00-08:30" },
    { id: 2, rango: "08:30-10:00" },
    { id: 3, rango: "10:00-10:30" },
    { id: 4, rango: "11:00-11:30" },
    { id: 5, rango: "11:30-12:00" },
    { id: 6, rango: "12:00-12:30" },
    { id: "almuerzo", rango: "12:55-13:45", esAlmuerzo: true },
    { id: "almuerzo", rango: "13:45-14:30", esAlmuerzo: true },
    { id: 7, rango: "14:30-15:00" },
    { id: 8, rango: "15:00-15:30" },
    { id: 9, rango: "16:00-16:30" },
    { id: 10, rango: "16:30-17:00" },
    { id: 11, rango: "17:30-18:00" },
    { id: 12, rango: "18:30-19:00" },
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
    let tiempo_servidor = new Date(document.getElementById("tiempo-servidor").innerHTML);
    // console.log(tiempo_servidor);
    
    const tablaHorario = document.getElementById("tabla-horario");
    tablaHorario.innerHTML = "";
    const semana = document.getElementById("semana-select").value.replace("00:00:00", "");

    function marcarTodos(dia) {
        // Selecciona todos los checkboxes de los bloques para el día específico
        const checkboxes = document.querySelectorAll(`.checkbox-bloquear-${dia}`);
        const checked = document.getElementById(`checkbox-${dia}`).checked;

        // Marca o desmarca todos los checkboxes del día según el estado del checkbox de la cabecera
        checkboxes.forEach(checkbox => {
            checkbox.checked = checked;
        });
    }

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

            let t1 = horario.rango.split("-")[0].trim() + ":00";
            let tiempo_bloque_ini = new Date(semana + t1);

            tiempo_bloque = new Date(tiempo_bloque.getTime() + (dia - 1) * 24 * 3600 * 1000);
            tiempo_bloque_ini = new Date(tiempo_bloque_ini.getTime() + (dia - 1) * 24 * 3600 * 1000);

            // Si el bloque está disponible, muestra el checkbox y el botón de agendar
            if (horario.esAlmuerzo || tiempo_bloque < tiempo_servidor) {
                celda.innerHTML = `<div style='color: #ff0000;'>(no disponible)</div>`;
            } else {
    // Condición para determinar si se muestra el checkbox
    const mostrarCheckbox = tipoUsuario === 'administrador' || tipoUsuario === 'trabajadorsocial';

    celda.innerHTML = `
        <div>
            ${
                mostrarCheckbox
                    ? `<input type="checkbox" id="bloquear-${horario.id}-${dia}" class="checkbox-bloquear checkbox-bloquear-${['lunes', 'martes', 'miercoles', 'jueves', 'viernes'][dia - 1]}" />`
                    : ``
            }
            <button class="btn" onClick="agendar(${dia}, ${horario.id}, '${tiempo_bloque_ini}', '${tiempo_bloque}')">Agendar</button>
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