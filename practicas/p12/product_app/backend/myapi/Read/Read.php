<?php
namespace TECWEB\MYAPI\Read;

use TECWEB\MYAPI\DataBase;

class Read extends DataBase {
    
    /**
     * Lista registros de la base de datos
     * @return void
     */
    public function list() {
        // Implementación básica o abstracta
    }
    
    /**
     * Busca un registro por ID
     * @param string $id ID del registro
     * @return void
     */
    public function single($id) {
        // Implementación básica o abstracta
    }
    
    /**
     * Busca registros según criterio
     * @param string $criteria Criterio de búsqueda
     * @return void
     */
    public function search($criteria) {
        // Implementación básica o abstracta
    }
}
?>