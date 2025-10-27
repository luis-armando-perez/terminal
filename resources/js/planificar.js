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

