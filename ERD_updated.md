Updated ERD — Student Marketplace + Admin

Entities (PK = red, FK = blue)

- USER (studentID PK, firstName, lastName, contactNumber, email, role)
- BUYER (buyerID PK, studentID FK -> USER.studentID)
- SELLER (sellerID PK, studentID FK -> USER.studentID)
- CATEGORY (categoryID PK, categoryName)
- POSTS (itemID PK, sellerID FK -> SELLER.sellerID, categoryID FK -> CATEGORY.categoryID, title, description, price, datePosted, condition)
- TRANSACTION (transactionID PK, itemID FK -> POSTS.itemID, buyerID FK -> BUYER.buyerID, sellerID FK -> SELLER.sellerID, meetupLocation, transactionDate, finalPrice)

Admin is not a separate entity in the ERD.
It is represented by the USER.role attribute, such as Student or Admin.

Notes:
- Admin is stored as a USER role value, not as a separate ERD entity.
- Foreign keys use InnoDB and preserve referential integrity.
- Types and lengths chosen in `uniswap_schema.sql` match these fields (studentID CHAR(10), itemID/transactionID CHAR(12)). Adjust lengths if your IDs differ.

Diagram (text):

USER(studentID PK)
  |\
  | \__ BUYER(buyerID PK, studentID FK)
  |\
  | \__ SELLER(sellerID PK, studentID FK)

SELLER --< POSTS(itemID PK, sellerID FK, categoryID FK)
CATEGORY(categoryID PK)

POSTS --< TRANSACTION(transactionID PK, itemID FK, buyerID FK, sellerID FK)

USER(role = Student | Admin)
