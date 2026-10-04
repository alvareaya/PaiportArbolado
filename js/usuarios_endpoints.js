async function iniciarSesion(nombre, contrasena) {
    try {

        const response = await fetch('api/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ nombre: nombre, contrasena: contrasena })
        });

        const data = await response.json();

        if (response.ok) {
            alert("¡Bienvenido! Redirigiendo...");
            window.location.href = 'index.php'; 
        } else {
            alert("Error: " + data.error);
        }

    } catch (error) {
        console.error("Error en la petición:", error);
        alert("No se pudo conectar con el servidor.");
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const formulario = document.getElementById('form-login');
    
    if (formulario) {
        formulario.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const nombre = document.getElementById('input-usuario').value;
            const contrasena = document.getElementById('input-contrasena').value;
            
            await iniciarSesion(nombre, contrasena);
        });
    }
});
