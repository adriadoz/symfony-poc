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
Se ha creado un nuevo entorno de trabajo _prod_ en la carpeta `config/packages/` el cual es configurarle desde la parametrización global en **.env**

Se imprime por pantalla, al usuario el mensaje **Hola `dev|prod` World** dependiendo del entorno en que se encuentre.

