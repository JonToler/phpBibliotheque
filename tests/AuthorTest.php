<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Author.php";

    $server = 'mysql:host=localhost;dbname=bibliotheque_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class AuthorTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Author::deleteAll();
        }

        function test_getName()
        {
            // Arrange
            $name = "Joe McCool";
            $id = null;
            $test_author = new Author($name, $id);
            // Act
            $result = $test_author->getName();
            // Assert
            $this->assertEquals($name, $result);
        }

        function test_getId()
        {
            // Arrange
            $name = "Joe McCool";
            $id = 10;
            $test_author = new Author($name, $id);
            // Act
            $result = $test_author->getId();
            // Assert
            $this->assertEquals($id, $result);
        }

        function test_setName()
        {
            // Arrange
            $name = "Joe McCool";
            $id = null;
            $test_author = new Author($name, $id);
            $test_author->save();
            $new_name = "Joseph McCool";
            // Act
            $test_author->setName($new_name);
            $result = $test_author->getName();
            // Assert
            $this->assertEquals($new_name, $result);
        }

        function test_getAll()
        {
            // Arrange
            $name = "Joe McCool";
            $id = null;
            $test_author1 = new Author($name, $id);
            $test_author1->save();
            $name = "Toni Thyme";
            $test_author2 = new Author($name, $id);
            $test_author2->save();
            // Act
            $result = Author::getAll();
            // Assert
            $this->assertEquals([$test_author1, $test_author2], $result);
        }

        function test_save()
        {
            // Arrange
            $name = "Joe McCool";
            $id = null;
            $test_author1 = new Author($name, $id);
            $test_author1->save();
            // Act
            $result = Author::getAll();
            // Assert
            $this->assertEquals([$test_author1], $result);
        }

        function test_deleteAll()
        {
            // Arrange
            $name = "Joe McCool";
            $id = null;
            $test_author1 = new Author($name, $id);
            $test_author1->save();
            $name = "Toni Thyme";
            $test_author2 = new Author($name, $id);
            $test_author2->save();
            // Act
            Author::deleteAll();
            // Assert
            $this->assertEquals([], Author::getAll());
        }

        function test_find()
        {
            // Arrange
            $name = "Joe McCool";
            $id = null;
            $test_author1 = new Author($name, $id);
            $test_author1->save();
            $name = "Toni Thyme";
            $test_author2 = new Author($name, $id);
            $test_author2->save();
            // Act
            $result = Author::find($test_author1->getId());
            // Assert
            $this->assertEquals($test_author1, $result);
        }

        function test_update()
        {
            // Arrange
            $name = "Joe McCool";
            $id = null;
            $test_author1 = new Author($name, $id);
            $test_author1->save();
            $new_name = "Joseph McCool";
            // Act
            $test_author1->update($new_name);
            $result = Author::find($test_author1->getId())->getName();
            // Assert
            $this->assertEquals($new_name, $result);
        }

        function test_delete()
        {
            // Arrange
            $name = "Joe McCool";
            $id = null;
            $test_author1 = new Author($name, $id);
            $test_author1->save();
            $name = "Toni Thyme";
            $test_author2 = new Author($name, $id);
            $test_author2->save();
            $name = "Jane Morrison";
            $test_author3 = new Author($name, $id);
            $test_author3->save();
            // Act
            $test_author1->delete();
            $result = Author::getAll();
            // Assert
            $this->assertEquals([$test_author2, $test_author3], $result);
        }

    }
?>
