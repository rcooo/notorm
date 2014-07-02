<?php

/** information about mapping tables to entities
 */
interface NotORM_Entity_Mapper {

    /** Enable or disable NotORM_Entity_Mapper
     * @param boolean $value
     */
    public function enable($value);

    /** Get entity from table
     * @param string $table table name
     * @param string $default default value
     */
    public function getEntity($table, $default);
}

/** Conventional mapper table_name map to TableName
 *
 * Namespace of entities or prefix and suffix is possible to define
 */
class NotORM_Entity_Mapper_Convention implements NotORM_Entity_Mapper {
    private $enabled, $entity, $namespace, $mapper;

    /**
     * @param string $namespace if entities are defined in namespace
     * @param string $entity if entities have prefix of suffix use e.g.: "%s_Entity"
     * @param array $mapper for non-convention entities define array('table_name' => 'entity_name')
     */
    function __construct($namespace = '', $entity = '%s', $mapper = array()) {
        $this->enabled = true;
        $this->entity = $entity;
        $this->namespace = $namespace;
        $this->mapper = $mapper;
    }

    public function enable($value) {
        $this->enabled = $value;
    }

    public function getEntity($table, $default) {
        if (!$this->enabled) return $default;

        if (array_key_exists($table, $this->mapper)) {
            $entityName = $this->mapper[$table];
        } else {
            $entityName = $this->getEntityName($table);
        }
        $entityName = sprintf($this->entity, $entityName);
        $entityName = $this->namespace . $entityName;
        if (class_exists($entityName)) {
            return $entityName;
        }
        return $default;
    }

    /** convert underscored to upper-camelcase
     * example "this_method_name" -> "ThisMethodName"
     * @see http://www.php.net/manual/en/function.ucwords.php#92092
     *
     * @param string $table
     * @return string
     */
    private function getEntityName($table) {
        return preg_replace_callback('/(?:^|_)(.?)/e', function($m) { strtoupper($m[1]); },$table);
    }

}