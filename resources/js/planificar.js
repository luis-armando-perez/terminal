document.addEventListener("DOMContentLoaded", () => {
    const selectDestino = document.getElementById("selectDestino");
    const selectRuta = document.getElementById("selectRuta");
    const inputPrecio = document.getElementById("inputPrecio");
    const inputDia = document.getElementById("inputDia");
    const formPlanificar = document.getElementById("formPlanificar");

    const urlRutas = selectDestino.dataset.url;

    // 🔹 Cuando cambia el destino, cargamos las rutas disponibles
    selectDestino.addEventListener("change", async () => {
        const destino = selectDestino.value;
        selectRuta.innerHTML = `<option selected disabled>Cargando rutas...</option>`;
        inputPrecio.value = "";

        try {
            const res = await fetch(
                `${urlRutas}?destino=${encodeURIComponent(destino)}`
            );
            if (!res.ok) throw new Error(`Error HTTP: ${res.status}`);

            const rutas = await res.json();

            selectRuta.innerHTML = `<option selected disabled>Selecciona una ruta</option>`;

            if (!rutas.length) {
                selectRuta.innerHTML = `<option disabled>No hay rutas disponibles</option>`;
                return;
            }

            // 🔹 Mostrar hora (salida) y tipo en el select
            rutas.forEach((r) => {
                selectRuta.innerHTML += `
                    <option 
                        value="${r.id}" 
                        data-precio="${r.precio}">
                        ${r.salida} — ${r.tipo}
                    </option>
                `;
            });
        } catch (error) {
            console.error("Error al obtener rutas:", error);
            selectRuta.innerHTML = `<option disabled>Error al cargar rutas</option>`;
        }
    });

    // 🔹 Actualizar precio cuando se selecciona una ruta
    selectRuta.addEventListener("change", () => {
        const option = selectRuta.options[selectRuta.selectedIndex];
        if (!option || !option.dataset.precio) {
            inputPrecio.value = "";
            return;
        }
        inputPrecio.value = `${option.dataset.precio} C$`;
    });

    // 🔹 Enviar formulario
    formPlanificar.addEventListener("submit", async (e) => {
        e.preventDefault();

        const option = selectRuta.options[selectRuta.selectedIndex];

        // Validaciones básicas
        if (!option || !option.value) {
            alertaError("Por favor, selecciona una ruta válida.");
            return;
        }
        if (!inputDia.value) {
            alertaError("Por favor, selecciona una fecha.");
            return;
        }

        const data = {
            ruta_id: selectRuta.value,
            destino: selectDestino.value,
            dia: inputDia.value,
            precio: inputPrecio.value.replace(" C$", ""),
        };

        console.log("Datos a enviar:", data);

        try {
            const res = await fetch("/planificar/guardar", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                },
                body: JSON.stringify(data),
            });

            const text = await res.text();
            let result;
            try {
                result = JSON.parse(text);
            } catch {
                console.error("Respuesta no JSON:", text);
                alertaError(
                    "Ocurrió un error inesperado al guardar la planificación."
                );
                return;
            }

            if (res.ok) {
                alertaExito("El viaje fue planificado correctamente 🎉");
                formPlanificar.reset();
                selectRuta.innerHTML = `<option selected disabled>Selecciona una ruta</option>`;
                inputPrecio.value = "";
            } else {
                alertaError(result.message || "No se pudo guardar el plan.");
            }
        } catch (error) {
            console.error("Error al guardar:", error);
            alertaError(
                "Ocurrió un error inesperado al guardar la planificación."
            );
        }
    });
});

// --- alerts.js ---

// Confirmación genérica (retorna true o false)
async function confirmarAccion(titulo, texto, icono = "warning") {
    const result = await Swal.fire({
        title: titulo,
        text: texto,
        icon: icono,
        showCancelButton: true,
        confirmButtonColor: "#2563eb",
        cancelButtonColor: "#6b7280",
        confirmButtonText: "Sí, continuar",
        cancelButtonText: "Cancelar",
        backdrop: `rgba(0,0,0,0.4) blur(3px)`,
    });
    return result.isConfirmed;
}

// Éxito genérico
function alertaExito(mensaje = "Operación realizada con éxito") {
    Swal.fire({
        icon: "success",
        title: "¡Éxito!",
        text: mensaje,
        timer: 1800,
        showConfirmButton: false,
        backdrop: `rgba(0,0,0,0.4) blur(3px)`,
    });
}

// Error genérico
function alertaError(mensaje = "Ocurrió un error inesperado") {
    Swal.fire({
        icon: "error",
        title: "Error",
        text: mensaje,
        confirmButtonColor: "#d33",
        backdrop: `rgba(0,0,0,0.4) blur(3px)`,
    });
}

function mostrarLoader() {
    document.getElementById("loader").classList.remove("hidden");
}

function ocultarLoader() {
    document.getElementById("loader").classList.add("hidden");
}

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("formPlanificar");
    const contenedor = document.getElementById("contenedorPlanificaciones");
    const modal = document.getElementById("modalEditar");
    const formEditar = document.getElementById("formEditar");

    async function cargarPlanes() {
        mostrarLoader();
        try {
            const res = await fetch("/planificar/listar");
            const planes = await res.json();

            contenedor.innerHTML = "";

            if (planes.length === 0) {
                contenedor.innerHTML =
                    "<p class='text-gray-500 text-center'>No hay planes disponibles.</p>";
                return;
            }
            planes.forEach((plan) => {
                // 🔹 Asignamos color según el destino
                let colorIcono = "text-gray-600"; // color predeterminado
                let fondoIcono = "bg-gray-100"; // fondo predeterminado

                switch (plan.destino.toLowerCase()) {
                    case "ocotal":
                        colorIcono = "text-green-600";
                        fondoIcono = "bg-green-100";
                        break;
                    case "managua":
                        colorIcono = "text-red-600";
                        fondoIcono = "bg-red-100";
                        break;
                    case "esteli":
                        colorIcono = "text-blue-600";
                        fondoIcono = "bg-blue-100";
                        break;
                    case "san jose de cusmapa":
                        colorIcono = "text-purple-600";
                        fondoIcono = "bg-purple-100";
                        break;
                    case "las sabanas":
                        colorIcono = "text-yellow-600";
                        fondoIcono = "bg-yellow-100";
                        break;
                }

                            contenedor.innerHTML += `
                    <div 
                        class="bg-white rounded-2xl shadow-sm p-6 mb-4 transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:scale-[1.02]" 
                        data-plan-id="${plan.id}">
                        
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-4">
                                <div class="${fondoIcono} rounded-2xl p-4">
                                    <svg class="w-8 h-8 ${colorIcono}" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M4 16c0 .88.39 1.67 1 2.22V20c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h8v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1.78c.61-.55 1-1.34 1-2.22V6c0-3.5-3.58-4-8-4s-8 .5-8 4v10zm3.5 1c-.83 0-1.5-.67-1.5-1.5S6.67 14 7.5 14s1.5.67 1.5 1.5S8.33 17 7.5 17zm9 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm1.5-6H6V6h12v5z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900">${plan.destino}</h2>
                                    <p class="text-gray-500 text-sm">Ruta directa desde Somoto</p>
                                </div>
                            </div>
                            <div class="bg-blue-50 rounded-full px-4 py-2">
                                <span class="text-blue-600 font-bold text-lg">${plan.precio}</span>
                                <span class="text-blue-600 text-sm ml-1">C$</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                    <path d="M12 6v6l4 2" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                <span class="font-medium">${plan.salida}</span>
                            </div>
                            <div class="flex gap-2">
                                <button class="flex items-center gap-2 bg-red-50 text-red-600 px-4 py-2 rounded-lg hover:bg-red-100 transition eliminar-btn" data-id="${plan.id}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    </div>`;
            });
        } catch (error) {
            console.error("Error al cargar planificaciones:", error);
            contenedor.innerHTML =
                "<p class='text-red-600 text-center'>Error al cargar los datos.</p>";
        } finally {
            ocultarLoader();
        }
    }

    // --- Guardar nuevo plan ---
    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const datos = new FormData(form);

        mostrarLoader();
        try {
            const res = await fetch("/planificar/guardar", {
                method: "POST",
                body: datos,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                },
            });

            if (!res.ok) throw new Error("Error al guardar plan");

            form.reset();
            await cargarPlanes();
        } catch (error) {
            console.error("Error:", error);
            alert("Ocurrió un error al guardar el plan");
        } finally {
            ocultarLoader();
        }
    });

    // --- Editar plan (abrir modal) y eliminar ---
    contenedor.addEventListener("click", async (e) => {
        const id = e.target.dataset.id;

        // 📝 Editar
        if (e.target.classList.contains("editar-btn")) {
            mostrarLoader();
            try {
                const res = await fetch("/planificar/listar");
                const planes = await res.json();
                const plan = planes.find((p) => p.id == id);
                if (plan) abrirModalEditar(plan);
            } catch (error) {
                console.error("Error al obtener plan:", error);
            } finally {
                ocultarLoader();
            }
        }

        // 🗑️ Eliminar
        if (e.target.classList.contains("eliminar-btn")) {
            const id = e.target.dataset.id;

            const confirmar = await confirmarAccion(
                "¿Seguro que quieres eliminar este plan?",
                "Esta acción no se puede deshacer."
            );

            if (!confirmar) return;

            mostrarLoader();
            try {
                const res = await fetch(`/planificar/eliminar/${id}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                    },
                });

                const result = await res.json();

                if (result.success) {
                    // Eliminar visualmente
                    document.querySelector(`[data-plan-id="${id}"]`).remove();
                    // Mostrar éxito
                    alertaExito("El plan se eliminó correctamente 🗑️");
                } else {
                    alertaError(
                        result.message || "No se pudo eliminar el plan."
                    );
                }
            } catch (error) {
                console.error("Error al eliminar:", error);
                alertaError("Ocurrió un error al intentar eliminar el plan.");
            } finally {
                ocultarLoader();
            }
        }
    });

    
    // --- Inicializar ---
    cargarPlanes();
});
