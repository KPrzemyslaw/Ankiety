<?php
/**
 * Class reg.php
 *
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */

$html = '

<htmltemplate id="ankiety-edycja-fields">
    <fieldset class="ankiety-edycja-fields">
        <legend>Opis</legend>
        <div class="kpcontainer kp100pr" >
            <span class="ankiety-edycja-fields_main_description"></span>
        </div>
        <div class="kpcontainer kp100pr ankiety-edycja-fieldsList"></div>
        <!--
        <div class="kpcontainer kp100pr">
            <div class="kpcontainer kp94pr kpfloatleft">
                <div class="kpcontainer kp100pr ankiety-edycja-fieldsList"></div>
            </div>
            <div class="kpcontainer kp5pr kpfloatright">
                <button type="button" class="kp99pr kp-questionnaire-edit-page-editNewFieldButton">Edycja</button>
            </div>
        </div>
        -->
    </fieldset>
        <!--
        <div class="kpcontainer kp5pr kpfloatright">
                <button type="button" class="kp99pr kp-questionnaire-edit-page-editNewFieldButton">Edycja</button>
            </div>
        -->
        <legend>Opis</legend>
        <div class="kpcontainer kp100pr" >
            <span class="ankiety-edycja-fields_main_description"></span>
        </div>
        <div class="kpcontainer kp100pr ankiety-edycja-fieldsList"></div>
        <!--
        <div class="kpcontainer kp100pr">
            <div class="kpcontainer kp94pr kpfloatleft">
                <div class="kpcontainer kp100pr ankiety-edycja-fieldsList"></div>
            </div>
            <div class="kpcontainer kp5pr kpfloatright">
                <button type="button" class="kp99pr kp-questionnaire-edit-page-editNewFieldButton">Edycja</button>
            </div>
        </div>
        -->
</htmltemplate>

';

$html = preg_replace('/<!--(.*)-->/Uis', '', $html);
$html = trim(preg_replace('/\s\s+/', ' ', $html));
$html = str_replace('> <', '><', $html);
$html = str_replace('" >', '">', $html);
$html = str_replace("
", '', $html);

$your_string = "
";
$array = str_split($your_string);
foreach($array as $a) {
    $cc = ord($a);
    if($cc < 32 || $cc > 126) {
        $your_string = str_replace($a, '', $your_string);
    }
}

var_dump($your_string);
