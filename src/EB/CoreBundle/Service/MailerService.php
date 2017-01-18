<?php

namespace EB\CoreBundle\Service;

class MailerService
{
    /** @var \Swift_Mailer $mailer */
    protected $mailer;

    /** @var \Twig_Environment $twig */
    protected $twig;

    /**
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $twig
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * @param $recipient
     * @param $templateName
     * @param array $options
     * @todo improve this method and class with the richer variant from gist
     */
    public function sendEmail($recipient, $templateName, array $options = array())
    {
        /** @var \Twig_Template $template */
        $template = $this->twig->loadTemplate($templateName);
        $subject  = trim($template->renderBlock('subject', $options));
        $text     = trim($template->renderBlock('text', $options));
        $html     = trim($template->renderBlock('html', $options));

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom(array('no-reply@getaride.ro' => 'no-reply@getaride.ro'))
            ->setTo($recipient)
        ;

        if (!empty($html)) {
            $message->setBody($html, 'text/html');
        } else {
            $message->setBody($text);
        }

        $this->mailer->send($message);
    }
}
