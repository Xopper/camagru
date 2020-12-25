<?php

namespace System\Http;

class Upload
{
	/**
	 * the uploaded file variable
	 * extension
	 * 
	 * @var array
	 */
	private	$file = [];

	/**
	 * The uploaded filename
	 * 
	 * @var string
	 */
	private $fileName;

	/**
	 * The uploaded fileName without extension
	 * 
	 * @var string
	 */
	private	$nameOnly;

	/**
	 * the uploaded file extension
	 * 
	 * @var string
	 */
	private $extension;

	/**
	 * The uploaded file mime type
	 * 
	 * @var string
	 */
	private $mime;

	/**
	 * The uploaded Temp file path
	 * 
	 * @var string
	 */
	private $tempFile;

	/**
	 * File size in bytes
	 * 
	 * @var int
	 */
	private $fileSize;

	/**
	 * Uploaded file error
	 * 
	 * @var int
	 */
	private $error;

	/**
	 * The allowed image extensions to consider
	 * that the uploaded file is an image
	 * 
	 * @var array
	 */
	private $allowedImageExtensions = ['gif', 'jpg', 'jpeg', 'png', 'webp'];

	/**
	 * Constructor
	 * 
	 * @param string $input
	 */
	public function __construct($input)
	{
		$this->getFileData($input);
	}

	/**
	 * Start collecting uploded file data
	 * 
	 * @param string $input
	 * @return void
	 */
	private function getFileData($input)
	{
		// pre($_FILES);
		if (empty($_FILES[$input])){
			return;
		}
		$this->error = $_FILES[$input]['error'];

		if ($this->error != UPLOAD_ERR_OK){
			return;
		}

		$this->file = $_FILES[$input]; // after checking if there is no error

		$this->fileName = $this->file['name'];

		pre(pathinfo($this->fileName));
		$fileInfo = pathinfo($this->fileName); // get all info about the given file

		$this->nameOnly = $fileInfo['filename'];
		$this->extension = strtolower($fileInfo['extension']);

		$this->tempFile = $this->file['tmp_name'];
		$this->mime = mime_content_type($this->tempFile);

		$this->fileSize = $this->file['size'];
	}

	/**
	 * determine if the file exists or not
	 * 
	 * @return bool
	 */
	public function exists()
	{
		return !empty($this->file);
	}

	/**
	 * Get the file name include the extension
	 * 
	 * @return string
	 */
	public function getFileName()
	{
		return $this->fileName;
	}

	/**
	 * Get the file name only
	 * 
	 * @return string
	 */
	public function getNameOnly()
	{
		return $this->nameOnly;
	}

	/**
	 * Get the extension
	 * 
	 * @return string
	 */
	public function getExtension()
	{
		return $this->extension;
	}

	/**
	 * Get the mime type
	 * 
	 * @return string
	 */
	public function getMimeType()
	{
		return $this->mime;
	}

	/**
	 * get the file size in bytes
	 * 
	 * @return int
	 */
	public function getFileSize()
	{
		return $this->fileSize;
	}

	/**
	 * determine whether the uploaded file is an image
	 * 
	 * @return bool
	 */
	public function isImage()
	{
		return strpos($this->getMimeType(), 'image/') === 0 and 
			   in_array($this->getExtension(), $this->allowedImageExtensions);
	}

	/**
	 * upload file to the given target
	 * it will be like $file = $this->request->file('image');
	 * it will be like $file->moveTo($this->file->to("public/images"));
	 * 
	 * @param string $target
	 * @param string $newFileName null by default
	 * @return string
	 */
	public function moveTo($target, $newFileName = null)
	{
		$fileName = $newFileName ?: sha1(mt_rand()) . "_" . sha1(mt_rand()); // total lenght 81 chars
		$fileName .= "." . $this->getExtension();
		if (!is_dir($target)){
			mkdir($target, 0777, true);
		}
		$uploadedFilePath = $target . "/" . $fileName;
		move_uploaded_file($this->tempFile, $uploadedFilePath);
		return $fileName;
	}
}
