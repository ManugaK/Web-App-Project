Web-Application-Project
General Guidelines
The Library Management System is a comprehensive software solution designed for efficient library operations. It enables administrators to manage user registrations, book entries, and member fines. With features like book availability tracking and seamless integration, the system ensures a user-friendly and reliable tool for librarians to organize, monitor, and maintain library resources effectively.

Feature List
Feature 1 – Login and User Registration
Library staff should be able to register with the system by providing their user ID, firstname, lastname, username, password, and email address.
Validation: Passwords must be more than 8 digits. Registration will be ignored if the password is less than 8 digits. Email and username need to be checked for existing registrations before submission, provided email also needs to validate over correct format.
Validation: The user ID should be created in the 'U<USER_ID>' format only (e.g., U001). If the user sets another text format as the User ID, that record should not be submitted to the system. (Hint: Use Regular Expressions).
Other features should be inside the admin panel. Access to these features is granted only after logging in to the system. (Hint: PHP sessions can be used.)
User details (user ID, firstname, lastname, username, password, email address) need to be updated individually once they are added to the system.
Each user should log in to the system using their specific username and password registered with the system.
User details need to be deleted and updated individually.
User records should be visible in a table with columns for user ID, firstname, lastname, username, password, and email address.
User should be able to logout from the system.
Extra marks (optional) – You can use any hashing technique (SHA128, MD5…) to store passwords.
Feature 2 – Books Registration
Library staff should be able to register books with specific details:
Book ID
Book Name
Book Category (A dropdown menu should be provided for category selection.)
Validation: The Book ID should be created in the 'B<BOOK_ID>' format only (e.g., B001). If the user sets another text format as the Book ID, that record should not be submitted to the system. (Hint: Use Regular Expressions).
Library staff should be able to update each book's details (Book ID, Book Name, Book Category) after adding it to the database.
Library staff should be able to delete each book record individually.
Library staff should be able to display each book record in a table with the following columns:
Book ID
Book Name
Book Category
Feature 3 – Book Category Registration
Library staff should be able to register book categories with specific details:
Category ID
Category Name
Date Modified (system date-time of that instance being submitted to the system).
Validation: The Category ID should be created in the 'C<CATEGORY_ID>' format only (e.g., C001). If the user sets another text format as the Category ID, that record should not be submitted to the system. (Hint: Use Regular Expressions).
Library staff should be able to update each book category's details (Category ID, Category Name, Date Modified) after adding it to the system.
Library staff should be able to delete each book category record individually.
Library staff should be able to display each book category record in a table with the following columns:
Category ID
Category Name
Date Modified
Feature 4 – Library Member Registration (by library staff)
Library staff should be able to register library members with the system by providing the following details:
Member ID
Firstname
Lastname
Birthday
Email address
Validation: Email address validation should ensure that the email is in a valid format (e.g., sample@mymail.com). The format of the email must be checked before submitting.
Validation: The Member ID should be created in the 'M<MEMBER_ID>' format only (e.g., M001). If the user sets another text format as the Member ID, that record should not be submitted to the system. (Hint: Use Regular Expressions).
Library staff should be able to update user details (Member ID, Firstname, Lastname, Birthday, Email address) individually once they are added to the system.
Library staff should be able to delete each library member's details individually.
Library staff should be able to display each library member's record in a table with the following columns:
Member ID
Firstname
Lastname
Birthday
Email address
Feature 5 – Book Borrow Details
Library staff must be able to add borrow details to the system by providing the following information:
Borrow ID
Book ID
Member ID
Borrow status
Modified date (system date)
Borrow Status Update: If a library member borrows a book, the Borrow status should change to "borrowed"; otherwise, it should be set to "available" (use drop-down menu).
Validation: The Borrow ID should be created in the 'BR<BORROW_ID>' format only (e.g., BR001). If the user sets another text format as the Borrow ID, that record should not be submitted to the system. (Hint: Use Regular Expressions).
Validation: The Book ID should be created in the 'B<BOOK_ID>' format only (e.g., B001). If the user sets another text format as the Book ID, that record should not be submitted to the system. (Hint: Use Regular Expressions).
Validation: The Member ID should be created in the 'M<MEMBER_ID>' format only (e.g., M001). If the user sets another text format as the Member ID, that record should not be submitted to the system. (Hint: Use Regular Expressions).
Library staff should be able to update user borrow book details (Borrow ID, Book ID, Borrow status, Modified date) individually once added to the system.
Library staff should be able to delete each borrow book detail individually.
Library staff should be able to display each Borrow Book record in a table with the following columns:
Book ID
Member who borrowed
Book Name
Borrow Status
Date Modified
Feature 6 – Assign Fine for a User
Library staff should be able to assign fines for a library member by providing the following details:
Fine ID
Member ID
Book ID
Fine amount in LKR
Date modified (system date and time).
Validation: The fine amount must be within the range of 2 LKR (minimum) to 500 LKR (maximum). Amounts outside this range will not be accepted.
Library staff should be able to update assigned fines by Member ID, fine amount in LKR, and date modified once added to the system.
Library staff should be able to delete each assigned fine detail individually.
Library staff should be able to display each "Assign Fine" record in a table with the following columns:
Fine ID
Member ID
Member Name
Book name related to relevant fine
Fine amount in LKR
Date Modified
