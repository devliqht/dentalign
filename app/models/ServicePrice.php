<?php

class ServicePrice
{
    protected $conn;
    protected $table = "ServicePrices";

    public $servicePriceID;
    public $serviceName;
    public $servicePrice;
    public $isActive;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table . " 
                  (ServiceName, ServicePrice, IsActive) 
                  VALUES (?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $this->serviceName = htmlspecialchars(strip_tags($this->serviceName));
        $this->isActive = $this->isActive ?? 1;

        $stmt->bind_param("sdi", $this->serviceName, $this->servicePrice, $this->isActive);

        if ($stmt->execute()) {
            $this->servicePriceID = $this->conn->insert_id;
            return true;
        }

        return false;
    }

    public function getAllServices()
    {
        $query = "SELECT 
                    ServicePriceID,
                    ServiceName,
                    ServicePrice,
                    IsActive,
                    CreatedAt,
                    UpdatedAt
                  FROM " . $this->table . "
                  ORDER BY ServiceName";

        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getActiveServices()
    {
        $query = "SELECT 
                    ServicePriceID,
                    ServiceName,
                    ServicePrice,
                    IsActive,
                    CreatedAt,
                    UpdatedAt
                  FROM " . $this->table . "
                  WHERE IsActive = 1
                  ORDER BY ServiceName";

        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getServiceByName($serviceName)
    {
        $query = "SELECT 
                    ServicePriceID,
                    ServiceName,
                    ServicePrice,
                    IsActive,
                    CreatedAt,
                    UpdatedAt
                  FROM " . $this->table . "
                  WHERE ServiceName = ?
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $serviceName);
        $stmt->execute();
        
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getServiceById($servicePriceID)
    {
        $query = "SELECT 
                    ServicePriceID,
                    ServiceName,
                    ServicePrice,
                    IsActive,
                    CreatedAt,
                    UpdatedAt
                  FROM " . $this->table . "
                  WHERE ServicePriceID = ?
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $servicePriceID);
        $stmt->execute();
        
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateService($servicePriceID, $serviceName, $servicePrice, $isActive = 1)
    {
        $query = "UPDATE " . $this->table . " 
                  SET ServiceName = ?, ServicePrice = ?, IsActive = ?, UpdatedAt = CURRENT_TIMESTAMP 
                  WHERE ServicePriceID = ?";

        $stmt = $this->conn->prepare($query);
        $serviceName = htmlspecialchars(strip_tags($serviceName));

        $stmt->bind_param("sdii", $serviceName, $servicePrice, $isActive, $servicePriceID);

        return $stmt->execute();
    }

    public function deleteService($servicePriceID)
    {
        $query = "DELETE FROM " . $this->table . " WHERE ServicePriceID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $servicePriceID);

        return $stmt->execute();
    }

    public function toggleServiceStatus($servicePriceID)
    {
        $query = "UPDATE " . $this->table . " 
                  SET IsActive = NOT IsActive, UpdatedAt = CURRENT_TIMESTAMP 
                  WHERE ServicePriceID = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $servicePriceID);

        return $stmt->execute();
    }

    public function getServicePricesArray()
    {
        $services = $this->getActiveServices();
        $pricesArray = [];
        
        foreach ($services as $service) {
            $pricesArray[$service['ServiceName']] = (float)$service['ServicePrice'];
        }
        
        return $pricesArray;
    }
}
?>
