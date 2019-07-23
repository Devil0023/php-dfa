<?php


class CheckWords extends Base
{
    private $map = [];
    private $exclude_character = ['@', '$', '&', '\\', '/', '|'];

    /**
     * 设定排除的字符
     * @param array $character
     */
    public function setExcludeCharacters($character = []){
        $this->exclude_character = $character;
    }

    /**
     * 获取需要排除的字符
     * @return array
     */
    public function getExcludeCharacter(){
        return $this->exclude_character;
    }

    /**
     * 选择map
     * @param string $map
     * @return bool
     * @throws \Exception
     */
    public function setMap($map)
    {

        $map_file = $this->map_folder . '/' . $map . '.map';

        if (!is_file($map_file)) {
            throw new \Exception("Can't find map file: " . $map);
        }

        $this->map = json_decode(file_get_contents($map_file), true);

        return true;
    }

    /**
     * 检查
     * @param $string
     * @return array
     */
    public function check($string = '')
    {

        if (!$string)
            return [
                "hits" => 0,
                "words" => [],
            ];


        $word = "";
        $words = [];

        $length = mb_strlen($string);
        $nowMap = $this->map;

        for ($i = 0; $i < $length; $i++) {

            $nowWord = mb_substr($string, $i, 1);

            if (in_array($nowWord, $this->exclude_character)) {
                continue;
            }

            $nowMap = $nowMap[$nowWord];

            if ($nowMap) {

                $word .= $nowWord;

                if ($nowMap['end']) {
                    array_push($words, $word);

                    $word = '';
                    $nowMap = $this->map;

                }

            } else {

                if (!empty($word)) {
                    $i--;
                }

                $word = '';
                $nowMap = $this->map;
            }
        }

        return [
            "hits" => count($words),
            "words" => $words,
        ];
    }

}