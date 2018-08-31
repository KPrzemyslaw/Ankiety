<?php
include_once 'KPAutoloader.php';


use KPrzemyslaw\KPConfigure;
use KPrzemyslaw\KPFile;
use KPrzemyslaw\KPArguments;

/**
 * class KPHtmlCompiler
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */
class KPHtmlCompiler
{
    public function run()
    {
        $args = new KPArguments();

        if(!$args->getData('bundle')) {
            echo "Bundle must be set!\n\n";
            exit();
        }

        $indexPath = sprintf(
            '%s/html/%s.html',
            KPConfigure::APP_CONTENT,
            $args->getData('bundle')
        );
        if(file_exists($indexPath)) {
            $htmlXmlContent = file_get_contents($indexPath);
            $parsedContent = $this->parseContent($htmlXmlContent);
            $parsedContent = $this->clearContent($parsedContent);
            file_put_contents($indexPath, $parsedContent);

            chmod($indexPath, 0666);
        }
    }

    /**
     * @param string    $content
     * @return string
     */
    private function parseContent($content)
    {
        $includePattern = '/<include(.*)src="(.*)"(.*)\/>/i';
        if(preg_match($includePattern, $content, $data)) {
            $htmlTemplatePattern = sprintf(
                '/<htmltemplate(.*?)id=\"%s\"(.*?)>(.*?)<\/htmltemplate>/si',
                str_replace('/', '\/', $data[2])
            );

            if(preg_match($htmlTemplatePattern, $content, $dataHtml)) {
                $content = str_replace($data[0], $dataHtml[3], $content);
                $content = $this->parseContent($content);
            }
        }
        return $content;
    }

    /**
     * @param string $content
     * @return string
     */
    private function clearContent($content)
    {
        $content = preg_replace('/<!--(.*)-->/Uis', '', $content);
        //$content = trim(preg_replace('/\s\s+/', ' ', $content));
        $content = str_replace('> <', '><', $content);
        $content = str_replace('" >', '">', $content);

        /*$contentSplited = str_split($content);
        foreach($contentSplited as $char) {
            $cc = ord($char);
            if($cc < 32 || $cc > 126) {
                $content = str_replace($char, '', $content);
            }
        }*/
        return $content;
    }
}

(new KPHtmlCompiler())->run();

