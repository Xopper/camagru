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
	private	$files = [];

	/**
	 * The uploaded filesname
	 * 
	 * @var array
	 */
	private $filesName = [];

	/**
	 * The uploaded filesName without extension
	 * 
	 * @var array
	 */
	private	$namesOnly = [];

	/**
	 * the uploaded files extension
	 * 
	 * @var array
	 */
	private $extensions = [];

	/**
	 * The uploaded files mime type
	 * 
	 * @var array
	 */
	private $mimes = [];

	/**
	 * The uploaded Temp files path
	 * 
	 * @var array
	 */
	private $tempFiles = [];

	/**
	 * Files size in bytes
	 * 
	 * @var array
	 */
	private $filesSize;

	/**
	 * Uploaded files error
	 * 
	 * @var array
	 */
	private $errors = [];

	/**
	 * The allowed image extensions to consider
	 * that the uploaded file is an image
	 * 
	 * @var array
	 */
	private $allowedImageExtensions = ['gif', 'jpg', 'jpeg', 'png', 'wbmp'];

	/**
	 * Files count
	 * 
	 * @var int
	 */
	private $filesCount;

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
		if (empty($_FILES[$input]))	return; // End processing upload

		$this->filesCount = count($_FILES[$input]['name']);

		$this->errors = $_FILES[$input]['error'];

		if (!$this->checkErrors()) return; // End processing upload

		$this->files = $_FILES[$input]; // after checking if there is no error [+] usfull for exists() 








		$this->filesName = $this->files['name'];

		// get all info about the given file
		// pre(pathinfo($this->fileName));
		// $fileInfo = pathinfo($this->fileName); 

		$this->setNamesOnly();
		// $this->extension = strtolower($fileInfo['extension']); // needs help
		$this->setExtensions();

		$this->tempFiles = $this->files['tmp_name'];
		// $this->mime = mime_content_type($this->tempFile); // needs hepl
		$this->setMimesType();

		$this->filesSize = $this->files['size'];
	}

	/**
	 * determine if the files exist or not
	 * 
	 * @return bool
	 */
	public function exists()
	{
		return !empty($this->files);
	}

	/**
	 * Get the files name include the extension
	 * 
	 * @return string
	 */
	public function getFilesName()
	{
		return $this->filesName;
	}

	/**
	 * Get the extension
	 * 
	 * @return string
	 */
	public function getExtensions()
	{
		return $this->extensions;
	}

	/**
	 * Get names only
	 */
	public function getNamesOnly()
	{
		return $this->namesOnly;
	}

	/**
	 * Get the mime type
	 * 
	 * @return string
	 */
	public function getMimesType()
	{
		return $this->mimes;
	}

	/**
	 * get the file size in bytes
	 * 
	 * @return int
	 */
	public function getFileSize()
	{
		return $this->filesSize;
	}

	/**
	 * Get files count 
	 * 
	 * @return int
	 */
	public function getFilesCount()
	{
		return $this->filesCount;
	}

	/**
	 * Check errors
	 * 
	 * @return bool
	 */
	public function checkErrors()
	{
		foreach ($this->errors as $error) {
			if ($error != UPLOAD_ERR_OK) return false;
		}
		return true;
	}

	/**
	 * Set mime type to all uploaded files
	 * 
	 * @return void
	 */
	private function setMimesType()
	{
		for ($i = 0; $i < $this->filesCount; $i++) {
			$this->mimes[$i] = mime_content_type($this->tempFiles[$i]);
		}
	}

	/**
	 * Set Extension to all uploaded files
	 * 
	 * @return void
	 */
	private function setExtensions()
	{
		for ($i = 0; $i < $this->filesCount; $i++) {
			$fileInfo = pathinfo($this->filesName[$i]);
			$this->extensions[$i] = strtolower($fileInfo['extension']);
		}
	}

	/**
	 * Set NamesOnly to all uploaded files
	 * 
	 * @return void
	 */
	private function setNamesOnly()
	{
		for ($i = 0; $i < $this->filesCount; $i++) {
			$fileInfo = pathinfo($this->filesName[$i]);
			$this->namesOnly[$i] = $fileInfo['filename'];
		}
	}

	/**
	 * determine whether the uploaded filea are all images
	 * 
	 * @return bool
	 */
	public function isImages()
	{
		// inalid contion or ($extension != $this->getExtensions()[$i])
		for ($i = 0; $i < $this->filesCount; $i++) {
			list($type, $extension) = explode("/", $this->getMimesType()[$i]);
			if (strpos($this->getMimesType()[$i], 'image/') !== 0 or !in_array($this->getExtensions()[$i], $this->allowedImageExtensions)) {
				// echo "Mime type => " . $this->getMimesType()[$i] . "<br />";
				// echo "Real extension type => " . $this->getExtensions()[$i] . "<br />";
				// echo "Fake entension type => " . $extension . "<br />";
				return false;
			}
		}
		return true;
	}

	/**
	 * upload file to the given target
	 * it will be like $file = $this->request->file('image');
	 * it will be like $file->moveTo($this->file->to("public/images"));
	 * 
	 * @param string $target
	 * @param bool $setRandNames
	 * @return array
	 */
	public function moveTo($target, bool $setRandNames = false)
	{
		/**
		 * mkdir needs some server setup to work properlly
		 * @link https://stackoverflow.com/questions/5246114/php-mkdir-permission-denied-problem
		 */
		if (!is_dir($target)) {
			mkdir($target, 0777, true);
			chmod($target, 0777);
		}
		$extensions = $this->getExtensions();
		$files = [];
		for ($i = 0; $i < $this->filesCount; $i++) {
			if ($setRandNames) {
				$fileName = sha1(mt_rand()) . "_" . sha1(mt_rand()); // total lenght 81 chars
				$fileName .= "." . $extensions[$i];
			} else {
				$fileName = $this->getFilesName()[$i];
			}
			$uploadedFilePath = $target . "/" . $fileName;
			move_uploaded_file($this->tempFiles[$i], $uploadedFilePath);
			$files[] = $fileName;
		}
		return $files;
	}
}
