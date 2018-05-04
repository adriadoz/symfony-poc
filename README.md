# Grupo 3 - Taller de Symfony

El equipo consta de 3 integrantes:
- Mario
- Adrià
- Alex

## Sesión 1
### Requerimiento: Instalación de Symfony 4.0.9
Para instalar symfony, se requiere composer instalado o en su defecto un compilado de [composer.phar][1], como grupo optamos con ejecutar un binario compilado, v1.6.3 y así tener cada uno en su maquina la misma versión de composer.

Para realizar la instalación de Symfony, se nos presentaron dos alternativas.
website-skeleton y skeleton, cuya diferencia radica en los módulos preinstalados, el primero como lo indica esta pensado para el desarrollo de un sitio web, por lo cual incluye por defecto módulos para esa función.

Comando por defecto para instalación de symfony 4.
`composer create-project symfony/website-skeleton my-project`

En nuestro caso
`php composer.phar create-project symfony/skeleton symfony_g3`

La instalación se realizo sobre el repositorio creado para propósitos del equipo: repositorio en [bitbucket][2], desde la siguiente [maquina virtual.][3]

### Requerimiento: Hola Mundo
Se desarrolla un controlador en la carpeta `src/Controller` llamado `MainController.php`, el controlador cuenta con una clase la cual extiende de los controladores de symfony.

En el controlador se creo un método, el cual retorna un string html con el mensaje solicitado.

Para crear la ruta al controlador, encontramos dos alternativas.
1. Rutas por comentario
2. Rutas por configuración

Ambas cubren nuestra necesidad, pero luego de un consenso, llegamos a la conclusión de utilizar la primera opción, debido a que un archivo de configuración puede crecer haciéndolo poco legible y causar conflictos si un método cambia de nombre. Ademas, es mas rápido leer en los comentarios la ruta y función del método especifico.

Para utilizar rutas por comentarios, se instalo la receta annotations desde composer.

`php composer.phar require annotations`

## Sesión 2
### Diferentes Entornos
- Se ha creado un nuevo entorno de trabajo _prod_ en la carpeta `config/packages/` el cual es configurarle desde la parametrización global en **.env**

- Se imprime por pantalla, al usuario el mensaje **Hola `dev|prod` World** dependiendo del entorno en que se encuentre.
Para el correcto funcionamiento de esto, se instala la dependencia "symfony/dotenv" de flex.

- se pasan los entornos por parametros customizando los resultados.

llamar a http://localhost:8000 para mostrar en nombre custom del environment o http://localhost:8000 para el environment de .env

- Se instala una receta de symfony flex - symfony/var-dumper

### Entornos personalizados
Con los entornos _prod_ y _dev_ ya configurados, se agregan en sus respectivas carpetas `config/packages/dev` y `config/packages/prod` un archivo llamado `parameters.yaml` encargado de gestionar los parámetros de nombre para cada entorno `ganianes_dev`y `not_fail` los cuales, luego son enviados al controlador por la inyección de servicios desde `config/services.yaml` argumentando los parámetros del constructor de la siguiente manera.

	App\Controller\MainController:
	        arguments: ['%kernel.environment%', '%environment.name%']

Donde, el primer argumento, captura el entorno actual del usuario y el segundo, el nombre personalizado de ese entorno. Para acceder al nombre personalizado, se puede hacer a travez del método `showHelloCustom` en el controlador `MainController` desde la ruta [http://localhost:8000/custom][4]

## Sesión 3
### Gestionar Log de los entornos.
Utilizando la receta **MonoLog**, se configuran la gestión de logs y se configuran los entornos desde monolog.yaml en sus respectivas carpetas.

### Mensaje de warning, al acceder a Hello World
En symfony 4, al tener configurado nuestros servicios como `autowire: true` podemos inyectar dependencias automáticamente, por lo cual se agrega la dependencia al constructor de nuestro controlador, para gestionar los log (`LoggerInterface $logger`).

Luego se crea un evento de warning al ejecutar el método principal de saludo.

	$this->logger->info('InfoLogger');
	$this->logger->warning('WarningLogger');

### Error en un get incompleto y errores solo en prod.
Se gestiona mostrar un error en caso de que exista un parametro get `?bum`  en la url, el cual no contengo información.

	if ($request->query->has('bum')) {
	    $this->logger->error('ErrorLogger');
	}

Luego se crea un filtro en `config/packages/prov/monolog.yaml` para guardar los los, solo cuando se produzca un error.


#Capítulo 5 reto 2/3

Cuando se desactiva `autoconfigure: false`, nos vemos obligados a configurar el servicio por medio de etiquetas, por ejemplo en el caso de comando, debemos indicar que es un comando de consola y que comando lo ejecuta.

Cuando se desactiva `autowire: false`, las inyección de constructores, deben ser especificadas por argumentos en el servicio.
