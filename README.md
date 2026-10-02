
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
### Añadir el fichero actions.log
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
