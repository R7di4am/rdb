

# **Database Operations with PDO - Basic Operations**

## **Project Overview**

This project shows how to work with a database using PDO (PHP Data Objects) through a custom `rdb` class. The main features include:

- Connecting to the database.
- Running basic queries like SELECT and INSERT.
- Handling database transactions (making sure everything works together correctly).

## **Features**

- Run database queries with prepared statements.
- Execute multiple queries in one transaction to make sure all of them succeed or fail together.
- Get results in different formats (like arrays or objects).
- Simple interface for working with the database.

## **Requirements**

- PHP 7.x or higher.
- MySQL or MariaDB database.
- A database table `users` with fields `id`, `username`, and `password` for testing.

### **Table Schema**

Here is an example table that works with this project:

```sql
CREATE TABLE `users` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL
);
```

---

## **How to Use**

### **1. Connect to the Database**

To connect to the database, you can use the following code:

```php
$pdo = new PDO("mysql:host=localhost;dbname=test", "root", "password");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
```

- Replace `"root"`, `"password"`, and `"test"` with your actual database username, password, and database name.

---

### **2. Overview of `rdb` Class**

The `rdb` class is a simple helper that makes it easier to interact with the database. Here are the key methods you can use:

#### **Methods**

- **`query(string $query, array $params = [])`**: Runs an SQL query.
- **`result()`**: Returns the result of the query (PDOStatement).
- **`affected_rows()`**: Returns how many rows were affected by the last query.
- **`fetch_all()`**: Gets all the results as an associative array.
- **`fetch_row()`**: Gets a single row as a numbered array.
- **`fetch_assoc()`**: Gets a single row as an associative array.
- **`fetch_object()`**: Gets a single row as an object.
- **`beginTransaction()`**: Starts a transaction.
- **`commit()`**: Saves changes made during the transaction.
- **`rollback()`**: Cancels changes if something goes wrong.

---

### **3. Running Queries**

#### **Example: Get All Users**

```php
<?php
$db = new rdb($pdo);
$db->query("SELECT * FROM users");
$users = $db->fetch_all();
echo "<pre>" . json_encode($users, JSON_PRETTY_PRINT) . "</pre>";
?>
```

#### **Example: Get a User by ID**

```php
<?php
$db->query("SELECT * FROM users WHERE id = :id", ['id' => 2]);
$user = $db->fetch_assoc();
echo "<pre>" . json_encode($user, JSON_PRETTY_PRINT) . "</pre>";
?>
```

#### **Example: Insert a New User**

```php
<?php
$sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
$params = ['username' => 'r7di4am', 'password' => '12345678'];

$db->query($sql, $params);
echo "User inserted successfully.";
?>
```

---

### **4. Using Transactions**

#### **Example: Running Multiple Queries Together (Transaction)**

```php
try {
    // Start a transaction
    $db->beginTransaction();

    // Insert a new user
    $sql = "INSERT INTO users (id, username, password) VALUES (NULL, :username, :password)";
    $params = ['username' => 'r7di4am', 'password' => 'somthing'];
    $db->query($sql, $params);

    // Delete a user
    $sql = "DELETE FROM users WHERE id = :id";
    $db->query($sql, ["id" => $users[0]['id']]);

    // Commit the transaction (save the changes)
    $db->commit();
    echo "<h3>Users inserted successfully and one user deleted.</h3>";
} catch (Exception $e) {
    // If something goes wrong, cancel the changes
    $db->rollback();
    echo "<h3>Error:</h3> " . $e->getMessage();
}
```

---

### **5. Error Handling**

- **`try-catch` Blocks**: We use `try-catch` blocks to catch any errors that might happen during the database operations. This is helpful for handling issues like connection problems or invalid queries.

---

## **Conclusion**

This project shows how to work with a database using PDO in PHP. The `rdb` class makes it easier to run queries and handle transactions. It also allows you to fetch data in different formats and manage errors effectively.

**Key features:**
- Easy-to-use PDO-based database operations.
- Support for transactions (begin, commit, rollback).
- Methods for fetching data in different formats (array, object).
- Simple error handling.

Let me know if you need any help or have questions!

---

**Made with ❤️ by [@R7di4am](https://github.com/R7di4am)**