<?php
/**
 * @package        solo
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

namespace Solo\Controller;


class Log extends ControllerDefault
{
	/**
	 * Allows the user to select the log origin to display or display the log file itself
	 *
	 * @return  void
	 */
	public function main()
	{
		$tag = $this->input->get('tag', null, 'cmd');
		
		if (empty($tag))
		{
			$tag = null;
		}
		
		$model = $this->getModel();
		$model->setState('tag', $tag);

		$this->display();
	}

	/**
	 * Renders the log contents for use in an iFrame
	 *
	 * @return  void
	 */
	public function iframe()
	{
		$tag = $this->input->get('tag', null, 'cmd');

		if (empty($tag))
		{
			$tag = null;
		}

		$model = $this->getModel();
		$model->setState('tag', $tag);

		$this->display();
	}

	/**
	 * Downloads the log file as a plain text file
	 *
	 * @return  void
	 */
	public function download()
	{
		$tag = $this->input->get('tag', null, 'cmd');

		if (empty($tag))
		{
			$tag = null;
		}

		@ob_end_clean(); // In case some braindead plugin spits its own HTML
		header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
		header("Content-Description: File Transfer");
		header('Content-Type: text/plain');
		header('Content-Disposition: attachment; filename="Akeeba Solo Debug Log.txt"');

		$model = $this->getModel();
		$model->setState('tag', $tag);

		$model->echoRawLog();

		@flush();
		$this->container->application->close();
	}

} 