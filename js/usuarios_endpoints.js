async function iniciarSesion(nombre, contrasena) {
    try {
        const response = await fetch('api/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ nombre: nombre, contrasena: contrasena })
        });

        // 1. Validar primero si la respuesta fue exitosa
        if (response.ok) {
            const data = await response.json(); // Solo hacemos .json() si el servidor respondió bien
            alert("¡Bienvenido! Redirigiendo...");
            window.location.href = 'index.php'; 
        } else {
            // 2. Si hay un error (como el 500), leemos el error como texto para inspeccionarlo
            const errorTexto = await response.text();
            console.error("Detalle del error del servidor:", errorTexto);
            alert(`Error en el servidor (${response.status}). Revisa la consola.`);
        }

    } catch (error) {
        console.error("Error en la petición:", error);
        alert("No se pudo conectar con el servidor.");
    }
}

document.getElementById('form-login').addEventListener('submit', function(event) {
    // 1. Evita imperativamente que la página se recargue por defecto
    event.preventDefault(); 

    // 2. Captura los valores de los campos utilizando los IDs de tu HTML
    const usuarioValue = document.getElementById('input-usuario').value;
    const contrasenaValue = document.getElementById('input-contrasena').value;

    // 3. Ejecuta tu función asíncrona pasándole los datos capturados
    iniciarSesion(usuarioValue, contrasenaValue);
});