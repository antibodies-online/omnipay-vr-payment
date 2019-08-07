<?php


namespace Omnipay\VrPayment\Message;


use Omnipay\Common\Message\AbstractResponse as OmnipayAbstractResponse;

class NotificationServerResponse extends OmnipayAbstractResponse
{
    /**
     * Whether to exit immediately on responding.
     */
    protected $exit_on_response = true;

    /**
     * This method checks if the submitted data could be parsed.
     */
    public function isSuccessful()
    {
        return $this->request->isValid();
    }


    public function acknowledge($exit = true)
    {
        if ($exit) {
            exit;
        }
    }
    /**
     * Added for consistency with Sage Pay Server.
     * The nextUrl and detail message are not used.
     */
    public function accept($nextUrl = null, $detail = null)
    {
        $this->acknowledge($this->exit_on_response);
    }
    /**
     * Added for consistency with Sage Pay Server.
     * The nextUrl and detail message are not used.
     */
    public function reject($nextUrl = null, $detail = null)
    {
        // Don't output anything - just exit.
        // The gateway will treat that as a non-acceptance, but will try
        // to send the notification multiple times.
        if ($this->exit_on_response) {
            exit;
        }
    }
    /**
     * Set or reset flag to exit immediately on responding.
     * Switch auto-exit off if you have further processing to do.
     *
     * @param boolean true to exit; false to not exit.
     */
    public function setExitOnResponse($value)
    {
        $this->exit_on_response = (bool)$value;
    }
    /**
     * Alias of acknowledge as a more consistent OmniPay lexicon.
     */
    public function send($exit = true)
    {
        return $this->acknowledge($exit);
    }
}