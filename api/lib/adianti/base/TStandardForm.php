<?php
namespace Adianti\Base;

use Adianti\Control\TPage;

/**
 * Standard page controller for forms
 *
 * @version    2.0
 * @package    base
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006-2014 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class TStandardForm extends TPage
{
    protected $form;
    
    use AdiantiStandardFormTrait;
}
