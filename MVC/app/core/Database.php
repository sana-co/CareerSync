<?php
trait Database
{  //a trait cant run on its own unless used within another class
    private function connect()
    {
        $conn = new mysqli(DBHOST, DBUSER, DBPASS);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $dbCreateSQL = "CREATE DATABASE IF NOT EXISTS " . DBNAME;
        if (!$conn->query($dbCreateSQL)) {
            die("Database creation failed: " . $conn->error);
        }

        if (!$conn->select_db(DBNAME)) {
            die("Database selection failed: " . $conn->error);
        }
        return $conn;
    }
    public function query($query, $data = [])
    {
        $conn = $this->connect();
        if (stripos(trim($query), "USE") === 0) {
            $conn->query($query);
            return 0;
        }
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        // Bind parameters if data is provided
        // Bind parameters if data is provided
        if (!empty($data)) {
            $types = '';
            $bind_values = [];
            foreach ($data as $param) {
                if (is_null($param)) {
                    $types .= 's';           // bind NULL as string — mysqli handles it correctly
                    $bind_values[] = null;
                } elseif (is_int($param)) {
                    $types .= 'i';
                    $bind_values[] = $param;
                } elseif (is_double($param)) {
                    $types .= 'd';
                    $bind_values[] = $param;
                } else {
                    $types .= 's';
                    $bind_values[] = $param;
                }
            }
            $stmt->bind_param($types, ...$bind_values);
        }
        
        $stmt->execute();
        if (stripos(trim($query), "SELECT") === 0) {
            $result = $stmt->get_result();
            $rows = [];
            if ($result) {
                while ($row = $result->fetch_object()) {
                    $rows[] = $row;
                }
                return $rows ?: false;
            }
        } elseif (stripos(trim($query), "INSERT") === 0) {
            return $stmt->insert_id ?: true; // Return insert ID or true for successful insert
        } elseif (stripos(trim($query), "UPDATE") === 0 || stripos(trim($query), "DELETE") === 0) {
            return $stmt->affected_rows > 0; // Return true if rows were affected
        }
        return false;
    }
}
