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
    }
?>
