<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Title.php";
    require_once "src/Author.php";
    require_once "src/Copy.php";
    require_once "src/Loan.php";
    require_once "src/Patron.php";

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
            Copy::deleteAll();
            Loan::deleteAll();
            Patron::deleteAll();
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

        function test_createCopy()
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
            $new_title->addAuthor($new_author);
            $new_title->addAuthor($new_author2);
            $copy_quantity = 4;
            $new_title->addCopies($copy_quantity);

            // Act
            $result = count($new_title->getCopies());

            // Assert
            $this->assertEquals($copy_quantity, $result);
        }

        function test_deleteCopies()
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
            $new_title->addAuthor($new_author);
            $new_title->addAuthor($new_author2);
            $copy_quantity = 4;
            $new_title->addCopies($copy_quantity);

            // Act
            $new_title->delete();
            $result = Copy::getAll();

            // Assert
            $this->assertEquals([], $result);
        }

        function test_onLoanList()
        {
            // Arrange
            $name = "Joe McCool";
            $test_patron1 = new Patron($name);
            $test_patron1->save();
            $author_name = "Brian Herbert";
            $new_author = new Author($author_name);
            $new_author->save();
            $author_name2 = "Kevin Anderson";
            $new_author2 = new Author($author_name2);
            $new_author2->save();
            $title_name = "The Milkman of Dune";
            $new_title = new Title($title_name);
            $new_title->save();
            $new_title->addAuthor($new_author);
            $new_title->addAuthor($new_author2);
            $copy1 = new Copy($new_title->getId());
            $copy1->save();
            $copy2 = new Copy($new_title->getId());
            $copy2->save();
            $copy3 = new Copy($new_title->getId());
            $copy3->save();
            $copy4 = new Copy($new_title->getId());
            $copy4->save();
            $date1 = date("Y-m-d");
            $date2 = "2016-10-02";
            $date3 = "2016-10-01";
            $loan1 = new Loan($date1, $date2, $date3, $copy1->getId(), $test_patron1->getId());
            $loan1->save();
            $loan2 = new Loan($date1, $date2, null, $copy2->getId(), $test_patron1->getId());
            $loan2->save();
            $loan3 = new Loan($date1, $date2, null, $copy3->getId(), $test_patron1->getId());
            $loan3->save();

            // Act
            $onLoan = $new_title->onLoanList()[0];
            $available = $new_title->onLoanList()[1];
            $result = array($onLoan, $available);

            // Assert
            $this->assertEquals(array([$copy2, $copy3], [$copy1, $copy4]), $result);
        }

        function test_nonAuthors()
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
            $new_title2->addAuthor($new_author2);

            // Act
            $result = $new_title->nonAuthors();

            // Assert
            $this->assertEquals([$new_author2], $result);
        }

    }
?>
