<?php 

Class Persona{

    public $id;
    public $nombre;
    public $apellido;

    public function __construct($id, $nombre, $apellido)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
    }
}

