<?php

/*
	Ali Tavakoli

	File: qa-plugin/qa-publish-to-email/qa-publish-to-email.php
	Version: 0.1
	Date: 2014-04-11
	Description: Event module class for publishing questions/answers/comments to email
*/



class qa_publish_to_email_event
{
	function option_default($option)
	{
		if ($option == 'plugin_publish2email_emails')
			return '';

		if ($option == 'plugin_publish2email_fav_categories_only')
			return false;

		if ($option == 'plugin_publish2email_use_bcc')
			return false;
	}

	function admin_form(&$qa_content)
	{
		$saved = false;

		if (qa_clicked('plugin_publish2email_save_button'))
		{
			qa_opt('plugin_publish2email_emails', qa_post_text('plugin_publish2email_emails_field'));
			qa_opt('plugin_publish2email_fav_categories_only', (int)qa_post_text('plugin_publish2email_fav_cats_field'));
			qa_opt('plugin_publish2email_use_bcc', (int)qa_post_text('plugin_publish2email_use_bcc_field'));
			$saved = true;
		}

		return array(
			'ok' => $saved ? 'Settings saved' : null,

			'fields' => array(
				array(
					'label' => 'Notification Emails:',
					'type' => 'text',
					'value' => qa_opt('plugin_publish2email_emails'),
					'suffix' => '(separate multiple emails with commas or semicolons)',
					'tags' => 'NAME="plugin_publish2email_emails_field"',
				),
				array(
					'label' => 'Only send emails for favorite categories (emails must be registered)',
					'type' => 'checkbox',
					'value' => qa_opt('plugin_publish2email_fav_categories_only'),
					'tags' => 'NAME="plugin_publish2email_fav_cats_field"',
				),
				array(
					'label' => 'Use BCC instead of To for emails',
					'type' => 'checkbox',
					'value' => qa_opt('plugin_publish2email_use_bcc'),
					'tags' => 'NAME="plugin_publish2email_use_bcc_field"',
				),
			),

			'buttons' => array(
				array(
					'label' => 'Save Changes',
					'tags' => 'NAME="plugin_publish2email_save_button"',
				),
			),
		);
	}

	function process_event($event, $userid, $handle, $cookieid, $params)
	{
		require_once QA_INCLUDE_DIR.'qa-class.phpmailer.php';
		require_once QA_INCLUDE_DIR.'qa-app-format.php';
		require_once QA_INCLUDE_DIR.'qa-util-string.php';

		switch ($event)
		{
		case 'q_post':
			$subject = $params['title'];
			// fall through instead of breaking
		case 'a_post':
		case 'c_post':
			if (!isset($subject))
				$subject = "RE: " . $params['question']['title'];

			// Get the configured list of emails and split by commas/semi-colons (and possible whitespace)
			$emails = preg_split('/[,;] */', qa_opt('plugin_publish2email_emails'), -1, PREG_SPLIT_NO_EMPTY);

			if (count($emails) == 0)
				return;

			// Filter for emails that have this post's category as favorite
			if (qa_opt('plug_publish2email_fav_categories_only'))
				$emails = $this->qa_db_favorite_category_emails($emails, $params['categoryid']);

			$mailer=new PHPMailer();
			$mailer->CharSet='utf-8';

			$mailer->Sender=qa_opt('from_email');
			$mailer->From=qa_opt('from_email');
			$mailer->FromName=(isset($handle) ? $handle : qa_opt('site_title'));

			if (qa_opt('qa_publish2email_use_bcc'))
			{
				foreach ($emails as $email)
				{
					$mailer->AddBCC($email);
				}
			}
			else
			{
				foreach ($emails as $email)
				{
					$mailer->AddAddress($email);
				}
			}

			$mailer->Subject=$subject;

			if ($params['format'] === 'html')
			{
			        $mailer->IsHTML(true);
				$mailer->Body=$params['content'];
			}
			else
			{
			        $mailer->IsHTML(false);
				$mailer->Body=$params['text'];
			}

			if (qa_opt('smtp_active'))
			{
				$mailer->IsSMTP();
				$mailer->Host=qa_opt('smtp_address');
				$mailer->Port=qa_opt('smtp_port');
			}

			if (qa_opt('smtp_secure'))
				$mailer->SMTPSecure=qa_opt('smtp_secure');

			if (qa_opt('smtp_authenticate'))
			{
				$mailer->SMTPAuth=true;
				$mailer->Username=qa_opt('smtp_username');
				$mailer->Password=qa_opt('smtp_password');
			}

			$mailer->Send();
		}
	}

	function qa_db_category_favorite_emails($emails, $categoryid)
	{
		require_once QA_INCLUDE_DIR.'qa-app-updates.php';
                require_once QA_INCLUDE_DIR.'qa-db-selects.php';

		return qa_db_select_with_pending(array(
			'columns' => array('email' => 'DISTINCT ^users.email'),
			'source' => "^users JOIN ^userfavorites USING (userid) WHERE ^users.email IN ($) AND ^userfavorites.entityid=$ AND ^userfavorites.entitytype=$",
			'arguments' => array($emails, $categoryid, QA_ENTITY_CATEGORY),
		));
	}
};

/*
	Omit PHP closing tag to help avoid accidental output
*/
