

## Specifications

* Able to retrieve Author name
  * Input: database record for Author named "Joe McCool"
  * Output: "Joe McCool"

* Able to retrieve Author ID Number
  * Input: database record for Author named "Joe McCool", ID Number of 1
  * Output: 1

* Can record Author information into database
  * Input: Author named "Joe McCool"
  * Output: create and retrieve "Joe McCool" from database

* Can replace Author name with new name
  * Input: new name for "Joe McCool" - "Joseph McCool"
  * Output: updated name - "Joseph McCool"

* Able to retrieve all Authors
  * Input: database with the Authors "Joe McCool", "Lana Smith", "Roland Curley"
  * Output: retrieved the list "Joe McCool", "Lana Smith", "Roland Curley"

* Can remove all Authors from database
  * Input: database with the Authors "Joe McCool", "Lana Smith", "Roland Curley"
  * Output: retrieved an empty list

* Can find Author based on ID Number
  * Input: database record for Author named "Joe McCool", ID Number of 1, finding Author with ID Number of 1
  * Output: "Joe McCool"'s database record

* Can update Author record with new name
  * Input: new name for "Joe McCool" saved in database - "Joseph McCool"
  * Output: retrieved "Joseph McCool" from database

* Can remove Author record from database
  * Input: remove "Joe McCool" from database containing "Joe McCool", "Lana Smith", "Roland Curley"
  * Output: only "Lana Smith" and "Roland Curley" remain in database

* Able to retrieve Title name
  * Input: database record for Title named "Joe McCool"
  * Output: "Joe McCool"

* Able to retrieve Title ID Number
  * Input: database record for Title named "Joe McCool", ID Number of 1
  * Output: 1

* Can record Title information into database
  * Input: Title named "Joe McCool"
  * Output: create and retrieve "Joe McCool" from database

* Can replace Title name with new name
  * Input: new name for "Joe McCool" - "Joseph McCool"
  * Output: updated name - "Joseph McCool"

* Able to retrieve all Titles
  * Input: database with the Titles "Joe McCool", "Lana Smith", "Roland Curley"
  * Output: retrieved the list "Joe McCool", "Lana Smith", "Roland Curley"

* Can remove all Titles from database
  * Input: database with the Titles "Joe McCool", "Lana Smith", "Roland Curley"
  * Output: retrieved an empty list

* Can find Title based on ID Number
  * Input: database record for Title named "Joe McCool", ID Number of 1, finding Title with ID Number of 1
  * Output: "Joe McCool"'s database record

* Can update Title record with new name
  * Input: new name for "Joe McCool" saved in database - "Joseph McCool"
  * Output: retrieved "Joseph McCool" from database

* Can remove Title record from database
  * Input: remove "Joe McCool" from database containing "Joe McCool", "Lana Smith", "Roland Curley"
  * Output: only "Lana Smith" and "Roland Curley" remain in database

* Able to retrieve Patron name
  * Input: database record for Patron named "Joe McCool"
  * Output: "Joe McCool"

* Able to retrieve Patron ID Number
  * Input: database record for Patron named "Joe McCool", ID Number of 1
  * Output: 1

* Can record Patron information into database
  * Input: Patron named "Joe McCool"
  * Output: create and retrieve "Joe McCool" from database

* Can replace Patron name with new name
  * Input: new name for "Joe McCool" - "Joseph McCool"
  * Output: updated name - "Joseph McCool"

* Able to retrieve all Patrons
  * Input: database with the Patrons "Joe McCool", "Lana Smith", "Roland Curley"
  * Output: retrieved the list "Joe McCool", "Lana Smith", "Roland Curley"

* Can remove all Patrons from database
  * Input: database with the Patrons "Joe McCool", "Lana Smith", "Roland Curley"
  * Output: retrieved an empty list

* Can find Patron based on ID Number
  * Input: database record for Patron named "Joe McCool", ID Number of 1, finding Patron with ID Number of 1
  * Output: "Joe McCool"'s database record

* Can update Patron record with new name
  * Input: new name for "Joe McCool" saved in database - "Joseph McCool"
  * Output: retrieved "Joseph McCool" from database

* Can remove Patron record from database
  * Input: remove "Joe McCool" from database containing "Joe McCool", "Lana Smith", "Roland Curley"
  * Output: only "Lana Smith" and "Roland Curley" remain in database

* Can add an Author to a Title and retrieve author
  * Input: add "Brian Herbert" to "The Milkman of Dune"
  * Output: querying "The Milkman of Dune" returns "Brian Herbert" as Author

* Can add multiple Authors to a Title and retrieve authors
  * Input: add "Brian Herbert" and "Kevin Anderson" to "The Milkman of Dune"
  * Output: querying "The Milkman of Dune" returns "Brian Herbert" and "Kevin Anderson" as Authors

* Able to search for a book by its Title
  * Input: search for "milk"
  * Output: returns "The Milkman of Dune" by "Brian Herbert" and "Kevin Anderson"

* Able to search for a book by its Author
  * Input: search for "erber"
  * Output: returns "The Milkman of Dune" and "Dune and Its Rugrats"

* Can add create multiple Copies of a Title
  * Input: the Library stocks 4 copies of "The Milkman of Dune"
  * Output: returns 4 Copy records for "The Milkman of Dune"
