<?php

class WorkController {

    public function actionGetFiles() {
        //проверка на отправку документа
        if (!isset($_POST['submit'])) {
            goto end;
        }
        //проверка на существование файла
        if (!file_exists($_FILES["images"]["tmp_name"])) {
            echo '<h1>File not Found</h1>';
            goto end;
        }
        //проверяем формат файла
        $name = $_FILES['images']['name'];
        $parent = '/(.csv)/';
        if (!preg_match($parent, $name) == TRUE) {
            echo '<h1>Fail of the wrong format</h1>';
            goto end;
        }
        //проверка на существования файла по умолчанию
        if (!file_exists('files/test.csv')) {
            $this->savefale();
        }
        //вызов функции обработки файла
        $arr = $this->treatmentFile();
        //вызов функции создания массива
        $finish = $this->readyArray($arr);

        end:
        //вывод в фаил
        require_once (ROOT . '/views/main.php');
        return TRUE;
    }

    public function savefale() {
        //проверяем на ошибки
        foreach ($_FILES["images"] as $key => $error) {
            if ($error == UPLOAD_ERR_OK) {
                $tmp_name = $_FILES["images"]["tmp_name"];
                // basename() может предотвратить атаку на файловую систему;
                $name = basename($_FILES["images"]["name"]);
                $upload_dir = (ROOT . '/files');
                //сохраняем
                move_uploaded_file($tmp_name, "$upload_dir/$name");
            }
        }
        echo '<h1>File saved</h1>';
        return 0;
    }

    public function treatmentFile() {
        
        //путь к файлу
        $path_file = $_FILES['images']['tmp_name'];
        //передаем в функцию чтения из файла
        $arr = $this->bust($path_file);
        //обрезаем заголовки колонок
        array_shift($arr);
         
        //записываем в фаил новые элементы
        if ($this->changeFile($arr) == 0) {
            $path_file = 'files/test.csv';
            $arr = $this->bust($path_file);

            return $arr;
        }
    }

    public function readyArray($arr) {

        $ready[] = array_shift($arr);
        $i = 0;

        do {
            $count = count($arr);
            $arr1 = array_shift($arr);
            $j = 0;
            while ($j < $count) {
                if ($arr1[0] == $arr[$j][0]) {
                    if ($arr1[2] == $arr[$j][2]) {
                        $arr1[1] = $arr1[1] + $arr[$j][1];
                        unset($arr[$j]);
                        $j = 0;
                        continue ;
                    }
                }
                $j++;
            }
            if ($arr1[1] > 0) {
                $ready[] = $arr1;
                unset($arr1);
            }
            unset($arr1);
        } while ($i < $count);
        
        do {
            $as = array_shift($ready);
            $j = 0;
            while ($j < count($ready)) {
                if ($as[0] == $ready[$j][0]) {
                   $as[2]=$as[2].','.$ready[$j][2];
                   unset($ready[$j]);
                   $j=0;
                   continue;
                }
                $j++;
            }
            $finish[]=$as;
            unset($as);
        } while ($i < count($ready));

        return $finish;
    }

    public function bust($path_file) {

        $row = 0;
        $arr = array();

        if (($handle = fopen($path_file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {
                $num = count($data);
                $arr[] = $data;
            }
            fclose($handle);
        }
        return $arr;
    }

    public function changeFile($arr) {
        $fp = fopen('files/test.csv', 'a');

        foreach ($arr as $fields) {
            fputcsv($fp, $fields, ";");
        }

        fclose($fp);
        return 0;
    }

}
