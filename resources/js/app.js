import "./bootstrap";
function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("overlay");

    sidebar.classList.toggle("-translate-x-full");
    overlay.classList.toggle("hidden");
}

document.querySelectorAll("#sidebar a").forEach((link) => {
    link.addEventListener("click", () => {
        if (window.innerWidth < 1024) toggleSidebar();
    });
});

document
    .getElementById("btnHamburger")
    .addEventListener("click", toggleSidebar);
document.getElementById("close").addEventListener("click", toggleSidebar);



document.getElementById("ciudad").addEventListener("change", function () {
    const ciudad = this.value;
    const tbody = document.getElementById("tablaRutas");

    const cacheKey = `rutas_json_${ciudad}`;
    const cacheTimeKey = `rutas_json_time_${ciudad}`;
    const ahora = new Date().getTime();

    const cachedData = localStorage.getItem(cacheKey);
    const cachedTime = localStorage.getItem(cacheTimeKey);

    if (cachedData && cachedTime && (ahora - cachedTime < 300000)) { // 5 minutos
        renderTabla(JSON.parse(cachedData));
        return;
    }

    tbody.innerHTML =
        '<tr><td colspan="6" class="text-center py-4">Cargando...</td></tr>';

    fetch(`/horarios/ajax?ciudad=${ciudad}`)
        .then((res) => res.json())
        .then((data) => {
            // Guardar en cache
            localStorage.setItem(cacheKey, JSON.stringify(data));
            localStorage.setItem(cacheTimeKey, ahora);

            renderTabla(data);
        })
        .catch((err) => {
            tbody.innerHTML =
                '<tr><td colspan="6" class="text-center py-4 text-red-500">Error al cargar rutas</td></tr>';
            console.error(err);
        });

    function renderTabla(data) {
        tbody.innerHTML = "";
        if (data.length === 0) {
            tbody.innerHTML =
                '<tr><td colspan="6" class="text-center py-4">No hay rutas</td></tr>';
            return;
        }

        const ahora = new Date();

        data.forEach((ruta) => {
            const salida = new Date();
            const [horaMin, ampm] = ruta.salida_formato.split(" ");
            let [hora, minuto] = horaMin.split(":").map(Number);

            if (ampm === "PM" && hora !== 12) hora += 12;
            if (ampm === "AM" && hora === 12) hora = 0;
            salida.setHours(hora, minuto, 0, 0);

            const estado = ahora > salida ? "Salió" : "A tiempo";
            const colorPunto = ahora > salida ? "bg-red-500" : "bg-green-500";

            tbody.innerHTML += `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-4 text-sm text-gray-600">${ruta.destino}</td>
                    <td class="px-4 py-4 text-sm text-gray-900">${ruta.precio}C$</td>
                    <td class="px-4 py-4 text-sm text-gray-600">${ruta.salida_formato}</td>
                    <td class="px-4 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            ${ruta.tipo ?? "tipo no disponible"}
                        </span>
                    </td>
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-1.5">
                            <div class="w-2 h-2 ${colorPunto} rounded-full"></div>
                            <span class="text-sm text-gray-700">${estado}</span>
                        </div>
                    </td>
                </tr>
            `;
        });
    }
});



