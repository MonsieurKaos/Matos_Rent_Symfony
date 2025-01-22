<?php
// src/Service/MyMailerService.php
namespace App\Services;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
	public function __construct(private MailerInterface $mailer)
 {
 }

	/**
	 * @throws TransportExceptionInterface
	 */
	public function sendEmail(string $from, string $to, string $replyTo, string $subject, string $body): void
	{
		$email = (new Email())
			->from($from)
			->to($to)
			->replyTo($replyTo)
			->subject($subject)
			->priority(Email::PRIORITY_HIGH)
			->text($body);

		$this->mailer->send($email);
	}
}
