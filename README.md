
# Guía de Instalación y Configuración del Servidor

## Pasos de instalación

### 1. Actualizar el sistema
Actualiza el índice de paquetes y los paquetes del sistema a la última versión:
```bash
sudo apt update
sudo apt upgrade -y
```

### 2. Instalar el servidor web Apache
Instala **Apache** con el siguiente comando:
```bash
sudo apt install apache2 -y
```

Comprueba que el servicio está activo y funcionando correctamente:
```bash
sudo systemctl status apache2
```

### 3. Instalar MariaDB (Base de datos)
Instala el servidor y el cliente de **MariaDB**:
```bash
sudo apt install mariadb-server mariadb-client -y
```

Ejecuta el script de seguridad para configurar una contraseña de *root* y proteger la instalación:
```bash
sudo mariadb_secure_installation
```

### 4. Instalar PHP y módulos
Instala **PHP** junto con los módulos necesarios para Apache y MariaDB:
```bash
sudo apt install php libapache2-mod-php php-mysql -y
```

Reinicia Apache para aplicar los cambios de PHP:
```bash
sudo systemctl restart apache2
```

### 5. Probar el funcionamiento
Crea un archivo de prueba en la raíz del servidor web para verificar que PHP responde:
```bash
echo "<?php phpinfo(); ?>" | sudo tee /var/www/html/info.php
```

> [!INFO] **Comprobación**
> Comprueba que todo ha salido bien accediendo desde tu navegador a la siguiente dirección:
> `http://<IP-de-tu-servidor>/info.php`
> En caso de que este en NAT haz esto:
>`ssh nombre_usuario@127.0.0.1 -p puerto`

---

## Bases de datos

### 1. Importar el fichero SQL
Introduce el fichero `.sql` para poder crear el usuario, la base de datos y asignarle todos los permisos sobre ella:
```bash
sudo mariadb < create-db.sql
```

IMPORTANTE hay que añadir la siguiente linea antes del FLUSH PRIVILEGES para poder loguearse como localhost también.

```SQL
GRANT ALL PRIVILEGES ON PaiportArbolado.* TO 'user_bd'@'localhost';
```
### 2. Comprobar la creación del usuario y la base de datos

#### 2.1 Entrar como root
Accede a la consola de comandos de MariaDB:
```bash
sudo mariadb -u root -p
```

#### 2.2 Comprobar el usuario
Escribe la siguiente consulta para saber si el usuario se ha creado correctamente:
```sql
SELECT User, Host FROM mysql.user;
```

#### 2.3 Comprobar la base de datos
Escribe el siguiente comando para ver si aparece la nueva base de datos en la lista:
```sql
SHOW DATABASES;
```

#### 2.4 Introducir la tabla en la base de datos con el usuario que se ha creado
Escribe el siguiente commando:

```sql
mariadb -u user_bd -p PaiportArbolado < populate-sql.sql
```

Para saber si se ha metido correctamente escribe el siguiente comando:
```sql
mariadb -u user_bd -p -e "SHOW TABLES IN PaiportArbolado;"
```

```sql
+---------------------------+  
| Tables_in_PaiportArbolado |  
+---------------------------+  
| arboles                   |  
+---------------------------+
```


### 3. Solucionar error 500 al abrir index.php en la carpeta /var/www/html

Dar propiedades al usuario y al servidor web.
```bash
sudo chown -R $USER:www-data /var/www/html
```

Asignar permiso de escritura y lectura para poder guardar cambios.
```bash
sudo chmod -R 775 /var/www/html
```

Ahora lo que hay que hacer es movero copiar los ficheros que estan en PaiportArbolado con el siguiente comando
```bash
cp -r /var/www/PaiportArbolado/* /var/www/html/
```
 y hora al cargar tiene que aparecer la pagina web
 http://localhost:8080/index.php
 
### 4. Añadir el fichero actions.log
En el directorio PaiportaArbolada hay que crear una carpeta llamada logs y dentro de ella crear el fichero actions.log y que la estructura se vea tal que así.

```bash
.  
├── README.md  
├── config.php  
├── crear.php  
├── create-db.sql  
├── css  
│   └── style.css  
├── editar.php  
├── eliminar.php  
├── index.php  
├── js  
│   └── script.js  
├── logs  
│   └── actions.log  
└── populate-sql.sq
```

### 5. Añadir campo usuario_registro en index.php
No aparece el campo usuario_registro lo añadi para que aparezca el campo de la tabla y el valor.

```html
<table border="1">

<tr>

<th>ID</th>

<th>Especie</th>

<th>Ubicación</th>

<th>Fecha Plantación</th>

<th>Estado</th>

<th>Registro Usuario</th>

<th>Acciones</th>

</tr>

<?php while ($row = $result->fetch_assoc()): ?>

<tr>

<td><?= $row['id'] ?></td>

<td><?= htmlspecialchars($row['especie']) ?></td>

<td><?= htmlspecialchars($row['ubicacion']) ?></td>

<td><?= $row['fecha_plantacion'] ?></td>

<td><?= $row['estado'] ?></td>

<td><?= $row['usuario_registro'] ?></td>

<td>

<a href="editar.php?id=<?= $row['id'] ?>">Editar</a>

<a href="eliminar.php?id=<?= $row['id'] ?>" onclick="return confirm('¿Eliminar este árbol?')">Eliminar</a>

</td>

</tr>

<?php endwhile; ?>

</table>
```

### 6. Fichero crear.php
Hay que añadir el campo de estado que es un enum.

6.1 Obtener los estados

```php
$estados_enum = [];

$result = $conn->query("SHOW COLUMNS FROM arboles LIKE 'estado'");

  

if ($result && $row = $result->fetch_assoc()) {

$type = $row['Type'];

if (preg_match("/^enum\((.*)\)$/", $type, $matches)) {

$estados_enum = explode(",", str_replace("'", "", $matches[1]));

}

}
```

Luego se añade la variable para poder hacer el POST

```php
$estado = $conn->real_escape_string($_POST['estado']);
```

Y por ultimo se añade el campo de los estados en la parte de html.
```html
<label>Estado:</label>

<select name="estado" required>

<option value="">-- Selecciona un estado --</option>

<?php

if (!empty($estados_enum)) {

foreach ($estados_enum as $opcion) {

echo "<option value='" . $opcion . "'>" . ucfirst($opcion) . "</option>";

}

} else {

echo "<option value=''>No se pudieron cargar los estados</option>";

}

?>

</select><br>
```


### 7. El buscador de index.php no busca los arboles
El buscador no busca arboles ya que en esta parte la funcion se llama buscarArboles() pero en ./js/script.js la funcion se llama searchTrees() solo hay que cambiar el nombre de la funcion en scripts y ya funcionará el buscador.

Antes
```js
function searchTrees() {

const input = document.getElementById('buscar').value.toLowerCase();

const rows = document.querySelectorAll('table tr\:not(\:first-child)');

  

rows.forEach(row => {

const text = row.textContent.toLowerCase();

row.style.display = text.includes(input) ? '' : 'none';

});

}
```

Despues
```js
function buscarArboles() {

const input = document.getElementById('buscar').value.toLowerCase();

const rows = document.querySelectorAll('table tr\:not(\:first-child)');

  

rows.forEach(row => {

const text = row.textContent.toLowerCase();

row.style.display = text.includes(input) ? '' : 'none';

});

}
```


### 8. Añadir fotografias

Primero hay que añadir un campo mas a la tabla arboles:
```sql
ALTER TABLE arboles ADD imagen varchar(255);
```

8.1 Añadir campo nuevo en  crear.php para poder subir imagenes.

Añadir campo input en su su respectivo lugar:
```php
<label>Añadir imagen:</label>
<input type="file" name="imagen" required><br>
```

8.2 Modificar crear.php
Hay que añadir este comando para poder añadir la imagen
```php
$ruta_db = "";

  

if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {

$dir_destino = "./arboles_paiporta/uploads/";

if (!is_dir($dir_destino)) {

mkdir($dir_destino, 0755, true);

}

  

$nombre_archivo = time() . "_" . basename($_FILES['imagen']['name']);

$ruta_destino = $dir_destino . $nombre_archivo;

  

if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {

  

$ruta_db = $conn->real_escape_string($ruta_destino);

} else {

echo "Error al mover el archivo a la carpeta de destino.";

exit();

}

} else {

echo "Error en la subida del archivo.";

exit();

}

$sql = "INSERT INTO arboles (especie, ubicacion, fecha_plantacion, estado, imagen, usuario_registro)

VALUES ('$especie', '$ubicacion', '$fecha', '$estado', '$ruta_db','$usuario')";
```

8.3 Modificar parte en index.php para mostrar imagenes

Añadir o modificar el campo imagen
Antes
```php
<td><?= !empty($row['imagen']) ? $row['imagen'] : 'Sin imagen' ?></td>
```

Despues
```php
<td>

<?php if (!empty($row['imagen']) && file_exists($row['imagen'])): ?>

<img src="<?= htmlspecialchars($row['imagen']) ?>" alt="Miniatura de <?= htmlspecialchars($row['especie']) ?>" style="width: 80px; height: auto; border-radius: 4px;">

<?php else: ?>

<span>Sin imagen</span>

<?php endif; ?>

</td>
```

8.4 Hacer que la imagen se muestre en editar.php

Imagen actual
```php
$ruta_imagen_bd = $arbol['imagen']; 
```
Guarda en una variable la ruta de la imagen actual del árbol obtenida de la base de datos.

Subida de la nueva imagen
```php
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $carpeta_destino = './arboles_paiporta/uploads/';
    $nombre_original = basename($_FILES['imagen']['name']);

    $nombre_final = time() . "_" . str_replace(" ", "", $nombre_original);
    $fichero_subido = $carpeta_destino . $nombre_final;

    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $fichero_subido)) {
        $ruta_imagen_bd = $fichero_subido;
    } else {
        echo "Error al guardar la imagen en el servidor.";
    }
}
```

Comprueba si se ha subido un archivo mediante el formulario, le genera un nombre único usando `time()`, elimina los espacios en blanco, lo mueve a la carpeta de destino (`./arboles_paiporta/uploads/`) y actualiza la variable `$ruta_imagen_bd`.


Hacer que la imagen sea de tamaño mediano:
```css
.img-mediana {
    max-width: 300px;
    height: auto;
    display: block;
    margin: 10px 0 20px 0;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}
```


Atributo obligatorio para subir archivos en el formulario:
```html
<form method="POST" enctype="multipart/form-data">
```
El atributo `enctype="multipart/form-data"` es indispensable para que el formulario pueda enviar archivos (como imágenes) al servidor.

Mapeo y visualización de la imagen actual:
```php
<label>Imagen del Árbol:</label><br>

<?php if (!empty($arbol['imagen'])): ?>
    <img id="vista-previa" src="<?= htmlspecialchars($arbol['imagen']) ?>" alt="Imagen del árbol" class="img-mediana">
<?php else: ?>
    <img id="vista-previa" src="" alt="Vista previa" class="img-mediana" style="display: none;">
    <p id="sin-imagen-texto" style="color: gray; font-style: italic;">No hay imagen disponible para este árbol.</p>
<?php endif; ?>

```
Si el árbol ya tiene una ruta de imagen asignada en la base de datos, la muestra. Si no, oculta la etiqueta de imagen y muestra el texto _"No hay imagen disponible para este árbol".

Campo de selección de archivo:
```html
<label>Cambiar Imagen:</label>
<input type="file" id="input-imagen" name="imagen" accept="image/*"><br><br>
```
El botón de tipo `file` para que el usuario seleccione un archivo desde su dispositivo. El atributo `accept="image/*"` limita la selección solo a archivos de imagen.

Previsualización en tiempo real:
```js
document.getElementById('input-imagen').addEventListener('change', function(event) {
    const archivo = event.target.files[0];

    if (archivo) {
        const lector = new FileReader();

        lector.onload = function(e) {
            const imgPreview = document.getElementById('vista-previa');
            const txtNoImage = document.getElementById('sin-imagen-texto');

            imgPreview.src = e.target.result;
            imgPreview.style.display = 'block';

            if (txtNoImage) {
                txtNoImage.style.display = 'none';
            }
        }

        lector.readAsDataURL(archivo);
    }
});
```
Escucha cuando el usuario selecciona un nuevo archivo local, lo lee instantáneamente sin recargar la página utilizando `FileReader` y actualiza dinámicamente la etiqueta de la imagen (`#vista-previa`) para mostrar la nueva foto antes de enviar el formulario.

### 9. Hacer llamadas con endpoints

1. Crear el directorio api/ para pode hacer las consultas
2. crear en js/ el script endpoints.js para poder usar fetch

### 10. Parte usuario

10.1 Crear tabla usuarios y introducirla en PaiportArbolado

```sql
USE PaiportArbolado;

CREATE TABLE usuarios (
	id INT AUTO_INCREMENT PRIMARY KEY,
	nombre VARCHAR(100) NOT NULL,
	contrasena VARCHAR(255) NOT NULL,
	fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

Introducir la tabla a la base de datos
```bash
mariadb -u user_bd -p PaiportArbolado < usuarios.sql
```

Insertar usuario
```sql
INSERT INTO usuarios (nombre, contrasena) VALUES ('alvaro','$2a$12$ZliSZiiNBRvPIif1KZe4/Ovqvre3pAlOJkgqQf/5Oichw5RpxkkoW');
```

Crear api/login.php para hacer la consultas , crear js/usuarios_endpoints.js para hacer las llamadas y login.php para poder loguearse.