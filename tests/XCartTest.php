<?php

use PHPUnit\Framework\TestCase;
use Facebook\WebDriver\Remote\WebDriverCapabilityType;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

class XCartTest extends TestCase {

    protected $url = 'http://demostore.x-cart.com';
    protected $webDriver;

    public function setUp()
    {
        $capabilities = array(WebDriverCapabilityType::BROWSER_NAME => 'chrome');
        $this->webDriver = RemoteWebDriver::create('http://localhost:4444/wd/hub', $capabilities);
        $this->webDriver->manage()->window()->maximize();
    }
    public function tearDown()
    {
        $this->webDriver->quit();
    }
    public function testXcart()
    {
        //открыть главную страницу магазина, проверить что это главная страница магазина (выбрать признак который соответствует главной странице магазина)
        $this->webDriver->get($this->url);
        // checking that page title contains word 'X-Cart Demo store company > Catalog'
        $this->assertContains('X-Cart Demo store company > Catalog', $this->webDriver->getTitle());

        // открыть первую категорию, проверить, что это нужная категория (выбрать нужный уникальный признак)
        $search1 = $this->webDriver->findElement(WebDriverBy::xpath('.//*[@id=\'header-area\']//span[text()=\'Catalog\']'))->click();
        $search2 = $this->webDriver->findElement(WebDriverBy::xpath('.//*[@id=\'header-area\']//span[text()=\'Apparel\']'))->click();

        // checking that page title contains word 'Apparel'
        $this->assertContains('Apparel', $this->webDriver->getTitle());

        //открыть первый продукт со страницы категории, проверить что это нужный продукт(выбрать нужный уникальный признак).

        $search3 = $this->webDriver->findElement(WebDriverBy::xpath('.//*[@class=\'block block-block\']//a[text() ="Mordorable! Fitted Ladies\' Tee"]'))->click();

        // checking that page title contains word 'Mordorable! Fitted Ladies' Tee'
        $this->assertContains('Mordorable! Fitted Ladies\' Tee', $this->webDriver->getTitle());

        //Добавить продукт в карту, проверить что продукт успешно добавился в карту
        $search4 = $this->webDriver->findElement(WebDriverBy::xpath('.//*[ @type=\'submit\' and span=\'Add to cart\']'))->click();

        $this->webDriver->wait(3)->until(

        // checking that page contains word 'You have just added'
            WebDriverExpectedCondition::textToBePresentInElement(WebDriverBy::xpath('.//*[@id=\'ui-id-4\']'), 'You have just added' )
        );
    }
}
?>
