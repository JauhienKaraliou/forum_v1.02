<?php

/**
 * Class Template
 * Класс для работы с шаблонами html
 */
class Template{
    private $tpl;

    /**
     * Извлекает нужный шаблон html
     * @param $name название шаблона html
     */
    public function __construct($name){
        $fileName = TPL_DIR . DIRECTORY_SEPARATOR . $name . '.html';
        if(file_exists($fileName)){
            $this -> tpl = file_get_contents($fileName);
        }
    }

    /**
     * Заполняет форму определенными ошибками
     * @param array $data массив ошибок
     * @return mixed
     */
    public function processTemplateErrorOutput( array $data = array()){
        foreach($data as $key => $val){
            $this -> tpl = str_replace(
                "<p class=\"help-block\" data-name=\"$key\"></p>",
                "<p class=\"help-block\" data-name=\"$key\">$val</p>",
                $this -> tpl
            );
        }
        return  $this -> tpl;
    }

    /**
     * Соединяет данные с шаблоном html
     * @param array $data данные для вставки в шаблон
     * @return mixed
     */
    public function processTemplate(array $data = array()){
        foreach($data as $key => $val){
            $this -> tpl = str_replace('{{'.$key.'}}', $val,  $this -> tpl);
        }
        return  $this -> tpl;
    }

    /**
     * Возвращает шаблон в виде строки
     * @return string
     */
    public function __toString(){
        return $this -> tpl;
    }

    /**
     *                              //@todo напиши коммент
     * @param $name
     * @param array $statements
     * @return mixed|string
     */
    public static function getPageElement($name, array $statements)
    {
        $fileName = TPL_DIR . DIRECTORY_SEPARATOR . $name . '.html';
        $p='';
        if(file_exists($fileName)) {
            $template = file_get_contents($fileName);
            foreach ($statements as $key => $val) {
                $p = str_replace('{{' . $key . '}}', $val, $template);
            }
        }
        return $p;
    }
}