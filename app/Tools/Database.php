<?PHP
namespace App\Tools;
use \PDO;
use \PDOException;
class Database
{
    private $servername = "localhost";
    private $username = "root";
    private $password = "cpe@rmuti1234";
    private $dbname = "";
    public function __construct($database_name = "traveling_planner")
    {
        $this->dbname = $database_name;
    }
    private function connect()
    {
        try {
            $conn = new PDO(
                "mysql:host=$this->servername;dbname=$this->dbname",
                $this->username,
                $this->password
            );
            $conn->exec("set names utf8");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
    public function query($sql_command)
    {
        try {
            $conn = $this->connect();
            $stmt = $conn->prepare($sql_command);
            $result = $stmt->execute();
            try {
                $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
                return array(
                    "result" => $stmt->fetchAll(),
                    "rowCount" => $stmt->rowCount(),
                    "colCount" => $stmt->columnCount(),
                );
            } catch (PDOException $e) {
                return array(
                    "result" => $result,
                    "rowCount" => $stmt->rowCount(),
                );
            }
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}