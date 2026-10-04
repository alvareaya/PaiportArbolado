document.addEventListener("DOMContentLoaded", cargarArboles);

function cargarArboles() {
    // Hacemos la petición fetch al endpoint que creamos
    fetch('api/arboles.php')
        .then(response => response.json()) // Convertimos la respuesta a objeto JS
        .then(data => {
            const tbody = document.getElementById('tabla-arboles');
            tbody.innerHTML = ''; // Limpiamos la tabla por si acaso

            if (data.error) {
                console.error(data.error);
                return;
            }

            // Iteramos sobre cada árbol devuelto por la API
            data.forEach(arbol => {
                const tr = document.createElement('tr');

                // Validamos la imagen de forma similar a como lo hacías en PHP
                const imagenHTML = arbol.tiene_imagen 
                    ? `<img src="${arbol.imagen}" alt="Miniatura" style="width: 80px; height: auto; border-radius: 4px;">`
                    : `<span>Sin imagen</span>`;

                // Construimos las celdas usando Template Literals
                tr.innerHTML = `
                    <td>${arbol.id}</td>
                    <td>${escaparHTML(arbol.especie)}</td>
                    <td>${escaparHTML(arbol.ubicacion)}</td>
                    <td>${arbol.fecha_plantacion}</td>
                    <td>${arbol.estado}</td>
                    <td>${imagenHTML}</td>
                    <td>${arbol.usuario_registro}</td>
                    <td>
                        <a href="editar.php?id=${arbol.id}">Editar</a>
                        <a href="eliminar.php?id=${arbol.id}" onclick="return confirm('¿Eliminar este árbol?')">Eliminar</a>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(error => console.error("Error al obtener los datos:", error));
}

function escaparHTML(str) {
    return str.replace(/[&<>'"]/g, 
        tag => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#39;', '"': '&quot;' }[tag] || tag)
    );
}
