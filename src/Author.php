<?php
    class Author
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
          $GLOBALS['DB']->exec("INSERT INTO authors (name) VALUES ('{$this->getName()}')");
          $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_author = $GLOBALS['DB']->query("SELECT * FROM authors;");
            $authors = array();
            foreach($returned_author as $author) {
                $name = $author['name'];
                $id = $author['id'];
                $new_author = new Author($name, $id);
                array_push($authors, $new_author);
            }
            return $authors;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM authors;");
            $GLOBALS['DB']->exec("DELETE FROM authors_titles;");
        }

        static function find($search_id)
        {
            $found_author = null;
            $authors = Author::getAll();
            foreach($authors as $author) {
                $author_id = $author->getId();
                if ($author_id == $search_id) {
                  $found_author = $author;
                }
            }
            return $found_author;
        }

        function update($new_name)
        {
            $GLOBALS['DB']->exec("UPDATE authors SET name = '{$new_name}' WHERE id = {$this->getId()};");
            $this->setName($new_name);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM authors WHERE id = {$this->getId()};");
        }

        static function search($search_string)
        {
            $query = "/" . $search_string . "/i";
            $found_titles = array();
            $titles = Title::getAll();
            foreach ($titles as $title) {
                $authors = $title->getAuthor();
                foreach ($authors as $author)
                {
                    if (preg_match($query, $author->getName())) {
                        array_push($found_titles, $title);
                    }
                }
            }
            return $found_titles;
        }

        static function searchAuthor($search_string)
        {
            $query = "/" . $search_string . "/i";
            $found_authors = array();
            $authors = Author::getAll();
            foreach ($authors as $author) {
                if (preg_match($query, $author->getName())) {
                    array_push($found_authors, $author);
                    }
                }
            return $found_authors;
        }

        function getTitles()
        {
            $returned_titles = $GLOBALS['DB']->query("SELECT titles.* FROM titles JOIN authors_titles ON (titles.id = authors_titles.title_id) JOIN authors ON (authors.id = authors_titles.author_id) WHERE authors.id = {$this->getId()};");
            $titles = array();
            foreach($returned_titles as $title) {
                $name = $title['name'];
                $id = $title['id'];
                $new_title = new Title($name, $id);
                array_push($titles, $new_title);
            }
            return $titles;
        }

        function getNotAuthored()
        {
            $allTitles = Title::getAll();
            $authoredTitles = $this->getTitles();
            $notAuthored = array();
            foreach($allTitles as $title) {
                if(!in_array($title, $authoredTitles))
                {
                    $name = $title->getName();
                    $id = $title->getId();
                    $new_title = new Title($name, $id);                    array_push($notAuthored, $new_title);
                }
            }
            return $notAuthored;
        }

        function addTitle($title)
        {
            $author_id = $this->id;
            $title_id = $title->getId();
            $GLOBALS['DB']->exec("INSERT INTO authors_titles (author_id, title_id) VALUES ({$author_id}, {$title_id});");
        }
    }
?>
