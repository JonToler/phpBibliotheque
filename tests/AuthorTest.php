<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Copy.php";
    require_once "src/Title.php";
    require_once "src/Author.php";
    require_once "src/Loan.php";
    require_once "src/Patron.php";

    $server = 'mysql:host=localhost;dbname=bibliotheque_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class AuthorTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Copy::deleteAll();
            Title::deleteAll();
            Author::deleteAll();
            Loan::deleteAll();
            Patron::deleteAll();
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

        function test_searchAuthor()
        {
            // Arrange
            $author_name = "Brian Herbert";
            $new_author = new Author($author_name);
            $new_author->save();
            $author_name2 = "Kevin Anderson";
            $new_author2 = new Author($author_name2);
            $new_author2->save();
            $title_name = "The Milkman of Dune";
            $new_title = new Title($title_name);
            $new_title->save();
            $title_name2 = "Dune and Its Rugrats";
            $new_title2 = new Title($title_name2);
            $new_title2->save();
            $search_string = "erber";
            $new_title->addAuthor($new_author);
            $new_title->addAuthor($new_author2);
            $new_title2->addAuthor($new_author);

            // Act
            $result = Author::search($search_string);

            // Assert
            $this->assertEquals([$new_title, $new_title2], $result);
        }

        function test_getTitles()
        {
            // Arrange
            $author_name = "Brian Herbert";
            $new_author = new Author($author_name);
            $new_author->save();
            $author_name2 = "Kevin Anderson";
            $new_author2 = new Author($author_name2);
            $new_author2->save();
            $title_name = "The Milkman of Dune";
            $new_title = new Title($title_name);
            $new_title->save();
            $title_name2 = "Dune and Its Rugrats";
            $new_title2 = new Title($title_name2);
            $new_title2->save();
            $search_string = "erber";
            $new_title->addAuthor($new_author);
            $new_title->addAuthor($new_author2);
            $new_title2->addAuthor($new_author);

            // Act
            $result = $new_author2->getTitles();

            // Assert
            $this->assertEquals([$new_title], $result);
        }

        function test_addTitle()
        {
            // Arrange
            $author_name = "Brian Herbert";
            $new_author = new Author($author_name);
            $new_author->save();
            $author_name2 = "Kevin Anderson";
            $new_author2 = new Author($author_name2);
            $new_author2->save();
            $title_name = "The Milkman of Dune";
            $new_title = new Title($title_name);
            $new_title->save();
            $title_name2 = "Dune and Its Rugrats";
            $new_title2 = new Title($title_name2);
            $new_title2->save();

            // Act
            $new_author->addTitle($new_title);
            $result = $new_author->getTitles();

            // Assert
            $this->assertEquals([$new_title], $result);
        }

        function test_getNotAuthored()
        {
            // Arrange
            $author_name = "Brian Herbert";
            $new_author = new Author($author_name);
            $new_author->save();
            $author_name2 = "Kevin Anderson";
            $new_author2 = new Author($author_name2);
            $new_author2->save();
            $title_name = "The Milkman of Dune";
            $new_title = new Title($title_name);
            $new_title->save();
            $title_name2 = "Dune and Its Rugrats";
            $new_title2 = new Title($title_name2);
            $new_title2->save();
            $new_title->addAuthor($new_author);
            $new_title->addAuthor($new_author2);
            $new_title2->addAuthor($new_author);

            // Act
            $result = $new_author2->getNotAuthored();

            // Assert
            $this->assertEquals([$new_title2], $result);
        }
    }
?>
