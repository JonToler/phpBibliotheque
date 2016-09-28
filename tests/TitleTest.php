<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Title.php";
    require_once "src/Author.php";

    $server = 'mysql:host=localhost;dbname=bibliotheque_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class TitleTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Title::deleteAll();
            Author::deleteAll();
        }

        function test_getName()
        {
            // Arrange
            $name = "Joe McCool";
            $id = null;
            $test_title = new Title($name, $id);
            // Act
            $result = $test_title->getName();
            // Assert
            $this->assertEquals($name, $result);
        }

        function test_getId()
        {
            // Arrange
            $name = "Joe McCool";
            $id = 10;
            $test_title = new Title($name, $id);
            // Act
            $result = $test_title->getId();
            // Assert
            $this->assertEquals($id, $result);
        }

        function test_setName()
        {
            // Arrange
            $name = "Joe McCool";
            $id = null;
            $test_title = new Title($name, $id);
            $test_title->save();
            $new_name = "Joseph McCool";
            // Act
            $test_title->setName($new_name);
            $result = $test_title->getName();
            // Assert
            $this->assertEquals($new_name, $result);
        }

        function test_getAll()
        {
            // Arrange
            $name = "Joe McCool";
            $id = null;
            $test_title1 = new Title($name, $id);
            $test_title1->save();
            $name = "Toni Thyme";
            $test_title2 = new Title($name, $id);
            $test_title2->save();
            // Act
            $result = Title::getAll();
            // Assert
            $this->assertEquals([$test_title1, $test_title2], $result);
        }

        function test_save()
        {
            // Arrange
            $name = "Joe McCool";
            $id = null;
            $test_title1 = new Title($name, $id);
            $test_title1->save();
            // Act
            $result = Title::getAll();
            // Assert
            $this->assertEquals([$test_title1], $result);
        }

        function test_deleteAll()
        {
            // Arrange
            $name = "Joe McCool";
            $id = null;
            $test_title1 = new Title($name, $id);
            $test_title1->save();
            $name = "Toni Thyme";
            $test_title2 = new Title($name, $id);
            $test_title2->save();
            // Act
            Title::deleteAll();
            // Assert
            $this->assertEquals([], Title::getAll());
        }

        function test_find()
        {
            // Arrange
            $name = "Joe McCool";
            $id = null;
            $test_title1 = new Title($name, $id);
            $test_title1->save();
            $name = "Toni Thyme";
            $test_title2 = new Title($name, $id);
            $test_title2->save();
            // Act
            $result = Title::find($test_title1->getId());
            // Assert
            $this->assertEquals($test_title1, $result);
        }

        function test_update()
        {
            // Arrange
            $name = "Joe McCool";
            $id = null;
            $test_title1 = new Title($name, $id);
            $test_title1->save();
            $new_name = "Joseph McCool";
            // Act
            $test_title1->update($new_name);
            $result = Title::find($test_title1->getId())->getName();
            // Assert
            $this->assertEquals($new_name, $result);
        }

        function test_delete()
        {
            // Arrange
            $name = "Joe McCool";
            $id = null;
            $test_title1 = new Title($name, $id);
            $test_title1->save();
            $name = "Toni Thyme";
            $test_title2 = new Title($name, $id);
            $test_title2->save();
            $name = "Jane Morrison";
            $test_title3 = new Title($name, $id);
            $test_title3->save();
            // Act
            $test_title1->delete();
            $result = Title::getAll();
            // Assert
            $this->assertEquals([$test_title2, $test_title3], $result);
        }

        function test_addAuthor()
        {
            // Arrange
            $author_name = "Brian Herbert";
            $new_author = new Author($author_name);
            $new_author->save();
            $title_name = "The Milkman of Dune";
            $new_title = new Title($title_name);
            $new_title->save();

            // Act
            $new_title->addAuthor($new_author);
            $result = $new_title->getAuthor();

            // Assert
            $this->assertEquals([$new_author], $result);
        }

        function test_addAuthors()
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

            // Act
            $new_title->addAuthor($new_author);
            $new_title->addAuthor($new_author2);
            $result = $new_title->getAuthor();

            // Assert
            $this->assertEquals([$new_author, $new_author2], $result);
        }

        function test_searchTitle()
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
            $search_string = "milk";
            $new_title->addAuthor($new_author);
            $new_title->addAuthor($new_author2);
            $new_title2->addAuthor($new_author);

            // Act
            $result = Title::search($search_string);

            // Assert
            $this->assertEquals([$new_title], $result);
        }

        function test_searchTitleAuthors()
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
            $search_string = "milk";
            $new_title->addAuthor($new_author);
            $new_title->addAuthor($new_author2);
            $new_title2->addAuthor($new_author);
            $found_title = Title::search($search_string);

            // Act
            $result = $found_title[0]->getAuthor();

            // Assert
            $this->assertEquals([$new_author, $new_author2], $result);
        }

    }
?>
