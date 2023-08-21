<?php

class Database
{
    private $mysqli;

    public function __construct()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $this->mysqli = new mysqli($_ENV["DB_HOST"], $_ENV["DB_USER"], $_ENV["DB_PASS"], $_ENV["DB_NAME"]);
    }

    public function query($query, $bparam = null, ...$params)
    {
        try {
            $stmt = $this->mysqli->prepare($query);

            if ($bparam != null) {
                $stmt->bind_param($bparam, ...$params);
            }

            if (!$stmt->execute()) {
                return false;
            }

            if (($res = $stmt->get_result()) !== false) {
                return $res->fetch_all(MYSQLI_ASSOC);
            }

            return true;
        } catch (mysqli_sql_exception $e) {
            error_log($e->__toString());
            return false;
        }
    }

    public function __destruct()
    {
        $this->mysqli->close();
    }

    public function insertCoordinates($address, $latitude, $longitude)
    {
        $query = "INSERT INTO coordinates (address, latitude, longitude) VALUES (?, ?, ?)";
        $bparam = "sdd";
        $params = [$address, $latitude, $longitude];
        return $this->query($query, $bparam, ...$params);
    }
}
