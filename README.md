# Grupo 3 - Taller de Symfony

El equipo consta de 2 integrantes:
 
 - Adrià
 - Mario

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

Luego se crea un filtro en `config/packages/prod/monolog.yaml` para guardar los logs, solo cuando se produzca un error.

### Almacenar log en Json
En monolog.yaml, se crea un nuevo fichero con extensión .json y se le indica que le de formato con la siguiente instrucción `formatter: json_log`

### Rotar los log y almacenar los últimos 15 días
Se cambia el tipo de archivo generado, de `stream` a `rotating_file` y se le indica un máximo de archivos generados `max_files: 15`a 15 (equivalente a 15 días.

### Escribir contenida extra en errores.
Se puede enviar un array con datos adicionales, como por ejemplo el id del usuario que genero el error, para ellos los log reciben dos parámetros. `texto, array` 

## Sesión 4 - Console
La consola, permite ejecutar sentencias o comandos personalizados. Los comandos de la consola se pueden usar para cualquier tarea recurrente, como cronjobs, importaciones u otros trabajos por lotes.

Para la instalación se usa la receta `php composer.phar require symfony/console`

### Estructura de directorio
Antes de proseguir con la sesión 4, se reorganizaron los directorios, para alcanzar la arquitectura hexagonal y se personaliza el namespace correspondiendo al nombre del equipo.

### Console
La consola, es una clase que extiende de Command y pese a no exigir, requiere dos métodos básicos para el funcionamiento

`protected function configure()` principalmente se configura los datos del comando de linea.

Para el Hello World, se utiliza la siguiente configuración

		$this
			->setName('message:hello')
			->setDescription('Hello World')
			->setHelp('say Hello World. . .');

Y `protected function execute(InputInterface $input, OutputInterface $output)` 

la encargada de ejecutar las instrucciones solicitadas, recibe dos parámetros. `InputInterface` y `OutputInterface`

### Leer un archivo JSON como una entrada de dominio e imprimir las instancias como objetos.

Se instala la receta de symfony `symfony/serializer`

Con ayuda de finder, se lee el directorio de log completo, en este caso recurrimos a llamar solo al día.

Problemas: cada linea es un json, por lo cual hay que leer el fichero linea a linea, decodificarlo y luego codificarlo nuevamente antes de serializarlo.

Serialize, permite codificar en una clase la información de un json.

### Seleccionar el entorno a imprimir por pantalla especificándolo como argumento

Se añade la configuración siguiente `->addArgument('environment', InputArgument::REQUIRED, 'Enter a environment to show')` para forzar al usuario a especificar mediante un argumento el entorno del que desea ver los errores.

A la funcion `getTodayLog` se le pasa por parametro el entorno seleccionado. Se comprueba si es `prod` o `dev`.

### Filtrar los niveles de errores a imprimir por pantalla especifincándolos como argumento

Se añade una configuración a la método `configure` de la clase `LogReadCommand` para recojer la cadena que contiene los niveles a filtrar.

La cadena introducida será tratada en la función `getEnteredLevels` que devolverá un array con los distintos niveles.

En los métodos `print` y `printSummary` se verificarán que los errores a mostrar existen en el array de niveles introducido por comando.

### Si el nivel o entorno no ha sido especificado se le preguntará al usuario

Haciendo uso de `use Symfony\Component\Console\Question\Question` le preguntamos al usuario los datos que no ha introducido.

		`if(empty($enteredEnvironment)){
			 $question = new Question('Please enter a environment to show: ', 'dev');
			 $enteredEnvironment = $helper->ask($input, $output, $question);
		 }`

En el método `execute` comprobamos si el argumento está vacio, y en caso afirmativo preguntamos al usuario que especifique el entorno.

Repetimos el mismo procedimiento para especificar los niveles de errores a filtrar.

##Sesión 5 - Dependency Injection Container

### Encapsular la lógica de dominio del log summary console command

Se crea la clase del caso de uso para el comando que devuelve el resumen de logs. El hecho de que los datos se devuelvan por la consola, no tiene nada que ver con la lógica de dominio. Por eso se hace un refactor sobre `LogSummary` y se divide la lógica de negocio en `LogSummaryGetter` y la representación de los datos por consola en la clase `LogSummaryConsole`.

Inyectamos el servicio del caso de uso en el controlador del console command usando DIC con los flags a false:

Cuando se desactiva `autoconfigure: false`, nos vemos obligados a configurar el servicio por medio de etiquetas, por ejemplo en el caso de comando, debemos indicar que es un comando de consola y que comando lo ejecuta.

Cuando se desactiva `autowire: false`, las inyección de constructores, deben ser especificadas por argumentos en el servicio.

### Reactivar el flag de autoconfigure
Cuando se activa `autoconfigure: true` podemos eliminar los tags:

		tags: - { 
					name: 'console.command', 
					command: 'log:summary' 
				 }

Cuando se activa `autowire: true` podemos eliminar la inyección del servicio:

		arguments: 
				-$sayMessage: 
							'@G3\FrameworkPractice\Application\MessageCommand\SayHello'`

### Implementar un servicio que recibe todos los casos de uso definidos en la app
Creamos la clase `UseCaseSearcherConsole` que recibe un array de las instancias de servicios.
A continuación lo modificamos para que de forma dinámica obtenga los serivicios taggeados `$container->findTaggedServiceIds('g3.use_case')`
Para finalizar lo haremos de forma dinámica usando el compiler pass.

##Sesión 6 - Routing API HTTP
###Implementar un HTTP endpoint que devuelva los logs en formato JSON
Se añade una nueva ruta en yaml para que /logs devuelva los logs en formato JSON
		 
		 log_api
		- path: /logs
		- controller: G3\FrameworkPractice\Infrastructure\Controller\LogApiController::__invoke
		- methods: [GET]

A continuación implementamos la ruta usando annotations añadiendo un comentario en el controlador:
	 ` @Route("/logs", name="log_api") @Method({"GET"})`
 
Filtramos todos los métodos que no sean GET llamando al método del controlador `LogApiController` que devuelve un 405.
Cambiamos la ruta de la API a `log-summaries` indicando el entorno `/dev` y permitiendo filtrar mediante un parámetro en la misma URL.
En el yaml `log-api` añadimos el siguiente path `path: /log-summaries/{environment} defaults: environment: dev` para indicar el entorno.
Finalmente eliminamos todas las annotations routes para dejar las rutas en el yaml, tal y como haciamos desde el principio.

###Implementar un endpoint que inserte logs desde un servidor externo
Hemos añadido un endpoint para peticiones con método POST para añadir logs en `/var/log/database` mediante una url tal que `http://127.0.0.1:8000/log-summaries/?type=info&message=Warning to the log on each request to Hello Word`
donde el parámetro `type` marca el tipo de error y `message` el mensaje de error como tal.

###Cachear las peticiones tipo GET durante 30 segundos
Haremos uso de `CacheKernel` para setear en el header de la respuesta a 30 segundos el tiempo máximo válido:
		
		$kernel = new CacheKernel($kernel);
		$response->setSharedMaxAge(30);

###Implementa un repositorio de LogSummary que persista en MySQL usando PDO (no Doctrine)
Se crea una clase, en Infrastructure/Repository/ llamada MySQLogSummaryPDORepository.php la cual esta encargada
de realizar una conexión manual hacia la base de datos MySQL montada en vagrant. Esta clase implementa de LogSummaryRepositoryInterface, luego se encarga de realizar las peticiones correspondiente y almacenarlas en MySQL.

Con PDO los valores se parametrizan para evitar inyección  SQL.

La conexión a la base de datos, se realiza al momento de instancia la clase por su constructor, este contiene los datos de conexión.

Antes de ejecutar una query, se prepara con el método onPrepare que devuelve un Statement por el cual podremos bandera los parámetros antes de realizar la consulta.

###Cambia la implementación del repositorio de LogSummary que se inyecta en el endpoint GET /, es decir el listado de LogSummary en HTML por la implementación con PDO
En ApiController y MainController, la instancia de JsonLogSummaryRepository, cambia a MySQLPDORepository, ahora al llamar a /log-summary/{environment} desde el get de nuestra url, realizara la búsqueda desde la base de datos, en caso de no contener información este recalcara el historial de log.

De la misma manera, cuando se produzca un evento, nuestro distpatcher reconstruirá el LogSummary persistiendo en la base de datos.

###Refactoriza tu repositorio para que use Doctrine DBAL en vez de PDO
Manteniendo la misma lógica anterior, esta vez aprovechamos doctrine y sus configuraciones, para hacer la conexión directa por infección de dependencias, ademas la información de conexión a la base de datos esta mas segura al encontrarse almacenada en .env de nuestro sistema.

Para ejecutar las querys, se utiliza el QueryBuilder de doctrine para DBAL, de manera de mantener el código mas legible, limpio y seguro. 

# API Log Examples:

**Para Dev**
[http://localhost:8000/log-summaries/dev?filter\[level\]=warning,error][5]

[http://localhost:8000/log-summaries/dev][6]

**Para Prod**
[http://localhost:8000/log-summaries/prod][7]

[http://localhost:8000/log-summaries/dev?filter\[level\]=info,error][8]


[1]:	https://getcomposer.org/download/1.6.3/composer.phar "Binario de composer v1.6.3 sha256: 52cb7bbbaee720471e3b34c8ae6db53a38f0b759c06078a80080db739e4dcab6"
[2]:	https://bitbucket.org/mupwar/symfony_g3 "Repositorio Grupo 3"
[3]:	https://github.com/MarioDevment/VagranDebianServer "Maquina Virtual - Vagrant y Ansible"
[4]:	http://localhost:8000/custom "Ruta custom, para nombre de entorno personalizado"
[5]:	http://localhost:8000/log-summaries/dev?filter[level]=warning,error
[6]:	http://localhost:8000/log-summaries/dev
[7]:	http://localhost:8000/log-summaries/prod
[8]:	http://localhost:8000/log-summaries/dev?filter[level]=info,error
