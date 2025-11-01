document.getElementById("ciudad").addEventListener("change", function () {
    const ciudad = this.value;
    const tbody = document.getElementById("tablaRutas");

    const cacheKey = `rutas_json_${ciudad}`;
    const cacheTimeKey = `rutas_json_time_${ciudad}`;
    const ahora = new Date().getTime();

    const cachedData = localStorage.getItem(cacheKey);
    const cachedTime = localStorage.getItem(cacheTimeKey);

    if (cachedData && cachedTime && ahora - cachedTime < 300000) {
        console.log("Usando datos cacheados:", JSON.parse(cachedData));
        renderTabla(JSON.parse(cachedData));
        return;
    }

    tbody.innerHTML =
        '<tr><td colspan="5" class="text-center py-4">Cargando...</td></tr>';

    fetch(`/horarios/ajax?ciudad=${encodeURIComponent(ciudad)}`)
        .then((res) => {
            if (!res.ok) {
                throw new Error("Error en la respuesta del servidor");
            }
            return res.json();
        })
        .then((data) => {
            console.log("Datos recibidos del servidor:", data);

            // Guardar en cache
            localStorage.setItem(cacheKey, JSON.stringify(data));
            localStorage.setItem(cacheTimeKey, ahora.toString());

            renderTabla(data);
        })
        .catch((err) => {
            console.error("Error:", err);
            tbody.innerHTML =
                '<tr><td colspan="5" class="text-center py-4 text-red-500">Error al cargar rutas</td></tr>';
        });

    function renderTabla(data) {
        console.log("Renderizando tabla con datos:", data);
        tbody.innerHTML = "";

        if (!data || data.length === 0) {
            tbody.innerHTML =
                '<tr><td colspan="5" class="text-center py-4">No hay rutas disponibles para esta ciudad</td></tr>';
            return;
        }

        const ahora = new Date();
        const horaActual = ahora.getHours();
        const minutoActual = ahora.getMinutes();

        data.forEach((ruta) => {
            console.log("Procesando ruta:", ruta);

            // Parsear la hora formateada (ej: "8:30 AM")
            const [horaMin, ampm] = ruta.salida_formato.split(" ");
            let [hora, minuto] = horaMin.split(":").map(Number);

            // Convertir a formato 24 horas
            let hora24 = hora;
            if (ampm === "PM" && hora !== 12) {
                hora24 += 12;
            }
            if (ampm === "AM" && hora === 12) {
                hora24 = 0;
            }

            // Determinar estado comparando con hora actual
            let estado = "A tiempo";
            let colorPunto = "bg-green-500";

            if (
                hora24 < horaActual ||
                (hora24 === horaActual && minuto < minutoActual)
            ) {
                estado = "Salió";
                colorPunto = "bg-red-500";
            }

            // Crear la fila de la tabla
            const fila = document.createElement("tr");
            fila.className = "hover:bg-gray-50 transition-colors";
            fila.innerHTML = `
                <td class="px-6 py-4 text-sm text-gray-600">${
                    ruta.destino || "N/A"
                }</td>
                <td class="px-6 py-4 text-sm text-gray-900">${
                    ruta.precio || "0"
                } C$</td>
                <td class="px-6 py-4 text-sm text-gray-600">${
                    ruta.salida_formato || "N/A"
                }</td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        ${ruta.tipo || "Estándar"}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-1.5">
                        <div class="w-2 h-2 ${colorPunto} rounded-full"></div>
                        <span class="text-sm text-gray-700">${estado}</span>
                    </div>
                </td>
            `;

            tbody.appendChild(fila);
        });
    }
});
