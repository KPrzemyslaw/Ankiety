<?php
include_once 'KPAutoloader.php';


use KPrzemyslaw\KPConfigure;
use KPrzemyslaw\KPFile;
use KPrzemyslaw\KPArguments;

/**
 * Class KPContentCompiler
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */
class KPContentCompiler
{
    public function run()
    {
        $args = new KPArguments();

        if(!$args->getData('bundle')) {
            echo "Bundle must be set!\n\n";
            exit();
        }

        foreach(['js', 'css', 'html'] as $type) {
            $indexPath = sprintf(
                '%s/%s/%s.%s',
                KPConfigure::APP_CONTENT,
                $type,
                $args->getData('bundle'),
                $type
            );
            $file = fopen($indexPath, 'w');

            $pathList = KPFile::getFilesList(
                KPConfigure::BUNDLES_DIRECOTRY.'/'.ucfirst($args->getData('bundle')),
                '(.*)\.'.$type,
                KPFile::TYPE_FILE,
                true
            );

            $indexHtml = 'index.html';
            foreach($pathList as $path) {
                if(strrpos($path, $indexHtml) !== false) {
                    continue;
                }
                $jsContent = file_get_contents($path);
                if($type != 'html') {
                    $jsContent = $this->clearContent($jsContent);
                }
                fwrite($file, $jsContent.PHP_EOL);
            }
            fclose($file);

            chmod($indexPath, 0666);
        }
    }

    /**
     * @param string $content
     * @return string
     */
    private function clearContent($content)
    {
        $cr = chr(13);
        $lf = chr(10);

        $ddds = '://';
        $dddst = '_:_/_/_';

        /*$table = [
            'Ä…' => 'a',
            'Ä‡' => 'c',
            'Ä™' => 'e',
            'Ĺ‚' => 'l',
            'Ĺ„' => 'n',
            'Ăł' => 'o',
            'Ĺ›' => 's',
            'Ĺş' => 's',
            'ĹĽ' => 'z',
            'Ä„' => 'A',
            'Ä†' => 'C',
            'Ä�' => 'E',
            'Ĺ�' => 'L',
            'Ĺ�' => 'N',
            'Ă“' => 'O',
            'Ĺš' => 'S',
            'Ĺą' => 'Z',
            'Ĺ»' => 'Z'
        ];
        $content = strtr($content, $table);*/

        $content = str_replace($ddds, $dddst, $content);

        //$content = preg_replace("/\/\/(.*)($cr|$lf)/Uis", '', $content);
        $content = preg_replace("/\/\/(.*)($cr|$lf)/", '', $content);
        //$content = trim(preg_replace('/\s\s+/', ' ', $content));

        $content = str_replace($dddst, $ddds, $content);

        //$contentSplited = str_split($content);
        //$contentSplited = preg_split('//u', $content);
        //$contentSplited = $this->str_split_unicode($content);
        /*foreach($contentSplited as $char) {
            $cc = ord($char);
            if($cc < 32 || $cc > 126) {
                $content = str_replace($char, '', $content);
            }
        }*/

        return $content;
    }

    /*private function str_split_unicode($str, $length = 1) {
        $tmp = preg_split('~~u', $str, -1, PREG_SPLIT_NO_EMPTY);
        if ($length > 1) {
            $chunks = array_chunk($tmp, $length);
            foreach ($chunks as $i => $chunk) {
                $chunks[$i] = join('', (array) $chunk);
            }
            $tmp = $chunks;
        }
        return $tmp;
    }*/
}

(new KPContentCompiler())->run();

