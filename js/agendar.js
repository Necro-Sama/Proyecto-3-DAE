// Verificar que agendarConfig esté disponible
if (typeof window.agendarConfig === 'undefined') {
    console.error('Error: agendarConfig no está definido');
    throw new Error('agendarConfig no está definido');
}

// Obtener las variables del objeto global
const {
    tipoUsuario,
    site_url,
    base_url,
    run,
    trabajadorSocialActual,
    trabajadorSocialSeleccionado
} = window.agendarConfig;

// Debug
console.log('Variables extraídas:', {
    tipoUsuario,
    site_url,
    base_url,
    run,
    trabajadorSocialActual,
    trabajadorSocialSeleccionado
});

// Verificación de variables necesarias
if (typeof tipoUsuario === 'undefined') {
    console.error('Error: tipoUsuario no está definido');
}

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

function agendar(bloqueId, horario, fechaInicio, fechaFinal, trabajadorSocial) {
    const data = {
        ID: bloqueId,
        FechaInicio: fechaInicio,
        FechaTermino: fechaFinal,
        RUNTS: trabajadorSocial,
        FechaInicioSemana: obtenerInicioSemana(fechaInicio)
    };

    $("#exampleModal").modal();
    $("#bloque_id").val(bloqueId);
    $("#fecha_ini").val(fechaInicio);
    $("#fecha_ter").val(fechaFinal);
    $("#ts_run").val(trabajadorSocial);
    $("#dia").html(obtenerFechaFormateada(fechaInicio));
    $("#bloque_horario").html(`${horario.horaInicio} - ${horario.horaFinal}`);
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

function generarIdBloque(fecha, horario, trabajadorSocial) {
    // Generar un timestamp único basado en la fecha y hora
    const timestamp = new Date(fecha).getTime();
    // Crear un ID único combinando timestamp y RUN del TS
    const id = Math.floor(Math.random() * 1000000); // Número aleatorio para evitar colisiones
    return id;
}

function crearBotones(dia, horario, tiempo_bloque_ini) {
    const bloqueId = generarIdBloque(tiempo_bloque_ini, horario);
    
    // Formatear fechas para la base de datos
    const fechaInicio = formatearFechaHora(tiempo_bloque_ini, horario.horaInicio);
    const fechaFinal = formatearFechaHora(tiempo_bloque_ini, horario.horaFinal);

    let html = '';
    
    if (tipoUsuario === "administrador" || tipoUsuario === "trabajadorsocial") {
        // Botón de bloquear para admin y TS
        html = `
            <form onsubmit="return validarTrabajadorSocial(event)">
                <input type="hidden" name="ID" value="${bloqueId}">
                <input type="hidden" name="fechainicio" value="${fechaInicio}">
                <input type="hidden" name="fechafinal" value="${fechaFinal}">
                <button type="submit" class="btn btn-success btn-sm">Bloquear</button>
            </form>
        `;
    } else if (tipoUsuario === "estudiante" || tipoUsuario === "noestudiante") {
        // Botón de agendar para estudiantes y no estudiantes
        html = `
            <button type="button" 
                    class="btn btn-primary btn-sm" 
                    onclick="mostrarModalAgendar('${bloqueId}', '${fechaInicio}', '${fechaFinal}')">
                Agendar
            </button>
        `;
    }

    return html;
}

function mostrarModalAgendar(bloqueId, fechaInicio, fechaFinal) {
    // Debug
    console.log('Mostrando modal con:', {
        bloqueId,
        fechaInicio,
        fechaFinal,
        trabajadorSocialSeleccionado
    });

    // Actualizar los campos ocultos del modal
    document.getElementById('fecha_ini').value = fechaInicio;
    document.getElementById('fecha_ter').value = fechaFinal;
    document.getElementById('runTS').value = trabajadorSocialSeleccionado;
    document.getElementById('run_usuario').value = run;
    
    // Formatear la fecha para mostrar
    const fecha = new Date(fechaInicio);
    const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const fechaFormateada = fecha.toLocaleDateString('es-ES', opciones);
    
    // Formatear el horario
    const horaInicio = fechaInicio.split(' ')[1].substring(0, 5);
    const horaFin = fechaFinal.split(' ')[1].substring(0, 5);
    
    // Actualizar los campos de texto del modal
    document.getElementById('dia').textContent = fechaFormateada;
    document.getElementById('bloque_horario').textContent = `${horaInicio} - ${horaFin}`;
    
    // Mostrar el modal usando jQuery
    $('#exampleModal').modal('show');
}

// Función para validar trabajador social (solo para admin y TS)
function validarTrabajadorSocial(event) {
    event.preventDefault();
    let trabajadorSocial;
    
    if (tipoUsuario === "administrador") {
        trabajadorSocial = document.getElementById("ts-select").value;
        if (!trabajadorSocial) {
            alert('Por favor seleccione un trabajador social antes de bloquear');
            return false;
        }
    } else if (tipoUsuario === "trabajadorsocial") {
        trabajadorSocial = trabajadorSocialActual; // Esta variable debe estar definida globalmente
    }

    // Si hay trabajador social, crear y enviar el formulario
    const form = event.target;
    const nuevoForm = document.createElement('form');
    nuevoForm.method = 'POST';
    nuevoForm.action = `${site_url}/citas/bloquear`;

    // Copiar los inputs existentes
    const inputs = form.getElementsByTagName('input');
    for (let input of inputs) {
        const nuevoInput = input.cloneNode(true);
        nuevoForm.appendChild(nuevoInput);
    }

    // Agregar el RUN del trabajador social
    const runInput = document.createElement('input');
    runInput.type = 'hidden';
    runInput.name = 'RUN';
    runInput.value = trabajadorSocial;
    nuevoForm.appendChild(runInput);

    // Enviar el formulario
    document.body.appendChild(nuevoForm);
    nuevoForm.submit();
    document.body.removeChild(nuevoForm);
    
    return false;
}

function formatearFechaHora(fecha, hora) {
    const fechaObj = new Date(fecha);
    const año = fechaObj.getFullYear();
    const mes = String(fechaObj.getMonth() + 1).padStart(2, '0');
    const dia = String(fechaObj.getDate()).padStart(2, '0');
    return `${año}-${mes}-${dia} ${hora}`;
}

function obtenerInicioSemana(fecha) {
    const fechaObj = new Date(fecha);
    fechaObj.setHours(0, 0, 0, 0);
    const dia = fechaObj.getDay();
    const diff = fechaObj.getDate() - dia + (dia === 0 ? -6 : 1);
    fechaObj.setDate(diff);
    return formatearFechaHora(fechaObj, '00:00');
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
    
    // Debug para verificar que el modal existe
    console.log('Modal element:', document.getElementById('exampleModal'));
    
    // Verificar que jQuery y Bootstrap estén cargados
    console.log('jQuery version:', $.fn.jquery);
    console.log('Bootstrap modal:', typeof $('#exampleModal').modal);
});

// Función para marcar todos los bloques de un día
function marcarTodos(dia) {
    const checkbox = document.getElementById(`checkbox-${dia}`);
    const tabla = document.getElementById('tabla-horario');
    const filas = tabla.getElementsByTagName('tr');
    const diaIndex = getDiaIndex(dia);
    
    // Obtener el trabajador social seleccionado
    const tsSelect = document.getElementById('ts-select');
    const trabajadorSocial = tsSelect ? tsSelect.value : trabajadorSocialActual;
    
    if (!trabajadorSocial) {
        alert('Por favor seleccione un trabajador social antes de bloquear');
        checkbox.checked = false;
        return;
    }

    // Array para almacenar todas las promesas de bloqueo
    const promesasBloqueo = [];

    // Iterar sobre cada fila (horario)
    for (let i = 1; i < filas.length; i++) { // Empezamos desde 1 para saltar el encabezado
        const fila = filas[i];
        const celdas = fila.getElementsByTagName('td');
        
        if (celdas.length > diaIndex) {
            const celda = celdas[diaIndex];
            const horario = horarios[i-1]; // i-1 porque horarios empieza desde 0

            // Saltar el horario de almuerzo
            if (horario.esAlmuerzo) continue;

            // Obtener la fecha y hora para este bloque
            const fechaInicio = obtenerFechaHoraBloque(dia, horario.horaInicio);
            const fechaFinal = obtenerFechaHoraBloque(dia, horario.horaFinal);

            if (checkbox.checked) {
                // Generar ID único para el bloque
                const bloqueId = generarIdBloque(fechaInicio, horario);

                // Crear los datos para el bloqueo
                const datos = {
                    ID: bloqueId,
                    RUN: trabajadorSocial,
                    fechainicio: fechaInicio,
                    fechafinal: fechaFinal
                };

                // Agregar la promesa de bloqueo al array
                promesasBloqueo.push(bloquearHorario(datos));
            }
        }
    }

    // Procesar todos los bloqueos
    Promise.all(promesasBloqueo)
        .then(() => {
            // Recargar el calendario después de que todos los bloqueos se completen
            cargar_calendario();
        })
        .catch(error => {
            console.error('Error al bloquear horarios:', error);
            alert('Hubo un error al bloquear algunos horarios');
        });
}

// Función auxiliar para obtener el índice del día en la tabla
function getDiaIndex(dia) {
    const dias = {
        'lunes': 1,
        'martes': 2,
        'miercoles': 3,
        'jueves': 4,
        'viernes': 5
    };
    return dias[dia.toLowerCase()];
}

// Función para obtener la fecha y hora formateada para un bloque
function obtenerFechaHoraBloque(dia, hora) {
    const semana = document.getElementById("semana-select").value.replace("00:00:00", "");
    const fecha = new Date(semana);
    const diasHasta = getDiaIndex(dia) - 1;
    fecha.setDate(fecha.getDate() + diasHasta);
    return `${fecha.getFullYear()}-${String(fecha.getMonth() + 1).padStart(2, '0')}-${String(fecha.getDate()).padStart(2, '0')} ${hora}`;
}

// Función para realizar el bloqueo de un horario específico
async function bloquearHorario(datos) {
    try {
        const response = await fetch(`${site_url}/citas/bloquear`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(datos)
        });

        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }

        const result = await response.json();
        return result;
    } catch (error) {
        console.error('Error al bloquear horario:', error);
        throw error;
    }
}