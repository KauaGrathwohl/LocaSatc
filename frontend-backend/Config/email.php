<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 2.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * This is email configuration file.
 *
 * Use it to configure email transports of CakePHP.
 *
 * Email configuration class.
 * You can specify multiple configurations for production, development and testing.
 *
 * transport => The name of a supported transport; valid options are as follows:
 *  Mail - Send using PHP mail function
 *  Smtp - Send using SMTP
 *  Debug - Do not send the email, just return the result
 *
 * You can add custom transports (or override existing transports) by adding the
 * appropriate file to app/Network/Email. Transports should be named 'YourTransport.php',
 * where 'Your' is the name of the transport.
 *
 * from =>
 * The origin email. See CakeEmail::from() about the valid values
 */
class EmailConfig
{

	public $default = array(
		'transport' => 'Mail',
		'from' => 'you@localhost',
		'charset' => 'utf-8',
		'headerCharset' => 'utf-8',
	);

	public $smtp = array(
		'transport' => 'Smtp',
		'from' => 'contato@4oito.com.br',
		'host' => 'ssl://email-smtp.us-east-1.amazonaws.com',
		'port' => 465,
		'timeout' => 60,
		'username' => 'AKIAR5EFL5I5PFFFQZXK',
		'password' => 'BN6kkw4bQ0Sh7EKbKbcKRjn0W6Iuy10IKCVNhYHovadj',
		'client' => null,
		'log' => false,
		'charset' => 'utf-8',
		'headerCharset' => 'utf-8',
	);

}
