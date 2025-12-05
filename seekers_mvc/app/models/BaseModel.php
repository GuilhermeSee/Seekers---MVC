<?php
class BaseModel {
    protected $conexao;
    protected $table;
    
    public function __construct() {
        $this->conexao = conexao();
    }
    
    public function find($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function all() {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->conexao->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $fields = array_keys($data);
        $placeholders = ':' . implode(', :', $fields);
        $fieldsList = implode(', ', $fields);
        
        $sql = "INSERT INTO {$this->table} ({$fieldsList}) VALUES ({$placeholders})";
        $stmt = $this->conexao->prepare($sql);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        
        return $stmt->execute();
    }
    
    public function update($id, $data) {
        $fields = [];
        foreach (array_keys($data) as $field) {
            $fields[] = "{$field} = :{$field}";
        }
        $fieldsList = implode(', ', $fields);
        
        $sql = "UPDATE {$this->table} SET {$fieldsList} WHERE id = :id";
        $stmt = $this->conexao->prepare($sql);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->bindValue(':id', $id);
        
        return $stmt->execute();
    }
    
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    public function getConnection() {
        return $this->conexao;
    }
}