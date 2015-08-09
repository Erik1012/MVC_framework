<?php
	abstract class Model {
		static protected $dbd = false;
		protected $db;
		private $_where;
		private $_order;
		private $_limit;
		private $_query;
		private $_join;
		private $_select = "*";
		public function __construct() 
			{
				$this->db = self::getDb();
			}
		public static function getDb()
			{
				if(empty(self::$dbd))
					{
						$config = Config::$db;
						self::$dbd = new PDO('mysql:host='.$config['host'].';dbname='.$config['db'].';charset=UTF8', $config['login'], $config['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8'));
					}
				return self::$dbd;
			}
		public static function model()
			{
				return new static();
			}
		public function setAttrs($data)
			{
				foreach($data as $key => $value)
					$this->$key = $value;
			}
   
		/**
		 * Получаем публичные свойства объекта.
		 * @возвращаем array.
		 */
		private function getVars()
			{
				$bads = array("db", "_where", "_order", "_limit", "_query", "_join", "_select");
				$vars = array();
				foreach($this as $key => $value)
					{
						if(!in_array($key, $bads))
							$vars[$key] = $value;
					}
				return $vars;
			}

		private function prepare($string)
			{
				return str_replace(array("'", '"'), array("\'", '\"'), $string);
			}

		/**
		 * Создаем insert sql строку.
		 */
		private function makeInsert()
			{
				$vars = $this->getVars();
				$names = "(";
				$values = "(";
				foreach($vars as $key => $value){
					$value = $this->prepare($value);
					$names.="`$key`, ";
					$values.="'$value', ";
				}
				$names = substr($names, 0, -2) . ")";
				$values = substr($values, 0, -2) . ")";
				return array($names, $values);
			}	

		/**
		 * Создаем update sql строку.
		 */
		private function makeUpdate()
			{
				$vars = $this->getVars();
				$update = "";
				foreach($vars as $key => $value){
					$value = $this->prepare($value);
					$update.="`$key`='$value', ";
				}
				return substr($update, 0, -2);
			}

		/**
		 * Добавляем либо обновляем объект в базе.
		 * @возвращаем boolean
		 */
		public function save()
			{
				$primaryKey = $this->primaryKey();
				if($this->$primaryKey){
					$update = $this->makeUpdate();
					$primaryKey = $this->primaryKey();
					$query = "UPDATE `" . $this->tableName() . "` SET " . $update . " WHERE `" . $primaryKey . "`='" . $this->$primaryKey . "'"; 
					$sh = $this->db->query($query);
				} else {
					list($names, $values) = $this->makeInsert();
					$query = "INSERT INTO `" . $this->tableName() . "` " . $names . " VALUES " . $values;
					$sh = $this->db->query($query);
					$primaryKey = $this->primaryKey();
					if($primaryKey){
						$this->$primaryKey = $this->db->lastInsertId();
					}
				}
				return $sh;
			}

		public function update()
			{
				 $primaryKey = $this->primaryKey();
				  $update = $this->makeUpdate();
				  $primaryKey = $this->primaryKey();
				  $query = "UPDATE `" . $this->tableName() . "` SET " . $update . " WHERE `" . $primaryKey . "`='" . $this->$primaryKey . "'"; 
				  $sh = $this->db->query($query);
				  return $sh;
			}

		 public function insert()
			{
				 $primaryKey = $this->primaryKey();
				  list($names, $values) = $this->makeInsert();
				  $query = "INSERT INTO `" . $this->tableName() . "` " . $names . " VALUES " . $values;
				  $sh = $this->db->query($query);
				  $primaryKey = $this->primaryKey();
				  if($primaryKey){
					  $this->$primaryKey = $this->db->lastInsertId();
				  }
				  return $sh;
			}

		/*
		 * Удаляем модель
		 */
		public function delete()
			{
				$query = "DELETE FROM `" . $this->tableName() . "`";
				$query = $this->addOthers($query);
				$sh = $this->db->query($query);
				return $sh;
			}

		/**
		 * Находим единственную запись по where.
		 * @возвращаем array или boolean.
		 */
		public function findRow()
			{
				if(empty($this->_query)){
					$query = "SELECT " . $this->_select ." FROM " . $this->tableName();
					$query = $this->addOthers($query);
				} else {
					$query = $this->_query;
				}
				$sh = $this->db->query($query);
				if($sh){
					$class = new static();
					$result = $sh->fetch(PDO::FETCH_ASSOC);
					if(!$result)
						return False;
					foreach($result as $key => $value){
						$class->$key = $value;
					}
					return $class;
				}
				else
					return False;
			}

		/**
		 * Находим все записи по where.
		 * @возвращаем array или boolean.
		 */
		public function findAll()
			{
				if(empty($this->_query)){
					$query = "SELECT " . $this->_select ." FROM " . $this->tableName();
					$query = $this->addOthers($query);
				} else {
					$query = $this->_query;
				}

				$sh = $this->db->query($query);
				if($sh){
					$result = $sh->fetchAll(PDO::FETCH_ASSOC);
					$classes = array();
					foreach($result as $element){
						$class = new static();
						foreach($element as $key => $value){
							$class->$key = $value;
						}
						$classes[] = $class;
					}
					return $classes;
				} else {
					return array();
				}
			}

		public function countAll()
			{
				$query = "SELECT COUNT(*) FROM " . $this->tableName();
				$query = $this->addOthers($query);
				$sh = $this->db->query($query);
				if($sh){
					$result = $sh->fetchAll(PDO::FETCH_ASSOC);
					return (int)$result[0]["COUNT(*)"];
				} else {
					return False;
				}
			}

		private function addOthers($sql)
			{
				$others = array(" LEFT JOIN " => $this->_join, " WHERE "=>$this->_where, " ORDER BY "=>$this->_order, " LIMIT "=>$this->_limit);
				foreach($others as $key => $value)
				{
					if(!empty($value))
						$sql .= $key . $value;
				}
				return $sql;
			}

		public function where($sql)
			{
				$this->_where = $sql;
				return $this;
			}

		public function addWhere($sql, $type)
			{
				if(empty($this->_where))
					$this->where($sql);
				else
					$this->_where .= " " . $type . " " . $sql;
			}

		public function order($sql)
			{
				$this->_order = $sql;
				return $this;
			}

		public function limit($sql)
			{
				$this->_limit = $sql;
				return $this;
			}

		public function query($sql)
			{
				$this->_query = $sql;
				return $this;
			}

		public function join($sql)
			{
				if(empty($this->_join))
					$this->_join = $sql;
				else
					$this->_join .= $sql;
				return $this;
			}

		public function select($sql)
			{
				$this->_select = $sql;
				return $this;
			}	
			
			
		abstract function tableName();
		abstract function primaryKey();
	}
/* ======CREATED_BY_ERIK======= */
/* ===========2015============= */
?>
