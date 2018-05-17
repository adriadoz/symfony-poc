# Grupo 3 - Taller de Symfony

El equipo consta de 2 integrantes:
 
 - Adrià Velardos Palomar
 - Mario Hidalgo García

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

##Sesión 7
###Incluir Twig en el proyecto
Se instala el sistema de templates al proyecto mediante `composer require "twig/twig:^2.0"`. 
Creamos una nueva carpeta llamada templates donde estaran las plantillas del proyecto. En el archivo de configuración `twig.yaml` seteamos la carpeta templates:

        twig:
            paths: ['%kernel.project_dir%/templates']
            debug: '%kernel.debug%'
            strict_variables: '%kernel.debug%'

###Hacer un controlador web que dé respuesta a GET / y devuelva en HTML básico usando Twig
Añadimos el método `read` para dar respuesta a peticiones GET en nuestro controlador LogApiController, y lo añadimos al archivo de configuración de rutas `log_api.yaml` tal que así:

        log_api:
            path: /log-summaries/{environment}
            defaults:
                environment: dev
            controller: G3\FrameworkPractice\Infrastructure\Endpoint\LogApiController::read
            methods: [GET]

Devolvemos el HTML a partir de la plantilla indicada en el primer parámetro de render y a continuación se añaden los datos a printar, tal que así:

        return $this->render(
            'logSummary.html.twig',
            [
              'environment' => $this->environment,
              'logs'        => json_decode($logApiBuilder->logSummary(), true),
            ]
        );
En nuestra plantilla mostraremos en el header el nombre del grupo y el entorno que se está consultando:

    <h1 class="display-5">LogSummary from Team 3 and environment: {{ environment }}</h1>
    
Y en el cuerpo se muestra el listado de número de eventos usando un iterador `for` de Twig:

    {% for key,log in logs %}
        <tr>
            <td>{{ key }}</td>
            <td>{{ log }}</td>
        </tr>
    {% endfor %}

###Hacer 2 ficheros de estilos CSS. Uno con un estilo simple para listados, y otro para el estilo del header. Incluid estos dos ficheros desde vuestra plantilla
Se incluyen dos ficheros css en la plantilla de Twig `logSummary.html.twig`:
    
    <link rel="stylesheet" type="text/css" href="../css/header.css">
    <link rel="stylesheet" type="text/css" href="../css/summary.css">

###Usad WebPack Encore para unificar los 2 CSS en un único fichero app.css
Primero de todo instalamos encore y yarn:

         composer require encore
         yarn install

A continuación importamos nuestros ficheros `scss` en `app.js`, de esta manera cuando ejecutemos `yarn run dev` nos generará un solo archivo css en la carpeta `public/build`.
Y para terminar modificamos la importación en nuestra plantilla Twig, tal que así:

    <link rel="stylesheet" type="text/css" href="{{ asset('build/app.css')}}">

##Sesión 8
###Modela tu LogSummary en tu dominio
Para modelar LogSummary en nuestro dominio, primero identificamos todos los métodos usados para hacer el cáculo del resumen de logs, y los inegramos en una nueva clase en el dominio.
Nuestra clase estará compuesta por variables privadas integer para almacenar el número de veces que se ha producido cada nivel de log. Otra variable privada nos servirá para almazenar los mismos datos en forma de array con clave el nivel de log y valor el número de repeticiones de este.
Dotamos a la clase con lógica para realizar el filtrado de niveles y devolver un array con el resultado del filtrado.

###Publica un evento log_record.locally_raised cuando se produzca un error desde tu handler de monolog
Instalamos EventDispatcher `composer require symfony/event-dispatcher` 
Implementamos la interficie EventDispatcher como LogEventDispatcher, con un método que será llamado cuando se publique el evento pedido.
Creamos una instancia de EventDispatcher en nuestro MainController, y lanzamos un evento, que previamente habremos suscrito tal que así:
    
    private function addDispatcher(): void
    {
        $dispatcher      = new EventDispatcher();
        $logRecordRaised = 'log_record.locally_raised';

        $dispatcher->addListener(
            $logRecordRaised,
            function (LogEventDispatcher $event) {
                $event->locallyRaised();
            }
        );

        $dispatcher->dispatch($logRecordRaised, new LogEventDispatcher($logRecordRaised));
    }
   
Vemos como se ejecuta el método del LogEventDispatcher cuando ejecutamos el código.

###Publica un evento log_record.remotely_added cuando se añada un error desde el endpoint público 
Añadimos un método más a nuestro LogEventDispatcher para que se ejecute cuando el evento de que se ha añadido un log de forma remota sea lanzado.
Repetimos el mismo proceder que en el anterior MainController, pero esta vez lo hacemos en LogApiController:

    private function addDispatcher(): void
    {
        $dispatcher      = new EventDispatcher();
        $logRecordRaised = 'log_record.remotely_added';

        $dispatcher->addListener(
            $logRecordRaised,
            function (LogEventDispatcher $event) {
                echo $event->remotelyAdded();
            }
        );

        $dispatcher->dispatch($logRecordRaised, new LogEventDispatcher($logRecordRaised));
    }

Y comprobamos que al hacer una llamada POST y insertamos un log se lanza el evento porque se ejecuta el método `remotelyAdded`.

Una vez hechas estas comprobaciones, añadimos tags en el archivo de configuración `services.yaml` para indicar qué método debe de ejecutarse según el evento:

    { name: kernel.event_listener, event: log_record.locally_raised, method: locallyRaised }
    
De esta manera podemos inyectar por constructor el EventDispatcher y ahorrarnos múltiples instancias en ambos controladores y añadir los listeners. Por lo que la generación de un evento quedaría así:

    $this->eventDispatcher->dispatch(
        'log_record.remotely_added',
        new LogEventDispatcher()
    );
    
    
###Materializa LogSummary para que cuando lo pidan no lo tengas que calcular
Creamos una interficie LogSummaryRepository que define dos métodos a implementar, uno para guardar un LogSummary y otra para leer un logSummary persistido en memoria.
Hacemos una implementación de esta para guardar en formato JSON los logSummary.

    $this->encoded = json_encode($logSummary->__invoke($this->levels),JSON_PRETTY_PRINT);
    $fp = fopen($this->path.$environment.'-summary.json', 'w');
    fwrite($fp, $this->encoded);
    fclose($fp);
    
Y también una para recuperar el logSummary persistido según el entorno:

    if(file_exists ( $this->path.$environment.'-summary.json')){
        $json = file_get_contents($this->path.$environment.'-summary.json');
        return json_decode($json, true);
    }else{
        return null;
    }
            
 Desde LogSummaryGetter controlaremos si el archivo persistido existe o no, en caso de que no se calculará el logSummary de nuevo.
 En LogEventDispatcher creamos un solo método que ejecute el método de guardar un logSummary en un fichero JSON cuando se produce uno de los dos eventos, según si ha sido generado de forma remota o local.

###Reemplaza tu EventDispatcher de Symfony por el de Prooph
Finalmente reemplazamos nuestro EventDispatcher por el de Prooph, lo instalamos con `composer require prooph/service-bus`
Instanciamos un CommandBus y un CommandRouter, y añadimos un route para que escuche un evento y cree una instancia de LogEventDispatcher.

    $this->eventDispatcher = new CommandBus();
    $this->router = new CommandRouter();
    $this->router->route('log_record.locally_raised')->to(new LogEventDispatcher($this->environment));
    $this->router->attachToMessageBus($this->eventDispatcher);

Donde se añade el log lanzamos el evento:

    $this->eventDispatcher->dispatch('log_record.locally_raised');

##Sesión 9
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

###Refactoriza tu repositorio para que use Doctrine ORM en vez de Doctrine DBAL 

Se instancia EntityManagerInterface, para persistir la clase en base de datos.

Primero se crea una nueva entidad en Infrastructure/Doctrine/Entity/LogSummaryEntry y con anotaciones ORM se indica la clase de entidad y su base de datos, luego se mapean los campos hacia la base de datos.

Para asegurarnos que la base de datos esta actualizada con nuestra entidad se ejecuta el comando de doctrine **php bin/console doctrine:schema:validate**

Se sigue la lógica de los demás repositorios para obtener o almacenar los datos, en el caso de doctrine, se aprovecha el EntityManager inyectado para persistir en la base de datos con el siguiente comando

    $logSummaryEntry = new LogSummaryEntry($levels, $total, $environment);
    
    $this->em->persist($logSummaryEntry);
    $this->em->flush();
    
En la primera linea, se instancia la entidad y a continuación se persiste y envia.



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
