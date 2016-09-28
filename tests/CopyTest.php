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

    class CopyTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Copy::deleteAll();
            Title::deleteAll();
            Author::deleteAll();
            Loan::deleteAll();
            Patron::deleteAll();
        }

        function test_getTitleId()
        {
            // Arrange
            $title_id = 1;
            $id = null;
            $test_copy = new Copy($title_id, $id);
            // Act
            $result = $test_copy->getTitleId();
            // Assert
            $this->assertEquals($title_id, $result);
        }

        function test_getId()
        {
            // Arrange
            $title_id = 1;
            $id = 10;
            $test_copy = new Copy($title_id, $id);
            // Act
            $result = $test_copy->getId();
            // Assert
            $this->assertEquals($id, $result);
        }

        function test_setTitleId()
        {
            // Arrange
            $title_id = 1;
            $id = null;
            $test_copy = new Copy($title_id, $id);
            $test_copy->save();
            $newtitle_id = 2;
            // Act
            $test_copy->setTitleId($newtitle_id);
            $result = $test_copy->getTitleId();
            // Assert
            $this->assertEquals($newtitle_id, $result);
        }

        function test_getAll()
        {
            // Arrange
            $title_id = 1;
            $id = null;
            $test_copy1 = new Copy($title_id, $id);
            $test_copy1->save();
            $title_id = 3;
            $test_copy2 = new Copy($title_id, $id);
            $test_copy2->save();
            // Act
            $result = Copy::getAll();
            // Assert
            $this->assertEquals([$test_copy1, $test_copy2], $result);
        }

        function test_save()
        {
            // Arrange
            $title_id = 1;
            $id = null;
            $test_copy1 = new Copy($title_id, $id);
            $test_copy1->save();
            // Act
            $result = Copy::getAll();
            // Assert
            $this->assertEquals([$test_copy1], $result);
        }

        function test_deleteAll()
        {
            // Arrange
            $title_id = 1;
            $id = null;
            $test_copy1 = new Copy($title_id, $id);
            $test_copy1->save();
            $title_id = 3;
            $test_copy2 = new Copy($title_id, $id);
            $test_copy2->save();
            // Act
            Copy::deleteAll();
            // Assert
            $this->assertEquals([], Copy::getAll());
        }

        function test_find()
        {
            // Arrange
            $title_id = 1;
            $id = null;
            $test_copy1 = new Copy($title_id, $id);
            $test_copy1->save();
            $title_id = 3;
            $test_copy2 = new Copy($title_id, $id);
            $test_copy2->save();
            // Act
            $result = Copy::find($test_copy1->getId());
            // Assert
            $this->assertEquals($test_copy1, $result);
        }

        function test_update()
        {
            // Arrange
            $title_id = 1;
            $id = null;
            $test_copy1 = new Copy($title_id, $id);
            $test_copy1->save();
            $newtitle_id = 2;
            // Act
            $test_copy1->update($newtitle_id);
            $result = Copy::find($test_copy1->getId())->getTitleId();
            // Assert
            $this->assertEquals($newtitle_id, $result);
        }

        function test_delete()
        {
            // Arrange
            $title_id = 1;
            $id = null;
            $test_copy1 = new Copy($title_id, $id);
            $test_copy1->save();
            $title_id = 3;
            $test_copy2 = new Copy($title_id, $id);
            $test_copy2->save();
            $title_id = 4;
            $test_copy3 = new Copy($title_id, $id);
            $test_copy3->save();
            // Act
            $test_copy1->delete();
            $result = Copy::getAll();
            // Assert
            $this->assertEquals([$test_copy2, $test_copy3], $result);
        }

        function test_isCheckedOut()
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
            $date1 = date("Y-m-d");
            $date2 = "2016-10-02";
            $date3 = "2016-10-01";
            $loan1 = new Loan($date1, $date2, $date3, $copy1->getId(), $test_patron1->getId());
            $loan1->save();
            $loan2 = new Loan($date1, $date2, null, $copy2->getId(), $test_patron1->getId());
            $loan2->save();

            // Act
            $result = [$copy1->isCheckedOut(), $copy2->isCheckedOut()];

            // Assert
            $this->assertEquals([false, true], $result);
        }

    }
?>
