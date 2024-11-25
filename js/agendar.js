let dias = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"];

function agendar(dia, bloque) {
  $("#exampleModal").modal();
  $("#dia")[0].value = dias[dia - 1];
  $("#bloque_horario")[0].value = bloque;
}
