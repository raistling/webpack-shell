<?php

    $HOST_DB = 'localhost';
    $USER_DB = 'nolin';
    $PASSWORD_DB = '7506';
    $DATABASE_DB = 'nasa';
    $PORT_DB = 3307;

    $connect_db = mysqli_connect($HOST_DB, $USER_DB, $PASSWORD_DB, $DATABASE_DB, $PORT_DB);

    if($connect_db->connect_error) {
        die("Conexión fallida: " . $connect_db->connect_error);
    }


    // INSERT INTO `users` (`id`, `username`, `password`, `token`) VALUES (NULL, 'nolin', '7506', 'asbxs');


    class UserDB {
        private $connect;

        public function __construct(){
            global $connect_db;
            $this->connect = $connect_db;
        }
        public function isUserRegistered($username, $password){
            // Usar una declaración preparada para prevenir inyección SQL
            $stmt = $this->connect->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
            $stmt->bind_param('ss', $username, $password);
            $stmt->execute();
            $result = $stmt->get_result();

            // Verificar si la consulta devolvió algún resultado
            return ($result->num_rows > 0);
        }
        public function closeConnection(){
            $this->connect->close();
        }

        public function getUser($username){

            $stmt = $this->connect->prepare("SELECT username,token FROM users WHERE username = ?");
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_assoc();
        }

        public function setUser($username, $password, $token){
            try {
                // Preparar la sentencia SQL
                $stmt = $this->connect->prepare("INSERT INTO users (id, username, password, token) VALUES (null, ?, ?, ?)");

                // Vincular los parámetros
                $stmt->bind_param("sss", $username, $password, $token);

                // Ejecutar la consulta
                $stmt->execute();

                return 1;
                //header('Location: login.php');

            } catch(Exception $e) {
                return 0;
            }
        }


    }





