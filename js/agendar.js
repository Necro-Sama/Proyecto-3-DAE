console.log('agendar.js cargado correctamente');

if (typeof window.agendarConfig === 'undefined') {
    console.error('Error: agendarConfig no está definido');
    throw new Error('agendarConfig no está definido');
}

console.log('Variables de configuración:', window.agendarConfig);

const {
    tipoUsuario,
    site_url,
    base_url,
    run,
    trabajadorSocialActual,
    trabajadorSocialSeleccionado
} = window.agendarConfig;

console.log('Variables extraídas:', {
    tipoUsuario,
    site_url,
    base_url,
    run,
    trabajadorSocialActual,
    trabajadorSocialSeleccionado
});

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
    document.querySelectorAll('.dia-checkbox').forEach(cb => cb.checked = false);
    cargar_calendario();
}

function cargar_calendario() {
    let tiempo_servidor = new Date(document.getElementById("tiempo-servidor").innerText);
    const tablaHorario = document.getElementById("tabla-horario");
    tablaHorario.innerHTML = ""; 
    const semana = document.getElementById("semana-select").value.replace("00:00:00", "");

    const fragment = document.createDocumentFragment(); 

    horarios.forEach((horario, index) => {
        const fila = document.createElement("tr");
        fila.className = index % 2 === 0 ? "fila1" : "fila2";

        const celdaHora = document.createElement("td");
        celdaHora.innerHTML = `<div class="p-2 display-8">${horario.id}<br>${horario.horaInicio} - ${horario.horaFinal}</div>`;
        fila.appendChild(celdaHora);

        for (let dia = 1; dia <= 5; dia++) {
            const celda = document.createElement("td");

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

    tablaHorario.appendChild(fragment);
}

function generarIdBloque(fechaInicio, horario) {

    const fecha = new Date(fechaInicio);
    const year = fecha.getFullYear();
    const month = String(fecha.getMonth() + 1).padStart(2, '0');
    const day = String(fecha.getDate()).padStart(2, '0');
    return `BLQ${year}${month}${day}${horario.id}`;
}

function crearBotones(dia, horario, tiempo_bloque_ini) {
    const bloqueId = generarIdBloque(tiempo_bloque_ini, horario);
    const fechaInicio = formatearFechaHora(tiempo_bloque_ini, horario.horaInicio);
    const fechaFinal = formatearFechaHora(tiempo_bloque_ini, horario.horaFinal);

    let html = '';
    
    if (tipoUsuario === "administrador" || tipoUsuario === "trabajadorsocial") {
        let trabajadorSocial;
        
        if (tipoUsuario === "trabajadorsocial") {
            // Para TS, usar directamente su RUN
            trabajadorSocial = trabajadorSocialActual;
        } else {
            // Para administrador, mantener el select
            const selectTS = document.getElementById('ts-select');
            trabajadorSocial = selectTS ? selectTS.value : null;
        }

        if (trabajadorSocial) {
            html = `
                <form class="bloqueo-individual-form">
                    <input type="hidden" name="ID" value="${bloqueId}">
                    <input type="hidden" name="RUNTS" value="${trabajadorSocial}">
                    <input type="hidden" name="FechaInicio" value="${fechaInicio}">
                    <input type="hidden" name="FechaTermino" value="${fechaFinal}">
                    <button type="button" 
                            class="btn btn-success btn-sm btn-bloquear-individual"
                            onclick="handleBloqueoIndividual(event, '${bloqueId}', '${fechaInicio}', '${fechaFinal}', '${trabajadorSocial}')">
                        Bloquear
                    </button>
                </form>
            `;
        } else if (tipoUsuario === "administrador") {
            html = '<div class="text-danger">Seleccione un trabajador social</div>';
        }
    } else if (tipoUsuario === "estudiante" || tipoUsuario === "noestudiante") {
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

    console.log('Mostrando modal con:', {
        bloqueId,
        fechaInicio,
        fechaFinal,
        trabajadorSocialSeleccionado
    });

    document.getElementById('fecha_ini').value = fechaInicio;
    document.getElementById('fecha_ter').value = fechaFinal;
    document.getElementById('runTS').value = trabajadorSocialSeleccionado;
    document.getElementById('run_usuario').value = run;
    
    const fecha = new Date(fechaInicio);
    const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const fechaFormateada = fecha.toLocaleDateString('es-ES', opciones);
    
    const horaInicio = fechaInicio.split(' ')[1].substring(0, 5);
    const horaFin = fechaFinal.split(' ')[1].substring(0, 5);
    
    document.getElementById('dia').textContent = fechaFormateada;
    document.getElementById('bloque_horario').textContent = `${horaInicio} - ${horaFin}`;
    
    $('#exampleModal').modal('show');
}

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
        trabajadorSocial = trabajadorSocialActual; 
    }

    const form = event.target;
    const nuevoForm = document.createElement('form');
    nuevoForm.method = 'POST';
    nuevoForm.action = `${site_url}/citas/bloquear`;
    
    const inputs = form.getElementsByTagName('input');
    for (let input of inputs) {
        const nuevoInput = input.cloneNode(true);
        nuevoForm.appendChild(nuevoInput);
    }

    const runInput = document.createElement('input');
    runInput.type = 'hidden';
    runInput.name = 'RUN';
    runInput.value = trabajadorSocial;
    nuevoForm.appendChild(runInput);

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
    
    console.log('Modal element:', document.getElementById('exampleModal'));
    
    console.log('jQuery version:', $.fn.jquery);
    console.log('Bootstrap modal:', typeof $('#exampleModal').modal);

    const $btnDesbloquear = $('#btn-desbloquear');
    
    $btnDesbloquear.on('click', async function(e) {
        e.preventDefault();
        console.log('Click en botón de desbloqueo');
        
        try {
            const checkboxes = document.querySelectorAll('.dia-checkbox:checked');
            if (checkboxes.length === 0) {
                alert('Por favor seleccione al menos un día para desbloquear');
                return;
            }

            const tsSelect = document.getElementById('ts-select');
            const trabajadorSocial = tsSelect ? tsSelect.value : trabajadorSocialActual;
            
            if (!trabajadorSocial) {
                alert('Por favor seleccione un trabajador social');
                return;
            }

            if (!confirm('¿Está seguro que desea desbloquear los días seleccionados?')) {
                return;
            }

            $btnDesbloquear.prop('disabled', true).text('Desbloqueando...');

            const resultados = await Promise.all(
                Array.from(checkboxes).map(checkbox => 
                    desbloquearDiaCompleto(checkbox.value, trabajadorSocial)
                )
            );

            const mensajes = resultados.map(r => r.message);
            alert(mensajes.join('\n'));
            
            checkboxes.forEach(cb => cb.checked = false);
            cargar_calendario();

        } catch (error) {
            console.error('Error al desbloquear días:', error);
            alert('Error al desbloquear los días: ' + error.message);
        } finally {
            $btnDesbloquear.prop('disabled', false).text('Desbloquear días seleccionados');
        }
    });
});

function marcarTodos(dia) {
    const checkbox = document.getElementById(`checkbox-${dia}`);
    const tabla = document.getElementById('tabla-horario');
    const filas = tabla.getElementsByTagName('tr');
    const diaIndex = getDiaIndex(dia);
    
    const tsSelect = document.getElementById('ts-select');
    const trabajadorSocial = tsSelect ? tsSelect.value : trabajadorSocialActual;
    
    if (!trabajadorSocial) {
        alert('Por favor seleccione un trabajador social antes de bloquear');
        checkbox.checked = false;
        return;
    }

    const promesasBloqueo = [];

    for (let i = 1; i < filas.length; i++) { 
        const fila = filas[i];
        const celdas = fila.getElementsByTagName('td');
        
        if (celdas.length > diaIndex) {
            const celda = celdas[diaIndex];
            const horario = horarios[i-1];


            if (horario.esAlmuerzo) continue;


            const fechaInicio = obtenerFechaHoraBloque(dia, horario.horaInicio);
            const fechaFinal = obtenerFechaHoraBloque(dia, horario.horaFinal);

            if (checkbox.checked) {

                const bloqueId = generarIdBloque(fechaInicio, horario);

                const datos = {
                    ID: bloqueId,
                    RUN: trabajadorSocial,
                    fechainicio: fechaInicio,
                    fechafinal: fechaFinal
                };

                promesasBloqueo.push(bloquearHorario(datos));
            }
        }
    }


    Promise.all(promesasBloqueo)
        .then(() => {

            cargar_calendario();
        })
        .catch(error => {
            console.error('Error al bloquear horarios:', error);
            alert('Hubo un error al bloquear algunos horarios');
        });
}


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


function obtenerFechaHoraBloque(dia, hora) {
    const semanaSelect = document.getElementById("semana-select");
    const fechaInicio = new Date(semanaSelect.value);
    
    // Mapeo de días a números (0 = domingo, 1 = lunes, etc.)
    const diasSemana = {
        'lunes': 1,
        'martes': 2,
        'miercoles': 3,
        'jueves': 4,
        'viernes': 5
    };

    // Ajustar la fecha al día seleccionado
    const diaNumero = diasSemana[dia.toLowerCase()];
    fechaInicio.setDate(fechaInicio.getDate() + (diaNumero - fechaInicio.getDay()));

    // Formatear la fecha y hora
    const [hours, minutes] = hora.split(':');
    fechaInicio.setHours(parseInt(hours), parseInt(minutes), 0);

    // Retornar en formato MySQL datetime
    return fechaInicio.toISOString().slice(0, 19).replace('T', ' ');
}

async function bloquearHorario(datos) {
    console.log('Intentando bloquear horario:', datos);
    
    try {
        const datosAjustados = {
            ...datos,
            run_trabajador: datos.RUN
        };
        delete datosAjustados.RUN;

        const formData = new URLSearchParams();
        for (const [key, value] of Object.entries(datosAjustados)) {
            formData.append(key, value);
        }

        const response = await fetch(`${site_url}/citas/bloquear`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: formData
        });

        const responseText = await response.text();
        console.log('Respuesta del servidor:', responseText);

        let result;
        try {
            result = JSON.parse(responseText);
        } catch (e) {
            console.error('Error al parsear respuesta:', responseText);
            throw new Error('Error en la respuesta del servidor: ' + responseText.substring(0, 100));
        }

        if (!result.success) {
            throw new Error(result.message || 'Error al bloquear horario');
        }

        return result;
    } catch (error) {
        console.error('Error al bloquear horario:', error);
        throw error;
    }
}

// Funciones para el bloqueo de horarios
async function bloquearHorarioIndividual(datos) {
    console.log('Bloqueando horario individual:', datos);
    
    try {
        const datosAjustados = {
            ...datos,
            rut_trabajador: datos.RUN
        };
        delete datosAjustados.RUN;

        const formData = new URLSearchParams(datosAjustados);
        
        const response = await fetch(`${site_url}/citas/bloquear`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: formData
        });

        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }

        const responseText = await response.text();
        console.log('Respuesta del servidor:', responseText);

        try {
            return JSON.parse(responseText);
        } catch (e) {
            console.error('Respuesta no válida:', responseText);
            throw new Error('Respuesta del servidor no válida');
        }
    } catch (error) {
        console.error('Error al bloquear horario individual:', error);
        throw error;
    }
}

async function bloquearDiaCompleto(dia, trabajadorSocial) {
    try {
        if (!horarios || !Array.isArray(horarios)) {
            throw new Error('Error en la configuración de horarios');
        }

        const bloques = horarios
            .filter(h => !h.esAlmuerzo)
            .map(horario => {
                const fechaInicio = obtenerFechaHoraBloque(dia, horario.horaInicio);
                const fechaFin = obtenerFechaHoraBloque(dia, horario.horaFinal);
                
                return {
                    ID: `BLQ${Date.now()}${Math.random().toString(36).substr(2, 5)}`,
                    run_trabajador: trabajadorSocial,
                    fechainicio: fechaInicio,
                    fechafinal: fechaFin
                };
            });

        const resultados = await Promise.all(
            bloques.map(async bloque => {
                try {
                    const bloqueoResponse = await fetch(`${site_url}/citas/bloquear_dia_completo`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams(bloque)
                    });

                    if (!bloqueoResponse.ok) {
                        throw new Error(`Error HTTP: ${bloqueoResponse.status}`);
                    }

                    const resultado = await bloqueoResponse.json();
                    if (!resultado.success) {
                        throw new Error(resultado.message);
                    }

                    return { success: true };
                } catch (error) {
                    return { success: false, error: error.message };
                }
            })
        );

        const exitosos = resultados.filter(r => r.success).length;
        
        return {
            success: exitosos > 0,
            message: `Se bloquearon ${exitosos} de ${bloques.length} bloques.`
        };

    } catch (error) {
        return {
            success: false,
            message: `Error al bloquear el día: ${error.message}`
        };
    }
}

// Nueva función para obtener la fecha de inicio de semana
function obtenerFechaInicioSemana(fecha) {
    const date = new Date(fecha);
    const day = date.getDay();
    const diff = date.getDate() - day + (day === 0 ? -6 : 1); // ajusta cuando es domingo
    const lunes = new Date(date.setDate(diff));
    lunes.setHours(0, 0, 0, 0);
    return lunes.toISOString().slice(0, 19).replace('T', ' ');
}

async function desbloquearDiaCompleto(dia, trabajadorSocial) {
    console.log('Desbloqueando día completo:', { dia, trabajadorSocial });
    
    try {
        if (!horarios || !Array.isArray(horarios)) {
            throw new Error('Error en la configuración de horarios');
        }

        const fechaSeleccionada = obtenerFechaHoraBloque(dia, '00:00:00').split(' ')[0];
        console.log('Fecha seleccionada:', fechaSeleccionada);

        // Obtener bloques bloqueados del día
        const response = await fetch(`${site_url}/citas/obtener_bloques_bloqueados`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                fecha: fechaSeleccionada,
                rut_trabajador: trabajadorSocial
            })
        });

        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }

        const { bloques } = await response.json();
        
        if (!bloques || bloques.length === 0) {
            return {
                success: false,
                message: `No hay bloques bloqueados para desbloquear el día ${dia}`
            };
        }

        const resultados = await Promise.all(
            bloques.map(async bloque => {
                try {
                    const response = await fetch(`${site_url}/citas/desbloquear`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({
                            ID: bloque.ID,
                            RUNTS: trabajadorSocial
                        })
                    });

                    if (!response.ok) {
                        throw new Error(`Error HTTP: ${response.status}`);
                    }

                    return await response.json();
                } catch (error) {
                    console.error('Error en desbloqueo:', error);
                    return { success: false };
                }
            })
        );

        const exitosos = resultados.filter(r => r.success).length;
        const totalBloques = bloques.length;

        return {
            success: exitosos > 0,
            message: `Día ${dia}: Se desbloquearon ${exitosos} de ${totalBloques} bloques.`
        };

    } catch (error) {
        console.error('Error al desbloquear día completo:', error);
        return {
            success: false,
            message: `Error al desbloquear el día ${dia}: ${error.message}`
        };
    }
}

console.log('=== INICIO CARGA AGENDAR.JS ===');

// Función para inicializar los eventos
function inicializarEventos() {
    console.log('Iniciando configuración de eventos...');
    
    // Verificar que estamos en el contexto correcto
    if (typeof $ === 'undefined') {
        console.error('jQuery no está disponible en inicializarEventos');
        return;
    }

    const btnBloquear = $('#btn-bloquear');
    console.log('Buscando botón de bloquear...', btnBloquear.length ? 'Encontrado' : 'No encontrado');

    if (!btnBloquear.length) {
        console.error('No se encontró el botón de bloquear');
        return;
    }

    // Remover eventos previos y agregar el nuevo
    btnBloquear
        .off('click')
        .on('click', async function(e) {
            e.preventDefault();
            console.log('Click en botón de bloquear');
            alert('Botón clickeado - Iniciando proceso de bloqueo');

            const $btnBloquear = $(this);
            $btnBloquear.prop('disabled', true).text('Procesando...');

            try {
                const checkboxes = $('.dia-checkbox:checked');
                console.log('Días seleccionados:', checkboxes.length);

                if (checkboxes.length === 0) {
                    throw new Error('Por favor, seleccione al menos un día para bloquear');
                }

                let trabajadorSocial;
                
                if (tipoUsuario === "trabajadorsocial") {
                    // Para TS, usar directamente su RUN
                    trabajadorSocial = trabajadorSocialActual;
                } else {
                    // Para administrador, obtener del select
                    trabajadorSocial = $('#ts-select').val();
                }

                if (!trabajadorSocial) {
                    throw new Error('Por favor, seleccione un trabajador social');
                }

                const resultados = await Promise.all(
                    checkboxes.map(async function() {
                        const dia = $(this).val();
                        console.log('Procesando día:', dia);
                        return bloquearDiaCompleto(dia, trabajadorSocial);
                    }).get()
                );

                console.log('Resultados:', resultados);
                alert('Proceso completado:\n' + resultados.map(r => r.message).join('\n'));

            } catch (error) {
                console.error('Error:', error);
                alert(error.message);
            } finally {
                $btnBloquear.prop('disabled', false).text('Bloquear días seleccionados');
            }
        });

    console.log('Eventos configurados correctamente');
}

// Función para ocultar/mostrar el select de TS según el tipo de usuario
function configurarInterfazSegunUsuario() {
    if (tipoUsuario === "trabajadorsocial") {
        // Ocultar el select para TS
        const selectContainer = document.querySelector('.ts-select-container');
        if (selectContainer) {
            selectContainer.style.display = 'none';
        }
    }
}

// Inicializar cuando el documento esté listo
$(document).ready(function() {
    console.log('Documento listo - Inicializando...');
    configurarInterfazSegunUsuario();
    inicializarEventos();
});

// También inicializar cuando se muestre el modal
$(document).on('shown.bs.modal', '#exampleModal', function() {
    console.log('Modal mostrado - Reinicializando...');
    inicializarEventos();
});

console.log('=== FIN CARGA AGENDAR.JS ===');

async function bloquearIndividual(bloque) {
    try {
        // Asegurarnos de que tenemos todos los datos necesarios
        const trabajadorSocial = document.getElementById('ts-select').value;
        
        const datosBloqueo = {
            RUNTS: trabajadorSocial,
            FechaInicio: bloque.fechainicio,
            FechaTermino: bloque.fechafinal || bloque.fechainicio
        };

        console.log('Datos de bloqueo individual:', datosBloqueo); // Para debug

        const response = await fetch(`${site_url}/citas/bloquear_individual`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(datosBloqueo)
        });

        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }

        const resultado = await response.json();
        if (!resultado.success) {
            throw new Error(resultado.message);
        }

        return resultado;
    } catch (error) {
        console.error('Error en bloqueo individual:', error);
        return {
            success: false,
            message: error.message
        };
    }
}

// Actualizar la función handleBloqueoIndividual para recibir todos los parámetros
async function handleBloqueoIndividual(event, bloqueId, fechaInicio, fechaFinal, trabajadorSocial) {
    event.preventDefault();
    
    if (!trabajadorSocial) {
        alert('No se ha seleccionado un trabajador social');
        return;
    }

    if (confirm('¿Está seguro que desea bloquear este horario?')) {
        try {
            const datosBloqueo = {
                ID: bloqueId,
                RUNTS: trabajadorSocial,
                FechaInicio: fechaInicio,
                FechaTermino: fechaFinal
            };

            const response = await fetch(`${site_url}/citas/bloquear_individual`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(datosBloqueo)
            });

            if (!response.ok) {
                throw new Error(`Error de conexión`);
            }

            const resultado = await response.json();

            if (resultado.success) {
                alert('Bloque bloqueado correctamente');
                location.reload();
            } else {
                alert('Error al bloquear: ' + resultado.message);
            }
        } catch (error) {
            alert('Error al procesar el bloqueo');
        }
    }
}

// Asegurarse de que los botones de bloqueo individual tengan el evento asignado
function inicializarBotonesBloqueo() {
    const botonesBloqueo = document.querySelectorAll('.btn-bloquear-individual');
    botonesBloqueo.forEach(boton => {
        boton.addEventListener('click', handleBloqueoIndividual);
    });
}

// Llamar a la inicialización cuando el documento esté listo
document.addEventListener('DOMContentLoaded', inicializarBotonesBloqueo);

// Agregar función para actualizar los botones cuando cambie el select
function actualizarBotonesAlCambiarTS() {
    const selectTS = document.getElementById('ts-select');
    if (selectTS) {
        selectTS.addEventListener('change', function() {
            cargar_calendario(); // Recargar el calendario para actualizar los botones
        });
    }
}

// Llamar a la función cuando el documento esté listo
document.addEventListener('DOMContentLoaded', function() {
    actualizarBotonesAlCambiarTS();
});