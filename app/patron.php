<?php

    $app->get("/patron", function () use ($app){
        return $app['twig']->render('patron.html.twig');
    });

    $app->get("/patron/authors", function () use ($app){
        return $app['twig']->render('patron/authors.html.twig', array('authors' => Author::getAll()));
    });

    $app->post("/patron/authors/search", function () use ($app){
        return $app['twig']->render('patron/authors.html.twig', array('authors' => Author::searchAuthor($_POST['search_string'])));
    });

    $app->get("/patron/titles", function () use ($app){
        return $app['twig']->render('patron/titles.html.twig', array('titles' => Title::getAll()));
    });

    $app->post("/patron/titles/search", function () use ($app){
        return $app['twig']->render('patron/titles.html.twig', array('titles' => Title::search($_POST['search_string'])));
    });

    $app->get("/patron/titles/{id}", function ($id) use ($app){
        $title = Title::find($id);
        $authors = $title->getAuthor();
        return $app['twig']->render('patron/title.html.twig', array('title' => $title, 'authors' => $authors, 'patrons' => Patron::getAll()));
    });

    $app->post("/patron/titles/{id}/checkout", function ($id) use ($app){
        $date_out = date("Y-m-d");
        $date_due = date('Y-m-d', strtotime("+30 days"));
        $date_returned = null;
        $title = Title::find($id);
        $authors = $title->getAuthor();
        $copy_id = $title->onLoanList()[1][0]->getId();
        $patron_id = $_POST['patron_id'];
        $new_loan = new Loan($date_out, $date_due, $date_returned, $copy_id, $patron_id);
        $new_loan->save();
        return $app['twig']->render('patron/title.html.twig', array('title' => $title, 'authors' => $authors, 'patrons' => Patron::getAll()));
    });

    $app->get("/patron/history", function () use ($app){

        return $app['twig']->render('patron/history.html.twig');
    });

?>
