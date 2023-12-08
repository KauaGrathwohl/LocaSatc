<?php
App::uses('CakeEmail', 'Network/Email');
App::uses('EmailComponent', 'Controller/Component');

class xEmailComponent extends EmailComponent
{
    public $break_point = "\r\n";

    public function send($content = null, $template = null, $layout = null)
    {
        return parent::send($content, $template, $layout);
    }

    public function dequeue($limit=1)
    {
        $this->_controller->loadModel('EmailQueue');

        $success = false;

        $queue = $this->_controller->EmailQueue->find('all', array(
            'conditions' => array(
                "EmailQueue.store_id" => $this->_controller->storeId,
                "EmailQueue.send_date <= " => date("Y-m-d H:i:s")
            ),
            'recursive' => -1,
            'limit' => $limit
        ));
        foreach ($queue as $i => $email)
        {
            $this->replyTo = null;
            $this->cc = array();
            $this->bcc = array();
            $this->_boundary = null;

            if(!empty($email['EmailQueue']['from']))
            {
                $this->from = $email['EmailQueue']['from'];
            }
            else
            {
                $this->from = $this->_controller->storeData['Store']["mailserver_username"];
            }

            if(!empty($email['EmailQueue']['reply_to']))
            {
                $this->replyTo = $email['EmailQueue']['reply_to'];
            }

            $this->to = $email['EmailQueue']['to'];

            if(!empty($email['EmailQueue']['cc']))
            {
                $this->cc = explode(", ", $email['EmailQueue']['cc']);
            }

            if(!empty($email['EmailQueue']['bcc']))
            {
                $this->bcc = explode(", ", $email['EmailQueue']['bcc']);
            }

            $this->sendAs = $email['EmailQueue']['type'];
            $this->subject = $email['EmailQueue']['subject'];

            if(!empty($email['EmailQueue']['boundary']))
            {
                $this->_boundary = $email['EmailQueue']['boundary'];
            }

            $a = explode($this->break_point, $email['EmailQueue']['body']);
            if($this->queueSend($a))
            {
                $this->_controller->EmailQueue->id = $email['EmailQueue']['id'];
                if($this->_controller->EmailQueue->delete()) $success = true;
            }
        }

        return $success;
    }

    public function queueSend($content = null)
    {
        $lib = new CakeEmail();
        $lib->charset = $this->charset;
        $lib->headerCharset = $this->charset;

        $lib->from($this->_formatAddresses((array)$this->from));
        if (!empty($this->to)) {
            $lib->to($this->_formatAddresses((array)$this->to));
        }
        if (!empty($this->cc)) {
            $lib->cc($this->_formatAddresses((array)$this->cc));
        }
        if (!empty($this->bcc)) {
            $lib->bcc($this->_formatAddresses((array)$this->bcc));
        }
        if (!empty($this->replyTo)) {
            $lib->replyTo($this->_formatAddresses((array)$this->replyTo));
        }
        if (!empty($this->return)) {
            $lib->returnPath($this->_formatAddresses((array)$this->return));
        }
        if (!empty($this->readReceipt)) {
            $lib->readReceipt($this->_formatAddresses((array)$this->readReceipt));
        }

        $lib->subject($this->subject);

        $headers = array('X-Mailer' => $this->xMailer);
        foreach ($this->headers as $key => $value) {
            $headers['X-' . $key] = $value;
        }
        if ($this->date) {
            $headers['Date'] = $this->date;
        }
        $lib->setHeaders($headers);

        $lib->emailFormat($this->sendAs);

        $lib->transport(ucfirst($this->delivery));
        if ($this->delivery === 'mail') {
            $lib->config(array('eol' => $this->lineFeed, 'additionalParameters' => $this->additionalParams));
        } elseif ($this->delivery === 'smtp') {
            $lib->config($this->smtpOptions);
        } else {
            $lib->config(array());
        }

        $sent = $lib->send($content);

        $this->htmlMessage = $lib->message(CakeEmail::MESSAGE_HTML);
        if (empty($this->htmlMessage)) {
            $this->htmlMessage = null;
        }
        $this->textMessage = $lib->message(CakeEmail::MESSAGE_TEXT);
        if (empty($this->textMessage)) {
            $this->textMessage = null;
        }

        $this->_header = array();
        $this->_message = array();

        return $sent;
    }

    public function renderBody($content = null, $template = null, $layout = null)
    {
        if($template) $this->template = $template;
        if($layout) $this->layout = $layout;
        if(is_array($content)) $content = implode("\n", $content) . "\n";

        $lib = new CakeEmail();
        $lib->charset = $this->charset;
        $lib->headerCharset = $this->charset;

        $lib->helpers($this->_controller->helpers);
        $lib->template($this->template, $this->layout)->viewVars($this->_controller->viewVars)->emailFormat($this->sendAs);

        $methodRender = new ReflectionMethod('CakeEmail', '_render');
        $methodRender->setAccessible(true);

        $methodWrap = new ReflectionMethod('CakeEmail', '_wrap');
        $methodWrap->setAccessible(true);

        $methodRender->invoke($lib, $methodWrap->invoke($lib, $content));

        $this->htmlMessage = $lib->message(CakeEmail::MESSAGE_HTML);
        if (empty($this->htmlMessage)) {
            $this->htmlMessage = null;
        }
        $this->textMessage = $lib->message(CakeEmail::MESSAGE_TEXT);
        if (empty($this->textMessage)) {
            $this->textMessage = null;
        }

        return $this->sendAs == 'text' ? $this->textMessage : $this->htmlMessage;
    }

    public function queue($send_date = null, $content = null, $template = null, $layout = null)
    {
        $body = $this->renderBody($content, $template, $layout);
        $body = is_array($body) ? implode($this->break_point, $body) : trim($body);

        if(empty($send_date)) $send_date = date("Y-m-d H:i:s");

        $new_queue = array(
            "store_id" => $this->_controller->storeId,
            "send_date" => (string) $send_date,
            "tag" => "default",
            "type" => $this->sendAs,
            "boundary" => is_null($this->_boundary) ? null : (string) $this->_boundary,
            "from" => (string) $this->from,
            "reply_to" => !empty($this->replyTo) ? (string) $this->replyTo : null,
            "to" => is_array($this->to) ? implode(", ", $this->to) : (string) $this->to,
            "cc" => is_array($this->cc) ? implode(", ", $this->cc) : (string) $this->cc,
            "bcc" => is_array($this->bcc) ? implode(", ", $this->bcc) : (string) $this->bcc,
            "subject" => $this->subject,
            "body" => $body,
            "ip" => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null
        );

        $this->_controller->loadModel('EmailQueue');
        $this->_controller->EmailQueue->create();

        return $this->_controller->EmailQueue->saveAll($new_queue);
    }
}
?>
