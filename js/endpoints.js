document.addEventListener("DOMContentLoaded", () => {
    if (document.getElementById('tabla-arboles')) {
        cargarArboles();
    }
    
    if (document.getElementById('form-crear')) {
        cargarEstados();
        crearArbol();
    }

    if (document.getElementById('form-editar')) {
        cargarDatosEditar();
        editarArbol();
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

function cargarDatosEditar() {
    const formulario = document.getElementById("form-editar");
    if (!formulario) return;

    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');

    if (!id) {
        alert("ID de árbol no especificado.");
        window.location.href = 'index.php';
        return;
    }

    cargarEstados();

    fetch(`api/editar_arboles.php?id=${id}`)
        .then(res => {
            if (!res.ok) {
                return res.text().then(text => { throw new Error(text) });
            }
            return res.json();
        })
        .then(arbol => {
            if (arbol.error) {
                alert(arbol.error);
                window.location.href = 'index.php';
                return;
            }

            if(formulario.querySelector("[name='especie']")) formulario.querySelector("[name='especie']").value = arbol.especie || '';
            if(formulario.querySelector("[name='ubicacion']")) formulario.querySelector("[name='ubicacion']").value = arbol.ubicacion || '';
            if(formulario.querySelector("[name='fecha_plantacion']")) formulario.querySelector("[name='fecha_plantacion']").value = arbol.fecha_plantacion || '';
            if(formulario.querySelector("[name='estado']")) formulario.querySelector("[name='estado']").value = arbol.estado || '';
            if(formulario.querySelector("[name='usuario']")) formulario.querySelector("[name='usuario']").value = arbol.usuario_registro || '';

            // Control dinámico de la miniatura de la imagen
            const imgPreview = document.getElementById("vista-previa");
            const txtSinImagen = document.getElementById("sin-imagen-texto");

            if (imgPreview) {
                if (arbol.imagen && arbol.imagen.trim() !== "") {
                    imgPreview.src = arbol.imagen;
                    imgPreview.style.display = "block";
                    if (txtSinImagen) txtSinImagen.style.display = "none";
                } else {
                    imgPreview.style.display = "none";
                    if (txtSinImagen) txtSinImagen.style.display = "block";
                }
            }
        })
        .catch(err => {
            console.error("Error crítico al cargar los detalles del árbol:", err);
            alert("No se pudieron cargar los datos del árbol.");
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


//PUT
function editarArbol() {
    const formulario = document.getElementById("form-editar");
    if (!formulario) return;

    formulario.addEventListener("submit", function(e) {
        e.preventDefault();

        const urlParams = new URLSearchParams(window.location.search);
        const id = urlParams.get('id');

        const formData = new FormData(this);
        formData.append('id', id);

        fetch('api/editar_arboles.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(data.mensaje);
                window.location.href = 'index.php';
            } else {
                alert("Error al guardar cambios: " + data.error);
            }
        })
        .catch(err => {
            console.error("Error en la petición de actualización:", err);
            alert("Ocurrió un error en el servidor al guardar la edición.");
        });
    });
}

function escaparHTML(str) {
    return str.replace(/[&<>'"]/g, 
        tag => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#39;', '"': '&quot;' }[tag] || tag)
    );
}
