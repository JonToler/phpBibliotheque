<?php
    class Title
    {
        private $name;
        private $id;

        function __construct($name, $id = null)
        {
            $this->name = $name;
            $this->id = $id;
        }

        function getName()
        {
            return $this->name;
        }

        function getId()
        {
            return $this->id;
        }

        function setName($new_name)
        {
            $this->name = (string) $new_name;
        }

        function setId($new_id)
        {
            $this->id = (int) $new_id;
        }

        function save()
        {
          $GLOBALS['DB']->exec("INSERT INTO titles (name) VALUES ('{$this->getName()}')");
          $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_title = $GLOBALS['DB']->query("SELECT * FROM titles;");
            $titles = array();
            foreach($returned_title as $title) {
                $name = $title['name'];
                $id = $title['id'];
                $new_title = new Title($name, $id);
                array_push($titles, $new_title);
            }
            return $titles;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM titles;");
            $GLOBALS['DB']->exec("DELETE FROM authors_titles;");
        }

        static function find($search_id)
        {
            $found_title = null;
            $titles = Title::getAll();
            foreach($titles as $title) {
                $title_id = $title->getId();
                if ($title_id == $search_id) {
                  $found_title = $title;
                }
            }
            return $found_title;
        }

        function update($new_name)
        {
            $GLOBALS['DB']->exec("UPDATE titles SET name = '{$new_name}' WHERE id = {$this->getId()};");
            $this->setName($new_name);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM copies WHERE title_id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM titles WHERE id = {$this->getId()};");
        }

        function addAuthor($author)
        {
            $title_id = $this->id;
            $author_id = $author->getId();
            $GLOBALS['DB']->exec("INSERT INTO authors_titles (author_id, title_id) VALUES ({$author_id}, {$title_id});");
        }

        function getAuthor()
        {
            $title_id = $this->id;
            $returned_authors = $GLOBALS['DB']->query("SELECT authors.* FROM titles JOIN authors_titles ON (titles.id = authors_titles.title_id) JOIN authors ON (authors.id = authors_titles.author_id) WHERE titles.id = {$title_id};");
            $authors = array();
            foreach($returned_authors as $author) {
                $name = $author['name'];
                $id = $author['id'];
                $new_author = new Author($name, $id);
                array_push($authors, $new_author);
            }
            return $authors;
        }

        function nonAuthors()
        {
            $allAuthors = Author::getAll();
            $titleAuthors = $this->getAuthor();
            $nonAuthors = array();
            foreach($allAuthors as $author) {
                if(!in_array($author, $titleAuthors))
                {
                    $name = $author->getName();
                    $id = $author->getId();
                    $new_author = new Author($name, $id);
                    array_push($nonAuthors, $new_author);
                }
            }
            return $nonAuthors;
        }

        static function search($search_string)
        {
            $query = "/" . $search_string . "/i";
            $found_titles = array();
            $titles = Title::getAll();
            foreach ($titles as $title) {
                if (preg_match($query, $title->getName())) {
                    array_push($found_titles, $title);
                }
            }
            return $found_titles;
        }

        function addCopies($copy_quantity)
        {
            $title_id = $this->id;
            for ($i = 1; $i <= $copy_quantity; $i++) {
                $new_copy = new Copy($title_id);
                $new_copy->save();
            }
        }

        function getCopies()
        {
            $title_id = $this->id;
            $copies = array();
            $returned_copies = $GLOBALS['DB']->query("SELECT copies.* FROM copies JOIN titles ON (copies.title_id = titles.id) WHERE titles.id = {$title_id};");
            foreach ($returned_copies as $copy) {
                $id = $copy['id'];
                $copy_title = $copy['title_id'];
                $new_copy = new Copy($copy_title, $id);
                array_push($copies, $new_copy);
            }
            return $copies;
        }

        function onLoanList()
        {
            $onLoan = array();
            $available = array();
            $copies = $this->getCopies();
            foreach ($copies as $copy){
                if ($copy->isCheckedOut()) {
                    array_push($onLoan, $copy);
                } else {
                    array_push($available, $copy);
                }
            }
            return array($onLoan, $available);
        }
    }
?>
