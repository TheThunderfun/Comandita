<?php

require_once '../DataBase/DB.php';

class Csv{

    public static function grabarEnCsv($item, $output)
    {
        if ($item) {
            fputcsv($output, (array)$item);
        } else {
            throw new Exception("Los datos para exportar son inválidos.");
        }
    }

    public static function exportarTabla($tabla, $clase)
    {
        $listaProductos = DB::obtenerTodos($tabla, $clase);
        if ($listaProductos) {
            $output = fopen('php://memory', 'r+');
            
          
            if (count($listaProductos) > 0) {
                $headers = array_keys((array)$listaProductos[0]);
                fputcsv($output, $headers);
            }
                foreach ($listaProductos as $item) {
                self::grabarEnCsv($item, $output);
            }

            rewind($output);
            $csvContent = stream_get_contents($output);
            fclose($output);
            
            return $csvContent;
        } else {
            throw new Exception("No se encontraron datos para exportar.");
        }
    }

        public static function LeerCsv($archivo)
        {
            $auxArchivo = fopen($archivo, "r");
            $array = [];

            if ($auxArchivo) {
                try {
                    while (!feof($auxArchivo)) {
                        $registro = fgets($auxArchivo);
                        if (!empty($registro)) {
                            $registro = str_replace('"', '', $registro); 
                            array_push($array, $registro);
                        }
                    }
                } catch (\Throwable $e) {
                    echo "No se pudo leer el archivo<br>";
                    printf($e);
                } finally {
                    fclose($auxArchivo);
                    return $array;
                }
            }
        }

}