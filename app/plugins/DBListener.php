<?php

namespace Application;
/**
 * Description of DBListener
 *
 * @author cofacoul
 */
class DBListener {
    


    public function afterQuery($event, $connection) {
        $var = $connection->getSQLVariables();
        if(count($var)){
            Logger::DEBUG($connection->getSQLStatement() . ' => ' . join(', ', $var));
        }else{
            Logger::DEBUG($connection->getSQLStatement());
        }
    }

}
