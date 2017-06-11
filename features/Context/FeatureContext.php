<?php

namespace Context;

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements KernelAwareContext, SnippetAcceptingContext
{
    /** @var KernelInterface $kernel */
    private $kernel;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @return ContainerInterface
     */
    protected function getContainer()
    {
        return $this->kernel->getContainer();
    }

//    /**
//     * @TODO: Recheck this method
//     * @BeforeScenario
//     */
//    public function restoreDatabase()
//    {
//        /** @var \Doctrine\Bundle\DoctrineBundle\Registry $doctrine */
//        $doctrine = $this->getContainer()->get('doctrine');
//
//        /** @var Connection $connection */
//        $connection = $doctrine->getConnection();
//        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0;');
//
//        $tables = $connection->getSchemaManager()->listTableNames();
//        foreach ($tables as $table) {
//            $connection->executeQuery(sprintf('TRUNCATE TABLE %s', $table));
//        }
//
//        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 1;');
//
//        // then we load the fixtures
//        $application = new Application($this->kernel);
//        $application->setAutoExit(false);
//
//        $fixtures = array(
//            'command'          => 'doctrine:fixtures:load',
//            '--no-interaction' => true,
//            '--quiet'          => true,
//            '--env'            => 'test',
//        );
//
//        $application->run(new ArrayInput($fixtures));
//    }

    /**
     * @When /^I check last email "([^"]*)"$/
     */
    public function iCheckLastEmail($email)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();

        $email = $em->getRepository('TSSAutomailerBundle:Automailer')->findOneBy(
            array('toEmail' => $email),
            array('id' => 'DESC')
        );

        $isEmailCreated = $email ? true : false;
        \PHPUnit_Framework_Assert::assertTrue($isEmailCreated, 'The email was not created');

        return $email;
    }

    /**
     * @When /^I check registration email "([^"]*)"$/
     */
    public function iCheckRegistrationEmail($email)
    {
        $email = $this->iCheckLastEmail($email);
        $isRegistration = strpos($email->getSubject(), 'Welcome') !== false ? true : false;
        \PHPUnit_Framework_Assert::assertTrue($isRegistration, 'The email is not a registration email');

        return $email;
    }

    /**
     * @param $emailBody
     * @param $type
     * @return mixed|string
     */
    private function getLinkFromEmailBody($emailBody, $type)
    {
        switch ($type) {
            case 'registration':
                $pathPos = stripos($emailBody, '/register/confirm');
                $link = substr($emailBody, $pathPos);
                $link = preg_replace('/\s+/', ' ', $link);;
                $link = explode(' ', $link)[0];
                break;
            case 'resetting':
                $pathPos = stripos($emailBody, '/resetting/reset');
                $link = substr($emailBody, $pathPos);
                $link = explode('"', $link)[0];
                break;
            default:
                // @TODO: refactor repeated logic
                $pathPos = stripos($emailBody, '/register/confirm');
                $link = substr($emailBody, $pathPos);
                $link = preg_replace('/\s+/', ' ', $link);;
                $link = explode(' ', $link)[0];
        }

        return $link;
    }

    /**
     * @Given /^I follow registration link "([^"]*)"$/
     */
    public function iFollowRegistrationLink($email)
    {
        $email = $this->iCheckRegistrationEmail($email);
        $link = $this->getLinkFromEmailBody($email->getBody(), 'registration');
        $this->visit($link);
    }

    /**
     * @BeforeStep
     */
    public static function prepare(\Behat\Behat\Hook\Scope\BeforeStepScope $event)
    {
        usleep(800000);
    }
}
