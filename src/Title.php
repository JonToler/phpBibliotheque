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
    }
?>
