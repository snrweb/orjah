<?php 
    namespace Core;
    use Core\DB;
    use Core\Sanitise;
    
    class Model {
        protected $db, $table, $modelName, $softDelete = false;
        public $buyer_id, $store_id, $id;

        public function __construct($table) {
            $this->db = DB::getInstance();
            $this->table = $table;
            $this->modelName = str_replace(' ', '', ucwords(str_replace('_', ' ', $this->table)));
        }

        public function find($params= []) {
            $resultQuery = $this->db->find($this->table, $params, get_class($this));
            if(!$resultQuery) return [];
            return $resultQuery;
        }

        public function findFirst($params= []) {
            $resultQuery = $this->db->findFirst($this->table, $params, get_class($this));
            if(!$resultQuery) return [];
            return $resultQuery;
        }

        public function insert($fields) {
            if(empty($fields)) return false;
            return $this->db->insert($this->table, $fields);
        }

        public function update($idName, $id, $fields) {
            if(empty($fields) || $id === '' ) return false;
            return $this->db->update($this->table, $idName, $id, $fields);
        }

        public function delete($idName, $id) {
            if($id === '') return false;
            if ($this->softDelete) {
                return $this->db->update($idName, $id, ['deleted' => 1]);
            }
            return $this->db->delete($this->table, $idName, $id);
        }

        public function query($sql, $bind = []) {
            return $this->db->query($sql, $bind);
        }

        public function findById($idName, $id) {
            return $this->findFirst(['conditions' => "{$idName} = ?", 'bind' => [$id]]);
        }

        public function assign($params) {
            if(!empty($params)) {
                foreach($params as $key => $value) {
                    if(property_exists($this, $key)) {
                        $this->$key = Sanitise::input($value);
                    }
                }
                return true;
            }
            return false;
        }

    }

?>