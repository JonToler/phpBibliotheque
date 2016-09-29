<?php
    date_default_timezone_set('America/Los_Angeles');
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Author.php";
    require_once __DIR__."/../src/Copy.php";
    require_once __DIR__."/../src/Loan.php";
    require_once __DIR__."/../src/Patron.php";
    require_once __DIR__."/../src/Title.php";

    use Symfony\Component\Debug\Debug;
    Debug::enable();

    $app = new Silex\Application();
    $app['debug'] = true;

    $server = 'mysql:host=localhost;dbname=bibliotheque';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    $app->get("/", function () use ($app){
        return $app['twig']->render('index.html.twig');
    });

    require_once __DIR__."/../app/librarian.php";
    require_once __DIR__."/../app/patron.php";

    return $app;
?>
