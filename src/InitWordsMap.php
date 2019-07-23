<?php


class InitWordsMap extends Base
{

    /**
     * 加载词库生成树形结构 存进文件
     */
    public function initMap()
    {
        $sensitive_words = [];

        $handle = opendir($this->raw_folder);

        while (($basename = readdir($handle)) !== false) {

            if ($basename === '.' || $basename === '..') {
                continue;
            }

            $file = fopen($this->raw_folder . '/' . $basename, 'r');

            if ($file) {

                while (!feof($file)) {
                    $line = trim(fgets($file));
                    $this->mergePathToMap($sensitive_words, $line);
                }

                $pathinfo = pathinfo($this->raw_folder . '/' . $basename);

                file_put_contents($this->map_folder . '/' . $pathinfo['filename'] . '.map', json_encode($sensitive_words));

                echo date('Y-m-d H:i:s') . ' Build: ' . $pathinfo['filename'] . '.map' . PHP_EOL;

                unset($sensitive_words);
            }
        }

    }

    /**
     * 将字符串变成树形结构
     * @param $str
     * @param int $length
     * @return array
     */
    private function getStrPath($str, $length = 0)
    {
        $result = [];
        $key = mb_substr($str, $length, 1);
        if ($length === mb_strlen($str) - 1) {
            $result[$key] = [
                'end' => 1
            ];
            return $result;
        } else {
            $result[$key] = $this->getStrPath($str, $length + 1);
            return $result;
        }
    }

    /**
     * 将字符串变成树形结构并插入进map
     * @param $map
     * @param $str
     * @param int $length
     */
    private function mergePathToMap(&$map, $str, $length = 0)
    {
        $key = mb_substr($str, $length, 1);
        if ($length === mb_strlen($str) - 1) {
            $map[$key] = [
                'end' => 1,
            ];
        } else {
            if ($map[$key]) {
                $this->mergePathToMap($map[$key], $str, $length + 1);
            } else {
                $map[$key] = $this->getStrPath($str, $length + 1);
            }
        }
    }
}