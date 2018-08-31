<?php
/**
 * Class Questionnaire\Index.php
 *
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */


namespace Bundles\Client\Questionnaire;

use KPrzemyslaw\Actions\KPController;

/**
 * Class Index
 * @package Bundles\Client\Questionnaire
 */
class Index extends KPController
{
    /** @var \Bundles\Client\Questionnaire $questionnaire */
    private $questionnaire = null;

    /**
     * Save constructor.
     * @param \KPrzemyslaw\KPRequest  $requestObject
     * @param \KPrzemyslaw\KPSession  $sessionObject
     * @param \PDO                  $dbResource
     * @param \KPrzemyslaw\KPUser     $user
     */
    public function __construct(\KPrzemyslaw\KPRequest $requestObject, \KPrzemyslaw\KPSession $sessionObject, \PDO $dbResource, \KPrzemyslaw\KPUser $user)
    {
        parent::__construct($requestObject, $sessionObject, $dbResource, $user);

        $this->questionnaire = new \Bundles\Client\Questionnaire($dbResource);
    }

    public function run()
    {
        $curr = $this->questionnaire->getCurrent();

        $ankieta = [
            'pages' => [
                $this->getPage1(),
                $this->getPage2()
            ]
        ];

        return [
            'data' => $curr,
            //'data' => $ankieta
        ];
    }

    private function getPage1()
    {
        $questions = [];

        $questions[] = [
            'type' => 'radio',
            'label' => 'Krzysiek',
            'name' => 'imie'
        ];
        $questions[] = [
            'type' => 'radio',
            'label' => 'Marek',
            'name' => 'imie'
        ];
        $questions[] = [
            'type' => 'radio',
            'label' => 'Zofia',
            'name' => 'imie'
        ];
        $questions[] = [
            'type' => 'radio-textarea',
            'label' => 'Tylko inaczej'
        ];
        $questions[] = [
            'type' => 'radio-text',
            'label' => 'Tylko inaczej'
        ];
        $questions[] = [
            'type' => 'textarea',
            'label' => 'Jakoś inaczej - wpisz'
        ];
        $questions[] = [
            'type' => 'other',
            'label' => 'Jakoś inaczej - wpisz'
        ];
        $questions[] = [
            'type' => 'textarea',
            'value' => 'value',
            'label' => 'Jakoś jeszcze inaczej - wpisz'
        ];
        $questions[] = [
            'type' => 'checkbox',
            'name' => 'xxxxxxxxxxxx',
            'label' => 'Jakoś inaczej - wpisz na xxxxxxxxx'
        ];
        $questions[] = [
            'type' => 'checkbox',
            'name' => 'yyyyyyyyyyyy',
            'label' => 'Jakoś inaczej - wpisz na yyyyyyyyyyyy',
            'required' => true,
        ];
        $questions[] = [
            'type' => 'checkbox',
            'name' => 'zzzzzzzzzzzzzzzzz',
            'label' => 'Jakoś inaczej - wpisz na zzzzzzzzzzzzzzzzz'
        ];

        return [
            'title' => 'Wprowdzenie',
            'begin' => 'To jest taka nakieta, bla bla bla. Jak masz na imię',
            'questions' => $questions,
            'ending' => 'to juz koniec ankiety, możesz przejść dalej'
        ];
    }

    private function getPage2()
    {
        $questions = [];

        $questions[] = [
            'type' => 'radio',
            'label' => 'Krzysiek'
        ];
        $questions[] = [
            'type' => 'radio',
            'label' => 'Marek'
        ];
        $questions[] = [
            'type' => 'textarea',
            'label' => 'Jakoś jeszcze inaczej - wpisz'
        ];

        return [
            'title' => 'qqqqqqqqqq',
            'begin' => 'wwwwwwwwwwwwwwwwwwwwwwwww',
            'questions' => $questions,
            'ending' => 'eeeeeeeeeeeeeeeeeeeeeee'
        ];
    }
}
