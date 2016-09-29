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

    $app->get("/librarian", function () use ($app){
        return $app['twig']->render('librarian.html.twig');
    });

    $app->get("/librarian/authors", function () use ($app){
        return $app['twig']->render('librarian/authors.html.twig', array('authors' => Author::getAll()));
    });

    $app->get("/librarian/authors/{id}", function ($id) use ($app){
        return $app['twig']->render('librarian/author.html.twig', array('author' => Author::find($id), 'titles' => Author::find($id)->getTitles(), 'all_titles' => Author::find($id)->getNotAuthored()));
    });

    $app->patch('/librarian/authors/{id}', function($id) use ($app) {
        $new_name = $_POST['new_author_name'];
        $author = Author::find($id);
        $author->update($new_name);
        return $app['twig']->render('librarian/authors.html.twig', array('authors' => Author::getAll()));
    });

    $app->delete("/librarian/authors/{id}", function ($id) use ($app){
        $author = Author::find($id);
        $author->delete();
        return $app['twig']->render('librarian/authors.html.twig', array('authors' => Author::getAll()));
    });

    $app->post("/librarian/authors/new_author", function () use ($app){
        $author = new Author($_POST['author_name']);
        $author->save();
        return $app['twig']->render('librarian/authors.html.twig', array('authors' => Author::getAll()));
    });

    $app->post("/librarian/authors/add_title", function () use ($app){
        $author = Author::find($_POST['author_id']);
        $title = Title::find($_POST['title_id']);
        $author->addTitle($title);
        return $app['twig']->render('librarian/author.html.twig', array('author' => $author, 'titles' => $author->getTitles(), 'all_titles' => $author->getNotAuthored()));
    });

    $app->get("/librarian/titles", function () use ($app){
        return $app['twig']->render('librarian/titles.html.twig', array('titles' => Title::getAll()));
    });

    $app->post("/librarian/titles/new_title", function () use ($app){
        $title = new Title($_POST['title_name']);
        $title->save();
        return $app['twig']->render('librarian/titles.html.twig', array('titles' => Title::getAll()));
    });

    $app->get("/librarian/patrons", function () use ($app){
        return $app['twig']->render('librarian/patrons.html.twig', array('patrons' => Patron::getAll()));
    });

    $app->post("/librarian/patrons/new_patron", function () use ($app){
        $patron = new Patron($_POST['patron_name']);
        $patron->save();
        return $app['twig']->render('librarian/patrons.html.twig', array('patrons' => Patron::getAll()));
    });

    $app->get("/librarian/overdue", function () use ($app){
        return $app['twig']->render('librarian/overdue.html.twig', array('loans' => Loan::allOverdue()));
    });




    return $app;
?>
