const tipoUsuario = "<?= $tipo_usuario ?>"; // 'estudiante' o 'administrador'

let dias = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"];
const horarios = [
    { id: 1, rango: "08:00-08:30", duracion: 30 },
    { id: 2, rango: "08:30-09:00", duracion: 30 },
    { id: 3, rango: "09:00-09:30", duracion: 30 },
    { id: 4, rango: "09:30-10:00", duracion: 30 },
    { id: 5, rango: "10:00-10:30", duracion: 30 },
    { id: 6, rango: "10:30-11:00", duracion: 30 },
    { id: 7, rango: "11:00-11:30", duracion: 30 },
    { id: 8, rango: "11:30-12:00", duracion: 30 },
    { id: 9, rango: "12:00-12:30", duracion: 30 },
    { id: 10, rango: "12:30-13:00", duracion: 30 },
    { id: 11, rango: "13:00-13:30", duracion: 30, bloqueado: true },  // Almuerzo bloqueado
    { id: 12, rango: "13:30-14:00", duracion: 30 },
    { id: 13, rango: "14:00-14:30", duracion: 30 },
    { id: 14, rango: "14:30-15:00", duracion: 30 },
    { id: 15, rango: "15:00-15:30", duracion: 30 },
    { id: 16, rango: "15:30-16:00", duracion: 30 },
    { id: 17, rango: "16:00-16:30", duracion: 30 },
    { id: 18, rango: "16:30-17:00", duracion: 30 },
    { id: 19, rango: "17:00-17:30", duracion: 30 },
    { id: 20, rango: "17:30-18:00", duracion: 30 },
];


// Función para generar semanas
// Función para generar semanas (lunes a viernes)
function generarSemanas() {
    const semanaSelect = document.getElementById('semana-select');
    const fechaActual = new Date();
    const inicioSemana = getInicioSemana(fechaActual);
    let semanaOptions = [];

    // Generamos opciones para las próximas 3 semanas
    for (let i = 0; i < 3; i++) {
        let fechaInicio = new Date(inicioSemana);
        fechaInicio.setDate(fechaInicio.getDate() + (i * 7));

        // Calculamos el final de la semana (viernes)
        let fechaFin = new Date(fechaInicio);
        fechaFin.setDate(fechaFin.getDate() + 4); // Solo sumamos 4 días desde el lunes

        semanaOptions.push({
            inicio: fechaInicio.toISOString().split('T')[0],
            fin: fechaFin.toISOString().split('T')[0],
            texto: `Semana ${i + 1} (${fechaInicio.toLocaleDateString()} - ${fechaFin.toLocaleDateString()})`
        });
    }

    // Añadimos las opciones al dropdown
    semanaOptions.forEach(option => {
        const opt = document.createElement('option');
        opt.value = option.inicio;
        opt.innerHTML = option.texto;
        semanaSelect.appendChild(opt);
    });
}

// Función para cargar el calendario
function cargar_calendario() {
    const tablaHorario = document.getElementById("tabla-horario").getElementsByTagName("tbody")[0];
    tablaHorario.innerHTML = "";

    const semana = document.getElementById("semana-select").value;
    const fechaSemana = new Date(semana);

    horarios.forEach((horario, index) => {
        const fila = document.createElement("tr");
        fila.className = index % 2 === 0 ? "fila1" : "fila2";

        // Columna de hora
        const celdaHora = document.createElement("td");
        celdaHora.innerHTML = `<div class="p-2 display-8">${horario.id}<br>${horario.rango}</div>`;
        fila.appendChild(celdaHora);

        // Columna para cada día de la semana (lunes a viernes)
        for (let dia = 1; dia < 6; dia++) { // Solo recorremos de lunes (0) a viernes (4)
            const celda = document.createElement("td");
            const fechaBloque = new Date(fechaSemana);
            fechaBloque.setDate(fechaSemana.getDate() + dia);

            // Verificamos si la fecha ya pasó
            if (fechaBloque < new Date()) {
                celda.innerHTML = `
                    <div class="no-disponible">
                        <span>${fechaBloque.getDate()}-${fechaBloque.getMonth() + 1}</span>
                        <button class="btn" disabled>No disponible</button>
                    </div>
                `;
            } else {
                // Si no es pasada, mostramos las fechas disponibles
                const fechaStr = `${fechaBloque.getDate()}-${fechaBloque.getMonth() + 1}`;
                const esAlmuerzo = horario.id === 11 ? "almuerzo" : "";
                const buttonClass = horario.bloqueado ? "btn-danger" : "btn";
                celda.innerHTML = `
                    <div>
                        <span>${fechaStr}</span>
                        <button class="${buttonClass}" onClick="agendar(${dia}, ${horario.id}, '${fechaBloque}')">${esAlmuerzo ? "Bloqueado" : "Agendar"}</button>
                    </div>
                `;
            }

            fila.appendChild(celda);
        }

        tablaHorario.appendChild(fila);
    });
}

function getInicioSemana(fecha) {
    const diaSemana = fecha.getDay(); // Obtiene el día de la semana (0 = Domingo, 1 = Lunes, etc.)
    let inicioSemana = new Date(fecha); // Crea una copia de la fecha original

    if (diaSemana === 1) {
        // Ya es lunes, no se cambia
        inicioSemana.setDate(fecha.getDate());
    } else if (diaSemana === 2) {
        // Martes: Retrocede un día
        inicioSemana.setDate(fecha.getDate() - 1);
    } else if (diaSemana === 3) {
        // Miércoles: Retrocede dos días
        inicioSemana.setDate(fecha.getDate() - 2);
    } else if (diaSemana === 4) {
        // Jueves: Retrocede tres días
        inicioSemana.setDate(fecha.getDate() - 3);
    } else if (diaSemana === 5) {
        // Viernes: Retrocede cuatro días
        inicioSemana.setDate(fecha.getDate() - 4);
    } else if (diaSemana === 6) {
        // Sábado: Retrocede cinco días
        inicioSemana.setDate(fecha.getDate() - 5);
    } else if (diaSemana === 0) {
        // Domingo: Retrocede seis días
        inicioSemana.setDate(fecha.getDate() - 6);
    }
    console.log("Día de la semana:", fecha.getDay()); 
    console.log("Fecha inicial calculada:", inicioSemana);
    return inicioSemana;
}


// Función para verificar si la semana está disponible (todos los bloques de la semana no deben haber pasado)
function estaSemanaDisponible(inicioSemana) {
    const semanaFinal = new Date(inicioSemana);
    semanaFinal.setDate(semanaFinal.getDate() + 6);

    // Comprobamos si todos los bloques de la semana han pasado
    let todosBloqueados = true;
    for (let i = 0; i < 5; i++) {
        const fechaBloque = new Date(inicioSemana);
        fechaBloque.setDate(inicioSemana.getDate() + i);
        if (fechaBloque >= new Date()) {
            todosBloqueados = false;
            break;
        }
    }

    return !todosBloqueados;
}

// Función para seleccionar semana
function seleccion_semana() {
    cargar_calendario();
}

window.onload = function() {
    generarSemanas(); // Llamamos a la función para generar las semanas al cargar la página
    cargar_calendario(); // Cargamos el calendario con la semana seleccionada
};
