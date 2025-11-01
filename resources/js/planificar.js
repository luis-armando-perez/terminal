document.addEventListener("DOMContentLoaded", () => {
    const selectDestino = document.getElementById("selectDestino");
    const selectRuta = document.getElementById("selectRuta");
    const inputPrecio = document.getElementById("inputPrecio");
    const hora = document.getElementById("inputHora").value; // input type="time"
    const formPlanificar = document.getElementById("formPlanificar");

    const urlRutas = selectDestino.dataset.url;

    selectDestino.addEventListener("change", async () => {
        const destino = selectDestino.value;
        selectRuta.innerHTML = `<option selected disabled>Cargando rutas...</option>`;
        inputPrecio.value = "";

        try {
            const res = await fetch(
                `${urlRutas}?destino=${encodeURIComponent(
                    destino
                )}&hora=${hora}`
            );
            const rutas = await res.json();

            selectRuta.innerHTML = `<option selected disabled>Selecciona una ruta</option>`;

            if (rutas.length === 0) {
                selectRuta.innerHTML = `<option disabled>No hay rutas disponibles</option>`;
                return;
            }

            rutas.forEach((r) => {
                selectRuta.innerHTML += `
                    <option value="${r.id}" data-precio="${r.precio}">
                        ${r.salida} — ${r.tipo}
                    </option>
                `;
            });
        } catch (error) {
            console.error("Error al obtener rutas:", error);
            selectRuta.innerHTML = `<option disabled>Error al cargar rutas</option>`;
        }
    });

    selectRuta.addEventListener("change", () => {
        const precio =
            selectRuta.options[selectRuta.selectedIndex].dataset.precio;
        inputPrecio.value = `${precio} C$`;
    });

    formPlanificar.addEventListener("submit", async (e) => {
        e.preventDefault(); // Evita recargar la página

        const data = {
            ruta_id: selectRuta.value,
            destino: selectDestino.value,
            hora: inputHora.value,
            precio: inputPrecio.value.replace(" C$", ""),
        };

        try {
            const res = await fetch("/planificar/guardar", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify(data),
            });

            const result = await res.json();

            if (res.ok) {
                alertaExito("El viaje fue planificado correctamente 🎉");
                formPlanificar.reset();
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
    const cerrarModal = document.getElementById("cerrarModal");
    const cancelarEditar = document.getElementById("cancelarEditar");

    // --- Función para mostrar el modal y precargar datos ---
    function abrirModalEditar(plan) {
        document.getElementById("editarId").value = plan.id;
        document.getElementById("editarDestino").value = plan.destino;
        document.getElementById("editarHora").value = plan.hora;
        document.getElementById("editarPrecio").value = plan.precio;

        modal.classList.remove("hidden");
    }

    // --- Cerrar modal ---
    [cerrarModal, cancelarEditar].forEach((btn) =>
        btn.addEventListener("click", () => modal.classList.add("hidden"))
    );

    // --- Cargar planes ---
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
                contenedor.innerHTML += `
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-gray-300 transition-colors" data-plan-id="${plan.id}">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-800">${plan.destino}</h3>
                                <p class="text-sm text-gray-600 mb-1">Hora: ${plan.hora}</p>
                                <p class="text-sm text-gray-500">Precio: ${plan.precio} C$</p>
                            </div>
                            <div class="flex gap-2 ml-4">
                                <button class="text-blue-600 hover:text-blue-800 font-semibold editar-btn" data-id="${plan.id}">Editar</button>
                                <button class="text-red-600 hover:text-red-800 font-semibold eliminar-btn" data-id="${plan.id}">Eliminar</button>
                            </div>
                        </div>
                    </div>
                `;
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

    // --- Actualizar plan desde el modal ---
    formEditar.addEventListener("submit", async (e) => {
        e.preventDefault();

        const id = document.getElementById("editarId").value;
        const datos = new FormData(formEditar);

        try {
            const res = await fetch(`/planificar/actualizar/${id}`, {
                method: "POST",
                body: datos,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                },
            });

            const result = await res.json();

            if (result.success) {
                modal.classList.add("hidden");
                await cargarPlanes();
            } else {
                alert("Error: " + result.message);
            }
        } catch (error) {
            console.error("Error al actualizar:", error);
            alert("No se pudo actualizar el plan.");
        }
    });

    // --- Inicializar ---
    cargarPlanes();
});
