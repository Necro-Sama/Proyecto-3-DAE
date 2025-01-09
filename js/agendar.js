let dias = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"];
// Puedes modificar estos horarios para ajustar la duración de cada bloque
const horarios = [
    { id: 1, rango: "08:00-08:30", duracion: 30 }, // Duración en minutos
    { id: 2, rango: "08:30-09:00", duracion: 30 },
    { id: 3, rango: "09:00-09:30", duracion: 30 },
    { id: 4, rango: "09:30-10:00", duracion: 30 },
    { id: 5, rango: "10:00-10:30", duracion: 30 },
    { id: 6, rango: "10:30-11:00", duracion: 30 },
    { id: 7, rango: "11:00-11:30", duracion: 30 },
    { id: 8, rango: "11:30-12:00", duracion: 30 },
    { id: 9, rango: "12:00-12:30", duracion: 30 },
    { id: 10, rango: "12:30-13:00", duracion: 30 },
    { id: 11, rango: "13:00-13:30", duracion: 30 },
    { id: 12, rango: "13:30-14:00", duracion: 30 },
    { id: 13, rango: "14:30-15:00", duracion: 30 },
    { id: 14, rango: "16:00-16:30", duracion: 30 },
    { id: 15, rango: "17:00-17:30", duracion: 30 },
];

function agendar(dia, bloque, fecha_ini, fecha_ter) {
    fecha_ini = new Date(fecha_ini);
    fecha_ter = new Date(fecha_ter);
    
    $("#exampleModal").modal();
    $("#dia")[0].innerHTML = dias[dia - 1] + " " + fecha_ini.toLocaleString();
    $("#bloque_horario")[0].innerHTML = bloque;
    
    let fecha_in = (new Date(fecha_ini.getTime() - (fecha_ini.getTimezoneOffset() * 60000))).toISOString().slice(0, 19).replace('T', ' ');
    let fecha_te = (new Date(fecha_ter.getTime() - (fecha_ter.getTimezoneOffset() * 60000))).toISOString().slice(0, 19).replace('T', ' ');
    $("#fecha_ini")[0].value = fecha_in;
    $("#fecha_ter")[0].value = fecha_te;
}

function cargar_calendario() {
    let tiempo_servidor = new Date(document.getElementById("tiempo-servidor").innerHTML);
    const tablaHorario = document.getElementById("tabla-horario");
    tablaHorario.innerHTML = "";
    const semana = document.getElementById("semana-select").value.replace("00:00:00", "");

    let idIncremental = 1;  // Contador para IDs incrementales

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

            // Ajustamos la duración dinámica de los bloques
            const duracion = horario.duracion || 30; // Usamos la duración configurada
            let tiempo_bloque_ter = new Date(tiempo_bloque_ini.getTime() + duracion * 60000); // Añadimos la duración en minutos

            if (tiempo_bloque < tiempo_servidor) {
                celda.innerHTML = `<div style='color: #ff0000;'>(no disponible)</div>`;
            } else {
                const idBloque = `bloque-${idIncremental}`; // ID incremental único
                idIncremental++;  // Aumentamos el contador para el siguiente bloque

                celda.innerHTML = `
                    <div>
                        <button class="btn" onClick="agendar(${dia}, ${horario.id}, '${tiempo_bloque_ini}', '${tiempo_bloque_ter}')">Agendar</button>
                    </div>
                `;
            }
            fila.appendChild(celda);
        }
        tablaHorario.appendChild(fila);
    });
}

window.onload = () => {
    cargar_calendario();
};

function seleccion_semana(e) {
    cargar_calendario();
}
