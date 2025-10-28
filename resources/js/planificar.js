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
                alert("Viaje planificado correctamente!");
                formPlanificar.reset();
            } else {
                alert("Error: " + result.message);
            }
        } catch (error) {
            console.error("Error al guardar:", error);
            alert("Ocurrió un error al guardar la planificación.");
        }
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("formPlanificar");

    // ✅ 1. Función para cargar todos los planes
    async function cargarPlanes() {
        const contenedor = document.getElementById("contenedorPlanificaciones");

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
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-gray-300 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-800">${plan.destino}</h3>
                                <p class="text-sm text-gray-600 mb-1">Hora: ${plan.hora}</p>
                                <p class="text-sm text-gray-500">Precio: ${plan.precio} C$</p>
                            </div>
                            <div class="flex gap-2 ml-4">
                                <button class="text-blue-600 hover:text-blue-800 font-semibold" data-id="${plan.id}">Editar</button>
                                <button class="text-red-600 hover:text-red-800 font-semibold" data-id="${plan.id}">Eliminar</button>
                            </div>
                        </div>
                    </div>
                `;
            });
        } catch (error) {
            console.error("Error al cargar planificaciones:", error);
            contenedor.innerHTML =
                "<p class='text-red-600 text-center'>Error al cargar los datos.</p>";
        }
    }

    // ✅ 2. Llamamos la función al cargar la página
    cargarPlanes();

    // ✅ 3. Manejamos el envío del formulario (guardar nuevo plan)
    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const datos = new FormData(form);

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

            // 🔥 Éxito: limpiar formulario y recargar lista
            form.reset();
            await cargarPlanes();
        } catch (error) {
            console.error("Error:", error);
            alert("Ocurrió un error al guardar el plan");
        }
    });

    // 🧨 Detectar clic en "Eliminar"
    contenedor.addEventListener("click", async (e) => {
        if (e.target.classList.contains("eliminar")) {
            const id = e.target.dataset.id;
            const confirmar = confirm(
                "¿Seguro que quieres eliminar este plan?"
            );

            if (!confirmar) return;

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
                    // 🔥 Eliminar visualmente el div sin recargar
                    e.target.closest("div[data-id]").remove();
                } else {
                    alert("Error: " + result.message);
                }
            } catch (error) {
                console.error("Error al eliminar:", error);
                alert("No se pudo eliminar el plan.");
            }
        }
    });
});
