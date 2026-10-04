document.addEventListener("DOMContentLoaded", () => {
    if (document.getElementById('tabla-arboles')) {
        cargarArboles();
    }
    
    if (document.getElementById('form-crear')) {
        cargarEstados();
        crearArbol();
    }
});

//GET
function cargarArboles() {
    fetch('api/arboles.php')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('tabla-arboles');
            tbody.innerHTML = '';

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


function cargarEstados() {
    const selectEstado = document.getElementById("select-estado");
    if (!selectEstado) return;

    fetch("api/estados.php") 
        .then(res => res.json())
        .then(estados => {
            if (estados.length > 0) {
                estados.forEach(estado => {
                    const option = document.createElement("option");
                    option.value = estado;
                    option.textContent = estado.charAt(0).toUpperCase() + estado.slice(1);
                    selectEstado.appendChild(option);
                });
            } else {
                selectEstado.innerHTML = "<option value=''>No se pudieron cargar los estados</option>";
            }
        })
        .catch(err => {
            console.error("Error cargando estados:", err);
            selectEstado.innerHTML = "<option value=''>Error al conectar con la API</option>";
        });
}

//POST
function crearArbol() {
    const formulario = document.getElementById("form-crear");
    if (!formulario) return;

    formulario.addEventListener("submit", function(e) {
        e.preventDefault(); 

        const formData = new FormData(this);

        fetch('api/crear_arboles.php', {
            method: 'POST',
            body: formData 
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.mensaje);
                window.location.href = 'index.php'; 
            } else {
                alert("Error al crear: " + data.error);
            }
        })
        .catch(error => {
            console.error("Error en la petición de guardado:", error);
            alert("Ocurrió un error en el servidor al intentar guardar.");
        });
    });
}

function escaparHTML(str) {
    return str.replace(/[&<>'"]/g, 
        tag => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#39;', '"': '&quot;' }[tag] || tag)
    );
}
