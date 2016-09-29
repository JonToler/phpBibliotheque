<?php

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

    $app->get("/librarian/titles/{id}", function ($id) use ($app){
        $title = Title::find($id);
        $copies = $title->getCopies();
        return $app['twig']->render('librarian/title.html.twig', array('title' => $title, 'authors' => $title->getAuthor(), 'all_authors' => $title->nonAuthors(), 'copies' => $copies));
    });

    $app->post("/librarian/titles/add_author", function () use ($app){
        $author = Author::find($_POST['author_id']);
        $title = Title::find($_POST['title_id']);
        $copies = $title->getCopies();
        $author->addTitle($title);
        return $app['twig']->render('librarian/title.html.twig', array('title' => $title, 'authors' => $title->getAuthor(), 'all_authors' => $title->nonAuthors(), 'copies' => $copies));
    });

    $app->patch('/librarian/titles/{id}', function($id) use ($app) {
        $new_title = $_POST['new_title_name'];
        $title = Title::find($id);
        $title->update($new_title);
        return $app['twig']->render('librarian/titles.html.twig', array('titles' => Title::getAll()));
    });

    $app->delete("/librarian/titles/{id}", function ($id) use ($app){
        $title = Title::find($id);
        $title->delete();
        return $app['twig']->render('librarian/titles.html.twig', array('titles' => Title::getAll()));
    });

    $app->post("/librarian/titles/{id}/add_copies", function ($id) use ($app){
        $title = Title::find($id);
        $title->addCopies($_POST['copy_quantity']);
        $copies = $title->getCopies();
        return $app['twig']->render('librarian/title.html.twig', array('title' => $title, 'authors' => $title->getAuthor(), 'all_authors' => $title->nonAuthors(), 'copies' => $copies));
    });

    $app->get("/librarian/patrons", function () use ($app){
        return $app['twig']->render('librarian/patrons.html.twig', array('patrons' => Patron::getAll()));
    });

    $app->post("/librarian/patrons/new_patron", function () use ($app){
        $patron = new Patron($_POST['patron_name']);
        $patron->save();
        return $app['twig']->render('librarian/patrons.html.twig', array('patrons' => Patron::getAll()));
    });

    $app->get("/librarian/patrons/{id}", function ($id) use ($app){
        $patron = Patron::find($id);
        return $app['twig']->render('librarian/patron.html.twig', array('patron' => $patron));
    });

    $app->patch('/librarian/patrons/{id}', function($id) use ($app) {
        $new_patron = $_POST['new_patron_name'];
        $patron = Patron::find($id);
        $patron->update($new_patron);
        return $app['twig']->render('librarian/patron.html.twig', array('patron' => $patron));
    });

    $app->delete("/librarian/patrons/{id}", function ($id) use ($app){
        $patron = Patron::find($id);
        $patron->delete();
        return $app['twig']->render('librarian/patrons.html.twig', array('patrons' => Patron::getAll()));
    });

    $app->get("/librarian/overdue", function () use ($app){
        return $app['twig']->render('librarian/overdue.html.twig', array('loans' => Loan::allOverdue()));
    });

?>
