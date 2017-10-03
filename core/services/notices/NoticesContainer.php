<?php

namespace EventEspresso\core\services\notices;

use EventEspresso\core\exceptions\InvalidDataTypeException;

defined('EVENT_ESPRESSO_VERSION') || exit;



/**
 * Class NoticesContainer
 * Container for holding multiple Notice objects until they can be processed
 *
 * @package       Event Espresso
 * @author        Brent Christensen
 * @since         $VID:$
 */
class NoticesContainer implements NoticesContainerInterface
{


    /**
     * @var NoticeInterface[] $information
     */
    private $information = array();


    /**
     * @var NoticeInterface[] $attention
     */
    private $attention = array();


    /**
     * @var NoticeInterface[] $error
     */
    private $error = array();


    /**
     * @var NoticeInterface[] $success
     */
    private $success = array();


    /**
     * @param string $notice
     * @param string $file
     * @param string $func
     * @param string $line
     * @param bool   $dismissible
     * @throws InvalidDataTypeException
     */
    public function addInformation($notice, $file = '', $func = '', $line = '', $dismissible = true)
    {
        $this->information[] = new Notice(
            Notice::INFORMATION,
            $notice,
            $file,
            $func,
            $line,
            $dismissible
        );
    }



    /**
     * @param string $notice
     * @param string $file
     * @param string $func
     * @param string $line
     * @param bool   $dismissible
     * @throws InvalidDataTypeException
     */
    public function addAttention($notice, $file = '', $func = '', $line = '', $dismissible = true)
    {
        $this->attention[] = new Notice(
            Notice::ATTENTION,
            $notice,
            $file,
            $func,
            $line,
            $dismissible
        );
    }



    /**
     * @param string $notice
     * @param string $file
     * @param string $func
     * @param string $line
     * @param bool   $dismissible
     * @throws InvalidDataTypeException
     */
    public function addError($notice, $file, $func, $line, $dismissible = true)
    {
        $this->error[] = new Notice(
            Notice::ERROR,
            $notice,
            $file,
            $func,
            $line,
            $dismissible
        );
    }



    /**
     * @param string $notice
     * @param string $file
     * @param string $func
     * @param string $line
     * @param bool   $dismissible
     * @throws InvalidDataTypeException
     */
    public function addSuccess($notice, $file = '', $func = '', $line = '', $dismissible = true)
    {
        $this->success[] = new Notice(
            Notice::SUCCESS,
            $notice,
            $file,
            $func,
            $line,
            $dismissible
        );
    }


    /**
     * @return boolean
     */
    public function hasInformation()
    {
        return ! empty($this->information);
    }



    /**
     * @return boolean
     */
    public function hasAttention()
    {
        return ! empty($this->attention);
    }



    /**
     * @return boolean
     */
    public function hasError()
    {
        return ! empty($this->error);
    }



    /**
     * @return boolean
     */
    public function hasSuccess()
    {
        return ! empty($this->success);
    }


    /**
     * @return int
     */
    public function countInformation()
    {
        return count($this->information);
    }



    /**
     * @return int
     */
    public function countAttention()
    {
        return count($this->attention);
    }



    /**
     * @return int
     */
    public function countError()
    {
        return count($this->error);
    }



    /**
     * @return int
     */
    public function countSuccess()
    {
        return count($this->success);
    }


    /**
     * @return NoticeInterface[]
     */
    public function getInformation()
    {
        return $this->information;
    }



    /**
     * @return NoticeInterface[]
     */
    public function getAttention()
    {
        return $this->attention;
    }



    /**
     * @return NoticeInterface[]
     */
    public function getError()
    {
        return $this->error;
    }



    /**
     * @return NoticeInterface[]
     */
    public function getSuccess()
    {
        return $this->success;
    }


}
