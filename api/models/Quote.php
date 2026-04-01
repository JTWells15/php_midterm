<?php
class Quote {
    private $conn;
    private $table = 'quotes';

    public $id;
    public $quote;
    public $author_id;
    public $category_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT 
                    q.id,
                    q.quote,
                    a.author,
                    c.category
                  FROM " . $this->table . " q
                  JOIN authors a ON q.author_id = a.id
                  JOIN categories c ON q.category_id = c.id
                  ORDER BY q.id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readSingle() {
        $query = "SELECT 
                    q.id,
                    q.quote,
                    a.author,
                    c.category
                  FROM " . $this->table . " q
                  JOIN authors a ON q.author_id = a.id
                  JOIN categories c ON q.category_id = c.id
                  WHERE q.id = :id
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function readByAuthor() {
        $query = "SELECT 
                    q.id,
                    q.quote,
                    a.author,
                    c.category
                  FROM " . $this->table . " q
                  JOIN authors a ON q.author_id = a.id
                  JOIN categories c ON q.category_id = c.id
                  WHERE q.author_id = :author_id
                  ORDER BY q.id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':author_id', $this->author_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function readByCategory() {
        $query = "SELECT 
                    q.id,
                    q.quote,
                    a.author,
                    c.category
                  FROM " . $this->table . " q
                  JOIN authors a ON q.author_id = a.id
                  JOIN categories c ON q.category_id = c.id
                  WHERE q.category_id = :category_id
                  ORDER BY q.id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $this->category_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function readByAuthorAndCategory() {
        $query = "SELECT 
                    q.id,
                    q.quote,
                    a.author,
                    c.category
                  FROM " . $this->table . " q
                  JOIN authors a ON q.author_id = a.id
                  JOIN categories c ON q.category_id = c.id
                  WHERE q.author_id = :author_id
                    AND q.category_id = :category_id
                  ORDER BY q.id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':author_id', $this->author_id, PDO::PARAM_INT);
        $stmt->bindParam(':category_id', $this->category_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function exists() {
        $query = "SELECT id FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch() ? true : false;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " (quote, author_id, category_id)
              VALUES (:quote, :author_id, :category_id)
              RETURNING id";

        $stmt = $this->conn->prepare($query);

        $this->quote = trim($this->quote);

        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id, PDO::PARAM_INT);
        $stmt->bindParam(':category_id', $this->category_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = (int) $row['id'];
            return true;
        }

        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table . "
                  SET quote = :quote,
                      author_id = :author_id,
                      category_id = :category_id
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->quote = trim($this->quote);

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id, PDO::PARAM_INT);
        $stmt->bindParam(':category_id', $this->category_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
?>