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
    const tablaHorario = document.getElementById("tabla-horario");
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
async function bloquear(dia, horario, tiempo_bloque_ini) {
    console.log('Iniciando función bloquear con parámetros:', {
        dia, 
        horario, 
        tiempo_bloque_ini
    });
    
    try {
        const requestData = {
            run: run,
            id: horario,
            fechaInicio: tiempo_bloque_ini,
            fechaFinal: tiempo_bloque_ini
        };
        
        console.log('Datos a enviar:', requestData);
        console.log('URL de destino:', base_url + 'citas/bloquear');

        const response = await fetch(base_url + 'citas/bloquear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(requestData),
        });

        console.log('Respuesta recibida:', response);

        const result = await response.json();
        console.log('Datos de respuesta:', result);

        if (result.success) {
            alert('Bloque bloqueado exitosamente');
            cargar_calendario();
        } else {
            alert('Error: ' + (result.message || 'No se pudo bloquear el horario'));
        }
    } catch (error) {
        console.error('Error detallado:', error);
        alert('Ocurrió un error al intentar bloquear el horario.');
    }
}

function crearBotones(dia, horario, tiempo_bloque_ini) {
    const container = document.createElement('div');

    if (tipoUsuario === "administrador" || tipoUsuario === "trabajadorsocial") {
        // Formatear la fecha para MySQL
        const formatearFecha = (fecha) => {
            return fecha.toISOString().slice(0, 19).replace('T', ' ');
        };

        // Verificar que run esté definido y no esté vacío
        if (!run || run === '') {
            console.error('RUN no está definido o está vacío');
            return '<div class="text-danger">Error: RUN no disponible. Por favor, inicie sesión nuevamente.</div>';
        }

        container.innerHTML = `
            <form method="POST" action="${site_url}/citas/bloquear" onsubmit="console.log('Formulario enviado');">
                <input type="hidden" name="ID" value="${horario.id}">
                <input type="hidden" name="fechainicio" value="${formatearFecha(tiempo_bloque_ini)}">
                <input type="hidden" name="fechafinal" value="${formatearFecha(tiempo_bloque_ini)}">
                <input type="hidden" name="RUN" value="${run}">
                <button type="submit" class="btn btn-success btn-sm" onclick="console.log('Botón clickeado');">Bloquear</button>
            </form>
        `;

        // Debug
        console.log('Formulario creado con valores:', {
            ID: horario.id,
            fechainicio: formatearFecha(tiempo_bloque_ini),
            fechafinal: formatearFecha(tiempo_bloque_ini),
            RUN: run
        });
    }
    if (tipoUsuario === "estudiante" || tipoUsuario === "noestudiante") {
        const btnAgendar = document.createElement('button');
        btnAgendar.className = 'btn btn-success';
        btnAgendar.innerText = 'Agendar';
        btnAgendar.onclick = () => 
            agendar(dia, horario.id, tiempo_bloque_ini.toISOString(), tiempo_bloque_ini.toISOString());
        container.appendChild(btnAgendar);

        if (reagenda) {
            const btnReagendar = document.createElement('button');
            btnReagendar.className = 'btn btn-warning mt-1';
            btnReagendar.innerText = 'Reagendar';
            btnReagendar.onclick = () =>
                Reagendar(run, horario.id, tiempo_bloque_ini.toISOString(), tiempo_bloque_ini.toISOString());
            container.appendChild(btnReagendar);
        }
    }

    return container.outerHTML;
}

// Agregar un event listener para los formularios después de cargar el calendario
document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('submit', function(e) {
        if (e.target.matches('form[action*="/citas/bloquear"]')) {
            console.log('Formulario de bloqueo enviado', {
                ID: e.target.elements.ID.value,
                fechainicio: e.target.elements.fechainicio.value,
                fechafinal: e.target.elements.fechafinal.value,
                RUN: e.target.elements.RUN.value
            });
        }
    });
});

$(document).ready(function() {
    cargar_calendario();
});